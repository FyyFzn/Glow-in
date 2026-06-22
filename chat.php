<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow-in | Messages</title>
  <link rel="stylesheet" href="./assets/CSS/base.css?v=6">
  <link rel="stylesheet" href="./assets/CSS/chat.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <input type="checkbox" id="menu-toggle" class="hidden-checkbox">

    <div class="layout"> 
<?php require_once 'includes/sidebar.php'; ?>

        <main class="main-content">
            <section class="messages-list-panel">
              <div class="main-header">
                <label for="menu-toggle" class="menu-toggle-btn" aria-label="Buka Menu">
                    <i class="fas fa-bars"></i>
                </label>
                <h1 class="panel-title">Messages</h1>
              </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search chats...">
                </div>

                <div class="chat-list" id="chat-list-container">
                    <!-- Daftar chat akan diload oleh Javascript -->
                    <p style="text-align:center; margin-top:20px; color:#999; font-size:14px;">Memuat...</p>
                </div>
            </section>

            <section class="chat-view-panel">
                <header class="chat-header" id="chat-header-container" style="display:none;">
                    <div class="avatar online" id="chat-header-avatar"></div>
                    <div class="chat-header-info">
                        <div class="chat-name" id="chat-header-name">Pilih User</div>
                        <div class="status">Online</div>
                    </div>
                    <span class="more-icon"><i class="fa-solid fa-ellipsis"></i></span>
                </header>

                <div class="conversation-area" id="conversation-area">
                    <!-- Percakapan akan diload oleh Javascript -->
                    <div style="margin:auto; color:#999; text-align:center;">
                        Pilih percakapan untuk mulai mengirim pesan
                    </div>
                </div>

                <div class="message-input-area" id="message-input-area" style="display:none;">
                    <input type="text" id="chat-input" placeholder="Type your message here..." onkeypress="handleEnter(event)">
                    <button class="send-button" id="send-btn" onclick="sendMessage()"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </section>
        </main>
<?php require_once 'includes/footer.php'; ?>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    const currentUserId = <?= $_SESSION['user_id'] ?? 0 ?>;
    let activeChatUser = null;

    function loadMessages() {
        fetch('api/messages.php', {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        })
        .then(res => res.json())
        .then(data => {
            const listContainer = document.getElementById('chat-list-container');
            if(data.error || data.length === 0) {
                listContainer.innerHTML = '<p style="text-align:center; margin-top:20px; color:#999; font-size:14px;">Belum ada pesan masuk</p>';
                return;
            }
            listContainer.innerHTML = '';
            
            const uniqueUsers = {};
            data.forEach(msg => {
                const otherId = msg.sender_id == currentUserId ? msg.receiver_id : msg.sender_id;
                if(!uniqueUsers[otherId]) {
                    uniqueUsers[otherId] = {
                        username: msg.username,
                        profile_pic: msg.profile_pic,
                        last_message: msg.message,
                        created_at: msg.created_at,
                        is_read: msg.is_read
                    };
                }
            });

            Object.keys(uniqueUsers).forEach(id => {
                const user = uniqueUsers[id];
                const date = new Date(user.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                
                listContainer.innerHTML += `
                <div class="chat-item ${activeChatUser == id ? 'selected' : ''}" onclick="openChat(${id}, '${user.username}', '${user.profile_pic}')">
                    <div class="avatar" style="background-image: url('${user.profile_pic || 'https://via.placeholder.com/45'}'); background-size: cover;"></div>
                    <div class="chat-info">
                        <div class="chat-name">${user.username}</div>
                        <div class="chat-preview">${user.last_message}</div>
                    </div>
                    <div class="chat-meta">
                        <div class="time">${date}</div>
                        ${user.is_read == 0 ? '<div class="badge">•</div>' : ''}
                    </div>
                </div>`;
            });
        });
    }

    function openChat(userId, userName, userAvatar) {
        activeChatUser = userId;
        document.getElementById('chat-header-container').style.display = 'flex';
        document.getElementById('message-input-area').style.display = 'flex';
        document.getElementById('chat-header-name').innerText = userName;
        document.getElementById('chat-header-avatar').style.backgroundImage = `url('${userAvatar || 'https://via.placeholder.com/45'}')`;
        document.getElementById('chat-header-avatar').style.backgroundSize = 'cover';

        const conversationArea = document.getElementById('conversation-area');
        conversationArea.innerHTML = '<p style="text-align:center; margin:auto; color:#999;">Memuat percakapan...</p>';

        fetch('api/messages.php?chat_with=' + userId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        })
        .then(res => res.json())
        .then(data => {
            conversationArea.innerHTML = '';
            if(data.length === 0) {
                 conversationArea.innerHTML = '<p style="text-align:center; margin:auto; color:#999;">Belum ada pesan. Mulai sapa!</p>';
                 return;
            }

            data.forEach(msg => {
                const isMine = msg.sender_id == currentUserId;
                const time = new Date(msg.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                
                if(isMine) {
                    conversationArea.innerHTML += `
                    <div class="message outgoing">
                        <p>${msg.message}</p>
                        <div class="time">${time}</div>
                    </div>`;
                } else {
                    conversationArea.innerHTML += `
                    <div class="message incoming">
                        <div class="avatar-message" style="background-image: url('${userAvatar || 'https://via.placeholder.com/45'}'); background-size: cover;"></div>
                        <div class="d-flex">
                          <p>${msg.message}</p>
                          <div class="time">${time}</div>
                        </div>
                    </div>`;
                }
            });
            conversationArea.scrollTop = conversationArea.scrollHeight;
        });

        // Highlight selected user in list
        loadMessages(); 
    }

    function handleEnter(e) {
        if(e.key === 'Enter') sendMessage();
    }

    function sendMessage() {
        if(!activeChatUser) return;
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if(!text) return;

        input.value = '';
        input.focus();

        const conversationArea = document.getElementById('conversation-area');
        const time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        if(conversationArea.querySelector('p')) conversationArea.innerHTML = '';
        
        conversationArea.innerHTML += `
        <div class="message outgoing" style="opacity: 0.7;">
            <p>${text}</p>
            <div class="time">${time}</div>
        </div>`;
        conversationArea.scrollTop = conversationArea.scrollHeight;

        fetch('api/messages.php', {
            method: 'POST',
            headers: { 
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ receiver_id: activeChatUser, message: text })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Reload specific chat to get correct IDs & times
                openChat(activeChatUser, document.getElementById('chat-header-name').innerText, document.getElementById('chat-header-avatar').style.backgroundImage.slice(5, -2));
            }
        });
    }

    // Auto open if parameter ?user=ID exists
    const urlParams = new URLSearchParams(window.location.search);
    const urlUser = urlParams.get('user');
    if (urlUser) {
        // Fetch user info first to open chat properly
        fetch('api/users.php?id=' + urlUser, { headers: { 'Authorization': 'Bearer ' + apiKey }})
        .then(res => res.json())
        .then(data => {
            if (data && data.username) openChat(urlUser, data.username, data.profile_pic);
            else loadMessages();
        })
        .catch(() => loadMessages());
    } else {
        loadMessages();
    }
</script>
</body>
</html>
