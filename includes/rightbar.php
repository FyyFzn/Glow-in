<aside class="sidebar-right">
    <div class="search-bar-desktop">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search Glow-in">
    </div>
    <div class="leaderboard-card">
        <h3>Leaderboard</h3>
        <ul class="leaderboard-list" id="rightbar-leaderboard">
            <!-- Static Data - will be replaced by API if successful -->
            <li class="leaderboard-item">
                <span class="rank-number">1</span>
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80" alt="Sarah Wilson">
                <span class="name">Sarah Wilson</span>
                <span class="points">2200 pts</span>
            </li>
            <li class="leaderboard-item">
                <span class="rank-number">2</span>
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80" alt="Jane Doe">
                <span class="name">Jane Doe</span>
                <span class="points">2000 pts</span>
            </li>
            <li class="leaderboard-item">
                <span class="rank-number">3</span>
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80" alt="Emily Davis">
                <span class="name">Emily Davis</span>
                <span class="points">1900 pts</span>
            </li>
            <li class="leaderboard-item">
                <span class="rank-number">4</span>
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80" alt="Alex Smith">
                <span class="name">Alex Smith</span>
                <span class="points">1800 pts</span>
            </li>
            <li class="leaderboard-item">
                <span class="rank-number">5</span>
                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=200&q=80" alt="Mike Johnson">
                <span class="name">Mike Johnson</span>
                <span class="points">1600 pts</span>
            </li>
        </ul>
        <a href="leaderboard.php" class="view-full">View Full Leaderboard</a>
    </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiLeaderboardKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    
    console.log('Rightbar API Key:', apiLeaderboardKey);
    
    fetch('../api/leaderboard.php?limit=5', {
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
            console.warn('Rightbar API error or no data, keeping static');
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
        // Keep static data on error
    });
});
</script>
