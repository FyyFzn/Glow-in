<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow-in Mockup (HTML/CSS Only)</title>
    <link rel="stylesheet" href="./assets/CSS/notification.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <input type="checkbox" id="menu-toggle" class="hidden-checkbox">

    <div class="layout">
<?php require_once 'includes/sidebar.php'; ?>

        <main class="main-content">
            <header class="top-header">
                <label for="menu-toggle" class="menu-toggle-btn" aria-label="Buka Menu">
                    <i class="fa-solid fa-bars"></i>
                </label>
                <h1>Notifications</h1>
            </header>

            <div class="container">
                <article class="tweet-card">
                    <ul class="notification-list">
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Lucy" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Lucy Adams</span>
                                <span class="action-text">menyukai postingan Anda.</span>
                                <p class="post-snippet">butuh lebih banyak libas ke chekien youtamanent.</p>
                            </div>
                            <span class="timestamp">5 menit yang lalu</span>
                        </li>

                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Miles" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Miles Peters</span>
                                <span class="action-text">mulai mengikuti Anda.</span>
                            </div>
                            <span class="timestamp">20 menit yang lalu</span>
                        </li>

                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>
                        
                        <li class="notification-item">
                            <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?q=80&w=2342&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Avatar Ethan" class="avatar-notif">
                            <div class="notification-content">
                                <span class="user-name">Ethan Wright</span>
                                <span class="action-text">mengomentari postingan Anda.</span>
                                <p class="post-snippet">Gambar yang bagus! (Pertahankan!) 🙌</p>
                            </div>
                            <span class="timestamp">45 menit yang lalu</span>
                        </li>

                    </ul>
                </article>

            </div>
        </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

