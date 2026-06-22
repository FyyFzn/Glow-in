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
  <title>Glow-in Leaderboard</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="assets/CSS/base.css?v=6" />
  <link rel="stylesheet" href="assets/CSS/leaderboard.css" />
</head>
<body>
<input type="checkbox" id="menu-toggle" class="hidden-checkbox">

<div class="layout">

  <!-- LEFT SIDEBAR (CHAT STRUCTURE) -->
<?php require_once 'includes/sidebar.php'; ?>

  <!-- MAIN CONTENT -->
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

    <!-- Your Rank Card disembunyikan sementara sampai API Rank dibuat -->
    <div class="rank-card" style="display:none;">
      <div class="rank-left">
        <span class="rank-number">-</span>
        <div>
          <p class="rank-label">Your Rank</p>
          <p class="rank-name"><?= htmlspecialchars($_SESSION['username'] ?? 'You') ?></p>
        </div>
      </div>
      <div class="rank-points">- points</div>
    </div>

    <div class="table">
      <div class="table-head">
        <span>Rank</span>
        <span>User</span>
        <span class="right">Points</span>
      </div>

      <div id="leaderboard-table-body">
        <p style="text-align: center; color: #999; padding: 20px;">Memuat leaderboard...</p>
      </div>
    </div>
    </div>
  </main>
<?php require_once 'includes/footer.php'; ?>

<script>
(function() {
    const apiLeaderboardKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    fetch('api/leaderboard.php', {
        headers: { 'Authorization': 'Bearer ' + apiLeaderboardKey }
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('leaderboard-table-body');
        if(data.error || data.length === 0) {
            tbody.innerHTML = '<p style="text-align: center; color: #999; padding: 20px;">Belum ada data</p>';
            return;
        }
        tbody.innerHTML = '';
        data.forEach((user, index) => {
            tbody.innerHTML += `
            <div class="row">
                <span>${index + 1}</span>
                <span class="user">
                    <img src="${user.profile_pic || 'https://via.placeholder.com/40'}" alt="${user.username}">
                    ${user.username}
                </span>
                <span class="right">
                    ${user.points} 
                    <a href="chat.php?user=${user.id}" style="margin-left:15px; color:#f26600; text-decoration:none;" title="Mulai Chat"><i class="fa-regular fa-comment"></i></a>
                </span>
            </div>`;
        });
    })
    .catch(err => console.error('Error fetching leaderboard:', err));
})();
</script>
</body>
</html>




