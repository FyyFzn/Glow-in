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
  <title>Chat UI - Messages</title>
  <link rel="stylesheet" href="./assets/CSS/chat.css">
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
                <a href="./chatprew.php" class="smart-chat-button">Chat Smarter with Glow-in</a>

                <div class="chat-list">
                    <div class="chat-item selected">
                        <div class="avatar online"></div>
                        <div class="chat-info">
                            <div class="chat-name">Alice Johnson</div>
                            <div class="chat-preview">Hey, are you free for a call lat...</div>
                        </div>
                        <div class="chat-meta">
                            <div class="time">2h ago</div>
                            <div class="badge">2</div>
                        </div>
                    </div>

                    <div class="chat-item">
                        <div class="avatar"></div>
                        <div class="chat-info">
                            <div class="chat-name">David Lee</div>
                            <div class="chat-preview">Sounds good! I'll check the n...</div>
                        </div>
                        <div class="chat-meta">
                            <div class="time">Yesterday</div>
                        </div>
                    </div>
                    
                    <div class="chat-item">
                        <div class="avatar"></div>
                        <div class="chat-info">
                            <div class="chat-name">Sophia Garcia</div>
                            <div class="chat-preview">Okay, I've sent the updated br...</div>
                        </div>
                        <div class="chat-meta">
                            <div class="time">Mon</div>
                        </div>
                    </div>

                    <div class="chat-item">
                        <div class="avatar"></div>
                        <div class="chat-info">
                            <div class="chat-name">Liam Miller</div>
                            <div class="chat-preview">Can we review the project sco...</div>
                        </div>
                        <div class="chat-meta">
                            <div class="time">Sun</div>
                            <div class="badge">1</div>
                        </div>
                    </div>
                    
                    <div class="chat-item">
                        <div class="avatar"></div>
                        <div class="chat-info">
                            <div class="chat-name">Emily White</div>
                            <div class="chat-preview">Got it! Thanks for the heads up.</div>
                        </div>
                        <div class="chat-meta">
                            <div class="time">1 week ago</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="chat-view-panel">
                <header class="chat-header">
                    <div class="avatar online"></div>
                    <div class="chat-header-info">
                        <div class="chat-name">Alice Johnson</div>
                        <div class="status">Online</div>
                    </div>
                    <span class="more-icon"><i class="fa-solid fa-ellipsis"></i></span>
                </header>

                <div class="conversation-area">
                    
                    <div class="message outgoing">
                        <p>Hey Alice, are you free for a call later today to discuss the project?</p>
                        <div class="time">2:30 PM</div>
                    </div>

                    <div class="message incoming">
                        <div class="avatar-message"></div>
                        <div class="d-flex">
                          <p>Hi! Yes, I should be free after 4 PM. What's on your mind?</p>
                        <div class="time">2:35 PM</div>
                        </div>
                    </div>
                    
                    <div class="message outgoing">
                        <p>Great! I wanted to go over the final presentation slides and some feedback from the client.</p>
                        <div class="time">2:40 PM</div>
                    </div>

                    <div class="message incoming">
                        <div class="avatar-message"></div>
                        <div class="d-flex">
                          <p>Okay, sounds important. I'll make sure to block out some time. Could you send me the latest version of the slides beforehand?</p>
                          <div class="time">2:42 PM</div>
                        </div>
                    </div>
                    
                    <div class="message outgoing">
                        <p>Absolutely! I'll send them over in a few minutes. Is 4:30 PM good for you?</p>
                        <div class="time">2:45 PM</div>
                    </div>
                    
                    <div class="message incoming">
                        <div class="avatar-message"></div>
                        <div class="d-flex">
                          <p>Yes, 4:30 PM works perfectly. Looking forward to it!</p>
                          <div class="time">2:46 PM</div>
                        </div>
                    </div>
                </div>

                <div class="message-input-area">
                    <input type="text" placeholder="Type your message here...">
                    <button class="send-button"><i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </section>
        </main>
<?php require_once 'includes/footer.php'; ?>

