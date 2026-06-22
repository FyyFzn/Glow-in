<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php';

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$displayName = !empty($user['name']) ? $user['name'] : $user['username'];
$bio = !empty($user['bio']) ? $user['bio'] : 'No bio available.';
$location = !empty($user['location']) ? $user['location'] : 'Unknown location';
$joinedDate = date('F Y', strtotime($user['created_at']));
$profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80';
$headerPic = !empty($user['header_pic']) ? $user['header_pic'] : 'https://images.unsplash.com/photo-1505839673365-e3971f8d9184?auto=format&fit=crop&w=1400&q=80';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow-in | Profile</title>
    <link rel="stylesheet" href="assets/CSS/base.css?v=4">
    <link rel="stylesheet" href="assets/CSS/profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
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
                <h1>Profile</h1>
            </header>

            <div class="container">
                <div class="profile-card">
                    <div class="profile-cover">
                        <img src="<?= htmlspecialchars($headerPic) ?>" alt="Cover">
                    </div>
                    <div class="profile-inner">
                        <div class="profile-header">
                            <img class="avatar-large" src="<?= htmlspecialchars($profilePic) ?>" alt="Avatar">
                            <div class="profile-info">
                                <div class="profile-name-row">
                                    <span class="profile-name"><?= htmlspecialchars($displayName) ?></span>
                                    <i class="fa-solid fa-circle-check verified-badge"></i>
                                </div>
                                <div class="profile-handle">@<?= htmlspecialchars($user['username']) ?></div>
                            </div>
                            <div class="profile-actions">
                                <a href="edit_profile.php" class="btn btn-message" style="text-decoration:none;">Edit Profile</a>
                            </div>
                        </div>

                        <p class="profile-bio">
                            <?= nl2br(htmlspecialchars($bio)) ?>
                        </p>
                        <div class="profile-meta">
                            <span><i class="fa-regular fa-calendar"></i> Joined <?= htmlspecialchars($joinedDate) ?></span>
                            <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($location) ?></span>
                        </div>
                        <div class="profile-stats">
                            <a href="following.php" class="mutual-tab"><span class="count">2,114</span> Following</a>
                            <a href="followers.php" class="mutual-tab"><span class="count">98,400</span> Followers</a>
                        </div>

                        <div class="profile-tabs">
                            <a href="#" class="profile-tab active">Tweets</a>
                            <a href="#" class="profile-tab">Tweets & replies</a>
                            <a href="#" class="profile-tab">Media</a>
                            <a href="#" class="profile-tab">Likes</a>
                        </div>
                    </div>
                </div>


                <article class="tweet-card">
                    <div class="post-header">
                        <img src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80"
                            class="avatar" alt="PSD Zone">
                        <div class="post-user-info">
                            <div class="name">PSD Zone</div>
                            <div class="handle">@psd_zone • 2h</div>
                        </div>
                    </div>
                    <p class="post-body">Discover our latest collection of vibrant abstract backgrounds, perfect for
                        your next design project.</p>
                    <div class="tweet-media">
                        <img src="https://i.pinimg.com/736x/c8/3d/fe/c83dfed69dcdb4d5cd385d90c4fbe0e6.jpg"
                            alt="Abstract pack">
                    </div>
                    <div class="tweet-actions">
                        <div class="item"><i class="fa-regular fa-heart"></i><span>770</span></div>
                        <div class="item"><i class="fa-regular fa-comment"></i><span>67</span></div>
                        <div class="item"><i class="fa-solid fa-retweet"></i><span>159</span></div>
                        <div class="item"><i class="fa-solid fa-share-nodes"></i><span>12</span></div>
                    </div>
                </article>

                <article class="tweet-card">
                    <div class="post-header">
                        <img src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80"
                            class="avatar" alt="PSD Zone">
                        <div class="post-user-info">
                            <div class="name">PSD Zone</div>
                            <div class="handle">@psd_zone • 1d</div>
                        </div>
                    </div>
                    <p class="post-body">New free PSD mockups templates available. Present your designs professionally
                        with these easy-to-use files.</p>
                    <div class="tweet-media">
                        <img src="https://i.pinimg.com/1200x/d9/d1/0f/d9d10f36ebaab4293f982bb5b934af1f.jpg"
                            alt="Mockup pack">
                    </div>
                    <div class="tweet-actions">
                        <div class="item"><i class="fa-regular fa-heart"></i><span>310</span></div>
                        <div class="item"><i class="fa-regular fa-comment"></i><span>25</span></div>
                        <div class="item"><i class="fa-solid fa-retweet"></i><span>98</span></div>
                        <div class="item"><i class="fa-solid fa-share-nodes"></i><span>5</span></div>
                    </div>
                </article>
            </div>
        </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

