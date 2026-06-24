<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$type = $_GET['type'] ?? null;
$post_id = $_GET['id'] ?? 0;
$comment_post_id = $_GET['post_id'] ?? 0;

if (!$type) {
    if ($post_id) {
        $type = 'edit';
    } elseif ($comment_post_id) {
        $type = 'comment';
    } else {
        $type = 'new';
    }
}

$is_edit = ($type === 'edit');
$is_comment = ($type === 'comment');
$is_new = ($type === 'new');
$post = null;

if ($is_edit) {
    if ($post_id) {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$post_id, $_SESSION['user_id']]);
        $post = $stmt->fetch();
    }
} elseif ($is_comment) {
    if (!$comment_post_id) {
        header('Location: home.php');
        exit();
    }
}

$formTitle = $is_comment ? 'Comment' : ($is_edit ? 'Edit Post' : 'Create Post');
$buttonLabel = $is_comment ? 'Post Comment' : ($is_edit ? 'Update Post' : 'Post');
$textareaPlaceholder = $is_comment ? 'Write your comment...' : "What's on your mind?";
$textareaValue = $post ? htmlspecialchars($post['content']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow-in | <?php echo $is_edit ? 'Edit Post' : 'Create Post'; ?></title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/CSS/base.css?v=6" />
  <link rel="stylesheet" href="assets/CSS/home.css" />
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
      <h1><?php echo $is_edit ? 'Edit Post' : 'Create Post'; ?></h1>
    </header>

    <div class="container">

      <?php if ($is_edit && $post_id && !$post): ?>
          <p>Post not found or unauthorized.</p>
      <?php else: ?>

      <form id="post-form" class="post-card">
        <div class="post-header">
          <img src="<?= htmlspecialchars($_SESSION['profile_pic'] ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80') ?>" class="avatar">
          <div>
            <div class="name"><?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'] ?? 'User'); ?></div>
            <div class="handle">@<?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?></div>
          </div>
        </div>

        <textarea id="post-content" name="content" class="post-textarea" placeholder="<?= htmlspecialchars($textareaPlaceholder) ?>" required><?= $textareaValue ?></textarea>

        <?php if (!$is_comment): ?>
        <div class="upload-box">
          <i class="fa-regular fa-image"></i>
          <span>Upload image</span>
        </div>
        <?php endif; ?>

        <div class="post-actions">
          <a href="<?= $is_comment ? 'detail.php?id=' . urlencode($comment_post_id) : 'home.php' ?>" class="btn cancel" style="text-decoration:none; text-align:center; display:inline-block; padding:8px 16px;">Cancel</a>
          <button type="submit" class="btn submit"><?= htmlspecialchars($buttonLabel) ?></button>
        </div>
      </form>
      <?php endif; ?>

    </div>
  </main>

  <!-- RIGHTBAR -->
<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>

<script>
const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
const userId = "<?= $_SESSION['user_id'] ?? '' ?>";
const postId = "<?= $post_id ?>";
const commentPostId = "<?= $comment_post_id ?>";
const isEdit = <?= $is_edit ? 'true' : 'false' ?>;
const isComment = <?= $is_comment ? 'true' : 'false' ?>;
const isNew = <?= $is_new ? 'true' : 'false' ?>;

const form = document.getElementById('post-form');
form.addEventListener('submit', function(e) {
    e.preventDefault();

    const content = document.getElementById('post-content').value.trim();

    if (!content) {
        alert('Please enter some content!');
        return;
    }

    if (isComment) {
        fetch('api/comments.php', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: userId,
                post_id: commentPostId,
                comment_text: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'detail.php?id=' + encodeURIComponent(commentPostId);
            } else {
                alert('Error: ' + (data.error || 'Failed to post comment'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to post comment. Please try again.');
        });
        return;
    }

    if (isEdit) {

        fetch('api/posts.php?id=' + postId, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                content: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'home.php';
            } else {
                alert('Error: ' + (data.error || 'Failed to update post'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update post. Please try again.');
        });
        return;
    }

    fetch('api/posts.php', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + apiKey,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user_id: userId,
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'home.php';
        } else {
            alert('Error: ' + (data.error || 'Failed to create post'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create post. Please try again.');
    });
});
</script>
</body>
</html>