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
  <title>Glow-in | New Post</title>

  <!-- ICON & FONT -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="assets/CSS/post.css" />
  <link rel="stylesheet" href="assets/CSS/base.css?v=4" />
</head>

<body>
<input type="checkbox" id="menu-toggle" class="hidden-checkbox">

<div class="layout">

  <!-- LEFTBAR -->
<?php require_once 'includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="main-content">
    <header class="top-header">
      <label for="menu-toggle" class="menu-toggle-btn">
        <i class="fa-solid fa-bars"></i>
      </label>
      <h1>Create Post</h1>
    </header>

    <div class="container">

      <form action="api/posts.php" method="POST" class="post-card">
        <input type="hidden" name="action" value="create">
        <div class="post-header">
          <img src="https://i.pravatar.cc/48?img=12" class="avatar">
          <div>
            <div class="name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></div>
            <div class="handle">@<?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?></div>
          </div>
        </div>

        <textarea name="content" class="post-textarea" placeholder="What's on your mind?" required></textarea>

        <div class="upload-box">
          <i class="fa-regular fa-image"></i>
          <span>Upload image</span>
        </div>

        <div class="post-actions">
          <a href="home.php" class="btn cancel" style="text-decoration:none; text-align:center; display:inline-block; padding:8px 16px;">Cancel</a>
          <button type="submit" class="btn submit">Post</button>
        </div>
      </form>

    </div>
  </main>

  <!-- RIGHTBAR -->
<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

