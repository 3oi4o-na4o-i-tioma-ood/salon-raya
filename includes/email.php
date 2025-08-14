<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendBookingConfirmationEmail($to, $name, $service, $date, $time, $notes = '') {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'arttema9@gmail.com';
        $mail->Password = 'glbq xzwa rwia lfyb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom('arttema9@gmail.com', 'Salon Raya');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Потвърждение на резервация - Salon Raya';
        
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
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Потвърждение на резервация</h1>
                </div>
                
                <p>Уважаема/м г-жо/г-н {$name},</p>
                
                <p>Благодарим ви за вашата резервация в Salon Raya. Ето детайлите на вашата резервация:</p>
                
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
                
                <div class='footer'>
                    <p>С най-добри пожелания,<br>Екипът на Salon Raya</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
        return false;
    }
} 