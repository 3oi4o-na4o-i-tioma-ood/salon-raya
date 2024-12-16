<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'barbershop';
$username = 'postgres';
$password = '1111';
$port = '5432';

try {
    // Create database connection
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validate and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $service = filter_input(INPUT_POST, 'service', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);

    // Basic validation
    if (!$name || !$email || !$phone || !$service || !$date || !$time) {
        throw new Exception("Please fill in all required fields");
    }

    // Prepare SQL statement
    $sql = "INSERT INTO bookings (name, email, phone, service, appointment_date, appointment_time, notes, created_at) 
            VALUES (:name, :email, :phone, :service, :date, :time, :notes, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Execute with parameters
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':service' => $service,
        ':date' => $date,
        ':time' => $time,
        ':notes' => $notes
    ]);

    // Send confirmation email
    $to = $email;
    $subject = "Booking Confirmation - The Barber Shop";
    $message = "Dear $name,\n\n";
    $message .= "Thank you for booking with The Barber Shop. Here are your appointment details:\n\n";
    $message .= "Service: $service\n";
    $message .= "Date: $date\n";
    $message .= "Time: $time\n\n";
    $message .= "If you need to make any changes, please contact us.\n\n";
    $message .= "Best regards,\nThe Barber Shop Team";
    
    $headers = "From: noreply@thebarbershop.com";

    mail($to, $subject, $message, $headers);

    $_SESSION['booking_message'] = "Booking successful! Check your email for confirmation.";
    header("Location: booking.php");
    exit();

} catch (Exception $e) {
    $_SESSION['booking_message'] = "Error: " . $e->getMessage();
    header("Location: booking.php");
    exit();
} 