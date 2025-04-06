<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>За Нас - Салон за Красота Райа</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <style>
        .about-container {
            padding: 8rem 10% 4rem;
            background-color: #111111;
            text-align: center;
            max-width: 100%;
            margin: 0 auto;
            color: #f5f5f5;
        }

        .about-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }

        .about-header .line {
            height: 2px;
            width: 100px;
            background-color: #a484e8;
        }

        .about-header h3 {
            color: #a484e8;
            font-size: 1.5rem;
            font-weight: 300;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.05em;
        }

        .about-container h1 {
            font-size: 2.5rem;
            margin-bottom: 4rem;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.05em;
            text-align: center;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
            margin-top: 40px;
        }

        .about-content {
            padding: 2rem;
            background: #222222;
            border-radius: 10px;
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            gap: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .top-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: flex-start;
        }

        .bottom-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 20px;
        }

        .about-text {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .about-section {
            margin-bottom: 30px;
            padding-left: 0;
        }

        .about-section h2 {
            color: #a484e8;
            font-size: A1.2rem;
            margin-bottom: 15px;
            font-weight: 300;
            padding-left: 0;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.05em;
        }

        .about-section p {
            margin: 10px 0;
            font-size: 1.1rem;
            color: #cccccc;
            padding-left: 0;
            line-height: 1.6;
            font-family: 'Montserrat', sans-serif;
        }

        .brands-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .brands-section li {
            font-size: 1.1rem;
            color: #cccccc;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .brands-section li::before {
            content: '•';
            color: #a484e8;
            font-weight: bold;
            margin-right: 10px;
        }

        .about-image {
            height: auto;
            max-height: 300px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            margin-top: 10px;
        }

        .about-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        .top-row .about-image {
            margin-top: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .about-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .about-container {
                padding: 4rem 5%;
            }

            .about-container h1 {
                font-size: 2rem;
                margin-bottom: 2rem;
            }
        }

        .brands-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .brand-card {
            background: #222222;
            padding: 1rem;
            text-align: center;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        .brand-card:hover {
            transform: translateY(-10px);
        }

        .brand-image {
            position: relative;
            margin-bottom: 1rem;
            overflow: hidden;
            background-color: white;
        }

        .brand-image img {
            max-width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .brand-logo {
            position: absolute !important;
            top: 10px;
            left: 10px;
            width: 65px !important;
            height: auto !important;
            z-index: 2;
        }

        .brand-info h3 {
            font-size: 1rem;
            color: #ffffff;
            margin-bottom: 0.5rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            letter-spacing: 0.05em;
        }

        .smaller-image {
            max-width: 50% !important;
            margin: 0 auto;
            display: block;
        }

        .bottom-row .about-image {
            margin-top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: auto;
            min-height: 400px;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .bottom-row .about-image img {
            width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            max-height: 400px;
            position: relative;
            top: 0;
            transition: transform 0.3s ease;
        }

        .bottom-row .about-image:hover img {
            transform: scale(1.05);
        }

        .featured-brand {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
            background: #222222;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .featured-brand .brand-logo {
            position: absolute !important;
            top: 30px;
            left: 25px;
            width: 45px !important;
            height: auto !important;
            z-index: 1;
            opacity: 0.9;
        }

        .featured-brand .brand-image {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0;
            position: relative;
        }

        .featured-brand .brand-description {
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .featured-brand .brand-description h3 {
            font-size: 1.5rem;
            color: #a484e8;
            margin-bottom: 1rem;
        }

        .featured-brand .brand-description p {
            font-size: 1rem;
            color: #cccccc;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        footer {
            padding: 5px 0 !important;
            margin-top: 0 !important;
        }

        .footer-content {
            padding: 5px 10px !important;
        }

        .social-links {
            margin-bottom: 0.2rem !important;
        }

        .social-links a {
            font-size: 18px !important;
            margin: 0 5px !important;
        }

        .footer-nav {
            margin: 2px 0 !important;
            background-color: #a484e8 !important;
            padding: 4px 10px !important;
            border-radius: 4px !important;
            display: inline-block !important;
        }

        .footer-nav a {
            margin: 0 3px !important;
            font-size: 0.8rem !important;
            color: white !important;
        }

        .copyright {
            font-size: 0.55em !important;
            margin: 0 !important;
        }

        .sign-in-icon {
            width: 24px !important;
            height: 24px !important;
            bottom: 8px !important;
            left: 8px !important;
        }

        /* Why Choose Us Section Styles */
        .why-choose-us {
            padding: 4rem 0;
            background-color: #111111;
            text-align: center;
            color: #f5f5f5;
        }

        .why-choose-us .section-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .why-choose-us .section-title .line {
            height: 2px;
            width: 100px;
            background-color: #a484e8;
        }

        .why-choose-us .section-title h2 {
            color: #a484e8;
            font-size: 1.5rem;
            font-weight: 300;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.05em;
        }

        .why-choose-us .main-title {
            font-size: 2.5rem;
            margin-bottom: 4rem;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.05em;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .feature-item {
            text-align: center;
            background: #222222;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-10px);
        }

        .feature-item .icon {
            font-size: 2.5rem;
            color: #a484e8;
            margin-bottom: 1.5rem;
        }

        .feature-item h3 {
            font-size: 1.2rem;
            color: #ffffff;
            margin-bottom: 1rem;
            font-weight: 300;
            line-height: 1.4;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.05em;
        }

        .feature-item p {
            font-size: 1rem;
            color: #cccccc;
            line-height: 1.6;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0.05em;
        }

        .feature-image {
            width: 100%;
            height: 200px;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
        }

        .feature-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .feature-item:hover .feature-image img {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .features-grid {
                padding: 0 1rem;
                display: flex;
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        .hairstyle-image {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .hairstyle-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .hairstyle-image:hover img {
            transform: scale(1.03);
        }

        body {
            background-color: #111111;
            color: #f5f5f5;
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

    <div class="about-container">
        <div class="about-header">
            <div class="line"></div>
            <h3>За Нас</h3>
            <div class="line"></div>
        </div>
        <h1>Салон за Красота Райа</h1>

        <div class="about-content">
            <div class="top-row">
                <div class="about-text">
                    <div class="about-section">
                        <h2>Нашата История</h2>
                        <p>Салон за красота "Райа" е създаден с любов и страст към красотата. Нашата мисия е да
                            предоставяме висококачествени услуги и да създаваме уникални преживявания за всеки наш
                            клиент.</p>
                        <p>С над 10 години опит в индустрията, нашият екип от професионалисти се стреми да надмине
                            очакванията на всеки клиент, предлагайки персонализирани решения и внимателно отношение.</p>
                    </div>
                </div>

                <div class="about-image">
                    <img src="images/salon/interior.jpg.webp" alt="Salon Interior">
                </div>
            </div>

            <div class="bottom-row">
                <div class="about-image">
                    <img src="images/salon/hairstyle.jpg" alt="Our Services">
                </div>
                <div class="about-text">
                    <div class="about-section">
                        <h2>Нашите Ценности</h2>
                        <p>Вярваме в качеството, професионализма и иновацията. Използваме само най-добрите продукти и
                            технологии, за да гарантираме отличен резултат за всеки наш клиент.</p>
                        <p>Нашият подход е базиран на внимателно слушане и разбиране на нуждите на клиента, след което
                            предлагаме персонализирано решение, което отразява неговата уникална красота.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us Section -->
        <div class="why-choose-us">
            <div class="section-title">
                <div class="line"></div>
                <h2>Фризьорски салон Райа</h2>
                <div class="line"></div>
            </div>
            <h2 class="main-title">Защо да изберете нашия салон?</h2>

            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-image">
                        <img src="images/salon/tools.jpg" alt="Professional Tools">
                    </div>
                    <div class="icon">
                        <i class="fas fa-cut"></i>
                    </div>
                    <h3>Професионални<br>Инструменти</h3>
                    <p>Работим с най-доброто оборудване<br>от panasonic и jaguar</p>
                </div>

                <div class="feature-item">
                    <div class="feature-image">
                        <img src="images/salon/cosmetics.jpg" alt="Quality Cosmetics">
                    </div>
                    <div class="icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h3>Качествена<br>Козметика</h3>
                    <p>Салон Райа<br>използва най-доброто от<br>Wella и Kerastase</p>
                </div>

                <div class="feature-item">
                    <div class="feature-image">
                        <img src="images/salon/experience.jpg" alt="Experience for Everyone">
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Изживяване<br>за всеки</h3>
                    <p>Нашият салон предлага голямо разнообразие от услуги<br>както за жени,<br> така и за мъже</p>
                </div>
            </div>
        </div>

        <div class="about-header">
            <div class="line"></div>
            <h3>Работим само с най-доброто</h3>
            <div class="line"></div>
        </div>
        <h1>Марките, с които работим</h1>

        <div class="featured-brand">
            <div class="brand-image">
                <img src="images/products/wella.png" alt="Wella Professional Logo" class="brand-logo">
                <img src="images/products/wella2.png" alt="Wella Professional">
            </div>
            <div class="brand-description">
                <h3>WELLA PROFESSIONAL</h3>
                <p>Wella Professionals е марка с дългогодишна история, която предлага иновативни продукти за коса с
                    професионално качество. Продуктите на Wella са създадени, за да вдъхновяват и подкрепят фризьорите
                    по целия свят, като им предоставят инструментите, необходими за постигане на изключителни резултати.
                </p>
                <p>В нашия салон използваме продуктите на Wella, за да ви предложим най-високо качество и да постигнем
                    перфектния резултат за вашата коса.</p>
            </div>
        </div>

        <div class="brands-grid">
            <div class="brand-card">
                <div class="brand-image">
                    <img src="images/products/biomagic-logo.png" alt="BioMagic Logo" class="brand-logo">
                    <img src="images/products/biomagic.png" alt="BioMagic">
                </div>
                <div class="brand-info">
                    <h3>BIOMAGIC</h3>
                </div>
            </div>

            <div class="brand-card">
                <div class="brand-image">
                    <img src="images/products/kinactif-logo.png" alt="Kinactif Logo" class="brand-logo">
                    <img src="images/products/kinactif.png" alt="Kinactif">
                </div>
                <div class="brand-info">
                    <h3>KINACTIF</h3>
                </div>
            </div>

            <div class="brand-card">
                <div class="brand-image">
                    <img src="images/products/spa-master-logo.png" alt="Spa Master Logo" class="brand-logo">
                    <img src="images/products/spa-master.png" alt="Spa Master">
                </div>
                <div class="brand-info">
                    <h3>SPA MASTER</h3>
                </div>
            </div>

            <div class="brand-card">
                <div class="brand-image">
                    <img src="images/products/starlet-logo.png" alt="Starlet Professional Logo" class="brand-logo">
                    <img src="images/products/starlet.png" alt="Starlet Professional" class="smaller-image">
                </div>
                <div class="brand-info">
                    <h3>STARLET PROFESSIONAL</h3>
                </div>
            </div>

            <div class="brand-card">
                <div class="brand-image">
                    <img src="images/products/kerastase/kerastase.png" alt="Kerastase Logo" class="brand-logo">
                    <img src="images/products/kerastase/kerastase 1.png" alt="Kerastase">
                </div>
                <div class="brand-info">
                    <h3>KERASTASE</h3>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/menu.js"></script>
</body>

</html>