document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenuToToggle = document.getElementById('nav-menu-wrapper'); // Ensure this ID matches your HTML

    if (menuToggle && navMenuToToggle) {
        menuToggle.addEventListener('click', () => {
            navMenuToToggle.classList.toggle('active'); // Toggle mobile nav visibility
            menuToggle.classList.toggle('active'); // Toggle hamburger icon animation class

            // Accessibility: Update aria-expanded attribute
            const isExpanded = navMenuToToggle.classList.contains('active');
            menuToggle.setAttribute('aria-expanded', isExpanded);
        });
    }

    // Close mobile menu if a nav link is clicked
    if (navMenuToToggle) {
        const navLinks = navMenuToToggle.querySelectorAll('a'); // Gets all links within the menu
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                // Only close if the mobile menu is actually active/visible
                if (navMenuToToggle.classList.contains('active')) {
                    navMenuToToggle.classList.remove('active');
                    menuToggle.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        });
    }

    // Update Copyright Year
    const currentYearSpan = document.getElementById('current-year');
    if (currentYearSpan) {
        currentYearSpan.textContent = new Date().getFullYear();
    }
});