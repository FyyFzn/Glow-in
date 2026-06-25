<?php
session_start();
require_once '../config.php';
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
    <title>Following - Glow-in</title>
    <link rel="stylesheet" href="../assets/CSS/base.css?v=6">
    <link rel="stylesheet" href="../assets/CSS/home.css">
    <link rel="stylesheet" href="../assets/CSS/followers.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<input type="checkbox" id="menu-toggle" class="hidden-checkbox">

<div class="layout">
    <?php require_once '../includes/sidebar.php'; ?>

    <main class="main-content">
        <header class="top-header">
            <label for="menu-toggle" class="menu-toggle-btn">
                <i class="fa-solid fa-bars"></i>
            </label>
            <h1>Following</h1>
        </header>

        <div class="container">
            <div class="table">
                <div id="following-container">
                    <!-- Dummy following list -->
                </div>
            </div>
        </div>
    </main>

    <?php require_once '../includes/rightbar.php'; ?>
    <?php require_once '../includes/footer.php'; ?>
</div>

<script>
const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
let followingList = [];

function loadFollowing() {
    fetch('../controllers/followController.php?type=following', {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    })
    .then(res => res.json())
    .then(data => {
        if (data.error || !Array.isArray(data)) {
            followingList = [];
        } else {
            followingList = data;
        }
        renderFollowing();
    })
    .catch(() => {
        followingList = [];
        renderFollowing();
    });
}

function renderFollowing() {
    const container = document.getElementById('following-container');
    if (followingList.length === 0) {
        container.innerHTML = '<div class="empty-state">Kamu belum mengikuti siapa pun.</div>';
        return;
    }
    container.innerHTML = followingList.map((follow) => {
        const buttonLabel = 'Unfollow';
        const displayName = follow.name || follow.username || 'User';
        const avatar = follow.profile_pic || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&q=80';
        return `
            <div class="row">
                <span class="user">
                    <img src="${avatar}" alt="${displayName}">
                    <div>
                        <div class="name">${displayName}</div>
                        <div class="handle">@${follow.username}</div>
                    </div>
                </span>
                <span class="right">
                    <button class="action-btn unfollow" onclick="unfollowUser(${follow.following_id || follow.id})">
                        ${buttonLabel}
                    </button>
                </span>
            </div>
        `;
    }).join('');
}

function unfollowUser(targetUserId) {
    fetch(`../controllers/followController.php?user_id=${targetUserId}`, {
        method: 'DELETE',
        headers: { 'Authorization': 'Bearer ' + apiKey }
    })
    .then(res => res.json())
    .then(() => {
        loadFollowing();
    });
}

loadFollowing();
</script>
</body>
</html>