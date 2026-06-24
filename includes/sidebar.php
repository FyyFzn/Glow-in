<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
        <aside class="sidebar-left">
            <div class="logo">
                <i class="fa-solid fa-message logo-icon"></i>
                <span>Glow-in</span>
            </div>
            <label for="menu-toggle" class="close-btn" aria-label="Tutup Menu">
                <i class="fas fa-times"></i>
            </label>

            <nav class="nav-menu">
                <a href="home.php" class="nav-item <?= $current_page == 'home.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i>
                    <span>Home</span>
                </a>
                <a href="explore.php" class="nav-item <?= $current_page == 'explore.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Explore</span>
                </a>
                <a href="notification.php" class="nav-item <?= $current_page == 'notification.php' ? 'active' : '' ?>">
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-bell"></i>
                        <span class="badge">20+</span>
                    </div>
                    <span>Notifications</span>
                </a>
                <a href="chat.php" class="nav-item <?= $current_page == 'chat.php' ? 'active' : '' ?>">
                    <i class="fa-regular fa-envelope"></i>
                    <span>Messages</span>
                </a>
                <a href="leaderboard.php" class="nav-item <?= $current_page == 'leaderboard.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span>Leaderboard</span>
                </a>
                <a href="profile.php" class="nav-item <?= $current_page == 'profile.php' ? 'active' : '' ?>">
                    <i class="fa-regular fa-user"></i>
                    <span>Profile</span>
                </a>
            </nav>
            <a href="edit_post.php?type=new" class="post-btn" style="text-decoration: none; display: flex; justify-content: center; align-items: center;"><span>Post</span></a>

            <div class="user-profile-mini" style="position: relative;">
                <img src="<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80'); ?>"
                    alt="Profile" class="avatar-mini">
                <div class="user-info-mini">
                    <div class="name"><?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'] ?? 'User'); ?></div>
                    <div class="handle">@<?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?></div>
                </div>
                <div class="post-dropdown" onclick="event.stopPropagation();">
                    <button class="post-dropdown-btn" onclick="this.nextElementSibling.classList.toggle('show');" style="padding: 0; margin-left: 5px; display:flex; align-items:center;">
                        <i class="fa-solid fa-ellipsis menu-dots"></i>
                    </button>
                    <div class="post-dropdown-content" style="bottom: 100%; top: auto; right: 0; margin-bottom: 10px; min-width: 150px;">
                        <a href="logout.php" style="color: #ff4757;">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </aside>
