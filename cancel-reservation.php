<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'includes/db_config.php';

// Log function
function logCancel($message) {
    $log_file = __DIR__ . '/cancellation.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
}

/**
 * Delete appointment from Google Calendar
 * 
 * @param int $appointmentId   Appointment ID to find matching event
 * @return bool                Success or failure
 */
function deleteFromGoogleCalendar($appointmentId) {
    // Path to your Google API credentials JSON file
    $credentialsPath = __DIR__ . '/credentials/google-credentials.json';
    $tokenPath = __DIR__ . '/credentials/google-token.json';
    
    // Skip if credentials file doesn't exist
    if (!file_exists($credentialsPath)) {
        logCancel("Google Calendar credentials file not found: $credentialsPath");
        return false;
    }
    
    // Skip if token file doesn't exist
    if (!file_exists($tokenPath)) {
        logCancel("Google Calendar token file not found: $tokenPath");
        return false;
    }
    
    try {
        // Create Google API client
        $client = new Google_Client();
        $client->setApplicationName('Salon Raya Appointments');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig($credentialsPath);
        $client->setAccessType('offline');
        
        // Load token
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
        
        // Check token validity
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            } else {
                logCancel("No refresh token available");
                return false;
            }
        }
        
        // Create Calendar service
        $calendarService = new Google_Service_Calendar($client);
        
        // Get primary calendar
        $calendarList = $calendarService->calendarList->listCalendarList();
        $primaryCalendarId = 'primary';
        
        foreach ($calendarList->getItems() as $calendarListEntry) {
            if ($calendarListEntry->getPrimary()) {
                $primaryCalendarId = $calendarListEntry->getId();
                break;
            }
        }
        
        // Search for events with our appointment ID in extendedProperties
        $optParams = [
            'privateExtendedProperty' => "appointmentId=$appointmentId",
            'orderBy' => 'startTime',
            'singleEvents' => true,
        ];
        
        $events = $calendarService->events->listEvents($primaryCalendarId, $optParams);
        
        // Check if we found any events
        if (count($events->getItems()) > 0) {
            foreach ($events->getItems() as $event) {
                // Double check it's our event with the correct property
                $extendedProperties = $event->getExtendedProperties();
                if ($extendedProperties) {
                    $privateProps = $extendedProperties->getPrivate();
                    if (isset($privateProps['appointmentId']) && $privateProps['appointmentId'] == $appointmentId) {
                        // Delete the event
                        $calendarService->events->delete($primaryCalendarId, $event->getId());
                        logCancel("Deleted Google Calendar event: " . $event->getId() . " for appointment #$appointmentId");
                        return true;
                    }
                }
            }
        }
        
        logCancel("No matching Google Calendar event found for appointment #$appointmentId");
        return false;
    } catch (Exception $e) {
        logCancel("Google Calendar Error: " . $e->getMessage());
        return false;
    }
}

// Get database connection
$conn = getDbConnection();
if (!$conn) {
    die("Connection failed");
}

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
                
                // Delete from Google Calendar
                $calendarResult = deleteFromGoogleCalendar($appointment['id']);
                if ($calendarResult) {
                    logCancel("Successfully deleted Google Calendar event for appointment #" . $appointment['id']);
                } else {
                    logCancel("Failed to delete Google Calendar event for appointment #" . $appointment['id']);
                }
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
    <meta name="description" content="Отмяна на резервация в салон Райа. Бързо и лесно отменяне на запазен час.">
    <title>Отмяна на резервация - Салон Рая</title>
    <link rel="icon" href="images/logo-short.svg" type="image/svg+xml">
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
        <a href="/">
            <img src="images/logo.svg" alt="Salon Raya Logo" class="logo">
        </a>
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
                    <a href="/" class="btn btn-back">Назад</a>
                    <button type="submit" name="confirm_cancel" class="btn btn-cancel">Потвърди отмяната</button>
                </div>
            </form>
            <?php else: ?>
            <div class="actions">
                <a href="/" class="btn btn-back">Начална страница</a>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="actions">
                <a href="/" class="btn btn-back">Начална страница</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 