<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$userAvatar = !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'https://ui-avatars.com/api/?name=User&background=ff6b00&color=ffffff';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Explore - Glow-in</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/CSS/base.css?v=99">
  <link rel="stylesheet" href="../assets/CSS/explore.css">
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
        <h1>Explore</h1>
      </header>

      <div class="container explore-grid-container">
        <div id="posts-container" class="pinterest-grid">
          <p class="empty-state">Memuat postingan...</p>
        </div>
      </div>
    </main>

<?php require_once '../includes/rightbar.php'; ?>
<?php require_once '../includes/footer.php'; ?>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";

    function loadExplorePosts() {
        fetch('../controllers/postController.php', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('posts-container');
            if (data.error || !Array.isArray(data)) {
                container.innerHTML = '<p class="empty-state">Gagal memuat postingan</p>';
                return;
            }
            const photoPosts = data.filter(post => post.image && post.image.trim() !== '');
            if (photoPosts.length === 0) {
                container.innerHTML = '<p class="empty-state">Belum ada foto untuk ditampilkan</p>';
                return;
            }
            container.innerHTML = '';

            photoPosts.forEach(post => {
                const cardHtml = `
                <div class="pinterest-card" onclick="window.location.href='detail.php?id=${post.id}';">
                    <div class="pinterest-img-wrapper">
                        <img src="${post.image}" alt="Explore Photo">
                        <div class="pinterest-overlay">
                            <div class="pinterest-likes"><i class="fa-solid fa-heart"></i> ${post.like_count || 0}</div>
                        </div>
                    </div>
                    <div class="pinterest-card-footer">
                        <img src="${post.profile_pic}" class="pinterest-avatar" alt="Avatar">
                        <span class="pinterest-username">${post.username}</span>
                    </div>
                </div>
                `;
                container.innerHTML += cardHtml;
            });
        })
        .catch(() => {
            const container = document.getElementById('posts-container');
            container.innerHTML = '<p class="empty-state">Gagal memuat postingan</p>';
        });
    }

    loadExplorePosts();
</script>
</body>
</html>
