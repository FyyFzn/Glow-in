<aside class="sidebar-right">
    <div class="search-bar-desktop">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search Glow-in">
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

    console.log('Rightbar API Key:', apiLeaderboardKey);

    fetch('../controllers/leaderboardController.php?limit=5', {
        headers: { 'Authorization': 'Bearer ' + apiLeaderboardKey }
    })
    .then(res => {
        console.log('Rightbar response status:', res.status);
        return res.text().then(text => {
            console.log('Rightbar response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                return { error: 'Invalid JSON: ' + text };
            }
        });
    })
    .then(data => {
        console.log('Rightbar data:', data);
        const list = document.getElementById('rightbar-leaderboard');

        if (data.error || !Array.isArray(data) || data.length === 0) {
            list.innerHTML = '<p class="empty-state">Belum ada data</p>';
            return;
        }

        list.innerHTML = '';
        data.forEach((user, index) => {
            const displayName = user.name ? user.name : user.username;
            list.innerHTML += `
            <li class="leaderboard-item">
                <span class="rank-number">${index + 1}</span>
                <img src="${user.profile_pic}" alt="${displayName}">
                <span class="name">${displayName}</span>
                <span class="points">${user.points} pts</span>
            </li>`;
        });
    })
    .catch(err => {
        console.error('Rightbar error fetching leaderboard:', err);

    });
});
</script>
