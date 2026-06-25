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
  <link rel="stylesheet" href="../assets/CSS/base.css?v=105" />
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
              <div class="post-dropdown ml-14" onclick="event.stopPropagation();">
                <button type="button" id="visibility-dropdown-btn" class="post-visibility-btn" onclick="this.nextElementSibling.classList.toggle('show');">
                  <span id="visibility-label">🌐 Public</span> <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div class="post-dropdown-content dropdown-left-menu">
                  <button type="button" onclick="selectVisibility(0, '🌐 Public')">🌐 Public</button>
                  <button type="button" onclick="selectVisibility(1, '🕵️ Anonim')">🕵️ Anonim</button>
                </div>
              </div>
              <input type="hidden" id="post-visibility" value="0">
              <?php endif; ?>
            </div>
            <div class="handle">@<?= htmlspecialchars($_SESSION['username'] ?? 'user') ?></div>
          </div>
        </div>

        <textarea id="post-content" name="content" class="post-textarea" placeholder="<?= htmlspecialchars($textareaPlaceholder) ?>" required></textarea>

        <?php if (!$is_comment): ?>
        <input type="hidden" id="post-image" value="">
        <div class="upload-box clickable-card" onclick="openImagePicker('post-image', 'onPostImagePicked')" style="cursor: pointer; border: 2px dashed #E5E7EB; padding: 16px; border-radius: 12px; text-align: center; margin: 12px 0;">
          <i class="fa-regular fa-image" style="color: #FF6B00; font-size: 20px;"></i>
          <span style="font-weight: 600; color: #374151; margin-left: 8px;">Pilih foto dari folder IMG / URL</span>
        </div>
        <div id="post-image-preview" class="tweet-media d-none" style="position: relative; margin-bottom: 16px;">
          <img id="preview-img-tag" src="" style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 16px;" alt="Preview">
          <button type="button" onclick="removePostImage()" style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; font-size: 16px;">&times;</button>
        </div>
        <?php endif; ?>

        <div class="post-actions">
          <a href="<?= $is_comment ? 'detail.php?id=' . urlencode($comment_post_id) : 'home.php' ?>" class="btn cancel">Cancel</a>
          <button type="submit" class="btn btn-primary submit"><?= htmlspecialchars($buttonLabel) ?></button>
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
                    const lbl = document.getElementById('visibility-label');
                    if(lbl) lbl.textContent = post.is_anonymous == 1 ? '🕵️ Anonim' : '🌐 Public';
                }
                if(post.image) {
                    const inp = document.getElementById('post-image');
                    if(inp) inp.value = post.image;
                    onPostImagePicked(post.image);
                }
            }
        });
    });
}

function onPostImagePicked(url) {
    if(!url) return;
    const prev = document.getElementById('post-image-preview');
    const tag = document.getElementById('preview-img-tag');
    if(prev && tag) {
        tag.src = url;
        prev.classList.remove('d-none');
    }
}

function removePostImage() {
    const inp = document.getElementById('post-image');
    if(inp) inp.value = '';
    const prev = document.getElementById('post-image-preview');
    if(prev) prev.classList.add('d-none');
}

function selectVisibility(val, text) {
    const hiddenEl = document.getElementById('post-visibility');
    const labelEl = document.getElementById('visibility-label');
    if (hiddenEl) hiddenEl.value = val;
    if (labelEl) labelEl.textContent = text;
    document.querySelectorAll('.post-dropdown-content.show').forEach(el => el.classList.remove('show'));
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

    const imageUrl = document.getElementById('post-image') ? document.getElementById('post-image').value.trim() : null;

    if (isEdit) {
        fetch('../controllers/postController.php?id=' + postId, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                content: content,
                image: imageUrl,
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
            image: imageUrl,
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