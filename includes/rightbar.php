        <aside class="sidebar-right">
            <div class="search-bar-desktop">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search Glow-in">
            </div>
            <div class="leaderboard-card">
                <h3>Leaderboard</h3>
                <ul class="leaderboard-list" id="rightbar-leaderboard">
                    <!-- Data leaderboard akan dirender di sini oleh Javascript -->
                    <p style="text-align: center; color: #999; font-size: 13px;">Memuat...</p>
                </ul>
                <a href="leaderboard.php" class="view-full">View Full Leaderboard</a>
            </div>
        </aside>

<script>
(function() {
    const apiLeaderboardKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    fetch('api/leaderboard.php', {
        headers: { 'Authorization': 'Bearer ' + apiLeaderboardKey }
    })
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById('rightbar-leaderboard');
        if(data.error || data.length === 0) {
            list.innerHTML = '<p style="text-align: center; color: #999; font-size: 13px;">Belum ada data</p>';
            return;
        }
        list.innerHTML = '';
        data.forEach(user => {
            list.innerHTML += `
            <li class="leaderboard-item">
                <img src="${user.profile_pic || 'https://via.placeholder.com/100'}" alt="${user.username}">
                <span class="name">${user.username}</span>
                <span class="points">${user.points} pts</span>
            </li>`;
        });
    })
    .catch(err => console.error('Error fetching leaderboard:', err));
})();
</script>
