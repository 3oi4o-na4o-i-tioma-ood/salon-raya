<?php
session_start();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Резервацията е потвърдена - Салон 
Райа
</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="canonical" href="https://salonraia.eu/booking-confirmation.php">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .confirmation-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            text-align: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .confirmation-icon {
            font-size: 64px;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .confirmation-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }

        .confirmation-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .home-button {
            display: inline-block;
            padding: 12px 24px;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .home-button:hover {
            background: #333;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <i class="fas fa-check-circle confirmation-icon"></i>
        <h1 class="confirmation-title">Резервацията е потвърдена!</h1>
        <p class="confirmation-message">
            Благодарим ви, че избрахте нашия салон. Ще получите имейл с потвърждение на резервацията.
            <br><br>
            Очакваме ви!
        </p>
        <a href="index.php" class="home-button">Обратно към началната страница</a>
    </div>
</body>
</html>
