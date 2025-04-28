<?php
require_once 'db_config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendBookingConfirmationEmail($to, $name, $service, $date, $time, $appointmentId, $notes = '') {
    $mail = new PHPMailer(true);

    try {
        // Create a unique cancellation token
        $cancellationToken = md5($appointmentId . $to . time());
        
        // Save the token to the database
        $conn = getDbConnection();
        if (!$conn) {
            error_log("Database connection failed in email function");
            return false;
        }
        
        // Check if cancellation_token column exists, if not add it
        $columnCheckResult = $conn->query("SHOW COLUMNS FROM appointments LIKE 'cancellation_token'");
        if ($columnCheckResult === false) {
            error_log("Failed to check if cancellation_token column exists: " . $conn->error);
            return false;
        }
        
        if ($columnCheckResult->num_rows === 0) {
            // Column doesn't exist, add it
            $addColumnResult = $conn->query("ALTER TABLE appointments ADD COLUMN cancellation_token VARCHAR(255) NULL");
            if ($addColumnResult === false) {
                error_log("Failed to add cancellation_token column: " . $conn->error);
                return false;
            }
            error_log("Added cancellation_token column to appointments table");
        }
        
        // Now update the record with the token
        $stmt = $conn->prepare("UPDATE appointments SET cancellation_token = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("Prepare statement failed: " . $conn->error);
            return false;
        }
        
        $stmt->bind_param("si", $cancellationToken, $appointmentId);
        $execResult = $stmt->execute();
        
        if (!$execResult) {
            error_log("Execute failed: " . $stmt->error);
            $stmt->close();
            $conn->close();
            return false;
        }
        
        $stmt->close();
        $conn->close();

        // Server settings
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // 0 = off, 1 = client messages, 2 = client and server messages
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hello@salonraia.eu';
        $mail->Password = 'h@tnTgQi6E';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom('hello@salonraia.eu', 'Салон Рая');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Потвърждение на резервация - Салон Рая';
        
        // Create the cancellation URL with proper error handling for missing HTTP_HOST
        $serverProtocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $serverHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $cancelUrl = $serverProtocol . $serverHost . "/cancel-reservation.php?token=" . $cancellationToken;
        
        // Email body in Bulgarian
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .details { background: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; font-size: 0.9em; color: #666; }
                a.cancel-btn { display: block; width: 200px; margin: 20px auto; padding: 10px 15px; background-color: #e74c3c; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Потвърждение на резервация</h1>
                </div>
                
                <p>Уважаема/м г-жо/г-н {$name},</p>
                
                <p>Благодарим ви за вашата резервация в Салон Рая. Ето детайлите на вашата резервация:</p>
                
                <div class='details'>
                    <p><strong>Услуга:</strong> {$service}</p>
                    <p><strong>Дата:</strong> {$date}</p>
                    <p><strong>Час:</strong> {$time}</p>";
        
        if (!empty($notes)) {
            $message .= "<p><strong>Бележки:</strong> {$notes}</p>";
        }
        
        $message .= "
                </div>
                
                <p>Ако искате да направите промени по вашата резервация, моля свържете се с нас.</p>
                <p>Ако искате да отмените резервацията, можете да използвате бутона по-долу:</p>
                
                <a href='{$cancelUrl}' class='cancel-btn'>Отмени резервацията</a>
                
                <div class='footer'>
                    <p>С най-добри пожелания,<br>Екипът на Салон Рая</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->Body = $message;
        $mail->AltBody = strip_tags($message) . "\n\nЗа отмяна на резервацията: {$cancelUrl}";

        $mail->send();
        error_log("Email sent successfully to: {$to}");
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
} 