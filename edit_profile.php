<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php';

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow-in | Edit Profile</title>

  <!-- ICON & FONT -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="assets/CSS/post.css" />
  <style>
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        box-sizing: border-box;
    }
    .form-group input:focus, .form-group textarea:focus {
        outline: none;
        border-color: var(--accent-orange, #ff6b00);
    }
  </style>
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
      <h1>Edit Profile</h1>
    </header>

    <div class="container">

      <form action="api/users.php" method="POST" class="post-card">
        <input type="hidden" name="action" value="update_profile">
        
        <div class="form-group">
            <label>Name Depan</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" placeholder="Your display name">
        </div>

        <div class="form-group">
            <label>Nama Belakang</label>
            <input type="text" name="nameback" value="<?= htmlspecialchars($user['nameback'] ?? '') ?>" placeholder="Your display name">
        </div>

        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" rows="3" placeholder="Tell us about yourself"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" value="<?= htmlspecialchars($user['location'] ?? '') ?>" placeholder="e.g. Jakarta, Indonesia">
        </div>

        <div class="form-group">
            <label>Profile Picture URL</label>
            <input type="url" name="profile_pic" value="<?= htmlspecialchars($user['profile_pic'] ?? '') ?>" placeholder="https://example.com/image.jpg">
        </div>

        <div class="form-group">
            <label>Header / Cover URL</label>
            <input type="url" name="header_pic" value="<?= htmlspecialchars($user['header_pic'] ?? '') ?>" placeholder="https://example.com/cover.jpg">
        </div>

        <div class="post-actions" style="margin-top: 20px;">
          <a href="profile.php" class="btn cancel" style="text-decoration:none; text-align:center; display:inline-block; padding:8px 16px;">Cancel</a>
          <button type="submit" class="btn submit">Save Profile</button>
        </div>
      </form>

    </div>
  </main>

  <!-- RIGHTBAR -->
<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>
