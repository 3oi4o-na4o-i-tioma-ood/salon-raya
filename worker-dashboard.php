<?php
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: sign-in.php');
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '1111', 'salon_raya');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Get appointments for selected date
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_date = ? ORDER BY appointment_time");
$stmt->bind_param("s", $selectedDate);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Работен Панел - Салон Рая</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <a href="sign-in.php?logout=true" class="exit-button">
                <i class="fas fa-sign-out-alt"></i>
                Изход
            </a>
            <div class="salon-info">
                <img src="images/salon/logo.png" alt="SALON RAYA LOGO" class="dashboard-logo">
                <div class="salon-details">
                    <h1>Салон Рая</h1>
                    <p>ул. Хайдушка гора 120</p>
                </div>
            </div>
        </div>

        <div class="dashboard-content">
            <div class="bookings-section">
                <div class="bookings-content">
                    <div class="date-picker">
                        <div id="calendar"></div>
                    </div>
                    <div class="appointments-section">
                        <div class="current-date">
                            <?php echo date('d.m.Y', strtotime($selectedDate)); ?>
                        </div>
                        <div class="appointments-list">
                            <?php if (count($appointments) > 0): ?>
                                <?php foreach ($appointments as $appointment): ?>
                                    <div class="appointment-card">
                                        <div class="appointment-time">
                                            <?php echo date('H:i', strtotime($appointment['appointment_time'])); ?>
                                        </div>
                                        <div class="appointment-details">
                                            <h3><?php echo htmlspecialchars($appointment['client_name']); ?></h3>
                                            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($appointment['phone']); ?></p>
                                            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($appointment['email']); ?></p>
                                            <p><i class="fas fa-cut"></i> <?php echo htmlspecialchars($appointment['service']); ?></p>
                                            <?php if (!empty($appointment['comment'])): ?>
                                                <p class="comment"><i class="fas fa-comment"></i> <?php echo htmlspecialchars($appointment['comment']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-appointments">
                                    <i class="far fa-calendar-times"></i>
                                    <p>Няма резервации за избраната дата</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#calendar", {
                inline: true,
                defaultDate: "<?php echo $selectedDate; ?>",
                dateFormat: "Y-m-d",
                locale: "bg",
                minDate: "today",
                onChange: function(selectedDates, dateStr) {
                    window.location.href = 'worker-dashboard.php?date=' + dateStr;
                }
            });
        });
    </script>

    <style>
    .exit-button {
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #ff0000;
        padding: 6px 12px;
        border-radius: 4px;
        transition: background-color 0.3s;
        font-weight: 500;
    }

    .exit-button:hover {
        background-color: #fff0f0;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        flex-direction: row;
        margin-bottom: 10px;
    }

    .salon-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-logo {
        height: 40px;
        width: auto;
    }

    .salon-details h1 {
        font-size: 18px;
        margin: 0;
    }

    .salon-details p {
        font-size: 14px;
        margin: 2px 0 0 0;
    }

    .bookings-content {
        display: flex;
        gap: 20px;
        padding: 0 15px;
    }

    .date-picker {
        flex: 0 0 40%;
        max-width: 40%;
    }

    .appointments-section {
        flex: 0 0 60%;
        max-width: 60%;
    }

    .current-date {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 15px;
        padding: 8px 12px;
        background: #f5f5f5;
        border-radius: 4px;
        display: inline-block;
    }

    .appointments-list {
        width: 100%;
    }

    .appointment-card {
        padding: 12px;
        margin-bottom: 10px;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .appointment-time {
        font-size: 14px;
        padding: 6px 10px;
        background: #f5f5f5;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 8px;
    }

    .appointment-details h3 {
        font-size: 15px;
        margin: 0 0 5px 0;
    }

    .appointment-details p {
        font-size: 14px;
        margin: 3px 0;
    }

    .no-appointments {
        padding: 15px;
        text-align: center;
        background: #fff;
        border-radius: 4px;
    }

    .no-appointments i {
        font-size: 24px;
        margin-bottom: 8px;
        color: #999;
    }

    .no-appointments p {
        font-size: 14px;
        margin: 0;
        color: #666;
    }

    .flatpickr-calendar {
        font-size: 14px;
        margin: 0 auto;
    }

    /* Update today's styling */
    .flatpickr-day.today {
        border: none;
        background: none;
    }

    .flatpickr-day.today.selected {
        background: #0366d6;
        color: #fff;
        border-color: #0366d6;
    }

    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
        background: #0366d6;
        color: #fff;
        border-color: #0366d6;
    }

    .flatpickr-day.today:hover {
        background: #e6e6e6;
    }

    .flatpickr-day.today.selected:hover {
        background: #0366d6;
    }

    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay,
    .flatpickr-day.flatpickr-disabled {
        color: #999 !important;
        background: none !important;
        cursor: default !important;
    }

    .flatpickr-day.prevMonthDay:hover,
    .flatpickr-day.nextMonthDay:hover,
    .flatpickr-day.flatpickr-disabled:hover {
        background: none !important;
    }

    /* Style for past days */
    .flatpickr-day.flatpickr-disabled {
        text-decoration: none;
        color: #999 !important;
    }
    </style>
</body>
</html> 