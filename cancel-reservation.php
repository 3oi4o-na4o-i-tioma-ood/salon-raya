<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'salon_raya');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Initialize status message
$status = '';
$statusClass = '';
$details = [];

// Check if a token was provided
if (!empty($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if the token exists in the database
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE cancellation_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $appointment = $result->fetch_assoc();
        $details = $appointment;
        
        // Check if the appointment is already cancelled
        if ($appointment['status'] === 'cancelled') {
            $status = "Тази резервация вече е отменена.";
            $statusClass = "info";
        }
        // Check if cancellation is confirmed
        else if (isset($_POST['confirm_cancel'])) {
            // Update the appointment status to cancelled
            $cancelStmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
            $cancelStmt->bind_param("i", $appointment['id']);
            
            if ($cancelStmt->execute()) {
                $status = "Вашата резервация беше успешно отменена.";
                $statusClass = "success";
                
                // Update the details array to reflect the new status
                $details['status'] = 'cancelled';
            } else {
                $status = "Възникна грешка при отмяната на резервацията. Моля, опитайте отново.";
                $statusClass = "error";
            }
            $cancelStmt->close();
        }
    } else {
        $status = "Невалиден или изтекъл линк за отмяна.";
        $statusClass = "error";
    }
    $stmt->close();
} else {
    $status = "Не е предоставен валиден токен за отмяна.";
    $statusClass = "error";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отмяна на резервация - Salon Raya</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .status {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .info {
            background-color: #cce5ff;
            color: #004085;
            border-left: 4px solid #0d6efd;
        }
        .details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: left;
        }
        .actions {
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
            border: none;
            font-size: 16px;
            font-weight: 500;
        }
        .btn-cancel {
            background-color: #e74c3c;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #c0392b;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            margin-right: 10px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            display: inline-block;
            width: 100px;
        }
        .logo {
            margin-bottom: 20px;
            height: 80px;
            width: auto;
        }
        .cancelled-badge {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.svg" alt="Salon Raya Logo" class="logo">
        <h1>Отмяна на резервация</h1>
        
        <?php if (!empty($status)): ?>
            <div class="status <?php echo $statusClass; ?>">
                <?php echo $status; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($details)): ?>
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Име:</span>
                    <span><?php echo htmlspecialchars($details['client_name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Услуга:</span>
                    <span><?php echo htmlspecialchars($details['service']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Дата:</span>
                    <span><?php echo date('d.m.Y', strtotime($details['appointment_date'])); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Час:</span>
                    <span><?php echo date('H:i', strtotime($details['appointment_time'])); ?></span>
                </div>
                <?php if (!empty($details['comment'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Бележка:</span>
                    <span><?php echo htmlspecialchars($details['comment']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($details['status']) && $details['status'] === 'cancelled'): ?>
                <div class="detail-row">
                    <span class="detail-label">Статус:</span>
                    <span><span class="cancelled-badge">Отменена</span></span>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if (!isset($_POST['confirm_cancel']) && (!isset($details['status']) || $details['status'] !== 'cancelled')): ?>
            <p>Сигурни ли сте, че искате да отмените тази резервация?</p>
            
            <form method="post">
                <div class="actions">
                    <a href="index.php" class="btn btn-back">Назад</a>
                    <button type="submit" name="confirm_cancel" class="btn btn-cancel">Потвърди отмяната</button>
                </div>
            </form>
            <?php else: ?>
            <div class="actions">
                <a href="index.php" class="btn btn-back">Начална страница</a>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="actions">
                <a href="index.php" class="btn btn-back">Начална страница</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 