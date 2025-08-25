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

// Create CSV content with clean two-column format (BGN only, numeric)
$csv_output = "\xEF\xBB\xBF"; // UTF-8 BOM
$csv_output .= "Услуга,Цена\n";

foreach ($servicesData as $categoryName => $subcategories) {
    foreach ($subcategories as $subcategoryName => $services) {
        foreach ($services as $service) {
            $serviceName = $service['name'];
            $price = $service['price'];
            // Output BGN price as number only
            $csv_output .= "$serviceName,$price\n";
            
            // Add options if they exist
            if (isset($service['options']) && is_array($service['options'])) {
                foreach ($service['options'] as $option) {
                    $optionName = $serviceName . " (" . $option['name'] . ")";
                    $optionPrice = $option['price'];
                    // Output BGN price as number only for options
                    $csv_output .= "$optionName,$optionPrice\n";
                }
            }
        }
    }
}

// Set headers for Excel download
$filename = 'salon-raya-price-list-' . date('Y-m-d') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
header('Pragma: public');

// Output the CSV
echo $csv_output;
exit();
?>
