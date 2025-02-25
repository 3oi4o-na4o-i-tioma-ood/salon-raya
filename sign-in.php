<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: sign-in.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    
    // Check for the specific password
    if ($password === 'salon123') {
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
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="sign-in-form" method="POST">
            <div class="password-container">
                <input type="password" name="password" placeholder="Парола" required>
            </div>
            <button type="submit">Продължи</button>
        </form>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
