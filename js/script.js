document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelectorAll('.nav-links a');
    const logo = document.querySelector('.logo');
    const services = document.querySelector('.services');

    window.addEventListener('scroll', () => {
        const servicesPosition = services.getBoundingClientRect().top;
        const viewportHeight = window.innerHeight;
        const fadeStartPoint = viewportHeight / 2;

        if (servicesPosition <= fadeStartPoint) {
            // Calculate fade from current opacity to 0
            const fadeProgress = Math.max(0, servicesPosition / fadeStartPoint);
            
            // Fade navbar background
            navbar.style.backgroundColor = `rgba(164, 132, 232, ${fadeProgress * 0.65})`; // 0.65 is the initial background opacity
            
            // Fade logo text
            logo.style.opacity = fadeProgress;
            
            // Fade navigation links
            navLinks.forEach(link => {
                link.style.opacity = fadeProgress;
            });
        } else {
            // Reset to original opacities
            navbar.style.backgroundColor = 'rgba(164, 132, 232, 0.65)';
            logo.style.opacity = 1;
            navLinks.forEach(link => {
                link.style.opacity = 1;
            });
        }
    });
});

// Password visibility toggle
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('.toggle-password');
    const password = document.querySelector('#password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            // Toggle the password visibility
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
}); 