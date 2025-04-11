<?php
session_start();
require_once 'includes/db_config.php'; // Include the config file

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: sign-in.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    
    // Hash the submitted password using SHA-256
    $submitted_hash = hash('sha256', $password);
    
    // Compare the submitted hash with the expected hash from config
    if ($submitted_hash === WORKER_PASSWORD_HASH) { // Use the constant
        $_SESSION['authenticated'] = true;
        header('Location: worker-dashboard.php');
        exit();
    } else {
        $error = 'Грешна парола';
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Салон Рая</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #f5f5f5;
        }

        .sign-in-container {
            position: relative;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>
<div class="sign-in-container">
        <a href="index.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="logo">Фризьорски салон Райа</div>
        <h1>Вход за служител</h1>
        
        
        <form class="sign-in-form" method="POST">
            <div class="password-container">
                <input type="password" name="password" placeholder="Парола" required="">
            </div>
            <button type="submit">Продължи</button>
        </form>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
