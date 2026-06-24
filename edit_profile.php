<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php';

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

  <!-- CSS -->
  <link rel="stylesheet" href="assets/CSS/base.css?v=4" />
  <link rel="stylesheet" href="assets/CSS/post.css" />
  <!-- ICON & FONT -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

      <form id="edit-profile-form" class="post-card">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" id="profile-name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" placeholder="Your display name">
        </div>

        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" id="profile-bio" rows="3" placeholder="Tell us about yourself"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" id="profile-location" value="<?= htmlspecialchars($user['location'] ?? '') ?>" placeholder="e.g. Jakarta, Indonesia">
        </div>

        <div class="form-group">
            <label>Profile Picture URL</label>
            <input type="url" name="profile_pic" id="profile-pic" value="<?= htmlspecialchars($user['profile_pic'] ?? '') ?>" placeholder="https://example.com/image.jpg">
        </div>

        <div class="form-group">
            <label>Header / Cover URL</label>
            <input type="url" name="header_pic" id="profile-header" value="<?= htmlspecialchars($user['header_pic'] ?? '') ?>" placeholder="https://example.com/cover.jpg">
        </div>

        <div class="post-actions" style="margin-top: 20px;">
          <a href="profile.php" class="btn cancel" style="text-decoration:none; text-align:center; display:inline-block; padding:8px 16px;">Cancel</a>
          <button type="button" class="btn submit" onclick="saveProfile()">Save Profile</button>
        </div>
      </form>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    const currentUserId = <?= $_SESSION['user_id'] ?? 0 ?>;

    function saveProfile() {
        const payload = {
            name: document.getElementById('profile-name').value,
            bio: document.getElementById('profile-bio').value,
            location: document.getElementById('profile-location').value,
            profile_pic: document.getElementById('profile-pic').value,
            header_pic: document.getElementById('profile-header').value
        };

        fetch('api/users.php?id=' + currentUserId, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert("Profile berhasil diperbarui!");
                window.location.href = 'profile.php';
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(err => alert("Gagal menyimpan profil: " + err));
    }
</script>

    </div>
  </main>

  <!-- RIGHTBAR -->
<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>
