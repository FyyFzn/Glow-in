<?php
$current_page = basename($_SERVER['PHP_SELF']);
$sidebarDispName = !empty($_SESSION['name']) ? $_SESSION['name'] : (!empty($_SESSION['username']) ? $_SESSION['username'] : 'User');
$sidebarAvatarFallback = 'https://ui-avatars.com/api/?name=' . urlencode($sidebarDispName) . '&background=ff6b00&color=ffffff';
$sidebarAvatar = !empty($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : $sidebarAvatarFallback;
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
                        <span id="notif-badge" class="badge" style="display: none;">0</span>
                    </div>
                    <span>Notifications</span>
                </a>
                <a href="javascript:void(0)" onclick="openChatChoicePopup(event)" class="nav-item <?= $current_page == 'chat.php' ? 'active' : '' ?>">
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
            <a href="edit_post.php?type=new" class="post-btn sidebar-post-link"><span>Post</span></a>

            <div class="user-profile-mini sidebar-profile-wrapper">
                <img src="<?= htmlspecialchars($sidebarAvatar) ?>" alt="Profile" class="avatar-mini">
                <div class="user-info-mini">
                    <div class="name"><?= htmlspecialchars($sidebarDispName) ?></div>
                    <div class="handle">@<?= htmlspecialchars($_SESSION['username'] ?? 'user') ?></div>
                </div>
                <div class="post-dropdown" onclick="event.stopPropagation();">
                    <button class="post-dropdown-btn sidebar-dots-btn" onclick="this.nextElementSibling.classList.toggle('show');">
                        <i class="fa-solid fa-ellipsis menu-dots"></i>
                    </button>
                    <div class="post-dropdown-content sidebar-dropdown-menu">
                        <a href="logout.php" class="sidebar-logout-link">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </aside>

<!-- CHAT CHOICE MODAL POPUP -->
<div id="chatChoiceModal" class="chat-choice-modal">
    <div class="chat-choice-backdrop" onclick="closeChatChoicePopup()"></div>
    <div class="chat-choice-container">
        <div class="chat-choice-header">
            <h3>Pilih Tipe Chat</h3>
            <button class="btn-close-modal" onclick="closeChatChoicePopup()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div class="chat-choice-grid">
            
            <!-- 1. AI -->
            <div class="chat-type-card" onclick="handleChatPopupSelect('ai')">
                <div class="chat-type-icon-box">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <span class="chat-type-label">AI</span>
            </div>

            <!-- 2. USER -->
            <div class="chat-type-card" onclick="handleChatPopupSelect('user')">
                <div class="chat-type-icon-box">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <span class="chat-type-label">User</span>
            </div>

            <!-- 3. SPESIALIS -->
            <div class="chat-type-card" onclick="handleChatPopupSelect('spesialis')">
                <div class="chat-type-icon-box">
                    <i class="fa-solid fa-briefcase-medical"></i>
                </div>
                <span class="chat-type-label">Spesialis</span>
            </div>

        </div>

        <div id="chatPlaceholderToast" class="chat-placeholder-toast">Fitur ini akan segera hadir!</div>
    </div>
</div>

<script>
function openChatChoicePopup(e) {
    if (e) e.preventDefault();
    const modal = document.getElementById('chatChoiceModal');
    if (modal) modal.classList.add('show');
}

function closeChatChoicePopup() {
    const modal = document.getElementById('chatChoiceModal');
    if (modal) modal.classList.remove('show');
    const toast = document.getElementById('chatPlaceholderToast');
    if (toast) toast.classList.remove('show');
}

function handleChatPopupSelect(type) {
    if (type === 'user') {
        window.location.href = 'chat.php';
    } else if (type === 'ai') {
        const toast = document.getElementById('chatPlaceholderToast');
        if (toast) {
            toast.textContent = 'Fitur Chat AI (Placeholder) - Akan segera hadir!';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    } else if (type === 'spesialis') {
        const toast = document.getElementById('chatPlaceholderToast');
        if (toast) {
            toast.textContent = 'Fitur Chat Spesialis (Placeholder) - Akan segera hadir!';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    }
}
</script>

<!-- REALTIME NOTIFICATION FLOATING BANNER -->
<div id="realtimeNotifBanner" class="realtime-notif-banner">
    <img id="realtimeNotifAvatar" src="" alt="Avatar">
    <div class="realtime-notif-text">
        <strong id="realtimeNotifUser">User</strong>
        <span id="realtimeNotifAction">berinteraksi denganmu</span>
    </div>
</div>

<style>
.realtime-notif-banner {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: rgba(26, 29, 36, 0.96);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 107, 0, 0.35);
    color: #fff;
    padding: 14px 18px;
    border-radius: 18px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5), 0 0 20px rgba(255, 107, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 14px;
    z-index: 9999999;
    transform: translateY(120px);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none;
    max-width: 340px;
}
.realtime-notif-banner.show {
    transform: translateY(0);
    opacity: 1;
    pointer-events: auto;
}
.realtime-notif-banner img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ff6b00;
    flex-shrink: 0;
}
.realtime-notif-text {
    display: flex;
    flex-direction: column;
    font-size: 13px;
    line-height: 1.4;
}
.realtime-notif-text strong {
    color: #ff6b00;
    font-weight: 700;
}
.realtime-notif-text span {
    color: #e0e0e0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarApiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    if (!sidebarApiKey) return;

    let lastKnownNotifId = null;

    function pollRealtimeNotifications() {
        fetch('../controllers/notificationController.php?action=realtime&_t=' + Date.now(), {
            cache: 'no-store',
            headers: { 'Authorization': 'Bearer ' + sidebarApiKey }
        })
        .then(res => res.json())
        .then(data => {
            if (!data || data.error) return;

            // Handle Badge
            const badge = document.getElementById('notif-badge');
            const isNotifPage = window.location.pathname.includes('notification.php');

            if (badge) {
                if (data.unread_count > 0 && !isNotifPage) {
                    badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }

            // Handle Toast Banner
            if (data.latest) {
                if (lastKnownNotifId !== null && data.latest.id > lastKnownNotifId) {
                    showRealtimeBanner(data.latest);
                }
                lastKnownNotifId = Math.max(lastKnownNotifId || 0, data.latest.id);
            } else if (lastKnownNotifId === null) {
                lastKnownNotifId = 0;
            }
        })
        .catch(() => {});
    }

    function showRealtimeBanner(notif) {
        if (window.location.pathname.includes('notification.php')) return;

        const banner = document.getElementById('realtimeNotifBanner');
        const avatar = document.getElementById('realtimeNotifAvatar');
        const userEl = document.getElementById('realtimeNotifUser');
        const actionEl = document.getElementById('realtimeNotifAction');

        if (!banner || !avatar || !userEl || !actionEl) return;

        const dispName = notif.name || notif.username || 'Seseorang';
        avatar.src = notif.profile_pic || `https://ui-avatars.com/api/?name=${encodeURIComponent(dispName)}&background=ff6b00&color=ffffff`;
        userEl.textContent = dispName;

        let actionText = "berinteraksi denganmu";
        if (notif.type === 'like') actionText = "menyukai postinganmu";
        else if (notif.type === 'comment') actionText = "mengomentari postinganmu";
        else if (notif.type === 'follow') actionText = "mulai mengikutimu";

        actionEl.textContent = actionText;

        banner.classList.add('show');
        setTimeout(() => {
            banner.classList.remove('show');
        }, 4000);
    }

    pollRealtimeNotifications();
    setInterval(pollRealtimeNotifications, 3000);
});
</script>
