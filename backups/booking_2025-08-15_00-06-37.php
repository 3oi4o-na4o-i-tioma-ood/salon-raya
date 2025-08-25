<?php
session_start();
?>
<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Запазете час онлайн за услугите в салон Райа. Изберете подстригване, прическа, боядисване или друга услуга по ваш избор.">
    <title>Резервация на час - Салон Райа</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="canonical" href="https://salonraia.eu/booking.php">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        <a href="/za-nas">За Нас</a>
        <a href="/contacts">Контакти</a>
    </div>

    <div class="booking-container">
        <h1 class="title">Резервация на час</h1>
        <p class="subtitle">Изберете услугите, които желаете</p>

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

        <div class="service-list date-selection-container">
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
                    {"name": "Матиране", "duration": "30", "price": "20"},
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
                    {"name": "Официален грим", "duration": "60", "price": "70"},
                    {"name": "Официален грим", "duration": "60", "price": "90"},
                    {"name": "Ежедневен грим", "duration": "60", "price": "70"},
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
                    {"name": "Подмишници - кола маска", "duration": "15", "price": "30"},
                    {"name": "Цели крака - кола маска", "duration": "60", "price": "60"},
                    {"name": "1/2 крака - кола маска", "duration": "60", "price": "60"},
                    {"name": "Цели ръце - кола маска", "duration": "20", "price": "40"},
                    {"name": "Цяло тяло - кола маска", "duration": "90", "price": "120"},
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

        <div class="booking-summary" style="display: none;">
            <div class="selected-services-list">
                <div class="selected-services-container">
                    <!-- Selected services will be added here dynamically -->
                </div>
            </div>

            <div class="summary-content">
                <div class="summary-left">
                    <div class="selected-count"><span>0</span> услуги</div>
                    <div class="total-duration"><span>0</span> мин.</div>
                </div>
                <div class="summary-right">
                    <div class="total-price">Избери час <span>0 лв.</span></div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="js/menu.js"></script>
    <script src="js/booking.js?v=<?php echo time(); ?>"></script>
</body>

</html>