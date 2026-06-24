<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

$rankStmt = $pdo->prepare("
    SELECT COUNT(*) + 1 AS user_rank 
    FROM users 
    WHERE points > ? OR (points = ? AND created_at < ?)
");
$rankStmt->execute([$currentUser['points'], $currentUser['points'], $currentUser['created_at']]);
$rankResult = $rankStmt->fetch(PDO::FETCH_ASSOC);
$currentUserRank = $rankResult['user_rank'];

$displayName = !empty($currentUser['name']) ? $currentUser['name'] : $currentUser['username'];
if (!empty($currentUser['profile_pic'])) {
    $profilePic = $currentUser['profile_pic'];
} else {
    $aiProfilePics = [
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80'
    ];
    $picIndex = crc32($currentUser['username']) % count($aiProfilePics);
    $profilePic = $aiProfilePics[$picIndex];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Glow-in</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/base.css?v=6">
    <link rel="stylesheet" href="assets/CSS/leaderboard.css">
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
            <h1>Global Leaderboard</h1>
        </header>

        <div class="container">
        <div class="filters">
            <select><option>All Time</option></select>
            <select><option>Top 10 Users</option></select>
        </div>

        <!-- Your Rank Card -->
        <div class="rank-card">
            <div class="rank-left">
                <span class="rank-number">#<?= htmlspecialchars($currentUserRank) ?></span>
                <div>
                    <p class="rank-label">Your Rank</p>
                    <p class="rank-name">
                        <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile" class="rank-avatar">
                        <?= htmlspecialchars($displayName) ?>
                    </p>
                </div>
            </div>
            <div class="rank-points"><?= htmlspecialchars($currentUser['points']) ?> points</div>
        </div>

        <div class="table">
            <div class="table-head">
                <span>Rank</span>
                <span>User</span>
                <span class="right">Points</span>
            </div>

            <div id="leaderboard-table-body">
                <!-- Static Data - will be replaced by API if successful -->
                <div class="row">
                    <span class="rank-badge">1</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80" alt="Sarah Wilson">
                        Sarah Wilson
                    </span>
                    <span class="right">2200</span>
                </div>
                <div class="row">
                    <span class="rank-badge">2</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80" alt="Jane Doe">
                        Jane Doe
                    </span>
                    <span class="right">2000</span>
                </div>
                <div class="row">
                    <span class="rank-badge">3</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80" alt="Emily Davis">
                        Emily Davis
                    </span>
                    <span class="right">1900</span>
                </div>
                <div class="row">
                    <span class="rank-badge">4</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80" alt="Alex Smith">
                        Alex Smith
                    </span>
                    <span class="right">1800</span>
                </div>
                <div class="row">
                    <span class="rank-badge">5</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=200&q=80" alt="Mike Johnson">
                        Mike Johnson
                    </span>
                    <span class="right">1600</span>
                </div>
                <div class="row">
                    <span class="rank-badge">6</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80" alt="John Doe">
                        John Doe
                    </span>
                    <span class="right">1500</span>
                </div>
                <div class="row">
                    <span class="rank-badge">7</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80" alt="User 7">
                        User 7
                    </span>
                    <span class="right">1400</span>
                </div>
                <div class="row">
                    <span class="rank-badge">8</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=200&q=80" alt="User 8">
                        User 8
                    </span>
                    <span class="right">1300</span>
                </div>
                <div class="row">
                    <span class="rank-badge">9</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80" alt="User 9">
                        User 9
                    </span>
                    <span class="right">1200</span>
                </div>
                <div class="row">
                    <span class="rank-badge">10</span>
                    <span class="user">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80" alt="User 10">
                        User 10
                    </span>
                    <span class="right">1100</span>
                </div>
            </div>
        </div>
    </div>
    </main>

    <?php require_once 'includes/footer.php'; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiLeaderboardKey = "<?= $_SESSION['api_key'] ?? '' ?>";

    console.log('Leaderboard API Key:', apiLeaderboardKey);

    fetch('api/leaderboard.php?limit=10', {
        headers: { 'Authorization': 'Bearer ' + apiLeaderboardKey }
    })
    .then(res => {
        console.log('Leaderboard response status:', res.status);
        return res.text().then(text => {
            console.log('Leaderboard response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                return { error: 'Invalid JSON: ' + text };
            }
        });
    })
    .then(data => {
        console.log('Leaderboard data:', data);

        if (data.error || !Array.isArray(data) || data.length === 0) {
            console.warn('Leaderboard API error or no data, keeping static');
            return;
        }

        const tbody = document.getElementById('leaderboard-table-body');
        tbody.innerHTML = '';
        data.forEach((user, index) => {
            const displayName = user.name ? user.name : user.username;
            const rank = index + 1;
            tbody.innerHTML += `
            <div class="row">
                <span class="rank-badge">${rank}</span>
                <span class="user">
                    <img src="${user.profile_pic}" alt="${displayName}">
                    ${displayName}
                </span>
                <span class="right">${user.points}</span>
            </div>`;
        });
    })
    .catch(err => {
        console.error('Leaderboard error fetching leaderboard:', err);

    });
});
</script>
</body>
</html>
