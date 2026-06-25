<?php
session_start();
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

if ($is_comment && !$comment_post_id) {
    header('Location: home.php');
    exit();
}

$formTitle = $is_comment ? 'Comment' : ($is_edit ? 'Edit Post' : 'Create Post');
$buttonLabel = $is_comment ? 'Post Comment' : ($is_edit ? 'Update Post' : 'Post');
$textareaPlaceholder = $is_comment ? 'Write your comment...' : "What's on your mind?";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Glow-in | <?= htmlspecialchars($formTitle) ?></title>
  <link rel="stylesheet" href="../assets/CSS/base.css?v=8" />
  <link rel="stylesheet" href="../assets/CSS/home.css" />
  <link rel="stylesheet" href="../assets/CSS/post.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
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
      <h1><?= htmlspecialchars($formTitle) ?></h1>
    </header>

    <div class="container">
      <form id="post-form" class="post-card">
        <div class="post-header post-header-flex">
          <img src="<?= htmlspecialchars($_SESSION['profile_pic'] ?? 'https://ui-avatars.com/api/?name=User&background=ff6b00&color=ffffff') ?>" class="avatar">
          <div>
            <div class="post-user-row">
              <span class="name"><?= htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'] ?? 'User') ?></span>
              <?php if (!$is_comment): ?>
              <select id="post-visibility" class="post-visibility-select">
                <option value="0">🌐 Public</option>
                <option value="1">🕵️ Anonim</option>
              </select>
              <?php endif; ?>
            </div>
            <div class="handle">@<?= htmlspecialchars($_SESSION['username'] ?? 'user') ?></div>
          </div>
        </div>

        <textarea id="post-content" name="content" class="post-textarea" placeholder="<?= htmlspecialchars($textareaPlaceholder) ?>" required></textarea>

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
    </div>
  </main>

<?php require_once '../includes/rightbar.php'; ?>
<?php require_once '../includes/footer.php'; ?>
</div>

<script>
const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
const userId = "<?= $_SESSION['user_id'] ?? '' ?>";
const postId = "<?= $post_id ?>";
const commentPostId = "<?= $comment_post_id ?>";
const isEdit = <?= $is_edit ? 'true' : 'false' ?>;
const isComment = <?= $is_comment ? 'true' : 'false' ?>;

if(isEdit && postId) {
    document.addEventListener('DOMContentLoaded', () => {
        fetch('../controllers/postController.php?id=' + postId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        })
        .then(res => res.json())
        .then(post => {
            if(post && !post.error) {
                document.getElementById('post-content').value = post.content || '';
                const vis = document.getElementById('post-visibility');
                if(vis && post.is_anonymous !== undefined) {
                    vis.value = post.is_anonymous == 1 ? "1" : "0";
                }
            }
        });
    });
}

const form = document.getElementById('post-form');
form.addEventListener('submit', function(e) {
    e.preventDefault();

    const content = document.getElementById('post-content').value.trim();
    const visibilityEl = document.getElementById('post-visibility');
    const isAnonymous = visibilityEl ? parseInt(visibilityEl.value) : 0;

    if (!content) {
        alert('Please enter some content!');
        return;
    }

    if (isComment) {
        fetch('../controllers/commentController.php', {
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
        fetch('../controllers/postController.php?id=' + postId, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                content: content,
                is_anonymous: isAnonymous
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

    fetch('../controllers/postController.php', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + apiKey,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            user_id: userId,
            content: content,
            is_anonymous: isAnonymous
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