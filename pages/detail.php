<?php
session_start();
require_once '../config.php';
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
    <title>Detail Post - Glow-in</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/CSS/base.css?v=6">
    <link rel="stylesheet" href="../assets/CSS/detail.css">
</head>
<body>
    <div class="layout">
<?php require_once '../includes/sidebar.php'; ?>

        <main class="main-content">
            <div class="detail-container">
                <div class="detail-grid">
                    
                    <!-- KARTU KIRI: MAIN POST -->
                    <article class="detail-post-card" id="main-post-card">
                        <p class="empty-state">Memuat detail postingan...</p>
                    </article>

                    <!-- KARTU KANAN: COMMENTS & ACTION -->
                    <section class="detail-interaction-card">
                        <div class="detail-comments-list" id="detail-comments-container">
                            <p class="empty-state">Memuat komentar...</p>
                        </div>

                        <div class="detail-card-footer">
                            <div class="detail-stats-row">
                                <button class="detail-stat-btn" id="btn-like-action" onclick="handleLike()">
                                    <i class="fa-regular fa-heart" id="detail-like-icon"></i>
                                    <span id="detail-like-count">0</span>
                                </button>
                                <div class="detail-stat-item">
                                    <i class="fa-regular fa-comment"></i>
                                    <span id="detail-comment-count">0</span>
                                </div>
                                <div class="detail-stat-item">
                                    <i class="fa-solid fa-share-nodes"></i>
                                    <span>0</span>
                                </div>
                            </div>

                            <div class="detail-input-box">
                                <button class="btn-input-emoji" type="button"><i class="fa-regular fa-face-smile"></i></button>
                                <input type="text" id="comment-input-field" class="detail-comment-input" placeholder="Tambahkan komentar..." onkeypress="if(event.key==='Enter') handleAddComment();">
                                <button class="btn-send-comment" onclick="handleAddComment()"><i class="fa-regular fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </main>
    </div>

<script>
const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
const postId = "<?= $post_id ?>";
const currentUserId = "<?= $_SESSION['user_id'] ?? '' ?>";

let isPostLiked = false;

async function fetchLikeStatus() {
    const res = await fetch(`../controllers/likeController.php?post_id=${postId}`, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const data = await res.json();
    return data ? true : false;
}

async function handleLike() {
    const method = isPostLiked ? 'DELETE' : 'POST';
    const res = await fetch(`../controllers/likeController.php?post_id=${postId}`, {
        method: method,
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const data = await res.json();
    if (data.success) {
        isPostLiked = !isPostLiked;
        const iconEl = document.getElementById('detail-like-icon');
        const countEl = document.getElementById('detail-like-count');
        countEl.textContent = data.count;
        if (isPostLiked) {
            iconEl.classList.remove('fa-regular');
            iconEl.classList.add('fa-solid');
        } else {
            iconEl.classList.remove('fa-solid');
            iconEl.classList.add('fa-regular');
        }
    }
}

function reportCurrentPost() {
    window.location.href = `report.php?post_id=${postId}`;
}

async function loadPostDetail() {
    const res = await fetch(`../controllers/postController.php?id=${postId}`, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const post = await res.json();
    if (post.error) {
        window.location.href = 'home.php';
        return;
    }

    isPostLiked = await fetchLikeStatus();
    const likeIcon = document.getElementById('detail-like-icon');
    const likeCount = document.getElementById('detail-like-count');
    const commentCount = document.getElementById('detail-comment-count');

    likeCount.textContent = post.like_count || 0;
    commentCount.textContent = post.comment_count || 0;
    if (isPostLiked) {
        likeIcon.classList.remove('fa-regular');
        likeIcon.classList.add('fa-solid');
    }

    let imgHtml = '';
    if (post.image && post.image.trim() !== '') {
        imgHtml = `<div class="detail-img-wrapper"><img src="${post.image}" class="detail-post-img" alt="Post Image"></div>`;
    }

    const contentLines = post.content ? post.content.split('\n') : [''];
    let titleHtml = '';
    let bodyText = post.content || '';

    if (contentLines.length > 1 && contentLines[0].length < 80) {
        titleHtml = `<h2 class="detail-post-title">${contentLines[0]}</h2>`;
        bodyText = contentLines.slice(1).join('\n').trim();
    }

    document.getElementById('main-post-card').innerHTML = `
        <header class="detail-post-header">
            <div class="detail-author-info">
                <img src="${post.profile_pic}" class="detail-avatar" alt="Avatar">
                <span class="detail-author-name">${post.username}</span>
            </div>
            <button class="btn-report-post" onclick="reportCurrentPost()">
                <i class="fa-regular fa-flag"></i> Report
            </button>
        </header>
        ${imgHtml}
        ${titleHtml}
        <p class="detail-post-body">${bodyText}</p>
    `;
}

async function loadCommentsDetail() {
    const res = await fetch(`../controllers/commentController.php?post_id=${postId}`, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    });
    const comments = await res.json();
    const container = document.getElementById('detail-comments-container');
    document.getElementById('detail-comment-count').textContent = Array.isArray(comments) ? comments.length : 0;


    container.innerHTML = '';
    comments.forEach(c => {
        const isOwner = c.user_id == currentUserId;
        let delBtn = '';
        if (isOwner) {
            delBtn = `<button class="detail-comment-delete" onclick="deleteCommentDetail(${c.id})">Hapus</button>`;
        }
        container.innerHTML += `
        <div class="detail-comment-item">
            <img src="${c.profile_pic}" class="detail-comment-avatar" alt="Avatar">
            <div class="detail-comment-content">
                <div class="detail-comment-author">${c.username}</div>
                <div class="detail-comment-text">${c.comment_text}</div>
                ${delBtn}
            </div>
        </div>`;
    });
}

async function handleAddComment() {
    const inputEl = document.getElementById('comment-input-field');
    const text = inputEl.value.trim();
    if (!text) return;

    inputEl.value = '';
    inputEl.focus();

    await fetch('../controllers/commentController.php', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + apiKey,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user_id: currentUserId,
            post_id: postId,
            comment_text: text
        })
    });

    loadCommentsDetail();
    loadPostDetail();
}

async function deleteCommentDetail(cId) {
    if (confirm('Hapus komentar ini?')) {
        await fetch(`../controllers/commentController.php?id=${cId}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        loadCommentsDetail();
        loadPostDetail();
    }
}

loadPostDetail();
loadCommentsDetail();
</script>
</body>
</html>