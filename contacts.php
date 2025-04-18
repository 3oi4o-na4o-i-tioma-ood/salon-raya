<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Свържете се с фризьорски салон Райа. Адрес, телефон, работно време и контактна форма.">
    <title>Контакти - Салон Райа</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Neucha&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .contacts-container {
            padding: 80px 5% 40px;
            max-width: 1200px;
            margin: 0 auto;
            color: #fff;
        }

        .contacts-container h1 {
            font-family: 'Neucha', cursive;
            text-align: center;
        }

        .contacts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
        }

        .contact-info {
            padding: 30px;
            background: rgba(164, 132, 232, 0.1);
            border-radius: 10px;
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        

        .contact-details {
            display: flex;
            flex-direction: column;
        }

        .contact-section {
            margin-bottom: 30px;
            padding-left: 0;
        }

        .contact-section h3 {
            color: #a484e8;
            font-size: 1.2rem;
            margin-bottom: 15px;
            font-weight: 300;
            padding-left: 0;
        }

        .contact-section p {
            margin: 10px 0;
            font-size: 1.1rem;
            color: #fff;
            padding-left: 0;
        }

        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            padding-left: 0;
            justify-content: flex-start;
        }

        .social-links a {
            color: #a484e8;
            font-size: 24px;
            transition: color 0.3s ease;
            padding-left: 0;
        }

        .social-links a:hover {
            color: #fff;
        }

        /* Specific styling for contact page social links */
        .contact-social-links {
            justify-content: flex-start;
            padding-left: 0;
            margin-left: 0;
        }

        .contact-social-links a {
            color: #a484e8;
            font-size: 24px;
            padding-left: 0;
            margin-left: 0;
        }

        .contact-social-links a:first-child {
            margin-left: 0;
            padding-left: 0;
        }

        .contact-social-links a:hover {
            color: #fff;
        }

        .map-container {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
            margin: 0 auto;
            margin-top: 30px;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .working-hours {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px 20px;
        }

        .working-hours span:nth-child(2n) {
            color: #a484e8;
        }

        @media (max-width: 768px) {
            .contacts-grid {
                grid-template-columns: 1fr;
            }
        }

        .nav-menu a {
            color: var(--accent-white);
            text-decoration: none;
            font-size: 1.8rem;
            margin: 1rem 0;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
        }

        @media (max-width: 800px) {
            .contacts-container {
                margin: 0;    
            }

            .contact-info {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .map-container {
                margin-top: 0;
            }

            .contact-info {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">Райа</a>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <nav class="navbar-scrolled">
    <a href="/" class="logo">Райа</a>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <div class="nav-menu">
        <a href="/#hero">Начало</a>
        <a href="/#services">Услуги</a>
        <a href="za-nas.php">За Нас</a>
        <a href="contacts.php">Контакти</a>
    </div>

    <div class="booking-button-container">
        <div class="booking-circle"></div>
        <a href="/booking" class="booking-button">
            <span>Online</span>
            <span>booking</span>
        </a>
    </div>

    <div class="contacts-container">
        <h1 style="text-align: center; font-family: 'Montserrat', sans-serif;">Контакти</h1>
        <div class="contacts-grid">
            <div class="contact-info">
                <div class="contact-details">
                    <div class="contact-section">
                        <h3>Адрес</h3>
                        <p><i class="fas fa-map-marker-alt"></i> ул. "Хайдушка гора" 120, София</p>
                    </div>

                    <div class="contact-section">
                        <h3>Работно време</h3>
                        <div class="working-hours">
                            <span>Понеделник - петък:</span>
                            <span>10:00 - 20:00</span>
                            <span>Събота и неделя:</span>
                            <span>12:00 - 17:00</span>
                        </div>
                    </div>

                    <div class="contact-section">
                        <h3>Контакти</h3>
                        <p><i class="fas fa-phone"></i> +359 88 888 8888</p>
                        <p><i class="fas fa-envelope"></i> info@salonraya.bg</p>
                    </div>

                    <div class="contact-section">
                        <h3>Социални мрежи</h3>
                        <div class="social-links contact-social-links" style="padding-left: 0; margin-left: 0;">
                            <a href="https://www.facebook.com/profile.php?id=100063576123123" target="_blank" style="margin-left: 0; padding-left: 0;"><i class="fab fa-facebook"></i></a>
                            <a href="https://www.instagram.com/salon.raya/" target="_blank"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>

                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2932.8876942608745!2d23.2843845!3d42.6894775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40aa85cb15c87077%3A0x7b3f87df06f73250!2sul.%20%22Haydushka%20Gora%22%20120%2C%201680%20Sofia!5e0!3m2!1sen!2sbg!4v1709932008090!5m2!1sen!2sbg" 
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/menu.js"></script>
</body>
</html>
