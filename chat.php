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
                    <!-- Static chat list -->
                    <div class="chat-item selected" onclick="openChat(2, 'Sarah Wilson', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80')">
                        <div class="avatar" style="background-image: url('https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80'); background-size: cover;"></div>
                        <div class="chat-info">
                            <div class="chat-name">Sarah Wilson</div>
                            <div class="chat-preview">Hey, how are you doing today?</div>
                        </div>
                        <div class="chat-meta">
                            <div class="time">10:30</div>
                            <div class="badge">•</div>
                        </div>
                    </div>
                    <div class="chat-item" onclick="openChat(3, 'Jane Doe', 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80')">
                        <div class="avatar" style="background-image: url('https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80'); background-size: cover;"></div>
                        <div class="chat-info">
                            <div class="chat-name">Jane Doe</div>
                            <div class="chat-preview">Thanks for your help!</div>
                        </div>
                        <div class="chat-meta">
                            <div class="time">09:15</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="chat-view-panel">
                <header class="chat-header" id="chat-header-container">
                    <div class="avatar online" id="chat-header-avatar" style="background-image: url('https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80'); background-size: cover;"></div>
                    <div class="chat-header-info">
                        <div class="chat-name" id="chat-header-name">Sarah Wilson</div>
                        <div class="status">Online</div>
                    </div>
                    <span class="more-icon"><i class="fa-solid fa-ellipsis"></i></span>
                </header>

                <div class="conversation-area" id="conversation-area">
                    <!-- Static chat messages -->
                    <div class="message incoming">
                        <div class="avatar-message" style="background-image: url('https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80'); background-size: cover;"></div>
                        <div class="d-flex">
                          <p>Hey! Good morning 👋</p>
                          <div class="time">09:00</div>
                        </div>
                    </div>
                    <div class="message outgoing">
                        <p>Morning Sarah! How are you?</p>
                        <div class="time">09:02</div>
                    </div>
                    <div class="message incoming">
                        <div class="avatar-message" style="background-image: url('https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80'); background-size: cover;"></div>
                        <div class="d-flex">
                          <p>I'm doing great! Just saw your post, it was amazing!</p>
                          <div class="time">09:05</div>
                        </div>
                    </div>
                    <div class="message outgoing">
                        <p>Thanks so much! I'm glad you liked it 😊</p>
                        <div class="time">09:07</div>
                    </div>
                    <div class="message incoming">
                        <div class="avatar-message" style="background-image: url('https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80'); background-size: cover;"></div>
                        <div class="d-flex">
                          <p>Hey, how are you doing today?</p>
                          <div class="time">10:30</div>
                        </div>
                    </div>
                </div>

                <div class="message-input-area" id="message-input-area">
                    <input type="text" id="chat-input" placeholder="Type your message here..." onkeypress="handleEnter(event)">
                    <button class="send-button" id="send-btn" onclick="sendMessage()"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </section>
        </main>
<?php require_once 'includes/footer.php'; ?>

<script>
    const currentUserId = <?= $_SESSION['user_id'] ?? 0 ?>;
    let activeChatUser = 2;
    let activeChatName = 'Sarah Wilson';
    let activeChatAvatar = 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80';

    function openChat(userId, userName, userAvatar) {
        activeChatUser = userId;
        activeChatName = userName;
        activeChatAvatar = userAvatar;
        
        document.getElementById('chat-header-container').style.display = 'flex';
        document.getElementById('message-input-area').style.display = 'flex';
        document.getElementById('chat-header-name').innerText = userName;
        document.getElementById('chat-header-avatar').style.backgroundImage = `url('${userAvatar}')`;
        document.getElementById('chat-header-avatar').style.backgroundSize = 'cover';

        // Update selected chat
        document.querySelectorAll('.chat-item').forEach(item => {
            item.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
    }

    function handleEnter(e) {
        if(e.key === 'Enter') sendMessage();
    }

    function sendMessage() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if(!text) return;

        input.value = '';
        input.focus();

        const conversationArea = document.getElementById('conversation-area');
        const time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        
        conversationArea.innerHTML += `
        <div class="message outgoing">
            <p>${text}</p>
            <div class="time">${time}</div>
        </div>`;
        conversationArea.scrollTop = conversationArea.scrollHeight;
        
        // Simulate reply
        setTimeout(() => {
            const replies = [
                "That's great! 👍",
                "I totally agree with you!",
                "Thanks for letting me know!",
                "Sounds good to me! 😊",
                "Haha, that's funny!"
            ];
            const randomReply = replies[Math.floor(Math.random() * replies.length)];
            const replyTime = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
            
            conversationArea.innerHTML += `
            <div class="message incoming">
                <div class="avatar-message" style="background-image: url('${activeChatAvatar}'); background-size: cover;"></div>
                <div class="d-flex">
                  <p>${randomReply}</p>
                  <div class="time">${replyTime}</div>
                </div>
            </div>`;
            conversationArea.scrollTop = conversationArea.scrollHeight;
        }, 1500);
    }
</script>
</body>
</html>