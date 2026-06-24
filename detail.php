<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$post_id = $_GET['id'] ?? 0;
if (!$post_id) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow-in | Detail Post</title>
    <link rel="stylesheet" href="assets/CSS/base.css?v=6">
    <link rel="stylesheet" href="assets/CSS/home.css">
    <link rel="stylesheet" href="assets/CSS/detail.css">
    <link rel="stylesheet" href="assets/CSS/post.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="layout">
<?php require_once 'includes/sidebar.php'; ?>

        <main class="main-content">
            <header class="top-header">
                <a href="home.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
                <h1>Detail</h1>
            </header>

            <div class="post-card" id="post-card">
                <!-- Post akan dimuat via API -->
            </div>

            <div class="comments-section">
                <h3>Comments (<span id="comments-count">0</span>)</h3>
                <div id="comments-container">
                    <!-- Komentar akan dimuat via API -->
                </div>
            </div>
        </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

<script>
const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
const postId = "<?= $post_id; ?>";
const currentUserId = "<?= $_SESSION['user_id'] ?? '' ?>";

async function checkLikeStatus(pId) {
    const res = await fetch('api/likes.php?post_id=' + pId, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const data = await res.json();
    return data ? true : false;
}

async function toggleLike(pId, iconEl, countEl) {
    const isLiked = await checkLikeStatus(pId);
    const method = isLiked ? 'DELETE' : 'POST';
    const res = await fetch('api/likes.php?post_id=' + pId, {
        method: method,
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const data = await res.json();
    if (data.success) {
        countEl.textContent = data.count;
        if (isLiked) {
            iconEl.classList.remove('fa-solid');
            iconEl.classList.add('fa-regular');
        } else {
            iconEl.classList.remove('fa-regular');
            iconEl.classList.add('fa-solid');
        }
    }
}

async function checkFollowStatus(uId) {
    if (uId == currentUserId) return false;
    const res = await fetch('api/follows.php?user_id=' + uId, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const data = await res.json();
    return data ? true : false;
}

async function toggleFollow(uId, btnEl) {
    const isFollowing = await checkFollowStatus(uId);
    const method = isFollowing ? 'DELETE' : 'POST';
    const res = await fetch('api/follows.php?user_id=' + uId, {
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
    }
}

async function loadPost() {
    const res = await fetch('api/posts.php?id=' + postId, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const post = await res.json();
    if (post.error) {
        window.location.href = 'home.php';
        return;
    }

    const date = new Date(post.created_at).toLocaleString('id-ID');
    const isOwner = post.user_id == currentUserId;
    const isLiked = await checkLikeStatus(post.id);
    const isFollowing = !isOwner ? await checkFollowStatus(post.user_id) : false;

    let followBtnHtml = '';
    if (!isOwner) {
        followBtnHtml = `
        <button class="btn ${isFollowing ? 'btn-outline' : 'btn-primary'}" onclick="toggleFollow(${post.user_id}, this)" style="padding: 6px 12px; font-size:12px;">
            ${isFollowing ? '<i class="fa-solid fa-user-minus"></i> Unfollow' : '<i class="fa-solid fa-user-plus"></i> Follow'}
        </button>`;
    }

    const likeIconClass = isLiked ? 'fa-solid' : 'fa-regular';

    document.getElementById('post-card').innerHTML = `
        <div class="post-header">
            <img src="${post.profile_pic}" class="avatar">
            <div class="post-user-info">
                <div class="name">${post.username}</div>
                <div class="handle">@${post.username}</div>
            </div>
            ${followBtnHtml}
        </div>
        <p class="post-body">${post.content}</p>
        <div class="post-meta">
            <span class="time">${date}</span>
            <div class="tweet-actions">
                <div class="item" onclick="toggleLike(${post.id}, this.querySelector('i'), this.querySelector('span'));">
                    <i class="${likeIconClass} fa-heart"></i>
                    <span>${post.like_count || 0}</span>
                </div>
                <div class="item" onclick="window.location.href='edit_post.php?type=comment&post_id=${post.id}'; event.stopPropagation();">
                    <i class="fa-regular fa-comment"></i>
                    <span>${post.comment_count || 0}</span>
                </div>
                <div class="item"><i class="fa-solid fa-share-nodes"></i><span>0</span></div>
            </div>
        </div>
    `;
}

async function loadComments() {
    const res = await fetch('api/comments.php?post_id=' + postId, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const comments = await res.json();
    document.getElementById('comments-count').textContent = comments.length;
    const container = document.getElementById('comments-container');
    container.innerHTML = '';
    comments.forEach(comment => {
        const isOwner = comment.user_id == currentUserId;
        const date = new Date(comment.created_at).toLocaleString('id-ID');
        let deleteBtn = '';
        if (isOwner) {
            deleteBtn = `
            <div class="comment-actions">
                <button onclick="deleteComment(${comment.id})" style="background:none; border:none; color:red; cursor:pointer;">Delete</button>
            </div>`;
        }
        container.innerHTML += `
        <div class="comment">
            <img src="${comment.profile_pic}" class="comment-avatar">
            <div class="comment-content">
                <div class="comment-header">
                    <span class="comment-name">${comment.username}</span>
                    <span class="comment-handle">@${comment.username}</span>
                    <span class="dot">•</span>
                    <span class="comment-time">${date}</span>
                </div>
                <p class="comment-text">${comment.comment_text}</p>
                ${deleteBtn}
            </div>
        </div>`;
    });
}

async function deleteComment(commentId) {
    if (confirm('Delete this comment?')) {
        const res = await fetch('api/comments.php?id=' + commentId, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();
        if (data.success) {
            loadComments();
            loadPost();
        }
    }
}

loadPost();
loadComments();
</script>
</body>
</html>