<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glow-in | Followers</title>
  <link rel="stylesheet" href="assets/CSS/base.css?v=4">
  <link rel="stylesheet" href="assets/CSS/followers.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <div class="layout">
<?php require_once 'includes/sidebar.php'; ?>

    <main class="main-content">
      <header class="top-header">
        <a href="profile.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
        <h1>Followers</h1>
      </header>

      <div class="container">
        <section class="followers-wrap">
          <div class="followers-card">
            <ul class="followers-list">
              <li class="follower-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Miles">
                <div class="follower-info">
                  <div class="name">Miles Peters</div>
                  <div class="handle">@milespeters</div>
                </div>
                <button class="btn-follow-orange">Follow</button>
              </li>
              <li class="follower-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Maria">
                <div class="follower-info">
                  <div class="name">Maria Gonzalez</div>
                  <div class="handle">@maria_gonzalez</div>
                </div>
                <button class="btn-follow-orange">Follow</button>
              </li>
              <li class="follower-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Sophia">
                <div class="follower-info">
                  <div class="name">Sophia Chen</div>
                  <div class="handle">@sophiachen</div>
                </div>
                <button class="btn-follow-orange">Follow</button>
              </li>
              <li class="follower-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Noah">
                <div class="follower-info">
                  <div class="name">Noah Clark</div>
                  <div class="handle">@noahclark</div>
                </div>
                <button class="btn-follow-orange">Follow</button>
              </li>
              <li class="follower-item">
                <img class="avatar" src="https://i.pinimg.com/736x/a4/7e/66/a47e660e9e2c1be2cefa9fb9e18fe306.jpg" alt="Ava Davik" title="Ava Davik">
                <div class="follower-info">
                  <div class="name">Ava Davik</div>
                  <div class="handle">@avadavik</div>
                </div>
                <button class="btn-follow-orange">Follow</button>
              </li>
              <li class="follower-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Lizzy">
                <div class="follower-info">
                  <div class="name">Lizzy Watson</div>
                  <div class="handle">@lizzywatson</div>
                </div>
                <button class="btn-following">Following</button>
              </li>
              <li class="follower-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1544723795-3fb6469f5b39?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Ethan">
                <div class="follower-info">
                  <div class="name">Ethan Wright</div>
                  <div class="handle">@ethanw</div>
                </div>
                <button class="btn-follow-orange">Follow</button>
              </li>
            </ul>
          </div>
        </section>
      </div>
    </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>


