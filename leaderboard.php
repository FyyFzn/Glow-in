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

    <div class="rank-card">
      <div class="rank-left">
        <span class="rank-number">125</span>
        <div>
          <p class="rank-label">Your Rank</p>
          <p class="rank-name">You (John Doe)</p>
        </div>
      </div>
      <div class="rank-points">4,500 points</div>
    </div>

    <div class="table">
      <div class="table-head">
        <span>Rank</span>
        <span>User</span>
        <span class="right">Points</span>
      </div>

      <div class="row"><span>1</span><span class="user"><img src="avatar.png">Alex Johnson</span><span class="right">15,400</span></div>
      <div class="row"><span>2</span><span class="user"><img src="avatar.png">Maria Gonzalez</span><span class="right">14,980</span></div>
      <div class="row"><span>3</span><span class="user"><img src="avatar.png">David Lee</span><span class="right">14,500</span></div>
      <div class="row"><span>4</span><span class="user"><img src="avatar.png">Sophia Chen</span><span class="right">13,800</span></div>
      <div class="row"><span>5</span><span class="user"><img src="avatar.png">Ethan Wright</span><span class="right">13,200</span></div>
    </div>
    </div>
  </main>
<?php require_once 'includes/footer.php'; ?>




