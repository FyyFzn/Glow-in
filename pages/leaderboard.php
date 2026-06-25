<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$displayName = !empty($_SESSION['name']) ? $_SESSION['name'] : ($_SESSION['username'] ?? 'User');
$profilePic = !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : ('https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=ff6b00&color=ffffff');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Glow-in</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/CSS/base.css?v=102">
    <link rel="stylesheet" href="../assets/CSS/leaderboard.css?v=102">
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
            <h1>Global Leaderboard</h1>
        </header>

        <div class="container">

        <div class="rank-card">
            <div class="rank-left">
                <span class="rank-number" id="my-rank-number">#-</span>
                <div>
                    <p class="rank-label">Your Rank</p>
                    <p class="rank-name">
                        <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile" class="rank-avatar">
                        <?= htmlspecialchars($displayName) ?>
                    </p>
                </div>
            </div>
            <div class="rank-points" id="my-rank-points">... points</div>
        </div>

        <div class="table">
            <div class="table-head">
                <span>Rank</span>
                <span>User</span>
                <span class="right">Points</span>
            </div>

            <div id="leaderboard-table-body">
                <p class="empty-state">Memuat data...</p>
            </div>
        </div>
    </div>
    </main>

    <?php require_once '../includes/footer.php'; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiLeaderboardKey = "<?= $_SESSION['api_key'] ?? '' ?>";

    fetch('../controllers/leaderboardController.php?action=my_rank', {
        headers: { 'Authorization': 'Bearer ' + apiLeaderboardKey }
    })
    .then(res => res.json())
    .then(my => {
        if(my && !my.error) {
            document.getElementById('my-rank-number').textContent = '#' + (my.rank || '-');
            document.getElementById('my-rank-points').textContent = (my.points || 0) + ' points';
        }
    })
    .catch(() => {
        document.getElementById('my-rank-points').textContent = '0 points';
    });

    fetch('../controllers/leaderboardController.php?limit=20', {
        headers: { 'Authorization': 'Bearer ' + apiLeaderboardKey }
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('leaderboard-table-body');

        if (data.error || !Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = '<p class="empty-state">Belum ada data pengguna</p>';
            return;
        }

        tbody.innerHTML = '';
        data.forEach((user, index) => {
            const dispName = user.name ? user.name : user.username;
            const rank = index + 1;
            tbody.innerHTML += `
            <div class="row">
                <span class="rank-badge">${rank}</span>
                <span class="user">
                    <img src="${user.profile_pic}" alt="${dispName}">
                    ${dispName}
                </span>
                <span class="right">${user.points}</span>
            </div>`;
        });
    })
    .catch(() => {
        const tbody = document.getElementById('leaderboard-table-body');
        tbody.innerHTML = '<p class="empty-state">Gagal memuat leaderboard</p>';
    });
});
</script>
</body>
</html>
