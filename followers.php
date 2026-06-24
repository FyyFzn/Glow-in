<?php
session_start();
require_once 'config.php';
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
    <link rel="stylesheet" href="assets/CSS/base.css?v=6">
    <link rel="stylesheet" href="assets/CSS/home.css">
    <link rel="stylesheet" href="assets/CSS/followers.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<input type="checkbox" id="menu-toggle" class="hidden-checkbox">

<div class="layout">
    <?php require_once 'includes/sidebar.php'; ?>

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

    <?php require_once 'includes/rightbar.php'; ?>
    <?php require_once 'includes/footer.php'; ?>
</div>

<script>
const dummyFollowers = [
    { id: 101, username: 'antonwira', name: 'Anton Wira', profile_pic: 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-14T08:30:00', is_following: true },
    { id: 102, username: 'linaayu', name: 'Lina Ayu', profile_pic: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-13T10:15:00', is_following: false },
    { id: 103, username: 'dwi_rahma', name: 'Dwi Rahma', profile_pic: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-12T14:20:00', is_following: true },
    { id: 104, username: 'rina_n', name: 'Rina N.', profile_pic: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-11T09:45:00', is_following: false },
    { id: 105, username: 'budi_kurnia', name: 'Budi Kurnia', profile_pic: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-10T12:00:00', is_following: true },
    { id: 106, username: 'nina_ayu', name: 'Nina Ayu', profile_pic: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-09T11:30:00', is_following: false },
    { id: 107, username: 'fikri_s', name: 'Fikri Syah', profile_pic: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-08T13:10:00', is_following: true },
    { id: 108, username: 'aditya07', name: 'Aditya R.', profile_pic: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-07T15:20:00', is_following: false },
    { id: 109, username: 'sari_ika', name: 'Sari Ika', profile_pic: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-06T17:05:00', is_following: true },
    { id: 110, username: 'rafli_her', name: 'Rafli Heri', profile_pic: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=80&q=80', created_at: '2026-06-05T18:25:00', is_following: false }
];

let followersList = [...dummyFollowers];

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function renderFollowers() {
    const container = document.getElementById('followers-container');

    if (followersList.length === 0) {
        container.innerHTML = '<div class="empty-state">Belum ada followers. Tambahkan dummy data lagi jika perlu.</div>';
        return;
    }

    container.innerHTML = followersList.map((follow) => {
        const buttonLabel = follow.is_following ? 'Unfollow' : 'Follow';

        return `
            <div class="row">
                <span class="user">
                    <img src="${follow.profile_pic}" alt="${follow.name}">
                    <div>
                        <div class="name">${follow.name}</div>
                        <div class="handle">@${follow.username}</div>
                    </div>
                </span>
                <span class="right">
                    <button class="action-btn${follow.is_following ? ' unfollow' : ''}" onclick="toggleFollowState(${follow.id})">
                        ${buttonLabel}
                    </button>
                </span>
            </div>
        `;
    }).join('');
}

function toggleFollowState(userId) {
    followersList = followersList.map(item => item.id === userId ? { ...item, is_following: !item.is_following } : item);
    renderFollowers();
}

renderFollowers();
</script>
</body>
</html>