    </div> <!-- end layout -->

    <label for="menu-toggle" id="overlay"></label>

    <script>

        window.onclick = function(event) {
            if (!event.target.closest('.post-dropdown-btn')) {
                var dropdowns = document.getElementsByClassName("post-dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
