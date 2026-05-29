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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glow-in Explore</title>

  <!-- Fonts & Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="assets/CSS/explore.css">
</head>

<body>
  <input type="checkbox" id="menu-toggle" class="hidden-checkbox">

  <div class="layout">

<?php require_once 'includes/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <header class="top-header">
        <label for="menu-toggle" class="menu-toggle-btn" aria-label="Buka Menu">
          <i class="fa-solid fa-bars"></i>
        </label>
        <img src="https://i.pravatar.cc/40?img=12" class="avatar-top">
        <div class="search-bar-desktop">
          <input type="text" placeholder="Search Glow-in">
          <i class="fas fa-search"></i>
        </div>
      </header>

      <div class="container">

        <!-- POST -->
        <article class="tweet-card">
          <div class="post-header">
            <img src="https://i.pravatar.cc/40?img=12" class="avatar">
            <div class="post-user-info">
              <div class="name">Willem Dafoe</div>
              <div class="handle">@willem.dafoe · 2h</div>
            </div>
          </div>

          <p class="post-body">
            Going stars monopoly is go. Claim star share at 3.
          </p>

          <div class="tweet-actions">
            <div class="item"><i class="fa-regular fa-heart"></i><span>9</span></div>
            <div class="item"><i class="fa-regular fa-comment"></i><span>5</span></div>
            <div class="item"><i class="fa-solid fa-retweet"></i><span>8</span></div>
            <div class="item"><i class="fa-solid fa-share-nodes"></i><span>3</span></div>
          </div>
        </article>

        <!-- POST IMAGE -->
        <article class="tweet-card">
          <div class="post-header">
            <img src="https://i.pravatar.cc/40?img=5" class="avatar">
            <div class="post-user-info">
              <div class="name">Harold</div>
              <div class="handle">@harold · 5m</div>
            </div>
          </div>

          <p class="post-body">Vacation is going great! Loving the peace 🌴</p>

          <div class="tweet-media">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30">
          </div>

          <div class="tweet-actions">
            <div class="item"><i class="fa-regular fa-heart"></i><span>34</span></div>
            <div class="item"><i class="fa-regular fa-comment"></i><span>12</span></div>
            <div class="item"><i class="fa-solid fa-retweet"></i><span>10</span></div>
            <div class="item"><i class="fa-solid fa-share-nodes"></i><span>7</span></div>
          </div>
        </article>

      </div>
    </main>

    <!-- RIGHT SIDEBAR -->
<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

