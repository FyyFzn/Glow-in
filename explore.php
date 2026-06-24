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
  <link rel="stylesheet" href="assets/CSS/base.css?v=6">
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

        <div id="posts-container">
          <!-- Data posts akan dirender di sini oleh Javascript -->
        </div>

      </div>
    </main>

    <!-- RIGHT SIDEBAR -->
<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    const currentUserId = "<?= $_SESSION['user_id'] ?? '' ?>";

    function loadExplorePosts() {
        fetch('api/posts.php', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('posts-container');
            container.innerHTML = '';

            data.forEach(post => {
                const date = new Date(post.created_at).toLocaleString('id-ID');

                const postHtml = `
                <article class="tweet-card" style="cursor: pointer;">
                    <div onclick="window.location.href='detail.php?id=${post.id}';" class="click-post" style="display:block;">
                        <div class="post-header">
                            <img src="${post.profile_pic}" class="avatar" alt="Avatar">
                            <div class="post-user-info">
                                <div class="name">${post.username}</div>
                                <div class="handle">@${post.username} • ${date}</div>
                            </div>
                        </div>
                        <p class="post-body" style="margin-top: 10px;">${post.content}</p>

                        <div class="tweet-actions" style="margin-top: 15px;">
                            <div class="item"><i class="fa-regular fa-heart"></i><span>0</span></div>
                            <div class="item" onclick="event.stopPropagation();"><i class="fa-regular fa-comment"></i><span>${post.comment_count || 0}</span></div>
                            <div class="item" onclick="event.stopPropagation();"><i class="fa-solid fa-share-nodes"></i><span>0</span></div>
                        </div>
                    </div>
                </article>
                `;
                container.innerHTML += postHtml;
            });
        })
        .catch(error => console.error('Error fetching posts:', error));
    }

    loadExplorePosts();
</script>

