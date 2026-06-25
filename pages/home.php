<?php
session_start();
require_once '../config.php';
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
    <link rel="stylesheet" href="../assets/CSS/base.css?v=101">
    <link rel="stylesheet" href="../assets/CSS/home.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;500;600;700&display=swap" rel="stylesheet">
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
                <div class="main-nav-tabs">
                    <a href="#" class="tab active">For you</a>
                    <a href="#" class="tab">Following</a>
                </div>
            </header>

            <div class="container">
                <div id="posts-container">
                    <!-- Data posts akan dirender di sini oleh Javascript -->
                </div>
            </div>
        </main>

<?php require_once '../includes/rightbar.php'; ?>
<?php require_once '../includes/footer.php'; ?>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    const currentUserId = "<?= $_SESSION['user_id'] ?? '' ?>";

    async function checkLikeStatus(postId) {
        const res = await fetch('../controllers/likeController.php?post_id=' + postId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();
        return data ? true : false;
    }

    async function getLikeCount(postId) {
        const res = await fetch('../controllers/postController.php?id=' + postId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const post = await res.json();

        const countRes = await fetch('../controllers/likeController.php?post_id=' + postId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });

        return 0;
    }

    async function toggleLike(postId, iconEl, countEl) {
        event.stopPropagation();
        const isLiked = await checkLikeStatus(postId);

        const method = isLiked ? 'DELETE' : 'POST';
        const res = await fetch('../controllers/likeController.php?post_id=' + postId, {
            method: method,
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();

        if (data.success) {
            countEl.textContent = data.count;
            if (isLiked) {
                iconEl.classList.remove('fa-solid', 'text-red-500');
                iconEl.classList.add('fa-regular');
            } else {
                iconEl.classList.remove('fa-regular');
                iconEl.classList.add('fa-solid', 'text-red-500');
            }
        } else {
            alert(data.error);
        }
    }

    async function checkFollowStatus(userId) {
        if (userId == currentUserId) return false;
        const res = await fetch('../controllers/followController.php?user_id=' + userId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();
        return data ? true : false;
    }

    async function toggleFollow(userId, btnEl) {
        event.stopPropagation();
        const isFollowing = await checkFollowStatus(userId);

        const method = isFollowing ? 'DELETE' : 'POST';
        const res = await fetch('../controllers/followController.php?user_id=' + userId, {
            method: method,
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();

        if (data.success) {
            if (isFollowing) {
                btnEl.textContent = 'Follow';
                btnEl.classList.remove('following');
            } else {
                btnEl.textContent = 'Following';
                btnEl.classList.add('following');
            }
        } else {
            alert(data.error);
        }
    }

    async function loadPosts() {
        const container = document.getElementById('posts-container');
        container.innerHTML = '<p class="loading-state">Loading...</p>';

        try {
            const res = await fetch('../controllers/postController.php', {
                headers: { 'Authorization': 'Bearer ' + apiKey }
            });
            const posts = await res.json();

            container.innerHTML = '';

            if (!Array.isArray(posts) || posts.length === 0) {
                container.innerHTML = '<p class="empty-state">No posts yet. Be the first to post!</p>';
                return;
            }

            for (const post of posts) {
                const isOwner = post.user_id == currentUserId;
                const isLiked = await checkLikeStatus(post.id);
                const isFollowing = !isOwner ? await checkFollowStatus(post.user_id) : false;

                let dropdownHtml = '';
                if (isOwner) {
                    dropdownHtml = `
                    <div class="post-dropdown" onclick="event.stopPropagation();">
                        <button class="post-dropdown-btn" onclick="this.nextElementSibling.classList.toggle('show');"><i class="fa-solid fa-ellipsis"></i></button>
                        <div class="post-dropdown-content">
                            <a href="edit_post.php?id=${post.id}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <button onclick="deletePost(${post.id})"><i class="fa-solid fa-trash"></i> Delete</button>
                        </div>
                    </div>`;
                }

                let followBtnHtml = '';
                if (!isOwner && !post.is_anonymous) {
                    followBtnHtml = `
                    <button class="follow-pill-btn ${isFollowing ? 'following' : ''}" onclick="toggleFollow(${post.user_id}, this)">
                        ${isFollowing ? 'Following' : 'Follow'}
                    </button>`;
                }

                const likeIconClass = isLiked ? 'fa-solid text-red-500' : 'fa-regular';

                let imgHtml = '';
                if (post.image && post.image.trim() !== '') {
                    imgHtml = `<div class="tweet-media"><img src="${post.image}" alt="Post Image"></div>`;
                }

                const postHtml = `
                <article class="tweet-card">
                    <div onclick="window.location.href='detail.php?id=${post.id}';" class="click-post clickable-card">
                        <div class="post-header">
                            <img src="${post.profile_pic}" class="avatar" alt="Avatar">
                            <div class="post-user-info">
                                <span class="name">${post.username}</span>
                            </div>
                            ${followBtnHtml}
                            ${dropdownHtml}
                        </div>
                        <p class="post-body">${post.content}</p>
                        ${imgHtml}
                        <div class="post-divider"></div>
                        <div class="tweet-actions">
                            <div class="item" onclick="toggleLike(${post.id}, this.querySelector('i'), this.querySelector('span'));">
                                <i class="${likeIconClass} fa-heart ${isLiked ? 'text-error' : ''}"></i>
                                <span>${post.like_count || 0}</span>
                            </div>
                            <div class="item" onclick="window.location.href='detail.php?id=${post.id}'; event.stopPropagation();">
                                <i class="fa-regular fa-comment"></i>
                                <span>${post.comment_count || 0}</span>
                            </div>
                            <div class="item" onclick="event.stopPropagation();"><i class="fa-solid fa-share-nodes"></i><span>0</span></div>
                        </div>
                    </div>
                </article>
                `;
                container.innerHTML += postHtml;
            }
        } catch (error) {
            console.error('Error fetching posts:', error);
            container.innerHTML = '<p class="error-state">Failed to load posts.</p>';
        }
    }

    function deletePost(postId) {
        event.stopPropagation();
        if(confirm("Hapus postingan ini?")) {
            fetch('../controllers/postController.php?id=' + postId, {
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + apiKey }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) loadPosts();
                else alert(data.error);
            });
        }
    }

    loadPosts();
</script>
</body>
</html>