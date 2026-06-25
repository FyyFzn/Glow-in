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
        <label for="menu-toggle" class="menu-toggle-btn" aria-label="Buka Menu">
          <i class="fa-solid fa-bars"></i>
        </label>
        <img src="<?= htmlspecialchars($userAvatar) ?>" class="avatar-top" alt="Avatar">
        <div class="search-bar-desktop">
          <input type="text" placeholder="Search Glow-in">
          <i class="fas fa-search"></i>
        </div>
      </header>

      <div class="container">
        <div id="posts-container">
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
            if (data.error || !Array.isArray(data) || data.length === 0) {
                container.innerHTML = '<p class="empty-state">Belum ada postingan</p>';
                return;
            }
            container.innerHTML = '';

            data.forEach(post => {
                let imgHtml = '';
                if (post.image && post.image.trim() !== '') {
                    imgHtml = `<div class="tweet-media"><img src="${post.image}" alt="Post Image"></div>`;
                }

                const postHtml = `
                <article class="tweet-card explore-card">
                    <div onclick="window.location.href='detail.php?id=${post.id}';" class="click-post explore-card-link clickable-card">
                        <div class="post-header">
                            <img src="${post.profile_pic}" class="avatar" alt="Avatar">
                            <div class="post-user-info">
                                <span class="name">${post.username}</span>
                            </div>
                        </div>
                        <p class="post-body explore-post-body">${post.content}</p>
                        ${imgHtml}
                        <div class="post-divider"></div>
                        <div class="tweet-actions explore-tweet-actions">
                            <div class="item"><i class="fa-regular fa-heart"></i><span>${post.like_count || 0}</span></div>
                            <div class="item" onclick="event.stopPropagation();"><i class="fa-regular fa-comment"></i><span>${post.comment_count || 0}</span></div>
                            <div class="item" onclick="event.stopPropagation();"><i class="fa-solid fa-share-nodes"></i><span>0</span></div>
                        </div>
                    </div>
                </article>
                `;
                container.innerHTML += postHtml;
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
