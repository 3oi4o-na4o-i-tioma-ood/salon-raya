<?php
session_start();
require_once 'includes/db_config.php';

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: sign-in.php');
    exit();
}

// Function to convert LV to Euro
function convertToEuro($priceLV) {
    return ceil(($priceLV / 1.95583) * 100) / 100;
}

// All services data
$servicesData = [
    'Коса' => [
        'Подстригване и прически' => [
            ["name" => "Дамско подстригване", "duration" => "20", "price" => "35"],
            ["name" => "Дамско подстригване + измиване и подсушаване", "duration" => "40", "price" => "55", "options" => [
                ["name" => "на средно дълга коса", "duration" => "40", "price" => "55"],
                ["name" => "на дълга коса", "duration" => "40", "price" => "65"]
            ]],
            ["name" => "Мъжко подстригване", "duration" => "20", "price" => "30"],
            ["name" => "Мъжко подстригване + измиване и подсушаване", "duration" => "30", "price" => "40"],
            ["name" => "Детско подстригване", "duration" => "15", "price" => "20"],
            ["name" => "Подсушаване с прав сешоар", "duration" => "15", "price" => "25"],
            ["name" => "Подсушаване с четка", "duration" => "20", "price" => "30"],
            ["name" => "Плитка", "duration" => "20", "price" => "35"],
            ["name" => "Официална прическа", "duration" => "60", "price" => "80"],
            ["name" => "Сватбена прическа", "duration" => "90", "price" => "120"],
            ["name" => "Измиване на коса + маска", "duration" => "15", "price" => "22"]
        ],
        'Боядисване и кичури' => [
            ["name" => "Боядисване с Wella", "duration" => "60-120", "price" => "80", "options" => [
                ["name" => "на корени", "duration" => "60", "price" => "80"],
                ["name" => "на къса коса", "duration" => "60", "price" => "85"],
                ["name" => "на средно дълга коса", "duration" => "60", "price" => "90"],
                ["name" => "на дълга коса", "duration" => "75", "price" => "120"],
                ["name" => "тониране", "duration" => "45", "price" => "60"]
            ]],
            ["name" => "Кичури с фолио", "duration" => "90", "price" => "120"],
            ["name" => "Кичури с шапка", "duration" => "75", "price" => "90"],
            ["name" => "Омбре/Шатуш", "duration" => "120", "price" => "150"],
            ["name" => "Освежаване на цвета", "duration" => "20", "price" => "20"],
            ["name" => "Матиране", "duration" => "30", "price" => "40"]
        ],
        'Къдрене и изправяне' => [
            ["name" => "Изправяне с преса", "duration" => "60", "price" => "50", "options" => [
                ["name" => "средна", "duration" => "60", "price" => "50"],
                ["name" => "дълга", "duration" => "60", "price" => "60"]
            ]],
            ["name" => "Навиване с преса", "duration" => "60", "price" => "50", "options" => [
                ["name" => "средна", "duration" => "60", "price" => "50"],
                ["name" => "дълга", "duration" => "60", "price" => "60"]
            ]]
        ],
        'Терапии за коса' => [
            ["name" => "Терапия от 4 стъпки на Wella", "duration" => "30", "price" => "80"],
            ["name" => "Кератинова терапия за коса", "duration" => "40", "price" => "70"],
            ["name" => "Терапия за бързо възстановяване на суха и изтощена коса с Wella", "duration" => "30", "price" => "40"],
            ["name" => "Арганова терапия за коса", "duration" => "30", "price" => "70"],
            ["name" => "Ампула за коса против косопад", "duration" => "30", "price" => "22"],
            ["name" => "Маска за копринена коса", "duration" => "40", "price" => "35"]
        ],
        'Брада и бръснене' => [
            ["name" => "Оформяне на брада", "duration" => "15", "price" => "20"]
        ]
    ],
    'Лице' => [
        'Професионален грим' => [
            ["name" => "Професионален грим", "duration" => "60", "price" => "70"],
            ["name" => "Вечерен грим", "duration" => "60", "price" => "70"],
            ["name" => "Сватбен грим", "duration" => "90", "price" => "100"],
            ["name" => "Официален грим", "duration" => "60", "price" => "70"],
            ["name" => "Официален грим", "duration" => "60", "price" => "90"],
            ["name" => "Мъжки грим", "duration" => "45", "price" => "80"],
            ["name" => "Детски грим", "duration" => "30", "price" => "70"],
            ["name" => "Модерен грим", "duration" => "60", "price" => "70"],
            ["name" => "Фото грим", "duration" => "90", "price" => "100"]
        ],
        'Перманентен грим' => [
            ["name" => "Перманентен грим - вежди", "duration" => "120", "price" => "450"]
        ],
        'Други услуги' => [
            ["name" => "Пробиване на уши", "duration" => "10", "price" => "25"]
        ]
    ],
    'Епилация' => [
        'Кола маска жени' => [
            ["name" => "Подмишници - кола маска", "duration" => "15", "price" => "30"],
            ["name" => "Цели крака - кола маска", "duration" => "60", "price" => "60"],
            ["name" => "1/2 крака - кола маска", "duration" => "60", "price" => "60"],
            ["name" => "Цели ръце - кола маска", "duration" => "20", "price" => "40"],
            ["name" => "Цяло тяло - кола маска", "duration" => "90", "price" => "120"],
            ["name" => "Вежди - кола маска", "duration" => "5", "price" => "15"],
            ["name" => "Мустачка - кола маска", "duration" => "5", "price" => "10"],
            ["name" => "Брадичка - кола маска", "duration" => "5", "price" => "10"],
            ["name" => "Скули - кола маска", "duration" => "5", "price" => "10"]
        ],
        'Кола маска мъже' => [
            ["name" => "Подмишници - кола маска", "duration" => "10", "price" => "15"],
            ["name" => "Гръб - кола маска", "duration" => "20", "price" => "40"],
            ["name" => "Гърди + корем - кола маска", "duration" => "30", "price" => "50"],
            ["name" => "Гърди - кола маска", "duration" => "60", "price" => "40"],
            ["name" => "Корем - кола маска", "duration" => "30", "price" => "40"],
            ["name" => "Ръце - кола маска", "duration" => "20", "price" => "30"],
            ["name" => "Крака - кола маска", "duration" => "60", "price" => "60"],
            ["name" => "Лице - кола маска", "duration" => "15", "price" => "20"],
            ["name" => "Врат - кола маска", "duration" => "10", "price" => "15"]
        ]
    ],
    'Масаж' => [
        'Класически масаж' => [
            ["name" => "Релаксиращ масаж", "duration" => "60", "price" => "100"],
            ["name" => "Класически масаж при Вики", "duration" => "60", "price" => "100"]
        ],
        'Спортен масаж' => [
            ["name" => "Спортен масаж", "duration" => "50", "price" => "100"]
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ценова листа - Салон Райа</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 20px;
        }
        
        .price-list-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #a484e8, #8b6ad4);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .header p {
            margin: 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .content {
            padding: 2rem;
        }
        
        .category-section {
            margin-bottom: 3rem;
        }
        
        .category-title {
            background: #f8f9fa;
            color: #333;
            padding: 1rem 1.5rem;
            margin: 0 0 1rem 0;
            border-left: 4px solid #a484e8;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .subcategory-section {
            margin-bottom: 2rem;
        }
        
        .subcategory-title {
            color: #666;
            font-size: 1.2rem;
            font-weight: 500;
            margin: 0 0 1rem 0;
            padding: 0.5rem 0;
            border-bottom: 2px solid #e1e5e9;
        }
        
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .services-table th,
        .services-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e1e5e9;
        }
        
        .services-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .services-table td {
            color: #555;
        }
        
        .service-name {
            font-weight: 500;
            color: #333;
        }
        
        .service-option {
            padding-left: 20px;
            font-style: italic;
            color: #666;
        }
        
        .price {
            font-weight: 600;
            color: #a484e8;
        }
        
        .duration {
            color: #666;
            font-size: 0.9rem;
        }
        
        .actions {
            text-align: center;
            padding: 2rem;
            border-top: 1px solid #e1e5e9;
            background: #f8f9fa;
        }
        
        .download-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }
        
        .download-btn:hover {
            background: #218838;
            transform: translateY(-1px);
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .content {
                padding: 1rem;
            }
            
            .services-table {
                font-size: 0.9rem;
            }
            
            .services-table th,
            .services-table td {
                padding: 8px 10px;
            }
            
            .actions {
                padding: 1rem;
            }
            
            .download-btn,
            .back-btn {
                width: 100%;
                justify-content: center;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="price-list-container">
        <div class="header">
            <h1>Ценова листа</h1>
            <p>Всички услуги с цени в лева и евро</p>
        </div>
        
        <div class="content">
            <?php foreach ($servicesData as $categoryName => $subcategories): ?>
                <div class="category-section">
                    <h2 class="category-title">
                        <i class="fas fa-<?php 
                            echo $categoryName == 'Коса' ? 'cut' : 
                                ($categoryName == 'Лице' ? 'eye' : 
                                ($categoryName == 'Епилация' ? 'sparkles' : 'hand-paper')); 
                        ?>"></i>
                        <?php echo $categoryName; ?>
                    </h2>
                    
                    <?php foreach ($subcategories as $subcategoryName => $services): ?>
                        <div class="subcategory-section">
                            <h3 class="subcategory-title"><?php echo $subcategoryName; ?></h3>
                            
                            <table class="services-table">
                                <thead>
                                    <tr>
                                        <th>Услуга</th>
                                        <th>Продължителност</th>
                                        <th>Цена (лв)</th>
                                        <th>Цена (€)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service): ?>
                                        <tr>
                                            <td class="service-name"><?php echo htmlspecialchars($service['name']); ?></td>
                                            <td class="duration"><?php echo htmlspecialchars($service['duration']); ?> мин</td>
                                            <td class="price"><?php echo htmlspecialchars($service['price']); ?> лв</td>
                                            <td class="price"><?php echo number_format(convertToEuro($service['price']), 2); ?> €</td>
                                        </tr>
                                        
                                        <?php if (isset($service['options']) && is_array($service['options'])): ?>
                                            <?php foreach ($service['options'] as $option): ?>
                                                <tr>
                                                    <td class="service-option">↳ <?php echo htmlspecialchars($option['name']); ?></td>
                                                    <td class="duration"><?php echo htmlspecialchars($option['duration']); ?> мин</td>
                                                    <td class="price"><?php echo htmlspecialchars($option['price']); ?> лв</td>
                                                    <td class="price"><?php echo number_format(convertToEuro($option['price']), 2); ?> €</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="actions">
            <a href="generate-price-list.php" class="download-btn" target="_blank">
                <i class="fas fa-globe"></i>
                Отвори в Excel Web
            </a>
            <a href="worker-dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Назад към панела
            </a>
        </div>
    </div>
</body>
</html>
