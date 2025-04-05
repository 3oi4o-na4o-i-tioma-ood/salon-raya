document.addEventListener('DOMContentLoaded', () => {
    // Get navigation elements
    const navbar = document.querySelector('.navbar');
    const navbarScrolled = document.querySelector('.navbar-scrolled');
    const allHamburgers = document.querySelectorAll('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    // Toggle menu when any hamburger is clicked
    allHamburgers.forEach(hamburger => {
        hamburger.addEventListener('click', () => {
            allHamburgers.forEach(h => h.classList.toggle('active'));
            navMenu.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });
    });

    // Close menu when a nav link is clicked
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            allHamburgers.forEach(hamburger => hamburger.classList.remove('active'));
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
    });

    // Show/hide scrolled navbar based on scroll position
    if (navbarScrolled) {
        window.addEventListener('scroll', () => {
            const heroSection = document.querySelector('.hero');
            if (heroSection) {
                const heroPosition = heroSection.getBoundingClientRect().top;
                if (heroPosition <= 0) {
                    navbarScrolled.classList.add('visible');
                } else {
                    navbarScrolled.classList.remove('visible');
                }
            } else {
                // If no hero section, show scrolled navbar after scrolling down 100px
                if (window.scrollY > 100) {
                    navbarScrolled.classList.add('visible');
                } else {
                    navbarScrolled.classList.remove('visible');
                }
            }
        });
    }
}); 