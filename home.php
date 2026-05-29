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
    <title>Glow-in Mockup (HTML/CSS Only)</title>
    <link rel="stylesheet" href="./assets/CSS/growincss.css">
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
                <?php
                $stmt = $pdo->query("SELECT p.*, u.username, u.profile_pic FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
                while ($post = $stmt->fetch()) {
                    $time_ago = date('d M Y H:i', strtotime($post['created_at']));
                    
                    $stmt_count = $pdo->prepare("SELECT COUNT(*) AS total FROM comments WHERE post_id = ?");
                    $stmt_count->execute([$post['id']]);
                    $comment_count = $stmt_count->fetch()['total'];
                ?>
                <article class="tweet-card" style="cursor: pointer;">
                    <div onclick="window.location.href='detail.php?id=<?= $post['id'] ?>';" class="click-post" style="display:block;">
                        <div class="post-header">
                            <img src="<?= htmlspecialchars($post['profile_pic']) ?>" class="avatar" alt="Avatar">
                            <div class="post-user-info">
                                <div class="name"><?= htmlspecialchars($post['username']) ?></div>
                                <div class="handle">@<?= htmlspecialchars($post['username']) ?> • <?= $time_ago ?></div>
                            </div>
                            <?php if ($_SESSION['user_id'] == $post['user_id']): ?>
                            <div class="post-dropdown" onclick="event.stopPropagation();">
                                <button class="post-dropdown-btn" onclick="this.nextElementSibling.classList.toggle('show');"><i class="fa-solid fa-ellipsis"></i></button>
                                <div class="post-dropdown-content">
                                    <a href="edit_post.php?id=<?= $post['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square" style="color: blue;"></i> Edit
                                    </a>
                                    <form action="api/posts.php" method="POST" style="margin:0;" onsubmit="return confirm('Delete this post?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                        <button type="submit" style="width: 100%; text-align: left;">
                                            <i class="fa-solid fa-trash" style="color: red;"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <p class="post-body" style="margin-top: 10px;"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        
                        <div class="tweet-actions" style="margin-top: 15px;">
                            <div class="item"><i class="fa-regular fa-heart"></i><span>0</span></div>
                            <div class="item" onclick="event.stopPropagation();">
                                <i class="fa-regular fa-comment"></i>
                                <span><?= $comment_count ?></span>
                            </div>
                            
                            <div class="item" onclick="event.stopPropagation();"><i class="fa-solid fa-share-nodes"></i><span>0</span></div>
                        </div>
                    </div>
                </article>
                <?php } ?>
            </div>

        </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

