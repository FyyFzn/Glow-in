<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
/* Dropdown Menu Styles */
.post-dropdown {
    position: relative;
    display: inline-block;
}

.post-dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background-color: #ffffff;
    min-width: 120px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1);
    z-index: 10;
    border-radius: 8px;
    border: 1px solid #eaeaea;
    overflow: hidden;
}

.post-dropdown-content a,
.post-dropdown-content button {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    font-size: 14px;
    cursor: pointer;
    font-family: inherit;
}

.post-dropdown-content a:hover,
.post-dropdown-content button:hover {
    background-color: #f4f7fe;
    color: var(--accent-orange);
}

.post-dropdown-content.show {
    display: block;
}

.post-dropdown-btn {
    background: none;
    border: none;
    color: var(--text-tertiary);
    cursor: pointer;
    padding: 8px;
    font-size: 16px;
    border-radius: 50%;
    transition: background-color 0.2s;
}
.post-dropdown-btn:hover {
    color: var(--text-primary);
    background-color: #f4f7fe;
}
</style>
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
            <a href="post.php" class="post-btn" style="text-decoration: none; display: flex; justify-content: center; align-items: center;">Post</a>

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
                    <div class="post-dropdown-content" style="bottom: 100%; top: auto; right: 0; margin-bottom: 10px; min-width: 120px;">
                        <a href="logout.php" style="color: #ff4757;">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </aside>
