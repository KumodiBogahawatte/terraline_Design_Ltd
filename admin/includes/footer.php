</div>
</main>
</div>

<script>
    // Mobile sidebar toggle
    const sidebar = document.getElementById('adminSidebar');
    const menuToggle = document.getElementById('menuToggle');
    const overlay = document.getElementById('sidebarOverlay');

    // Set initial state for mobile
    function initMobileSidebar() {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('mobile-active');
            sidebar.classList.add('mobile-hidden');
            overlay.classList.remove('active');
            menuToggle.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        } else {
            // On desktop, ensure sidebar is visible
            sidebar.classList.remove('mobile-hidden', 'mobile-active');
            overlay.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
    }

    function openSidebar() {
        sidebar.classList.remove('mobile-hidden');
        sidebar.classList.add('mobile-active');
        overlay.classList.add('active');
        menuToggle.classList.add('active');
        document.body.classList.add('sidebar-open');
    }

    function closeSidebar() {
        sidebar.classList.remove('mobile-active');
        sidebar.classList.add('mobile-hidden');
        overlay.classList.remove('active');
        menuToggle.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    }

    // Toggle sidebar on menu button click
    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (sidebar.classList.contains('mobile-active')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    // Close sidebar when clicking overlay
    overlay.addEventListener('click', closeSidebar);

    // Handle window resize
    window.addEventListener('resize', function() {
        initMobileSidebar();
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initMobileSidebar();
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        });
    }, 5000);
</script>
</body>

</html>