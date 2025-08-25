<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Фризьорски салон Райа - Професионални Фризьорски услуги за жени и мъже. Подстригване, боядисване, прически и грижа за косата.">
    <title>Салон за красота Райа</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="canonical" href="https://salonraia.eu/">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Make main page services section match booking page design */
        #services {
            background: #1a1a1a;
            color: #ccc;
        }
        
        .services-list {
            display: block !important;
            margin-top: 2rem;
        }
        
        .subcategories-list {
            margin-bottom: 2rem;
        }
        
        /* Match booking page subcategory styling */
        .subcategory {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #ccc !important;
            border: 1px solid transparent !important;
        }
        
        .subcategory.active {
            border: 1px solid var(--primary-color) !important;
            color: var(--text-color) !important;
        }
        
        .subcategory h3 {
            color: #eee !important;
        }
        
        .subcategory .price {
            color: #a484e8 !important;
        }
        
        /* Match booking page service item styling */
        .service-item {
            background: #2a2a2a !important;
            color: #ccc !important;
            border: 1px solid #444 !important;
        }
        
        .service-item h3 {
            color: #eee !important;
        }
        
        .service-item .service-duration {
            color: #aaa !important;
        }
        
        .service-item .price {
            color: #eee !important;
        }
        
        /* Options styling matching booking page */
        .service-item-content {
            width: 100%;
        }
        
        .service-main-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        
        .options-btn {
            background: none;
            border: 1px solid #a484e8;
            color: #a484e8;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 4px;
            transition: all 0.2s;
            min-width: 80px;
            text-align: center;
        }
        
        .options-btn:hover {
            background: #a484e8;
            color: white;
        }
        
        .service-options {
            display: none;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px dashed #444;
            width: 100%;
        }
        
        .service-options.show {
            display: block;
        }
        
        .service-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
            padding: 0.5rem 0;
        }
        
        .option-info {
            border: none;
            border-radius: 0;
            padding: 0;
            background: transparent;
            width: auto;
            margin-left: 0;
        }
        
        .option-name {
            color: #ccc;
            font-size: 0.9rem;
            font-weight: 500;
            display: block;
        }
        
        .option-duration {
            color: #aaa;
            font-size: 0.85rem;
            display: block;
            margin-top: 0.25rem;
        }
        
        .option-price {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: flex-end;
            min-width: auto;
            padding-right: 0;
        }
        
        .option-price .price {
            color: #eee;
            font-size: 0.9rem;
        }
        
        /* Make options select buttons smaller */
        .service-options .select-btn {
            padding: 4px 8px;
            font-size: 12px;
            min-width: 60px;
        }
        
        /* Make sure select buttons match */
        .select-btn {
            text-decoration: none !important;
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
        <a href="#hero">Начало</a>
        <a href="#services">Услуги</a>
        <a href="za-nas.php">За Нас</a>
        <a href="contacts.php">Контакти</a>
    </div>

    <div class="booking-button-container">
        <div class="booking-circle"></div>
        <a href="booking.php" class="booking-button">
            <i class="fas fa-calendar-alt"></i>
            <span>booking</span>
        </a>
    </div>

    <section id="hero" class="hero">
        <img src="images/hero/hero1.jpg" class="hero-image" alt="hero">
        <div class="hero-content">
            <!-- <h1>Където стилът среща съвършенството.</h1> -->
            <h2 class="hero-subtitle">Фризьорски салон Райа</h2>
            <p>Вярваме, че всеки заслужава не просто подстригване - <br> а изключително и безупречно изживяване.</p>
        </div>
    </section>

    <section class="frame-section">
        <svg class="clip-path-svg">
            <clipPath id="frame-clip-path" clipPathUnits="objectBoundingBox">
                <path
                    d="M0,1 L0,0.207 C0.042,0.171,0.083,0.102,0.125,0.101 C0.167,0.102,0.208,0.171,0.25,0.182 C0.292,0.192,0.333,0.14,0.375,0.111 C0.417,0.08,0.458,0.071,0.5,0.091 C0.542,0.11,0.583,0.162,0.625,0.177 C0.667,0.192,0.708,0.171,0.75,0.151 C0.792,0.132,0.833,0.11,0.875,0.106 C0.917,0.102,0.958,0.11,1,0.111 L1,1">
                </path>
            </clipPath>
        </svg>

        <div class="frame-content">
            <div class="frame-left">
                <h2>Работно време</h2>
                <div class="timetable">
                    <div>Понеделник - петък</div>
                    <div class="hours"> 10:00 - 20:00</div>
                    <div>Събота и неделя</div>
                    <div class="hours"> 12:00 - 17:00</div>
                </div>
            </div>
            <img src="images/salon/interior.jpg.webp" alt="Salon Interior" class="salon-image">
        </div>
    </section>

    <section id="why-us" class="why-us">
        <div class="section-title">
            <span class="line"></span>
            <h2>Фризьорски салон Райа</h2>
            <span class="line"></span>
        </div>
        <h2 class="main-title">Защо да изберете нашия салон?</h2>
        <div class="features-grid">
            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-cut"></i>
                </div>
                <h3>Безупречно качество</h3>
                <p>Работим с водещи продукти и внимание към всеки детайл – защото Вашата коса заслужава най-доброто, най-доброто оборудване от Panasonic и Jaguar</p>
            </div>
            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Персонално отношение</h3>
                <p>Слушаме, съветваме и създаваме точно това, което искате – без изненади, само удоволствие</p>
            </div>
            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
                <h3>Резултат, който говори сам</h3>
                <p>Нашите клиенти не се нуждаят от филтри – просто красива, здрава коса, която впечатлява.</p>
            </div>
        </div>
    </section>

    <section id="services" class="services">
        <div class="service-choice">
            <h2>Choose your service</h2>
        </div>

        <div class="salon-header">
            <h1>Нашите услуги</h1>
        </div>

        <div class="service-categories">
            <button class="service-category" data-category="hair">
                <img src="images/icons/comb (2).png" alt="Коса" class="service-icon">
                <h2 class="category-title">Коса</h2>
            </button>

            <button class="service-category" data-category="face">
                <img src="images/icons/face (2).png" alt="Лице" class="service-icon">
                <h2 class="category-title">Лице</h2>
            </button>

            <button class="service-category" data-category="epilation">
                <img src="images/icons/epilaciq (2).png" alt="Епилация" class="service-icon">
                <h2 class="category-title">Епилация</h2>
            </button>

            <button class="service-category" data-category="massage">
                <img src="images/icons/massage (2).png" alt="Масаж" class="service-icon">
                <h2 class="category-title">Масаж</h2>
            </button>
        </div>

        <div class="service-list">
            <!-- Hair Services -->
            <div class="subcategories-list" data-category="hair">
                <div class="subcategory" data-services='[
                    {"name": "Дамско подстригване", "duration": "20", "price": "35"},
                    {"name": "Дамско подстригване + измиване и подсушаване", "duration": "40", "price": "55", "options": [
                        {"name": "на средно дълга коса", "duration": "40", "price": "55"},
                        {"name": "на дълга коса", "duration": "40", "price": "65"}
                    ]},
                    {"name": "Сешоар", "duration": "40-55", "price": "55", "options": [
                        {"name": "прав на средно дълга коса", "duration": "40", "price": "55"},
                        {"name": "прав на дълга коса", "duration": "40", "price": "65"},
                        {"name": "прав на много дълга коса", "duration": "45", "price": "70"},
                        {"name": "сешоар на букли", "duration": "45", "price": "70"},
                        {"name": "сешоар на четки", "duration": "55", "price": "70"}
                    ]},
                    {"name": "Мъжко подстригване с ножица и машинка + измиване", "duration": "30", "price": "35"},
                    {"name": "Мъжко подстригване с машинка", "duration": "20", "price": "30"},
                    {"name": "Детско подстригване до 12 години", "duration": "30", "price": "25"},
                    {"name": "Прическа с кок", "duration": "90-120", "price": "90", "options": [
                        {"name": "лесна", "duration": "90", "price": "90"},
                        {"name": "сложна", "duration": "120", "price": "100"}
                    ]},
                    {"name": "Официална прическа", "duration": "90-120", "price": "90", "options": [
                        {"name": "лесна", "duration": "90", "price": "90"},
                        {"name": "сложна", "duration": "120", "price": "100"}
                    ]},
                    {"name": "Дамско подстригване на бретон", "duration": "5", "price": "5"},
                    {"name": "Оформяне на врат", "duration": "5", "price": "4"},
                    {"name": "Измиване на коса + маска", "duration": "15", "price": "22"}
                ]'>
                    <h3>Подстригване и прически (11)</h3>
                    <div class="price">от 20 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Боядисване с Wella", "duration": "60-120", "price": "80", "options": [
                        {"name": "на корени", "duration": "60", "price": "80"},
                        {"name": "на къса коса", "duration": "60", "price": "85"},
                        {"name": "на средно дълга коса", "duration": "60", "price": "90"},
                        {"name": "на дълга коса", "duration": "75", "price": "120"},
                        {"name": "на цялата коса", "duration": "60", "price": "120"}
                    ]},
                    {"name": "Боядисване с боя на клиента", "duration": "90", "price": "50"},
                    {"name": "Обезцветяване", "duration": "30-60", "price": "100", "options": [
                        {"name": "на корени", "duration": "30", "price": "120"},
                        {"name": "на къса тънка коса", "duration": "30", "price": "100"},
                        {"name": "на къса гъста коса", "duration": "40", "price": "110"},
                        {"name": "на средно дълга тънка коса", "duration": "60", "price": "130"},
                        {"name": "на средно дълга гъста коса", "duration": "60", "price": "150"},
                        {"name": "на дълга тънка коса", "duration": "60", "price": "160"},
                        {"name": "на дълга гъста коса", "duration": "60", "price": "200"}
                    ]},
                    {"name": "Кичури", "duration": "120", "price": "150"},
                    {"name": "Матиране", "duration": "30", "price": "40"},
                    {"name": "Матиране", "duration": "30", "price": "40"}
                ]'>
                    <h3>Боядисване и кичури (6)</h3>
                    <div class="price">от 20 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Изправяне с преса", "duration": "60", "price": "50", "options": [
                        {"name": "средна", "duration": "60", "price": "50"},
                        {"name": "дълга", "duration": "60", "price": "60"}
                    ]},
                    {"name": "Навиване с преса", "duration": "60", "price": "50", "options": [
                        {"name": "средна", "duration": "60", "price": "50"},
                        {"name": "дълга", "duration": "60", "price": "60"}
                    ]}
                ]'>
                    <h3>Къдрене и изправяне (2)</h3>
                    <div class="price">от 50 лв.</div>
                </div>

                <div class="subcategory" data-services='[
                    {"name": "Терапия от 4 стъпки на Wella", "duration": "30", "price": "80"},
                    {"name": "Кератинова терапия за коса", "duration": "40", "price": "70"},
                    {"name": "Терапия за бързо възстановяване на суха и изтощена коса с Wella", "duration": "30", "price": "40"},
                    {"name": "Арганова терапия за коса", "duration": "30", "price": "70"},
                    {"name": "Ампула за коса против косопад", "duration": "30", "price": "22"},
                    {"name": "Маска за копринена коса", "duration": "40", "price": "35"}
                ]'>
                    <h3>Терапии за коса (6)</h3>
                    <div class="price">от 22 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Оформяне на брада", "duration": "15", "price": "20"}
                ]'>
                    <h3>Брада и бръснене (1)</h3>
                    <div class="price">от 20 лв.</div>
                </div>

            </div>

            <!-- Face Services -->
            <div class="subcategories-list" data-category="face">
                <div class="subcategory" data-services='[
                    {"name": "Професионален грим", "duration": "60", "price": "70"},
                    {"name": "Вечерен грим", "duration": "60", "price": "70"},
                    {"name": "Сватбен грим", "duration": "90", "price": "100"},
                    {"name": "Официален грим", "duration": "60", "price": "90"},
                    {"name": "Официален грим", "duration": "60", "price": "90"},
                    {"name": "Ежедневен грим", "duration": "60", "price": "90"},
                    {"name": "Ежедневен грим", "duration": "60", "price": "90"},
                    {"name": "Абитуриентски грим", "duration": "90", "price": "100"},
                    {"name": "Фото грим", "duration": "90", "price": "100"}
                ]'>
                    <h3>Професионален грим (9)</h3>
                    <div class="price">от 70 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Перманентен грим - вежди", "duration": "120", "price": "450"}
                ]'>
                    <h3>Перманентен грим (1)</h3>
                    <div class="price">от 450 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Пробиване на уши", "duration": "10", "price": "25"}
                ]'>
                    <h3>Други услуги (1)</h3>
                    <div class="price">от 25 лв.</div>
                </div>
            </div>

            <!-- Epilation Services -->
            <div class="subcategories-list" data-category="epilation">
                <div class="subcategory" data-services='[
                    {"name": "Подмишници - кола маска", "duration": "15", "price": "15"},
                    {"name": "Цели крака - кола маска", "duration": "60", "price": "40"},
                    {"name": "1/2 крака - кола маска", "duration": "60", "price": "100"},
                    {"name": "Цели ръце - кола маска", "duration": "20", "price": "60"},
                    {"name": "Цяло тяло - кола маска", "duration": "90", "price": "200"},
                    {"name": "Брадичка - кола маска", "duration": "5", "price": "10"},
                    {"name": "Горна устна - кола маска", "duration": "5", "price": "10"},
                    {"name": "Бакенбарди - кола маска", "duration": "5", "price": "10"},
                    {"name": "Скули - кола маска", "duration": "5", "price": "10"}
                ]'>
                    <h3>Кола маска жени (9)</h3>
                    <div class="price">от 10 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Подмишници - кола маска", "duration": "10", "price": "15"},
                    {"name": "Гръб - кола маска", "duration": "20", "price": "40"},
                    {"name": "Гърди + корем - кола маска", "duration": "30", "price": "50"},
                    {"name": "Гърди - кола маска", "duration": "60", "price": "40"},
                    {"name": "Корем - кола маска", "duration": "30", "price": "40"},
                    {"name": "Кръст - кола маска", "duration": "20", "price": "25"},
                    {"name": "Цели ръце - кола маска", "duration": "30", "price": "60"},
                    {"name": "Цели крака - кола маска", "duration": "40", "price": "40"},
                    {"name": "Цяло тяло - кола маска", "duration": "60", "price": "200"},
                    {"name": "Скули - кола маска", "duration": "10", "price": "10"},
                    {"name": "Врат - кола маска", "duration": "10", "price": "10"}
                ]'>
                    <h3>Кола маска мъже (11)</h3>
                    <div class="price">от 10 лв.</div>
                </div>
            </div>

            <!-- Massage Services -->
            <div class="subcategories-list" data-category="massage">
                <div class="subcategory" data-services='[
                    {"name": "Релаксиращ масаж", "duration": "60", "price": "100"},
                    {"name": "Класически масаж при Вики", "duration": "60", "price": "100"}
                ]'>
                    <h3>Класически масаж (2)</h3>
                    <div class="price">от 100 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Спортен масаж", "duration": "50", "price": "100"}
                ]'>
                    <h3>Спортен масаж (1)</h3>
                    <div class="price">от 100 лв.</div>
                </div>
            </div>

            <div class="services-list">
                <!-- Services will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <section id="testimonials" class="testimonials">
        <div class="testimonials-header">
            <div class="line"></div>
            <h3>Какво казват хората</h3>
            <div class="line"></div>
        </div>
        <h2>Отзиви</h2>

        <div class="testimonials-container">
            <div class="testimonials-grid" id="dynamicTestimonials">
                <!-- Reviews will be loaded dynamically from database -->
            </div>
        </div>
        
        <!-- Add Review Button -->
        <div class="add-review-section">
            <button class="add-review-btn" id="addClientReviewBtn">
                <i class="fas fa-plus"></i> Добави отзив...
            </button>
        </div>
    </section>

    <!-- Client Review Modal -->
    <div class="client-review-modal" id="clientReviewModal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Споделете вашето мнение</h3>
                <button class="close-btn" id="closeClientModal">&times;</button>
            </div>
            <form id="clientReviewForm">
                <div class="form-group">
                    <label for="clientName">Вашето име:</label>
                    <input type="text" id="clientName" name="client_name" required>
                </div>
                
                <div class="form-group">
                    <label>Оценка:</label>
                    <div class="star-rating" id="starRating">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="5">
                </div>
                
                <div class="form-group">
                    <label for="reviewText">Вашият отзив:</label>
                    <textarea id="reviewText" name="review_text" rows="4" placeholder="Споделете вашето преживяване..." required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="cancel-btn" id="cancelClientBtn">Отказ</button>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Изпрати отзив
                    </button>
                </div>
            </form>
        </div>
    </div>

    <section id="shop" class="shop">
        <div class="shop-header">
            <div class="line"></div>
            <h3>Помислили сме за външния ти вид</h3>
            <div class="line"></div>
        </div>
        <h2>Използваме качествени продукти</h2>

        <div class="products-grid">
            <div class="product-card">
                <div class="product-image">
                    <img src="images/products/wella.png" alt="Wella Logo" class="product-logo">
                    <img src="images/products/wella2.png" alt="Wella Products">
                </div>
                <div class="product-info">
                    <h3>WELLA PROFESSIONALS</h3>
                </div>
            </div>
            <div class="product-card">
                <div class="product-image">
                    <img src="images/products/kerastase/kerastase.png" alt="Kerastase Logo" class="product-logo">
                    <img src="images/products/kerastase/kerastase 1.png" alt="Kerastase Products">
                </div>
                <div class="product-info">
                    <h3>KERASTASE</h3>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
    <script>
        // Testimonials horizontal scrolling functionality
        document.addEventListener('DOMContentLoaded', function() {
            const testimonialsGrid = document.querySelector('.testimonials-grid');
            const testimonials = document.querySelector('.testimonials');
            
            if (!testimonialsGrid || !testimonials) return;
            
            // Load reviews from database
            loadMainPageReviews();
            
            function loadMainPageReviews() {
                fetch('get-main-page-reviews.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.reviews.length > 0) {
                            displayReviews(data.reviews);
                            // Re-initialize scrolling after reviews are loaded
                            setTimeout(initializeScrolling, 100);
                        } else {
                            // Show fallback message if no reviews
                            testimonialsGrid.innerHTML = '<div class="no-reviews">Няма публикувани отзиви все още.</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading reviews:', error);
                        // Show fallback message on error
                        testimonialsGrid.innerHTML = '<div class="no-reviews">Няма налични отзиви в момента.</div>';
                    });
            }
            
            function displayReviews(reviews) {
                testimonialsGrid.innerHTML = '';
                
                reviews.forEach(review => {
                    const reviewCard = document.createElement('a');
                    reviewCard.className = 'testimonial-card';
                    reviewCard.href = review.google_link || '#';
                    
                    // Generate stars based on rating
                    let starsHTML = '';
                    for (let i = 0; i < review.rating; i++) {
                        starsHTML += '<img src="images/icons/star.png" alt="star">';
                    }
                    
                    reviewCard.innerHTML = `
                <div class="testimonial-content">
                            <div class="user-icon" style="background-color: ${review.background_color};">${review.client_initial}</div>
                    <div class="stars">
                                ${starsHTML}
                    </div>
                            <p>${review.review_text}</p>
                            <h3>${review.client_name}</h3>
                    <span class="client-type">Клиент</span>
                </div>
                    `;
                    
                    testimonialsGrid.appendChild(reviewCard);
                });
            }
            
            function initializeScrolling() {
                let isScrolling = false;
                
                // Center the first review initially
                setTimeout(() => {
                    const containerWidth = testimonialsGrid.clientWidth;
                    const cardWidth = 300 + 24; // card width + gap
                    const centerOffset = (containerWidth - 300) / 2 - 150; // subtract padding
                    testimonialsGrid.scrollLeft = Math.max(0, cardWidth - centerOffset);
                }, 100);
                
                // Rest of scrolling functionality...
                setupScrollingEvents();
            }
            
            function setupScrollingEvents() {
                let isScrolling = false;
                
                // Auto-scroll functionality when cursor is near the right edge
                testimonials.addEventListener('mousemove', function(e) {
                if (isScrolling) return;
                
                const rect = testimonials.getBoundingClientRect();
                const mouseX = e.clientX - rect.left;
                const sectionWidth = rect.width;
                const rightThreshold = sectionWidth - 150; // 150px from right edge (fade zone)
                const leftThreshold = 150; // 150px from left edge (fade zone)
                
                // Check if mouse is near right edge - immediate scrolling
                if (mouseX > rightThreshold) {
                    smoothScrollRight();
                }
                // Check if mouse is near left edge - immediate scrolling
                else if (mouseX < leftThreshold) {
                    smoothScrollLeft();
                }
            });
            
            function smoothScrollRight() {
                isScrolling = true;
                const cardWidth = 300 + 24; // card width + gap
                const currentScroll = testimonialsGrid.scrollLeft;
                const maxScroll = testimonialsGrid.scrollWidth - testimonialsGrid.clientWidth;
                
                if (currentScroll < maxScroll) {
                    testimonialsGrid.scrollBy({
                        left: cardWidth,
                        behavior: 'smooth'
                    });
                } else {
                    // If at the end, scroll back to the beginning
                    testimonialsGrid.scrollTo({
                        left: 0,
                        behavior: 'smooth'
                    });
                }
                
                setTimeout(() => {
                    isScrolling = false;
                }, 800);
            }
            
            function smoothScrollLeft() {
                isScrolling = true;
                const cardWidth = 300 + 24; // card width + gap
                const currentScroll = testimonialsGrid.scrollLeft;
                
                if (currentScroll > 0) {
                    testimonialsGrid.scrollBy({
                        left: -cardWidth,
                        behavior: 'smooth'
                    });
                } else {
                    // If at the beginning, scroll to the end
                    testimonialsGrid.scrollTo({
                        left: testimonialsGrid.scrollWidth,
                        behavior: 'smooth'
                    });
                }
                
                setTimeout(() => {
                    isScrolling = false;
                }, 800);
            }
            
            // Touch/drag scrolling for mobile
            let isDown = false;
            let startX;
            let scrollLeft;
            
            testimonialsGrid.addEventListener('mousedown', (e) => {
                isDown = true;
                testimonialsGrid.style.cursor = 'grabbing';
                startX = e.pageX - testimonialsGrid.offsetLeft;
                scrollLeft = testimonialsGrid.scrollLeft;
            });
            
            testimonialsGrid.addEventListener('mouseleave', () => {
                isDown = false;
                testimonialsGrid.style.cursor = 'grab';
            });
            
            testimonialsGrid.addEventListener('mouseup', () => {
                isDown = false;
                testimonialsGrid.style.cursor = 'grab';
            });
            
            testimonialsGrid.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - testimonialsGrid.offsetLeft;
                const walk = (x - startX) * 2;
                testimonialsGrid.scrollLeft = scrollLeft - walk;
            });
            }
        });
    </script>
    <script>
        // Main page services functionality
        document.addEventListener('DOMContentLoaded', function() {
            const serviceCategories = document.querySelectorAll('.service-category');
            const subcategoriesLists = document.querySelectorAll('.subcategories-list');
            const servicesList = document.querySelector('.services-list');

            // Function to convert LV to Euro and format dual currency display
            function formatDualCurrency(priceLV) {
                const euroPrice = Math.ceil((priceLV / 1.95583) * 100) / 100; // Round up to cent
                return `${priceLV} лв. / ${euroPrice.toFixed(2)} €`;
            }

            // Function to render services for main page
            function renderServices(servicesData) {
                if (!servicesList) return;
                
                servicesList.innerHTML = '';
                
                servicesData.forEach((service, serviceIndex) => {
                    const serviceItem = document.createElement('div');
                    serviceItem.className = 'service-item';
                    
                    let duration = service.duration;
                    if (typeof duration === 'number') {
                        duration = duration + ' мин.';
                    } else if (typeof duration === 'string' && !duration.includes('мин') && !duration.includes('ч')) {
                        duration = duration + ' мин.';
                    }
                    
                    // Check if service has options
                    const hasOptions = service.options && service.options.length > 0;
                    
                    serviceItem.innerHTML = `
                        <div class="service-item-content">
                            <div class="service-main-content">
                                <div class="service-info">
                                    <h3>${service.name}</h3>
                                    <p class="service-duration">${duration}</p>
                                </div>
                                <div class="service-price-container">
                                    <div class="service-price">
                                        <div class="price">${formatDualCurrency(service.price)}</div>
                                    </div>
                                    ${hasOptions ? 
                                        `<button class="options-btn" data-service-index="${serviceIndex}">опции ▼</button>` : 
                                        `<a href="booking.php?service=${encodeURIComponent(service.name)}&price=${service.price}&duration=${duration.replace(' мин.', '')}" class="select-btn">Избери</a>`
                                    }
                                </div>
                            </div>
                            ${hasOptions ? `
                                <div class="service-options" data-service-index="${serviceIndex}">
                                    ${service.options.map(option => {
                                        let optionDuration = option.duration;
                                        if (typeof optionDuration === 'number') {
                                            optionDuration = optionDuration + ' мин.';
                                        } else if (typeof optionDuration === 'string' && !optionDuration.includes('мин') && !optionDuration.includes('ч')) {
                                            optionDuration = optionDuration + ' мин.';
                                        }
                                        
                                        return `
                                            <div class="service-option">
                                                <div class="option-info">
                                                    <div class="option-name">${option.name}</div>
                                                    <div class="option-duration">${optionDuration}</div>
                                                </div>
                                                <div class="option-price">
                                                    <div class="price">${formatDualCurrency(option.price)}</div>
                                                    <a href="booking.php?service=${encodeURIComponent(service.name + ' (' + option.name + ')')}&price=${option.price}&duration=${optionDuration.replace(' мин.', '')}" class="select-btn">Избери</a>
                                                </div>
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            ` : ''}
                        </div>
                    `;
                    
                    servicesList.appendChild(serviceItem);
                });
                
                // Add event listeners for options buttons
                document.querySelectorAll('.options-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const serviceIndex = this.getAttribute('data-service-index');
                        const optionsContainer = document.querySelector(`.service-options[data-service-index="${serviceIndex}"]`);
                        
                        if (optionsContainer) {
                            const isVisible = optionsContainer.classList.contains('show');
                            
                            // Hide all other options
                            document.querySelectorAll('.service-options').forEach(container => {
                                container.classList.remove('show');
                            });
                            
                            // Reset all buttons
                            document.querySelectorAll('.options-btn').forEach(button => {
                                button.textContent = 'опции ▼';
                            });
                            
                            // Toggle current options
                            if (!isVisible) {
                                optionsContainer.classList.add('show');
                                this.textContent = 'опции ▲';
                            }
                        }
                    });
                });
            }

            // Function to activate category and subcategory
            function activateAndRenderSubcategory(mainCategoryElement, subCategoryElement) {
                if (!mainCategoryElement || !subCategoryElement) return;

                // Activate Main Category
                serviceCategories.forEach(c => c.classList.remove('active'));
                mainCategoryElement.classList.add('active');

                // Show Correct Subcategory List
                const categoryType = mainCategoryElement.getAttribute('data-category');
                subcategoriesLists.forEach(list => {
                    if (list.getAttribute('data-category') === categoryType) {
                        list.style.display = 'flex';
                        list.classList.add('active');
                    } else {
                        list.style.display = 'none';
                        list.classList.remove('active');
                    }
                });

                // Activate Subcategory
                document.querySelectorAll('.subcategory').forEach(s => s.classList.remove('active'));
                subCategoryElement.classList.add('active');

                // Render Services
                const services = subCategoryElement.getAttribute('data-services');
                if (services) {
                    const servicesData = JSON.parse(services);
                    renderServices(servicesData);
                } else {
                    servicesList.innerHTML = '<p>Няма налични услуги в тази подкатегория.</p>';
                }
            }

            // Handle service category clicks
            serviceCategories.forEach(category => {
                category.addEventListener('click', function() {
                    const categoryType = this.getAttribute('data-category');
                    const subcategoryList = document.querySelector(`.subcategories-list[data-category="${categoryType}"]`);
                    const firstSubcategoryElement = subcategoryList ? subcategoryList.querySelector('.subcategory') : null;
                    
                    // Activate and render the first subcategory of the clicked main category
                    activateAndRenderSubcategory(this, firstSubcategoryElement);
                });
            });
            
            // Handle subcategory clicks
            document.querySelectorAll('.subcategory').forEach(subcategory => {
                subcategory.addEventListener('click', function() {
                    // Find parent main category to keep it active
                    const parentList = this.closest('.subcategories-list');
                    const mainCategoryType = parentList.getAttribute('data-category');
                    const mainCategoryElement = document.querySelector(`.service-category[data-category="${mainCategoryType}"]`);
                    
                    // Activate and render this specific subcategory
                    activateAndRenderSubcategory(mainCategoryElement, this);
                });
            });

            // Auto-load first category and subcategory on page load
            setTimeout(function() {
                const firstCategory = document.querySelector('.service-category[data-category="hair"]');
                if (firstCategory) {
                    firstCategory.click();
                }
            }, 100);
        });
    </script>
    
    <script>
        // Client Review Modal Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const addClientReviewBtn = document.getElementById('addClientReviewBtn');
            const clientReviewModal = document.getElementById('clientReviewModal');
            const closeClientModal = document.getElementById('closeClientModal');
            const cancelClientBtn = document.getElementById('cancelClientBtn');
            const clientReviewForm = document.getElementById('clientReviewForm');
            const starRating = document.getElementById('starRating');
            const ratingInput = document.getElementById('rating');
            
            // Open modal
            addClientReviewBtn.addEventListener('click', () => {
                clientReviewModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            
            // Close modal functions
            function closeModal() {
                clientReviewModal.classList.remove('active');
                document.body.style.overflow = 'auto';
                clientReviewForm.reset();
                // Reset stars to 0 (all hollow borders)
                updateStars(0);
                ratingInput.value = 0; // No rating selected initially
            }
            
            closeClientModal.addEventListener('click', closeModal);
            cancelClientBtn.addEventListener('click', closeModal);
            
            // Close modal when clicking overlay
            clientReviewModal.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal-overlay')) {
                    closeModal();
                }
            });
            
            // Star rating functionality
            const stars = starRating.querySelectorAll('.star');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    const rating = index + 1;
                    ratingInput.value = rating;
                    updateStars(rating);
                });
                
            });
            
            function updateStars(rating) {
                stars.forEach((star, index) => {
                    star.classList.remove('active');
                    if (index < rating) {
                        star.classList.add('active');
                    }
                });
            }
            
            // Initialize with 0 stars (all hollow borders)
            updateStars(0);
            ratingInput.value = 0; // No rating selected initially
            
            // Form submission
            clientReviewForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const formData = new FormData(clientReviewForm);
                formData.append('action', 'submit_client_review');
                
                // Show loading state
                const submitBtn = clientReviewForm.querySelector('.submit-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Изпращане...';
                submitBtn.disabled = true;
                
                fetch('submit-client-review.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Благодарим за вашия отзив! Той ще бъде прегледан и публикуван скоро.');
                        closeModal();
                    } else {
                        alert('Възникна грешка: ' + (data.message || 'Моля опитайте отново.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Възникна грешка при изпращането. Моля опитайте отново.');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
</body>

</html>