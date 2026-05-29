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
  <title>Following Page</title>
  <link rel="stylesheet" href="assets/CSS/following.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  </head>
<body>
  <div class="layout">
<?php require_once 'includes/sidebar.php'; ?>

    <main class="main-content">
      <header class="top-header">
        <a href="profile.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
        <h1>Following</h1>
      </header>

      <div class="container">
        <section class="following-wrap">
          <div class="following-card">
            <div class="list-top">
              <button class="chip active">All</button>
              <span class="subtext">things to follow</span>
            </div>
            <ul class="following-list">
              <li class="following-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1544723795-3fb6469f5b39?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Ethan Wright" title="Ethan Wright">
                <div class="user">
                  <div class="name">Ethan Wright</div>
                  <div class="handle">@ethanw</div>
                </div>
                <button class="btn-following">Following</button>
              </li>
              <li class="following-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Olivia King" title="Olivia King">
                <div class="user">
                  <div class="name">Olivia King</div>
                  <div class="handle">@oliviaking</div>
                </div>
                <button class="btn-following">Following</button>
              </li>
              <li class="following-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="David Lee" title="David Lee">
                <div class="user">
                  <div class="name">David Lee</div>
                  <div class="handle">@david-lee</div>
                </div>
                <button class="btn-following">Following</button>
              </li>
              <li class="following-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1527980965255-d3b416303d12?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Alex Johnson" title="Alex Johnson">
                <div class="user">
                  <div class="name">Alex Johnson</div>
                  <div class="handle">@alex-johnson</div>
                </div>
                <button class="btn-following">Following</button>
              </li>
              <li class="following-item">
                <img class="avatar" src="https://i.pinimg.com/736x/a4/7e/66/a47e660e9e2c1be2cefa9fb9e18fe306.jpg" alt="Liam Scott" title="Liam Scott">
                <div class="user">
                  <div class="name">Liam Scott</div>
                  <div class="handle">@liam-scott</div>
                </div>
                <button class="btn-following">Following</button>
              </li>
              <li class="following-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Maya Singh" title="Maya Singh">
                <div class="user">
                  <div class="name">Maya Singh</div>
                  <div class="handle">@maya-singh</div>
                </div>
                <button class="btn-following">Following</button>
              </li>
              <li class="following-item">
                <img class="avatar" src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?ixlib=rb-1.2.1&auto=format&fit=crop&w=80&q=80" alt="Noah Brown" title="Noah Brown">
                <div class="user">
                  <div class="name">Noah Brown</div>
                  <div class="handle">@noah-brown</div>
                </div>
                <button class="btn-following">Following</button>
              </li>
            </ul>
          </div>
        </section>
      </div>
    </main>

<?php require_once 'includes/rightbar.php'; ?>
<?php require_once 'includes/footer.php'; ?>


