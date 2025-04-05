<?php
session_start();
require_once 'includes/db_config.php';

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: sign-in.php');
    exit();
}

// Get database connection
$conn = getDbConnection();
if (!$conn) {
    die("Connection failed");
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
            <a href="/">
                <img src="images/logo.svg" alt="SALON RAYA LOGO" class="dashboard-logo">
            </a>
            <div class="dashboard-title">Работен панел</div>
            <a href="sign-in.php?logout=true" class="logout-btn">Изход</a>
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
                                    <div class="appointment-card <?php echo isset($appointment['status']) && $appointment['status'] === 'cancelled' ? 'cancelled' : ''; ?>">
                                        <div class="appointment-header">
                                            <span class="appointment-time"><?php echo date('H:i', strtotime($appointment['appointment_time'])); ?></span>
                                            <?php if (isset($appointment['status']) && $appointment['status'] === 'cancelled'): ?>
                                                <span class="status-badge cancelled">Отменена</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="appointment-details">
                                            <h3 class="client-name"><?php echo htmlspecialchars($appointment['client_name']); ?></h3>
                                            <p class="service"><?php echo htmlspecialchars($appointment['service']); ?></p>
                                            <p class="contact">
                                                <i class="fas fa-phone"></i> <?php echo htmlspecialchars($appointment['phone']); ?><br>
                                                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($appointment['email']); ?>
                                            </p>
                                            <?php if (!empty($appointment['comment'])): ?>
                                                <p class="notes"><i class="fas fa-sticky-note"></i> <?php echo htmlspecialchars($appointment['comment']); ?></p>
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
    
    .google-cal-button {
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #4285F4;
        padding: 6px 12px;
        border-radius: 4px;
        transition: background-color 0.3s;
        font-weight: 500;
    }
    
    .google-cal-button:hover {
        background-color: #e8f0fe;
    }
    
    .header-actions {
        display: flex;
        gap: 10px;
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

    .new-reservation-indicator {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #4CAF50;
        color: white;
        padding: 15px 25px;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    }

    .indicator-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .indicator-content i {
        font-size: 20px;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .notification-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        text-align: center;
        max-width: 90%;
        width: 400px;
    }

    .notification-popup h3 {
        margin: 0 0 15px 0;
        color: #333;
    }

    .notification-popup p {
        margin: 0 0 20px 0;
        color: #666;
        line-height: 1.5;
    }

    .notification-popup button {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .notification-popup button:hover {
        background: #45a049;
    }

    .notification-popup.hidden {
        display: none;
    }

    .appointment-card.cancelled {
        background-color: #ffebee;
        border-left: 4px solid #e74c3c;
    }
    
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 0.8em;
        margin-left: 10px;
        color: white;
    }
    
    .status-badge.cancelled {
        background-color: #e74c3c;
    }
    
    .status-badge.pending {
        background-color: #f39c12;
    }
    
    .status-badge.confirmed {
        background-color: #2ecc71;
    }
    </style>

    <div id="notificationPopup" class="notification-popup hidden">
        <h3>Разрешете Известията</h3>
        <p>Натиснете бутона по-долу, за да разрешите известия за нови резервации.</p>
        <button id="enableNotifications">Разреши известия</button>
    </div>

    <script>
        let audioPermissionGranted = false;
        let audioContext;
        let audioElement;
        let serviceWorkerRegistration = null;
        let pushSubscription = null;
        let userHasInteracted = false;

        // Check if user has notification permission (only consider 'granted' as valid)
        const hasNotificationPermission = 'Notification' in window && Notification.permission === 'granted';

        // Clear interaction memory if notifications are denied
        if (Notification.permission === 'denied') {
            sessionStorage.removeItem('userInteracted');
        }

        // Function to show notification popup if needed
        function showNotificationPopupIfNeeded() {
            if (!hasNotificationPermission) {
                document.getElementById('notificationPopup').classList.remove('hidden');
            } else if (Notification.permission === 'granted') {
                // If permission already granted, initialize notifications without showing popup
                initializePushNotifications();
            }
        }

        // Check for existing interaction in session storage - only valid if notifications are granted
        if (sessionStorage.getItem('userInteracted') === 'true' && hasNotificationPermission) {
            userHasInteracted = true;
            
            if (Notification.permission === 'granted') {
                initializePushNotifications();
            }
        } else {
            // Show popup after a short delay (to let the page load)
            setTimeout(showNotificationPopupIfNeeded, 500);
        }

        // Detect any user interaction with the page
        function markUserInteraction(event) {
            // Don't count clicks on the document as interaction if the notification popup is visible
            // This ensures users must explicitly interact with the permission button
            const notificationPopup = document.getElementById('notificationPopup');
            if (!notificationPopup.classList.contains('hidden') && 
                !notificationPopup.contains(event.target)) {
                // If clicked outside the popup while it's visible, don't count as interaction
                return;
            }
            
            if (!userHasInteracted) {
                userHasInteracted = true;
                
                // Only remember interaction if notifications are granted
                if (Notification.permission === 'granted') {
                    sessionStorage.setItem('userInteracted', 'true');
                }
                
                // Only hide popup if notifications are granted
                // Otherwise it should stay visible until they explicitly interact with the permission button
                if (Notification.permission === 'granted') {
                    document.getElementById('notificationPopup').classList.add('hidden');
                }
                
                // Initialize audio on first interaction
                initializeAudio();
            }
        }

        // Add interaction event listeners - only for deliberate actions
        ['click', 'touchstart', 'keydown'].forEach(eventType => {
            document.addEventListener(eventType, markUserInteraction, { once: true });
        });

        // Handle notification permission button click
        document.getElementById('enableNotifications').addEventListener('click', function() {
            if ('Notification' in window) {
                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        console.log('Notification permission granted');
                        document.getElementById('notificationPopup').classList.add('hidden');
                        sessionStorage.setItem('userInteracted', 'true');
                        initializeAudio();
                        initializePushNotifications();
                    } else if (permission === 'denied') {
                        // Clear interaction if user explicitly denies
                        sessionStorage.removeItem('userInteracted');
                    }
                });
            }
        });

        // Initialize push notifications
        async function initializePushNotifications() {
            try {
                // Register service worker
                serviceWorkerRegistration = await navigator.serviceWorker.register('/notification-worker.js');

                // Request push subscription
                pushSubscription = await serviceWorkerRegistration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: 'BG5UHBERE8s7_dbqGPohOTitg5VbEpC4CWdanwIL0g5AXl_1MjkEPIDmEwF4UnCSEzGiPJ7moFWKjEGzLehH-EM'
                });

                // Send subscription to server
                const response = await fetch('save_push_subscription.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(pushSubscription)
                });
                await response.json();
            } catch (error) {
                console.error('Push notification setup failed:', error);
            }
        }

        // Initialize audio on user interaction
        function initializeAudio() {
            if (!audioElement) {
                audioElement = new Audio('sounds/notification.mp3');
                audioElement.load();
            }
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }
            audioPermissionGranted = true;
        }

        // Function to show notification
        function showNotification(reservation) {
            const message = `${reservation.client_name} резервира ${reservation.service} за ${new Date(reservation.appointment_date).toLocaleDateString('bg-BG')} в ${reservation.appointment_time}`;
            
            if (serviceWorkerRegistration) {
                serviceWorkerRegistration.showNotification('Нова Резервация!', {
                    body: message
                });
            }
        }
    </script>
</body>
</html> 