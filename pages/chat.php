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
  <link rel="stylesheet" href="../assets/CSS/base.css?v=8">
  <link rel="stylesheet" href="../assets/CSS/chat.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <input type="checkbox" id="menu-toggle" class="hidden-checkbox">

    <div class="layout"> 
<?php require_once '../includes/sidebar.php'; ?>

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
                    <p class="empty-state">Belum ada percakapan</p>
                </div>
            </section>

            <section class="chat-view-panel">
                <header class="chat-header" id="chat-header-container" style="display: none;">
                    <div class="avatar online" id="chat-header-avatar"></div>
                    <div class="chat-header-info">
                        <div class="chat-name" id="chat-header-name">User</div>
                        <div class="status">Online</div>
                    </div>
                </header>

                <div class="conversation-area" id="conversation-area">
                    <p class="empty-state">Pilih kontak atau mulai percakapan baru</p>
                </div>

                <div class="message-input-area" id="message-input-area">
                    <input type="text" id="chat-input" placeholder="Type your message here..." onkeypress="handleEnter(event)">
                    <button class="send-button" id="send-btn" onclick="sendMessage()"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </section>
        </main>
<?php require_once '../includes/footer.php'; ?>

<script>
    const currentUserId = <?= $_SESSION['user_id'] ?? 0 ?>;
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    let activeChatUser = null;
    let activeChatName = '';
    let activeChatAvatar = '';

    function loadChatList() {
        fetch('../controllers/followController.php?type=following', {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        })
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('chat-list-container');
            if (data.error || !Array.isArray(data) || data.length === 0) {
                container.innerHTML = '<p class="empty-state">Belum ada kontak</p>';
                return;
            }
            container.innerHTML = '';
            data.forEach(user => {
                const displayName = user.name || user.username;
                const avatar = user.profile_pic || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80';
                const targetId = user.following_id || user.id;
                container.innerHTML += `
                <div class="chat-item" id="chat-item-${targetId}" onclick="openChat(${targetId}, '${displayName}', '${avatar}')">
                    <div class="avatar" style="background-image: url('${avatar}'); background-size: cover;"></div>
                    <div class="chat-info">
                        <div class="chat-name">${displayName}</div>
                        <div class="chat-preview">@${user.username}</div>
                    </div>
                </div>`;
            });
        });
    }

    function openChat(userId, userName, userAvatar) {
        activeChatUser = userId;
        activeChatName = userName;
        activeChatAvatar = userAvatar;

        document.getElementById('chat-header-container').style.display = 'flex';
        document.getElementById('message-input-area').style.display = 'flex';
        document.getElementById('chat-header-name').innerText = userName;
        document.getElementById('chat-header-avatar').style.backgroundImage = `url('${userAvatar}')`;
        document.getElementById('chat-header-avatar').style.backgroundSize = 'cover';

        document.querySelectorAll('.chat-item').forEach(item => {
            item.classList.remove('selected');
        });
        const activeItem = document.getElementById(`chat-item-${userId}`);
        if(activeItem) activeItem.classList.add('selected');

        loadMessages();
    }

    function loadMessages() {
        if (!activeChatUser) return;
        fetch(`../controllers/messageController.php?chat_with=${activeChatUser}`, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        })
        .then(res => res.json())
        .then(data => {
            const conversationArea = document.getElementById('conversation-area');
            if (data.error || !Array.isArray(data) || data.length === 0) {
                conversationArea.innerHTML = '<p class="empty-state">Belum ada pesan. Mulai percakapan sekarang!</p>';
                return;
            }
            conversationArea.innerHTML = '';
            data.forEach(msg => {
                const isOutgoing = msg.sender_id == currentUserId;
                const time = new Date(msg.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                if (isOutgoing) {
                    conversationArea.innerHTML += `
                    <div class="message outgoing">
                        <p>${msg.message}</p>
                        <div class="time">${time}</div>
                    </div>`;
                } else {
                    conversationArea.innerHTML += `
                    <div class="message incoming">
                        <div class="avatar-message" style="background-image: url('${activeChatAvatar}'); background-size: cover;"></div>
                        <div class="d-flex">
                          <p>${msg.message}</p>
                          <div class="time">${time}</div>
                        </div>
                    </div>`;
                }
            });
            conversationArea.scrollTop = conversationArea.scrollHeight;
        });
    }

    function handleEnter(e) {
        if(e.key === 'Enter') sendMessage();
    }

    function sendMessage() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if(!text || !activeChatUser) return;

        input.value = '';
        input.focus();

        fetch('../controllers/messageController.php', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                receiver_id: activeChatUser,
                message: text
            })
        })
        .then(res => res.json())
        .then(() => {
            loadMessages();
        });
    }

    loadChatList();
</script>
</body>
</html>