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

$stmt = $pdo->prepare("SELECT p.*, u.username, u.profile_pic FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: home.php');
    exit();
}

$stmt = $pdo->prepare("SELECT c.*, u.username, u.profile_pic FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glow-in | Detail Post</title>
    <link rel="stylesheet" href="assets/CSS/base.css?v=4">
    <link rel="stylesheet" href="assets/CSS/detail.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="layout">
<?php require_once 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <a href="home.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
                <h1>Detail</h1>
            </header>

            <div class="post-card">
                <div class="post-header">
                    <img src="<?= htmlspecialchars($post['profile_pic']) ?>" class="avatar">
                    <div class="post-user-info">
                        <div class="name"><?= htmlspecialchars($post['username']) ?></div>
                        <div class="handle">@<?= htmlspecialchars($post['username']) ?></div>
                    </div>
                </div>

                <p class="post-body"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                <div class="post-meta">
                    <span class="time"><?= date('d M Y H:i', strtotime($post['created_at'])) ?></span>
                    
                    <div class="interactions">
                        <div class="interaction-item">
                            <i class="fa-regular fa-heart"></i>
                            <span>0</span>
                        </div>
                        <div class="interaction-item">
                            <a href="edit_post.php?post_id=<?= $post['id'] ?>&type=comment" style="text-decoration:none; color:inherit;">
                                <i class="fa-regular fa-comment"></i>
                            </a>
                            <span><?= count($comments) ?></span>
                        </div>
                        <div class="interaction-item">
                            <i class="fa-solid fa-share-nodes"></i>
                            <span>0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="comments-section">
                <h3>Comments (<?= count($comments) ?>)</h3>
                
                <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <img src="<?= htmlspecialchars($comment['profile_pic']) ?>" class="comment-avatar">
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-name"><?= htmlspecialchars($comment['username']) ?></span>
                            <span class="comment-handle">@<?= htmlspecialchars($comment['username']) ?></span>
                            <span class="dot">•</span>
                            <span class="comment-time"><?= date('d M Y H:i', strtotime($comment['created_at'])) ?></span>
                        </div>
                        <p class="comment-text"><?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
                        <?php if ($_SESSION['user_id'] == $comment['user_id']): ?>
                        <div class="comment-actions">
                            <form action="api/comments.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                <button type="submit" style="background:none; border:none; color:red; cursor:pointer;" onclick="return confirm('Delete this comment?');">Delete</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

