<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Фризьорски салон Райа - Професионални фризьорски услуги за жени и мъже. Подстригване, боядисване, прически и грижа за косата.">
    <title>Салон за красота Райа</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css">
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
        <a href="#hero">Начало</a>
        <a href="#services">Услуги</a>
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
            <h2>Фризьорски сало Райа</h2>
            <span class="line"></span>
        </div>
        <h2 class="main-title">Защо да изберете нашия салон?</h2>
        <div class="features-grid">
            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-cut"></i>
                </div>
                <h3>Професионални<br>Инструменти</h3>
                <p>Работим с най-доброто оборудване<br>от panasonic и jaguar</p>
            </div>
            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Качествена<br>Козметика</h3>
                <p>Салон Райа<br>използва най-доброто от<br>Wella и Kerastase</p>
            </div>
            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Изживяване<br>за всеки</h3>
                <p>Нашият салон предлага голямо разнообразие от услуги<br>както за жени,<br> така и за мъже</p>
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
            <button class="service-category">
                <img src="images/icons/comb (2).png" alt="Коса" class="service-icon">
                <h2 class="category-title">Коса</h2>
            </button>

            <button class="service-category">
                <img src="images/icons/face (2).png" alt="Лице" class="service-icon">
                <h2 class="category-title">Лице</h2>
            </button>

            <button class="service-category">
                <img src="images/icons/epilaciq (2).png" alt="Епилация" class="service-icon">
                <h2 class="category-title">Епилация</h2>
            </button>

            <button class="service-category">
                <img src="images/icons/massage (2).png" alt="Масаж" class="service-icon">
                <h2 class="category-title">Масаж</h2>
            </button>
        </div>

        <div class="service-list">
            <div class="service-categories-list hair-categories">
                <div class="service-category-item active" data-category="haircuts">
                    <h3>Подстригване и прически (12)</h3>
                    <span class="price">от 20 лв.</span>
                </div>
                <div class="service-category-item" data-category="coloring">
                    <h3>Боядисване и кичури (6)</h3>
                    <span class="price">от 20 лв.</span>
                </div>
                <div class="service-category-item" data-category="curling">
                    <h3>Къдрене и изправяне (2)</h3>
                    <span class="price">от 25 лв.</span>
                </div>
                <div class="service-category-item" data-category="extensions">
                    <h3>Екстеншъни (1)</h3>
                    <span class="price">от 10 лв.</span>
                </div>
                <div class="service-category-item" data-category="treatments">
                    <h3>Терапии за коса (5)</h3>
                    <span class="price">от 22 лв.</span>
                </div>
                <div class="service-category-item" data-category="beard">
                    <h3>Брада и бръснене (1)</h3>
                    <span class="price">от 20 лв.</span>
                </div>
                <div class="service-category-item" data-category="other">
                    <h3>Други услуги за коса (1)</h3>
                    <span class="price">от 25 лв.</span>
                </div>
            </div>

            <div class="service-categories-list face-categories" style="display: none;">
                <div class="service-category-item active" data-category="makeup">
                    <h3>Професионален грим (7)</h3>
                    <span class="price">от 70 лв.</span>
                </div>
                <div class="service-category-item" data-category="permanent">
                    <h3>Перманентен грим (1)</h3>
                    <span class="price">от 450 лв.</span>
                </div>
            </div>

            <div class="service-categories-list epilation-categories" style="display: none;">
                <div class="service-category-item active" data-category="women">
                    <h3>Кола маска жени (9)</h3>
                    <span class="price">от 10 лв.</span>
                </div>
                <div class="service-category-item" data-category="men">
                    <h3>Кола маска мъже (11)</h3>
                    <span class="price">от 10 лв.</span>
                </div>
            </div>

            <div class="service-categories-list massage-categories" style="display: none;">
                <div class="service-category-item active" data-category="classic">
                    <h3>Класически масаж (2)</h3>
                    <span class="price">от 100 лв.</span>
                </div>
                <div class="service-category-item" data-category="sport">
                    <h3>Спортен масаж (1)</h3>
                    <span class="price">от 100 лв.</span>
                </div>
            </div>

            <div class="service-details-list">
                <div class="service-details-category hair-details active" data-category="haircuts">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Дамско подстригване</h3>
                            <p>20 мин.</p>
                        </div>
                        <div class="service-price">35 лв.</div>
                        <a href="/booking?category=hair&service=haircuts&detail=damsko-podstrigvane"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Дамско подстригване + измиване и подсушаване</h3>
                            <p>40 мин.</p>
                        </div>
                        <div class="service-price">от 55 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Сешоар</h3>
                            <p>40 мин. - 55 мин.</p>
                        </div>
                        <div class="service-price">от 55 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Сешоар</h3>
                            <p>40 мин. - 1 ч.</p>
                        </div>
                        <div class="service-price">от 45 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Мъжко подстригване с ножица и машинка + измиване</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">35 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Мъжко подстригване с машинка</h3>
                            <p>20 мин.</p>
                        </div>
                        <div class="service-price">30 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Детско подстригване до 12 години</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">25 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Прическа с кок</h3>
                            <p>1 ч.30 мин. - 2 ч.</p>
                        </div>
                        <div class="service-price">от 70 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Официална прическа</h3>
                            <p>1 ч.30 мин. - 2 ч.</p>
                        </div>
                        <div class="service-price">от 75 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Дамско подстригване на бретон</h3>
                            <p>5 мин.</p>
                        </div>
                        <div class="service-price">5 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Оформяне на врат</h3>
                            <p>5 мин.</p>
                        </div>
                        <div class="service-price">4 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Измиване на коса + маска</h3>
                            <p>15 мин.</p>
                        </div>
                        <div class="service-price">22 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category hair-details" data-category="coloring">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Боядисване с wella + сешоар</h3>
                            <p>1 ч.20 мин.</p>
                        </div>
                        <div class="service-price">100 лв.</div>
                        <a href="/booking?category=hair&service=coloring&detail=boyadisvane-wella"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Боядисване с Wella</h3>
                            <p>1 ч. - 2 ч.</p>
                        </div>
                        <div class="service-price">от 80 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Боядисване с боя на клиента</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">50 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Обезцветяване</h3>
                            <p>30 мин. - 1 ч.</p>
                        </div>
                        <div class="service-price">от 100 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Кичури</h3>
                            <p>2 ч.</p>
                        </div>
                        <div class="service-price">150 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Матиране</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">20 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category hair-details" data-category="curling">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Изправяне с преса</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">от 25 лв.</div>
                        <a href="/booking?category=hair&service=curling&detail=izpravyane-pres"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Измиване подстригване + Изправяне с сешоар</h3>
                            <p>1 ч.10 мин.</p>
                        </div>
                        <div class="service-price">75 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category hair-details" data-category="extensions">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Удължаване на коса с щипки</h3>
                            <p>10 мин. - 50 мин.</p>
                        </div>
                        <div class="service-price">от 10 лв.</div>
                        <a href="/booking?category=hair&service=extensions&detail=udalyavane-kosa"
                            class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category hair-details" data-category="treatments">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Кератинова терапия за коса</h3>
                            <p>40 мин.</p>
                        </div>
                        <div class="service-price">70 лв.</div>
                        <a href="/booking?category=hair&service=treatments&detail=keratinova-terapiya"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Терапия за бързо възстановяване на суха и изтощена коса с Wella</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">40 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Арганова терапия за коса</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">70 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Ампула за коса против косопад</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">22 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Маска за копринена коса</h3>
                            <p>40 мин.</p>
                        </div>
                        <div class="service-price">35 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category hair-details" data-category="beard">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Оформяне на брада</h3>
                            <p>15 мин.</p>
                        </div>
                        <div class="service-price">20 лв.</div>
                        <a href="/booking?category=hair&service=beard&detail=oformyane-brada"
                            class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category hair-details" data-category="other">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Пробиване на уши</h3>
                            <p>10 мин.</p>
                        </div>
                        <div class="service-price">25 лв.</div>
                        <a href="/booking?category=hair&service=other&detail=probivane-ushi"
                            class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category face-details" data-category="makeup">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Професионален грим</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">70 лв.</div>
                        <a href="/booking?category=face&service=makeup&detail=profesionalen-grim"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Вечерен грим</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">70 лв.</div>
                        <a href="/booking?category=face&service=makeup&detail=vecheren-grim"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Сватбен грим</h3>
                            <p>1 ч.30 мин.</p>
                        </div>
                        <div class="service-price">100 лв.</div>
                        <a href="/booking?category=face&service=makeup&detail=svatben-grim"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Официален грим</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">70 лв.</div>
                        <a href="/booking?category=face&service=makeup&detail=oficialen-grim"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Ежедневен грим</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">70 лв.</div>
                        <a href="/booking?category=face&service=makeup&detail=ezhedneven-grim"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Абитуриентски грим</h3>
                            <p>1 ч.30 мин.</p>
                        </div>
                        <div class="service-price">100 лв.</div>
                        <a href="/booking?category=face&service=makeup&detail=abiturientski-grim"
                            class="select-service-btn">Избери</a>
                    </div>

                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Фото грим</h3>
                            <p>1 ч.30 мин.</p>
                        </div>
                        <div class="service-price">100 лв.</div>
                        <a href="/booking?category=face&service=makeup&detail=foto-grim"
                            class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category face-details" data-category="permanent">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Перманентен грим</h3>
                            <p>2 ч.</p>
                        </div>
                        <div class="service-price">450 лв.</div>
                        <a href="/booking?category=face&service=permanent&detail=permanenten-grim"
                            class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category epilation-details" data-category="women">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Подмишници - кола маска</h3>
                            <p>15 мин.</p>
                        </div>
                        <div class="service-price">30 лв.</div>
                        <a href="/booking?category=epilation&service=women&detail=podmishnitsi-kola"
                            class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Цели крака - кола маска</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">60 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>1/2 крака - кола маска</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">60 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Цели ръце - кола маска</h3>
                            <p>20 мин.</p>
                        </div>
                        <div class="service-price">40 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Цяло тяло - кола маска</h3>
                            <p>1 ч.30 мин.</p>
                        </div>
                        <div class="service-price">120 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Брадичка - кола маска</h3>
                            <p>5 мин.</p>
                        </div>
                        <div class="service-price">10 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Горна устна - кола маска</h3>
                            <p>5 мин.</p>
                        </div>
                        <div class="service-price">10 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Бакенбарди - кола маска</h3>
                            <p>5 мин.</p>
                        </div>
                        <div class="service-price">10 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Скули - кола маска</h3>
                            <p>5 мин.</p>
                        </div>
                        <div class="service-price">10 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category epilation-details" data-category="men">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Подмишници - кола маска</h3>
                            <p>10 мин.</p>
                        </div>
                        <div class="service-price">15 лв.</div>
                        <a href="/booking?category=epilation&service=men&detail=podmishnitsi-kola"
                            class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Гръб - кола маска</h3>
                            <p>20 мин.</p>
                        </div>
                        <div class="service-price">40 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Гърди + корем - кола маска</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">50 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Гърди - кола маска</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">40 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Корем - кола маска</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">40 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Кръст - кола маска</h3>
                            <p>20 мин.</p>
                        </div>
                        <div class="service-price">25 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Цели ръце - кола маска</h3>
                            <p>30 мин.</p>
                        </div>
                        <div class="service-price">60 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Цели крака - кола маска</h3>
                            <p>40 мин.</p>
                        </div>
                        <div class="service-price">40 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Цяло тяло - кола маска</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">200 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Скули - кола маска</h3>
                            <p>10 мин.</p>
                        </div>
                        <div class="service-price">10 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Врат - кола маска</h3>
                            <p>10 мин.</p>
                        </div>
                        <div class="service-price">10 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category massage-details" data-category="classic">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Релаксиращ масаж</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">100 лв.</div>
                        <a href="/booking?category=massage&service=classic&detail=relaksirasht-masaj"
                            class="select-service-btn">Избери</a>
                    </div>
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Класически масаж при Вики</h3>
                            <p>1 ч.</p>
                        </div>
                        <div class="service-price">100 лв.</div>
                        <a href="/booking" class="select-service-btn">Избери</a>
                    </div>
                </div>

                <div class="service-details-category massage-details" data-category="sport">
                    <div class="service-detail-item">
                        <div class="service-info">
                            <h3>Спортен масаж</h3>
                            <p>50 мин.</p>
                        </div>
                        <div class="service-price">100 лв.</div>
                        <a href="/booking?category=massage&service=sport&detail=sporten-masaj"
                            class="select-service-btn">Избери</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <section id="testimonials" class="testimonials">
        <div class="testimonials-header">
            <div class="line"></div>
            <h3>Какво казват хората</h3>
            <div class="line"></div>
        </div>
        <h2>Отзиви</h2>

        <div class="testimonials-grid">
            <a class="testimonial-card" href="https://studio24.bg/salon-za-krasota-raia-s1292#ratingReviews">
                <div class="testimonial-content">
                    <div class="stars">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                    </div>
                    <p>Страхотно отношение и невероятен резултат. Услуга на такова ниво не съм получавала в популярните
                        салони. С две ръце препоръчвам Диди.</p>
                    <h3>safie</h3>
                    <span class="client-type">Клиент</span>
                </div>
            </a>

            <a class="testimonial-card" href="https://studio24.bg/salon-za-krasota-raia-s1292#ratingReviews">
                <div class="testimonial-content">
                    <div class="stars">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                    </div>
                    <p>Exceptional work and professional attitude! Highly recommend!</p>
                    <h3>aleksandar</h3>
                    <span class="client-type">Клиент</span>
                </div>
            </a>

            <a class="testimonial-card" href="https://studio24.bg/salon-za-krasota-raia-s1292#ratingReviews">
                <div class="testimonial-content">
                    <div class="stars">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                        <img src="images/icons/star.png" alt="star">
                    </div>
                    <p>Wonderful service and professionalism. We'll definitely visit Didi again with my daughter. ❤️💫🙏
                    </p>
                    <h3>desislava</h3>
                    <span class="client-type">Клиент</span>
                </div>
            </a>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/main.js"></script>
    <script>
        // Add this script to fix service links without parameters
        document.addEventListener('DOMContentLoaded', function () {
            // Map of Cyrillic service names to Latin slugs
            const serviceNameMap = {
                // Haircuts
                'Дамско подстригване': 'damsko-podstrigvane',
                'Дамско подстригване + измиване и подсушаване': 'damsko-podstrigvane-izmivane',
                'Дамско подстригване на бретон': 'damsko-podstrigvane-breton',
                'Мъжко подстригване с ножица и машинка + измиване': 'mujko-podstrigvane',
                'Мъжко подстригване с машинка': 'mujko-podstrigvane-mashinka',
                'Детско подстригване до 12 години': 'detsko-podstrigvane',
                'Официална прическа': 'oficialna-pricheska',
                'Прическа с кок': 'pricheska-s-kok',
                'Оформяне на врат': 'oformyane-vrat',
                'Измиване на коса + маска': 'izmivane-maska',

                // Coloring
                'Боядисване с wella + сешоар': 'boyadisvane-wella',
                'Боядисване с Wella': 'boyadisvane-wella-base',
                'Боядисване с боя на клиента': 'boyadisvane-client',
                'Обезцветяване': 'obezcvetyavane',
                'Кичури': 'kichuri',
                'Матиране': 'matirane',

                // Straightening
                'Изправяне с преса': 'izpravyane-presa',
                'Измиване подстригване + Изправяне с сешоар': 'izmivane-podstrigvane-izpravyane',

                // Extensions
                'Удължаване на коса с щипки': 'udalyavane-kosa',

                // Treatments
                'Кератинова терапия за коса': 'keratinova-terapiya',
                'Терапия за бързо възстановяване на суха и изтощена коса с Wella': 'terapiya-vazstanovyavane',
                'Арганова терапия за коса': 'arganova-terapiya',
                'Ампула за коса против косопад': 'ampula-kostopad',
                'Маска за копринена коса': 'maska-koprinena',

                // Beard
                'Оформяне на брада': 'oformyane-brada',
                'Тониране на сиви коси': 'tonirane-kosi',

                // Other
                'Пробиване на уши': 'probirane-ushi',

                // Makeup
                'Професионален грим': 'profesionalen-grim',
                'Вечерен грим': 'vecheren-grim',
                'Сватбен грим': 'svatben-grim',
                'Официален грим': 'oficialen-grim',
                'Ежедневен грим': 'ezhedneven-grim',
                'Абитуриентски грим': 'abiturientski-grim',
                'Фото грим': 'foto-grim',

                // Permanent
                'Перманентен грим на вежди': 'permanenten-vejdi',

                // Women's Epilation
                'Подмишници - кола маска': 'podmishnitsi-kola',
                'Цели крака - кола маска': 'celi-kraka-kola',
                '1/2 крака - кола маска': 'polovin-kraka-kola',
                'Цели ръце - кола маска': 'celi-race-kola',
                'Цяло тяло - кола маска': 'cyalo-tyalo-kola',
                'Брадичка - кола маска': 'bradichka-kola',
                'Горна устна - кола маска': 'gorna-ustna-kola',
                'Бакенбарди - кола маска': 'bakenbardi-kola',
                'Скули - кола маска': 'skuli-kola',

                // Men's Epilation
                'Гръб - кола маска': 'grab-kola',
                'Гърди + корем - кола маска': 'gradi-korem-kola',
                'Гърди - кола маска': 'gradi-kola',
                'Корем - кола маска': 'korem-kola',
                'Кръст - кола маска': 'krast-kola',
                'Врат - кола маска': 'vrat-kola',

                // Massage
                'Релаксиращ масаж': 'relaksirasht-masaj',
                'Класически масаж при Вики': 'klasicheski-masaj',
                'Спортен масаж': 'sporten-masaj'
            };

            // Process all service links
            document.querySelectorAll('.select-service-btn').forEach(link => {
                // Skip links that already have parameters
                if (link.href.includes('?')) return;

                // Get service name from the parent element
                const serviceItem = link.closest('.service-detail-item');
                if (!serviceItem) return;

                const serviceNameEl = serviceItem.querySelector('.service-info h3');
                if (!serviceNameEl) return;

                const serviceName = serviceNameEl.textContent.trim();

                // Get category and subcategory from parent elements
                const categoryEl = link.closest('[data-category]');
                if (!categoryEl) return;

                const subcategory = categoryEl.getAttribute('data-category');
                let mainCategory = '';

                // Determine main category from the class of the parent
                if (categoryEl.classList.contains('hair-details')) {
                    mainCategory = 'hair';
                } else if (categoryEl.classList.contains('face-details')) {
                    mainCategory = 'face';
                } else if (categoryEl.classList.contains('epilation-details')) {
                    mainCategory = 'epilation';
                } else if (categoryEl.classList.contains('massage-details')) {
                    mainCategory = 'massage';
                }

                if (!mainCategory || !subcategory) return;

                // Get a Latin slug from the mapping or generate a fallback
                let detailSlug = serviceNameMap[serviceName];

                // If no mapping found, use a simple fallback (just the subcategory)
                if (!detailSlug) {
                    detailSlug = subcategory + '-service';
                }

                // Update link href with parameters
                link.href = `/booking?category=${mainCategory}&service=${subcategory}&detail=${detailSlug}`;
            });
        });
    </script>
</body>

</html>