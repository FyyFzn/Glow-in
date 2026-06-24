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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;500;600;700&display=swap" rel="stylesheet">
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
                    <a href="#" class="tab">Following</a>
                </div>
            </header>

            <div class="container">
                <div id="posts-container">
                    <!-- Data posts akan dirender di sini oleh Javascript -->
                </div>
            </div>
        </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    const currentUserId = "<?= $_SESSION['user_id'] ?? '' ?>";

    async function checkLikeStatus(postId) {
        const res = await fetch('api/likes.php?post_id=' + postId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();
        return data ? true : false;
    }

    async function getLikeCount(postId) {
        const res = await fetch('api/posts.php?id=' + postId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const post = await res.json();
        // We'll get count from likes API for now
        const countRes = await fetch('api/likes.php?post_id=' + postId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        // Wait let's fix posts API to include like count, for now we'll calculate
        return 0;
    }

    async function toggleLike(postId, iconEl, countEl) {
        event.stopPropagation();
        const isLiked = await checkLikeStatus(postId);
        
        const method = isLiked ? 'DELETE' : 'POST';
        const res = await fetch('api/likes.php?post_id=' + postId, {
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
        const res = await fetch('api/follows.php?user_id=' + userId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();
        return data ? true : false;
    }

    async function toggleFollow(userId, btnEl) {
        event.stopPropagation();
        const isFollowing = await checkFollowStatus(userId);
        
        const method = isFollowing ? 'DELETE' : 'POST';
        const res = await fetch('api/follows.php?user_id=' + userId, {
            method: method,
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();
        
        if (data.success) {
            if (isFollowing) {
                btnEl.innerHTML = '<i class="fa-solid fa-user-plus"></i> Follow';
                btnEl.classList.remove('btn-outline');
                btnEl.classList.add('btn-primary');
            } else {
                btnEl.innerHTML = '<i class="fa-solid fa-user-minus"></i> Unfollow';
                btnEl.classList.remove('btn-primary');
                btnEl.classList.add('btn-outline');
            }
        } else {
            alert(data.error);
        }
    }

    async function loadPosts() {
        const container = document.getElementById('posts-container');
        container.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Loading...</p>';
        
        try {
            const res = await fetch('api/posts.php', {
                headers: { 'Authorization': 'Bearer ' + apiKey }
            });
            const posts = await res.json();
            
            container.innerHTML = '';
            
            if (!Array.isArray(posts) || posts.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">No posts yet. Be the first to post!</p>';
                return;
            }
            
            for (const post of posts) {
                const isOwner = post.user_id == currentUserId;
                const date = new Date(post.created_at).toLocaleString('id-ID');
                const isLiked = await checkLikeStatus(post.id);
                const isFollowing = !isOwner ? await checkFollowStatus(post.user_id) : false;
                
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
                
                let followBtnHtml = '';
                if (!isOwner) {
                    followBtnHtml = `
                    <button class="btn ${isFollowing ? 'btn-outline' : 'btn-primary'}" onclick="toggleFollow(${post.user_id}, this)" style="padding: 6px 12px; font-size: 12px;">
                        ${isFollowing ? '<i class="fa-solid fa-user-minus"></i> Unfollow' : '<i class="fa-solid fa-user-plus"></i> Follow'}
                    </button>`;
                }
                
                const likeIconClass = isLiked ? 'fa-solid' : 'fa-regular';
                
                const postHtml = `
                <article class="tweet-card">
                    <div onclick="window.location.href='detail.php?id=${post.id}';" class="click-post" style="display:block; cursor:pointer;">
                        <div class="post-header">
                            <img src="${post.profile_pic}" class="avatar" alt="Avatar">
                            <div class="post-user-info">
                                <div class="name">${post.username}</div>
                                <div class="handle">@${post.username} • ${date}</div>
                            </div>
                            ${followBtnHtml}
                            ${dropdownHtml}
                        </div>
                        <p class="post-body" style="margin-top: 10px;">${post.content}</p>
                        
                        <div class="tweet-actions" style="margin-top: 15px;">
                            <div class="item" onclick="toggleLike(${post.id}, this.querySelector('i'), this.querySelector('span'));">
                                <i class="${likeIconClass} fa-heart"></i>
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
            container.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Failed to load posts.</p>';
        }
    }

    function deletePost(postId) {
        event.stopPropagation();
        if(confirm("Hapus postingan ini?")) {
            fetch('api/posts.php?id=' + postId, {
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