<?php
require_once 'db_config.php';
require_once 'vendor/autoload.php';

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
        $mail->SMTPDebug = 2; // 0 = off, 1 = client messages, 2 = client and server messages
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hello@salonraia.eu';
        $mail->Password = 'h@tnTgQi6E';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom('hello@salonraia.eu', 'Салон Райа');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Потвърждение на резервация - Салон Райа';
        
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
                .details { background: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; font-size: 0.9em; color: #666; }
                a.cancel-btn { display: block; width: 200px; margin: 20px auto; padding: 10px 15px; background-color: #e74c3c; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='container'>
                <p>Здравейте, {$name},</p>
                
                <p>Благодарим ви, че избрахте Салон за Красота „Райа〞 </p>
                
                <p>С удоволствие потвърждаваме вашия час:</p>
                
                <div class='details'>
                    <p><strong>Дата:</strong> {$date}</p>
                    <p><strong>Час:</strong> {$time}</p>
                    <p><strong>Услуга:</strong> {$service}</p>";
        
        if (!empty($notes)) {
            $message .= "<p><strong>Бележки:</strong> {$notes}</p>";
        }
        
        $message .= "
                </div>
                
                <p>Ако имате въпроси или се наложи промяна, не се колебайте да се свържете с нас на <strong>0887 458 664</strong> или да отговорите на този имейл.</p>
                
                <p>Ако искате да отмените резервацията, можете да използвате бутона по-долу:</p>
                
                <a href='{$cancelUrl}' class='cancel-btn'>Отмени резервацията</a>
                
                <div class='footer'>
                    <p>Очакваме ви с усмивка!<br><strong>Екипът на Салон „Райа〞</strong></p>
                </div>
            </div>
        </body>
        </html>";

        $mail->Body = $message;
        $mail->AltBody = strip_tags($message) . "\n\nЗа отмяна на резервацията: {$cancelUrl}";

        $mail->send();
        error_log("EMAIL: Email sent successfully to: {$to}");
        return true;
    } catch (Exception $e) {
        error_log("EMAIL: Email sending failed: " . $e->getMessage());
        error_log("EMAIL: PHPMailer Error Info: " . $mail->ErrorInfo);
        return false;
    }
} 