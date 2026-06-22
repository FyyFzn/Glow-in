<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow-in | Home</title>
    <link rel="stylesheet" href="./assets/CSS/base.css?v=6">
    <link rel="stylesheet" href="./assets/CSS/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <input type="checkbox" id="menu-toggle" class="hidden-checkbox">

    <div class="layout">
<?php require_once 'includes/sidebar.php'; ?>

        <main class="main-content">
            <header class="top-header">
                <label for="menu-toggle" class="menu-toggle-btn" aria-label="Buka Menu">
                    <i class="fa-solid fa-bars"></i>
                </label>
                <div class="main-nav-tabs">
                    <a href="#" class="tab active">For you</a>
                    <a href="#" class="tab">Folowing</a>
                </div>
            </header>

            <div class="container">
                <div id="posts-container">
                    <!-- Data posts akan dirender di sini oleh Javascript -->
                </div>

        </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    const currentUserId = "<?= $_SESSION['user_id'] ?? '' ?>";

    function loadPosts() {
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
                const isOwner = post.user_id == currentUserId;
                const date = new Date(post.created_at).toLocaleString('id-ID');
                
                let dropdownHtml = '';
                if (isOwner) {
                    dropdownHtml = `
                    <div class="post-dropdown" onclick="event.stopPropagation();">
                        <button class="post-dropdown-btn" onclick="this.nextElementSibling.classList.toggle('show');"><i class="fa-solid fa-ellipsis"></i></button>
                        <div class="post-dropdown-content">
                            <a href="edit_post.php?id=${post.id}"><i class="fa-solid fa-pen-to-square" style="color: blue;"></i> Edit</a>
                            <button onclick="deletePost(${post.id})" style="width: 100%; text-align: left; background:none; border:none; padding:12px 16px; cursor:pointer;"><i class="fa-solid fa-trash" style="color: red;"></i> Delete</button>
                        </div>
                    </div>`;
                }

                const postHtml = `
                <article class="tweet-card" style="cursor: pointer;">
                    <div onclick="window.location.href='detail.php?id=${post.id}';" class="click-post" style="display:block;">
                        <div class="post-header">
                            <img src="${post.profile_pic}" class="avatar" alt="Avatar">
                            <div class="post-user-info">
                                <div class="name">${post.username}</div>
                                <div class="handle">@${post.username} • ${date}</div>
                            </div>
                            ${dropdownHtml}
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

    function deletePost(postId) {
        if(confirm("Hapus postingan ini?")) {
            fetch('api/posts.php?id=' + postId, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + apiKey
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) loadPosts();
                else alert(data.error);
            });
        }
    }

    // Load first time
    loadPosts();
</script>
</body>
</html>
