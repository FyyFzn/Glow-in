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
    <title>Chat AI - Glow-in</title>
    <link rel="stylesheet" href="./assets/CSS/chatprew.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <input type="checkbox" id="menu-toggle" class="hidden-checkbox">

    <div class="layout">
<?php require_once 'includes/sidebar.php'; ?>

        <main id="main-content-ai-chat">
            <section class="chat-view-full">
                <header class="chat-header">
                    <div class="chat-profile-info">
                        <div class="back-col">
                            <a href="./chat.php" class="back-link"><i class="fas fa-arrow-left"></i> </a>
                        </div>
                        <div class="avatar online"></div>
                        <div class="chat-header-info">
                            <div class="chat-name">Glow-in Ai</div>
                            <div class="status">Online</div>
                        </div>
                        <span class="more-icon"><i class="fa-solid fa-ellipsis"></i></span>
                    </div>
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
                        <p>Great! I wanted to go over the final presentation slides and some feedback from the client.
                        </p>
                        <div class="time">2:40 PM</div>
                    </div>

                    <div class="message incoming">
                        <div class="avatar-message"></div>
                        <div class="d-flex">
                            <p>Okay, sounds important. I'll make sure to block out some time. Could you send me the
                                latest version of the slides beforehand?</p>
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

        <aside id="rightbar-ai">
            <div class="ai-details-card">
                <div class="ai-avatar-large">
                    <div class="avatar-placeholder"></div>
                </div>
                <h3 class="ai-name">Glow-in Ai</h3>
                <div class="ai-status">
                    <i class="fa-solid fa-microphone"></i> Listening...
                </div>
                <div class="ai-metric">
                    <i class="fa-solid fa-clock"></i> 16 Minute
                </div>
            </div>
        </aside>

<?php require_once 'includes/footer.php'; ?>

