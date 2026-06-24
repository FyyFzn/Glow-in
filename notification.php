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
    <title>Glow-in | Notifications</title>
    <link rel="stylesheet" href="./assets/CSS/base.css?v=4">
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
                    <ul id="notifications-container" class="notification-list">
                        <!-- Data akan dirender oleh Javascript -->
                        <p style="text-align: center; color: #999;">Memuat...</p>
                    </ul>
                </article>
            </div>
        </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";

    function loadNotifications() {
        fetch('api/notifications.php', {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        })
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('notifications-container');
            if(data.error || data.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #999;">Belum ada notifikasi</p>';
                return;
            }
            container.innerHTML = '';
            data.forEach(notif => {
                let actionText = "";
                if(notif.type == 'like') actionText = "menyukai postingan Anda.";
                else if(notif.type == 'comment') actionText = "mengomentari postingan Anda.";
                else if(notif.type == 'follow') actionText = "mulai mengikuti Anda.";
                else actionText = "berinteraksi dengan Anda.";

                const date = new Date(notif.created_at).toLocaleString('id-ID');

                container.innerHTML += `
                <li class="notification-item ${notif.is_read == 0 ? 'unread' : ''}">
                    <img src="${notif.profile_pic || 'https://via.placeholder.com/40'}" alt="Avatar" class="avatar-notif">
                    <div class="notification-content">
                        <span class="user-name">${notif.username}</span>
                        <span class="action-text">${actionText}</span>
                    </div>
                    <span class="timestamp">${date}</span>
                </li>`;
            });

            fetch('api/notifications.php', {
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + apiKey }
            });
        });
    }

    loadNotifications();
</script>
</body>
</html>
