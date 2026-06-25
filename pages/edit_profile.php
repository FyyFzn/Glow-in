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
  <link rel="stylesheet" href="../assets/CSS/base.css?v=105" />
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
        <p id="profile-loading" class="loading-state mb-16">Memuat data profil via REST API...</p>
        <div id="profile-form-body" class="d-none">
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

          <div class="post-actions mt-20">
            <a href="profile.php" class="btn cancel">Cancel</a>
            <button type="button" class="btn btn-primary submit" onclick="saveProfile()">Save Profile</button>
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
            document.getElementById('profile-loading').classList.add('d-none');
            document.getElementById('profile-form-body').classList.remove('d-none');

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
        const formEl = document.getElementById('edit-profile-form');
        if (formEl && !formEl.checkValidity()) {
            formEl.reportValidity();
            return;
        }

        const btnEl = document.querySelector('.post-actions .submit');
        const origText = btnEl ? btnEl.textContent : 'Save Profile';
        if (btnEl) {
            btnEl.disabled = true;
            btnEl.textContent = 'Saving...';
        }

        const payload = {
            name: document.getElementById('profile-name').value.trim(),
            bio: document.getElementById('profile-bio').value.trim(),
            location: document.getElementById('profile-location').value.trim(),
            profile_pic: document.getElementById('profile-pic').value.trim(),
            header_pic: document.getElementById('profile-header').value.trim()
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
            if (btnEl) {
                btnEl.disabled = false;
                btnEl.textContent = origText;
            }
            if(data.success) {
                alert("Profile berhasil diperbarui!");
                window.location.href = 'profile.php';
            } else {
                alert("Error: " + (data.error || 'Gagal menyimpan'));
            }
        })
        .catch(err => {
            if (btnEl) {
                btnEl.disabled = false;
                btnEl.textContent = origText;
            }
            alert("Gagal menyimpan profil: " + err);
        });
    }
</script>
    </div>
  </main>

<?php require_once '../includes/rightbar.php'; ?>
<?php require_once '../includes/footer.php'; ?>
</div>
</body>
</html>
