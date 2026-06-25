<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Profile - Glow-in</title>
  <link rel="stylesheet" href="../assets/CSS/base.css?v=8" />
  <link rel="stylesheet" href="../assets/CSS/profile.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
      <h1>Edit Profile</h1>
    </header>

    <div class="container">
      <form id="edit-profile-form" class="post-card">
        <p id="profile-loading" style="color: #9CA3AF; margin-bottom: 16px;">Memuat data profil via REST API...</p>
        <div id="profile-form-body" style="display: none;">
          <div class="form-group">
              <label>Name</label>
              <input type="text" id="profile-name" placeholder="Your display name">
          </div>

          <div class="form-group">
              <label>Bio</label>
              <textarea id="profile-bio" rows="3" placeholder="Tell us about yourself"></textarea>
          </div>

          <div class="form-group">
              <label>Location</label>
              <input type="text" id="profile-location" placeholder="e.g. Jakarta, Indonesia">
          </div>

          <div class="form-group">
              <label>Profile Picture URL</label>
              <input type="url" id="profile-pic" placeholder="https://example.com/image.jpg">
          </div>

          <div class="form-group">
              <label>Header / Cover URL</label>
              <input type="url" id="profile-header" placeholder="https://example.com/cover.jpg">
          </div>

          <div class="post-actions" style="margin-top: 20px;">
            <a href="profile.php" class="btn cancel" style="text-decoration:none; text-align:center; display:inline-block; padding:8px 16px;">Cancel</a>
            <button type="button" class="btn submit" onclick="saveProfile()">Save Profile</button>
          </div>
        </div>
      </form>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    const currentUserId = <?= $_SESSION['user_id'] ?? 0 ?>;

    document.addEventListener('DOMContentLoaded', () => {
        fetch('../controllers/userController.php?id=' + currentUserId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        })
        .then(res => res.json())
        .then(user => {
            document.getElementById('profile-loading').style.display = 'none';
            document.getElementById('profile-form-body').style.display = 'block';

            if(user && !user.error) {
                document.getElementById('profile-name').value = user.name || '';
                document.getElementById('profile-bio').value = user.bio || '';
                document.getElementById('profile-location').value = user.location || '';
                document.getElementById('profile-pic').value = user.profile_pic || '';
                document.getElementById('profile-header').value = user.header_pic || '';
            }
        })
        .catch(() => {
            document.getElementById('profile-loading').textContent = 'Gagal memuat profil via REST API.';
        });
    });

    function saveProfile() {
        const payload = {
            name: document.getElementById('profile-name').value,
            bio: document.getElementById('profile-bio').value,
            location: document.getElementById('profile-location').value,
            profile_pic: document.getElementById('profile-pic').value,
            header_pic: document.getElementById('profile-header').value
        };

        fetch('../controllers/userController.php?id=' + currentUserId, {
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
                alert("Error: " + (data.error || 'Gagal menyimpan'));
            }
        })
        .catch(err => alert("Gagal menyimpan profil: " + err));
    }
</script>
    </div>
  </main>

<?php require_once '../includes/rightbar.php'; ?>
<?php require_once '../includes/footer.php'; ?>
</div>
</body>
</html>
