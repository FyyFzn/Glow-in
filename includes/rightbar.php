<aside class="sidebar-right">
    <div class="search-bar-desktop" style="position: relative; z-index: 500;">
        <i class="fas fa-search"></i>
        <input type="text" id="rightbar-search-input" placeholder="Search Glow-in users..." autocomplete="off">
        <div id="rightbar-search-dropdown" class="d-none" style="position: absolute; top: 100%; left: 0; right: 0; background: #FFFFFF; border-radius: 16px; box-shadow: 0 12px 35px rgba(0,0,0,0.18); margin-top: 8px; max-height: 320px; overflow-y: auto; border: 1px solid #E5E7EB; padding: 8px 0; z-index: 1000; text-align: left;">
        </div>
    </div>
    <div class="leaderboard-card">
        <h3>Leaderboard</h3>
        <ul class="leaderboard-list" id="rightbar-leaderboard">
            <p class="empty-state">Memuat...</p>
        </ul>
        <a href="leaderboard.php" class="view-full">View Full Leaderboard</a>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiLeaderboardKey = "<?= $_SESSION['api_key'] ?? '' ?>";

    // --- Leaderboard Fetch ---
    fetch('../controllers/leaderboardController.php?limit=5', {
        headers: { 'Authorization': 'Bearer ' + apiLeaderboardKey }
    })
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById('rightbar-leaderboard');
        if (data.error || !Array.isArray(data) || data.length === 0) {
            list.innerHTML = '<p class="empty-state">Belum ada data</p>';
            return;
        }
        list.innerHTML = '';
        data.forEach((user, index) => {
            const displayName = user.name ? user.name : user.username;
            list.innerHTML += `
            <li class="leaderboard-item" onclick="window.location.href='profile.php?id=${user.id}'" style="cursor: pointer; transition: background 0.2s; padding: 8px; border-radius: 10px;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='transparent'">
                <span class="rank-number">${index + 1}</span>
                <img src="${user.profile_pic}" alt="${displayName}">
                <span class="name">${displayName}</span>
                <span class="points">${user.points} pts</span>
            </li>`;
        });
    })
    .catch(() => {});

    // --- Live User Search ---
    const searchInp = document.getElementById('rightbar-search-input');
    const searchDrop = document.getElementById('rightbar-search-dropdown');
    let searchTimeout = null;

    if (searchInp) {
        searchInp.addEventListener('input', (e) => {
            const val = e.target.value.trim();
            clearTimeout(searchTimeout);

            if (!val) {
                searchDrop.classList.add('d-none');
                searchDrop.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`../controllers/userController.php?search=${encodeURIComponent(val)}`, {
                    headers: { 'Authorization': 'Bearer ' + apiLeaderboardKey }
                })
                .then(r => r.json())
                .then(users => {
                    searchDrop.innerHTML = '';
                    searchDrop.classList.remove('d-none');
                    if (users.error || !Array.isArray(users) || users.length === 0) {
                        searchDrop.innerHTML = '<div style="padding: 12px 16px; font-size: 13px; color: #6B7280; text-align: center;">Pengguna tidak ditemukan</div>';
                    } else {
                        users.forEach(u => {
                            const dName = u.name || u.username;
                            const handle = u.username;
                            const item = document.createElement('a');
                            item.href = `profile.php?id=${u.id}`;
                            item.style.cssText = 'display: flex; align-items: center; gap: 12px; padding: 10px 16px; text-decoration: none; color: #111827; transition: background 0.15s;';
                            item.onmouseover = () => item.style.background = '#F9FAFB';
                            item.onmouseout = () => item.style.background = 'transparent';
                            item.innerHTML = `
                                <img src="${u.profile_pic}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;" alt="${dName}">
                                <div style="overflow: hidden;">
                                    <div style="font-weight: 700; font-size: 13.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${dName}</div>
                                    <div style="font-size: 12px; color: #6B7280;">@${handle}</div>
                                </div>
                            `;
                            searchDrop.appendChild(item);
                        });
                    }
                })
                .catch(() => {});
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-bar-desktop')) {
                searchDrop.classList.add('d-none');
            }
        });
    }
});
</script>
