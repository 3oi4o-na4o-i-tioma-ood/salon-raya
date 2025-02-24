<?php
session_start();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Резервация на час - Фризьорски салон Райа</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="Salon Raya Logo">
        </div>
    </nav>

    <div class="booking-container">
        <h1>Резервация на час</h1>
        <p class="subtitle">Изберете услугите, които желаете</p>

        <div class="hero-sections">
            <div class="hero-section" data-category="hair">
                <h2>Коса</h2>
        </div>
            <div class="hero-section" data-category="face">
                <h2>Лице</h2>
                    </div>
            <div class="hero-section" data-category="epilation">
                <h2>Епилация</h2>
                </div>
            <div class="hero-section" data-category="massage">
                <h2>Масаж</h2>
            </div>
                    </div>

        <div class="service-selection">
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
                    {"name": "Сешоар", "duration": "40-60", "price": "45", "options": [
                        {"name": "на къса коса", "duration": "40", "price": "45"},
                        {"name": "на средна коса", "duration": "50", "price": "55"},
                        {"name": "на дълга коса", "duration": "60", "price": "65"}
                    ]},
                    {"name": "Мъжко подстригване с ножица и машинка + измиване", "duration": "30", "price": "35"},
                    {"name": "Мъжко подстригване с машинка", "duration": "20", "price": "30"},
                    {"name": "Детско подстригване до 12 години", "duration": "30", "price": "25"},
                    {"name": "Прическа с кок", "duration": "90-120", "price": "70", "options": [
                        {"name": "лесна", "duration": "90", "price": "70"},
                        {"name": "сложна", "duration": "120", "price": "90"}
                    ]},
                    {"name": "Официална прическа", "duration": "90-120", "price": "75", "options": [
                        {"name": "лесна", "duration": "90", "price": "75"},
                        {"name": "сложна", "duration": "120", "price": "100"}
                    ]},
                    {"name": "Дамско подстригване на бретон", "duration": "5", "price": "5"},
                    {"name": "Оформяне на врат", "duration": "5", "price": "4"},
                    {"name": "Измиване на коса + маска", "duration": "15", "price": "22"}
                ]'>
                    <h3>Подстригване и прически (12)</h3>
                    <div class="price">от 4 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Боядисване с wella + сешоар", "duration": "80", "price": "100"},
                    {"name": "Боядисване с Wella", "duration": "60-120", "price": "80", "options": [
                        {"name": "на корени", "duration": "60", "price": "80"},
                        {"name": "на къса коса", "duration": "60", "price": "85"},
                        {"name": "на средно дълга коса", "duration": "120", "price": "89"},
                        {"name": "на дълга коса", "duration": "75", "price": "90"},
                        {"name": "на цялата коса", "duration": "60", "price": "100"}
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
                    {"name": "Кичури", "duration": "120", "price": "80"},
                    {"name": "Матиране", "duration": "90", "price": "60"}
                ]'>
                    <h3>Боядисване и кичури (6)</h3>
                    <div class="price">от 40 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Изправяне с преса", "duration": "60", "price": "25", "options": [
                        {"name": "на средно дълга коса", "duration": "60", "price": "25"},
                        {"name": "на дълга коса", "duration": "60", "price": "30"}
                    ]},
                    {"name": "Измиване подстригване + Изправяне с сешоар", "duration": "70", "price": "75"}
                ]'>
                    <h3>Къдрене и изправяне (2)</h3>
                    <div class="price">от 25 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Удължаване на коса с щипки", "duration": "10-50", "price": "10", "options": [
                        {"name": "1 ред", "duration": "10", "price": "10"},
                        {"name": "2 реда", "duration": "20", "price": "20"},
                        {"name": "3 реда", "duration": "30", "price": "30"},
                        {"name": "4 реда", "duration": "40", "price": "40"},
                        {"name": "5 реда", "duration": "50", "price": "50"}
                    ]}
                ]'>
                    <h3>Екстеншъни (1)</h3>
                    <div class="price">от 10 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Кератинова терапия за коса", "duration": "90", "price": "120"},
                    {"name": "Терапия за бързо възстановяване на суха и изтощена коса", "duration": "60", "price": "80"},
                    {"name": "Арганова терапия за коса", "duration": "60", "price": "60"},
                    {"name": "Ампула за коса против косопад", "duration": "30", "price": "30"},
                    {"name": "Маска за копринена коса", "duration": "30", "price": "25"}
                ]'>
                    <h3>Терапии за коса (5)</h3>
                    <div class="price">от 25 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Оформяне на брада", "price": "30", "description": "Оформянето на брадата е специална процедура включваща избор на форма и контури на брада пак на база тип лице, личният стил и предпочитание на всеки мъж."},
                    {"name": "Тониране на сиви коси", "price": "40", "description": "Тониране на сиви коси, които придават естествен и младежки вид. Тонирането се извършва с внимание към типа коса."}
                ]'>
                    <h3>Брада и бръснене (1)</h3>
                    <div class="price">от 10 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Пробиване на уши", "duration": "15", "price": "30"}
                ]'>
                    <h3>Други услуги за коса (1)</h3>
                    <div class="price">от 30 лв.</div>
                </div>
            </div>

            <!-- Face Services -->
            <div class="subcategories-list" data-category="face">
                <div class="subcategory" data-services='[
                    {"name": "Професионален грим", "duration": "60", "price": "70"},
                    {"name": "Вечерен грим", "duration": "60", "price": "70"},
                    {"name": "Сватбен грим", "duration": "90", "price": "100"},
                    {"name": "Официален грим", "duration": "60", "price": "70"},
                    {"name": "Ежедневен грим", "duration": "60", "price": "70"},
                    {"name": "Абитуриентски грим", "duration": "90", "price": "100"},
                    {"name": "Фото грим", "duration": "90", "price": "100"}
                ]'>
                    <h3>Професионален грим (7)</h3>
                    <div class="price">от 70 лв.</div>
                </div>
                <div class="subcategory" data-services='[
                    {"name": "Перманентен грим на вежди", "duration": "120", "price": "250"}
                ]'>
                    <h3>Перманентен грим (1)</h3>
                    <div class="price">от 250 лв.</div>
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
                    {"name": "Спортен масаж", "duration": "60", "price": "120"}
                ]'>
                    <h3>Спортен масаж (1)</h3>
                    <div class="price">от 120 лв.</div>
                </div>
            </div>

            <div class="services-list">
                <!-- Services will be populated by JavaScript -->
            </div>
                    </div>

        <div class="booking-summary" style="display: none;">
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
    <script src="js/booking.js"></script>
</body>
</html> 