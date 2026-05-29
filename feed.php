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
  <title>Glow-in</title>
  <link rel="stylesheet" href="assets/CSS/feed.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
  <div class="container">
  <div class="sidebar">
    <div class="sidebar-header">
      <!-- <i class="fab fa-x-twitter"></i> -->
       <p>Glow-in</p>
    </div>

    <nav class="sidebar-nav">
      <ul>
        <li class="nav-item active">
          <a href="home.php">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="explore.php">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Explore</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="notification.php">
            <i class="fa-solid fa-bell"></i>
            <span>Notifications</span>
            <span class="badge">20+</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="chat.php">
            <i class="fa-solid fa-envelope"></i>
            <span>Messages</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="profile.php">
            <i class="fa-solid fa-user"></i>
            <span>Profile</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="#">
            <i class="fa-solid fa-ellipsis-h"></i>
            <span>More</span>
          </a>
        </li>
      </ul>
    </nav>

    <a href="post.php" class="post-button" style="text-decoration: none; display: flex; justify-content: center; align-items: center;">Post</a>

    <div class="profile-minibar">
      <div class="profile-info">
        <div class="avatar"></div>
        <div>
          <p class="name">xuzi kolo</p>
          <p class="handle">@rishaaaaa</p>
        </div>
      </div>
      <i class="fa-solid fa-ellipsis-h"></i>
    </div>
  </div>

  <main class="main-content">
     <header class="post-box">
        <input type="text" placeholder="What happening today?">
        <i class="fa-solid fa-magnifying-glass"></i>
      </header>

      <section class="posts">
        <article class="post">
          <img src="https://images.unsplash.com/photo-1763046287602-7f878927101f?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="user" class="post-avatar">
          <div>
            <h4>Willem Dafoe <span>@willem.dafoe · 2h</span></h4>
            <p>Going starus monopoly is go. Claim star share at 3. let me quit you stars talent #goingstarmonopoly</p>
            <div class="post-actions">
              <i class="fa-regular fa-comment"></i> 5
              <i class="fa-regular fa-heart"></i> 9
              <i class="fa-solid fa-share"></i> 8
            </div>
          </div>
        </article>
        
        <article class="post">
          <img src="https://images.unsplash.com/photo-1763046287602-7f878927101f?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="user" class="post-avatar">
          <div>
            <h4>Willem Dafoe <span>@willem.dafoe · 2h</span></h4>
            <p>Going starus monopoly is go. Claim star share at 3. let me quit you stars talent #goingstarmonopoly</p>
            <div class="post-actions">
              <i class="fa-regular fa-comment"></i> 5
              <i class="fa-regular fa-heart"></i> 9
              <i class="fa-solid fa-share"></i> 8
            </div>
          </div>
        </article>
        

        <a href="detail.php" class="post-link">
        <article class="post">
          <img src="https://images.unsplash.com/photo-1763046287602-7f878927101f?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="user" class="post-avatar">
          <div>
            <h4>Harold <span>@harold · 5m</span></h4>
            <p>Vacation is going great!</p>
            <img class="post-img" src="https://images.unsplash.com/photo-1508057198894-247b23fe5ade" alt="city">
            <div class="post-actions">
              <i class="fa-regular fa-comment"></i>
              <i class="fa-regular fa-heart"></i>
              <i class="fa-solid fa-share"></i>
            </div>
          </div>
        </article>
        </a>

        <article class="post">
          <img src="https://images.unsplash.com/photo-1763046287602-7f878927101f?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="user" class="post-avatar">
          <div>
            <h4>Willem Dafoe <span>@willem.dafoe · 2h</span></h4>
            <p>Going starus monopoly is go. Claim star share at 3. let me quit you stars talent #goingstarmonopoly</p>
            <div class="post-actions">
              <i class="fa-regular fa-comment"></i> 5
              <i class="fa-regular fa-heart"></i> 9
              <i class="fa-solid fa-share"></i> 8
            </div>
          </div>
        </article>

      </section>
  </main>
  
  <aside class="right-sidebar">
      <div class="trending-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search" aria-label="Search">
      </div>

      <div class="trending-box">
        <h4>Subscribe to Premium</h4>
        <p>Subscribe to unlock new features and if eligible, receive a share of revenue.</p>
        <button class="subscribe-button">Subscribe</button>
      </div>

      <div class="trending-box">
        <h4>What's happening</h4>
        <div class="trend-item">
          <p class="trend-category">Trending in Indonesia</p>
          <p class="trend-title">Pagli</p>
          <p class="trend-count">4,005 posts</p>
        </div>
        <div class="trend-item">
          <p class="trend-category">Music · Trending</p>
          <p class="trend-title">Are You Sure</p>
          <p class="trend-count">84K posts</p>
        </div>
      </div>
    </aside>

  </div>
</body>

</html>

