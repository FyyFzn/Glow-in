<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$type = $_GET['type'] ?? 'edit';
$post_id = $_GET['id'] ?? $_GET['post_id'] ?? 0;
$is_comment = ($type === 'comment');
$post = null;

if ($is_comment) {
    $stmt = $pdo->prepare("SELECT p.*, u.username, u.profile_pic FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();
    if (!$post) {
        header('Location: home.php');
        exit();
    }
} else {
    if ($post_id) {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$post_id, $_SESSION['user_id']]);
        $post = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow-in | <?php echo $is_comment ? 'Add Comment' : 'Edit Post'; ?></title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/CSS/base.css?v=4" />
  <link rel="stylesheet" href="assets/CSS/post.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body>
<input type="checkbox" id="menu-toggle" class="hidden-checkbox">

<div class="layout">

  <!-- LEFTBAR -->
<?php require_once 'includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="main-content">
    <header class="top-header">
      <label for="menu-toggle" class="menu-toggle-btn">
        <i class="fa-solid fa-bars"></i>
      </label>
      <h1><?php echo $is_comment ? 'Add Comment' : 'Edit Post'; ?></h1>
    </header>

    <div class="container">
      <?php if ($is_comment): ?>
      
      <form action="api/comments.php" method="POST" class="post-card">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
        <div class="post-header">
          <img src="<?= htmlspecialchars($_SESSION['profile_pic'] ?? 'https://i.pravatar.cc/48?img=12') ?>" class="avatar">
          <div>
            <div class="name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></div>
            <div class="handle">@<?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?></div>
          </div>
        </div>

        <textarea name="comment_text" class="post-textarea" placeholder="Write a comment..." required></textarea>

        <div class="post-actions">
          <a href="detail.php?id=<?= $post['id'] ?>" class="btn cancel" style="text-decoration:none; text-align:center; display:inline-block; padding:8px 16px;">Cancel</a>
          <button type="submit" class="btn submit">Post Comment</button>
        </div>
      </form>
      
      <?php else: ?>
      
      <?php if ($post_id && !$post): ?>
          <p>Post not found or unauthorized.</p>
      <?php else: ?>

      <form action="api/posts.php" method="POST" class="post-card">
        <input type="hidden" name="action" value="<?= $post ? 'edit' : 'create' ?>">
        <?php if ($post): ?>
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
        <?php endif; ?>
        <div class="post-header">
          <img src="<?= htmlspecialchars($_SESSION['profile_pic'] ?? 'https://i.pravatar.cc/48?img=12') ?>" class="avatar">
          <div>
            <div class="name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></div>
            <div class="handle">@<?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?></div>
          </div>
        </div>

        <textarea name="content" class="post-textarea" placeholder="What's on your mind?" required><?= $post ? htmlspecialchars($post['content']) : '' ?></textarea>

        <div class="upload-box">
          <i class="fa-regular fa-image"></i>
          <span>Upload image</span>
        </div>

        <div class="post-actions">
          <a href="home.php" class="btn cancel" style="text-decoration:none; text-align:center; display:inline-block; padding:8px 16px;">Cancel</a>
          <button type="submit" class="btn submit"><?= $post ? 'Update Post' : 'Post' ?></button>
        </div>
      </form>
      <?php endif; ?>
      
      <?php endif; ?>

    </div>
  </main>

  <!-- RIGHTBAR -->
<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

