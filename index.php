<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Barber Shop</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">THE BARBER SHOP</div>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#services">Services</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <section id="hero" class="hero">
        <div class="hero-content">
            <h1>Create Your Own Style</h1>
            <p>We believe that every man deserves more than just a haircut - they deserve an exceptional and flawless experience.</p>
            <button class="booking-btn" onclick="window.location.href='booking.php'">Online Booking</button>
        </div>
    </section>

    <section id="services" class="services">
        <h2>Our Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <i class="fas fa-cut"></i>
                <h3>Haircut</h3>
                <p>Professional haircut tailored to your style</p>
            </div>
            <div class="service-card">
                <i class="fas fa-razor"></i>
                <h3>Beard Trim</h3>
                <p>Expert beard grooming and styling</p>
            </div>
            <div class="service-card">
                <i class="fas fa-spray-can"></i>
                <h3>Hair Styling</h3>
                <p>Complete styling with premium products</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
            <p>&copy; 2023 The Barber Shop. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html> 