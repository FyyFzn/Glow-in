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
    <title>Followers - Glow-in</title>
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
            <h1>Followers</h1>
        </header>

        <div class="container">
            <div class="table">
                <div id="followers-container">
                    <!-- Dummy followers list -->
                </div>
            </div>
        </div>
    </main>

    <?php require_once '../includes/rightbar.php'; ?>
    <?php require_once '../includes/footer.php'; ?>
</div>

<script>
const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
let followersList = [];

function loadFollowers() {
    fetch('../controllers/followController.php?type=followers', {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    })
    .then(res => res.json())
    .then(data => {
        if (data.error || !Array.isArray(data)) {
            followersList = [];
        } else {
            followersList = data;
        }
        renderFollowers();
    })
    .catch(() => {
        followersList = [];
        renderFollowers();
    });
}

function renderFollowers() {
    const container = document.getElementById('followers-container');
    if (followersList.length === 0) {
        container.innerHTML = '<div class="empty-state">Belum ada followers.</div>';
        return;
    }
    container.innerHTML = followersList.map((follow) => {
        const buttonLabel = follow.is_following ? 'Unfollow' : 'Follow';
        const displayName = follow.name || follow.username || 'User';
        const avatar = follow.profile_pic || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&q=80';
        const targetId = follow.follower_id || follow.id;
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
                    <button class="action-btn${follow.is_following ? ' unfollow' : ''}" onclick="toggleFollow(${targetId}, ${follow.is_following})">
                        ${buttonLabel}
                    </button>
                </span>
            </div>
        `;
    }).join('');
}

function toggleFollow(targetUserId, currentlyFollowing) {
    const method = currentlyFollowing ? 'DELETE' : 'POST';
    fetch(`../controllers/followController.php?user_id=${targetUserId}`, {
        method: method,
        headers: { 'Authorization': 'Bearer ' + apiKey }
    })
    .then(res => res.json())
    .then(() => {
        loadFollowers();
    });
}

loadFollowers();
</script>
</body>
</html>