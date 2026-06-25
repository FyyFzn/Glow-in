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
    <title>Profile - Glow-in</title>
    <link rel="stylesheet" href="../assets/CSS/base.css?v=99">
    <link rel="stylesheet" href="../assets/CSS/profile.css?v=116">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;500;600;700&display=swap" rel="stylesheet">
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
            <h1>Profile</h1>
        </header>

        <div class="container">
            <div class="profile-card">
                <div class="profile-cover" id="profile-cover">
                    <!-- Header pic will be loaded via API -->
                </div>
                <div class="profile-inner">
                    <div class="profile-header">
                        <img class="avatar-large" id="profile-avatar" src="" alt="Avatar">
                        <div class="profile-info">
                            <div class="profile-name-row">
                                <span class="profile-name" id="profile-name"></span>
                                <i class="fa-solid fa-circle-check verified-badge"></i>
                            </div>
                            <div class="profile-handle" id="profile-handle"></div>
                        </div>
                        <div class="profile-actions" id="profile-actions">
                            <a href="edit_profile.php" class="btn btn-message">Edit Profile</a>
                        </div>
                    </div>

                    <p class="profile-bio" id="profile-bio"></p>
                    <div class="profile-meta">
                        <span><i class="fa-regular fa-calendar"></i> Joined <span id="profile-joined"></span></span>
                        <span><i class="fa-solid fa-location-dot"></i> <span id="profile-location"></span></span>
                    </div>
                    <div class="profile-stats">
                        <a href="following.php" class="mutual-tab"><span class="count">10</span> Following</a>
                        <a href="followers.php" class="mutual-tab"><span class="count">10</span> Followers</a>
                    </div>

                    <div class="profile-tabs">
                        <a href="#" class="profile-tab active">Tweets</a>
                        <a href="#" class="profile-tab">Tweets & replies</a>
                        <a href="#" class="profile-tab">Media</a>
                        <a href="#" class="profile-tab">Likes</a>
                    </div>
                </div>
            </div>

            <div id="user-posts-container">
                <!-- User posts will be loaded via API -->
            </div>
        </div>
    </main>

    <?php require_once '../includes/rightbar.php'; ?>
    <?php require_once '../includes/footer.php'; ?>
</div>

<script>
const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
const urlParams = new URLSearchParams(window.location.search);
const currentUserId = urlParams.get('id') || "<?= $_SESSION['user_id'] ?? '' ?>";

function loadProfile() {
    fetch('../controllers/userController.php?id=' + currentUserId, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    })
    .then(response => response.json())
    .then(user => {
        if (user.error) {
            alert('Error loading profile: ' + user.error);
            return;
        }

        document.getElementById('profile-cover').style.backgroundImage = `url('${user.header_pic || 'https://images.unsplash.com/photo-1505839673365-e3971f8d9184?auto=format&fit=crop&w=1400&q=80'}')`;
        document.getElementById('profile-cover').style.backgroundPosition = user.header_pos || '50% 50%';
        document.getElementById('profile-avatar').src = user.profile_pic || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80';
        document.getElementById('profile-avatar').style.objectPosition = user.profile_pos || '50% 50%';
        document.getElementById('profile-name').textContent = user.name || user.username;
        document.getElementById('profile-handle').textContent = '@' + user.username;
        document.getElementById('profile-bio').textContent = user.bio || 'No bio available.';
        document.getElementById('profile-location').textContent = user.location || 'Unknown location';

        const joinedDate = new Date(user.created_at);
        document.getElementById('profile-joined').textContent = joinedDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });

        // Action buttons logic (Self vs Others)
        const loggedInId = parseInt("<?= $_SESSION['user_id'] ?? 0 ?>");
        const actionsEl = document.querySelector('.profile-actions');
        if (actionsEl) {
            if (parseInt(user.id) === loggedInId) {
                actionsEl.innerHTML = '<a href="edit_profile.php" class="btn btn-message font-bold">Edit Profile</a>';
            } else {
                fetch('../controllers/followController.php?user_id=' + user.id, {
                    headers: { 'Authorization': 'Bearer ' + apiKey }
                })
                .then(r => r.json())
                .then(fData => {
                    const isF = (fData && fData.success) || (fData && fData.is_following);
                    actionsEl.innerHTML = `<button type="button" class="follow-pill-btn ${isF ? 'following' : ''}" onclick="toggleProfileFollow(${user.id}, this)">${isF ? 'Following' : 'Follow'}</button>`;
                })
                .catch(() => {
                    actionsEl.innerHTML = `<button type="button" class="follow-pill-btn" onclick="toggleProfileFollow(${user.id}, this)">Follow</button>`;
                });
            }
        }
    })
    .catch(error => {
        console.error('Error loading profile:', error);
    });
}

async function toggleProfileFollow(userId, btnEl) {
    const isF = btnEl.classList.contains('following');
    const method = isF ? 'DELETE' : 'POST';
    
    btnEl.disabled = true;
    try {
        const res = await fetch('../controllers/followController.php?user_id=' + userId, {
            method: method,
            headers: { 'Authorization': 'Bearer ' + apiKey }
        });
        const data = await res.json();
        if (data && (data.success || !data.error)) {
            if (isF) {
                btnEl.textContent = 'Follow';
                btnEl.classList.remove('following');
            } else {
                btnEl.textContent = 'Following';
                btnEl.classList.add('following');
            }
        }
    } catch(e) {}
    btnEl.disabled = false;
}

function loadUserPosts() {
    fetch('../controllers/postController.php?user_id=' + currentUserId, {
        headers: { 'Authorization': 'Bearer ' + apiKey }
    })
    .then(response => response.json())
    .then(posts => {
        const container = document.getElementById('user-posts-container');
        container.innerHTML = '';

        const userPosts = Array.isArray(posts) ? posts.filter(post => post.user_id == currentUserId) : [];

        if (userPosts.length === 0) {
            container.innerHTML = '<p class="empty-state">No posts yet.</p>';
            return;
        }

        userPosts.forEach(post => {
            let imgHtml = '';
            if (post.image && post.image.trim() !== '') {
                imgHtml = `<div class="tweet-media"><img src="${post.image}" alt="Post Image"></div>`;
            }

            const postHtml = `
            <article class="tweet-card clickable-card" onclick="window.location.href='detail.php?id=${post.id}';">
                <div class="post-header">
                    <img src="${post.profile_pic}" class="avatar" alt="Avatar">
                    <div class="post-user-info">
                        <span class="name">${post.username}</span>
                    </div>
                </div>
                <p class="post-body">${post.content}</p>
                ${imgHtml}
                <div class="post-divider"></div>
                <div class="tweet-actions">
                    <div class="item"><i class="fa-regular fa-heart"></i><span>${post.like_count || 0}</span></div>
                    <div class="item"><i class="fa-regular fa-comment"></i><span>${post.comment_count || 0}</span></div>
                    <div class="item"><i class="fa-solid fa-share-nodes"></i><span>0</span></div>
                </div>
            </article>
            `;
            container.innerHTML += postHtml;
        });
    })
    .catch(error => {
        console.error('Error loading posts:', error);
        document.getElementById('user-posts-container').innerHTML = '<p class="error-state">Failed to load posts.</p>';
    });
}

loadProfile();
loadUserPosts();
</script>
</body>
</html>