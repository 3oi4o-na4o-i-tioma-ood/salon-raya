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
    
    $headers = "From: noreply@salonraya.com";

    mail($to, $subject, $message, $headers);

    $_SESSION['booking_message'] = "Booking successful! Check your email for confirmation.";
    header("Location: booking.php");
    exit();

} catch (Exception $e) {
    $_SESSION['booking_message'] = "Error: " . $e->getMessage();
    header("Location: booking.php");
    exit();
}    <head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment - Salon Raya</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/booking.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">SALON RAYA</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="index.php#services">Services</a>
        <a href="index.php#about">About</a>
        <a href="index.php#contact">Contact</a>
    </div>
</nav>

<div class="booking-container">
    <h2>Book Your Appointment</h2>
    <?php
    if (isset($_SESSION['booking_message'])) {
        echo '<div class="message">' . $_SESSION['booking_message'] . '</div>';
        unset($_SESSION['booking_message']);
    }
    ?>
    <form action="process_booking.php" method="POST" class="booking-form">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>
        </div>

        <div class="form-group">
            <label for="service">Service:</label>
            <select id="service" name="service" required>
                <option value="">Select a service</option>
                <option value="haircut">Haircut</option>
                <option value="beard-trim">Beard Trim</option>
                <option value="hair-styling">Hair Styling</option>
                <option value="full-service">Full Service</option>
            </select>
        </div>

        <div class="form-group">
            <label for="date">Preferred Date:</label>
            <input type="date" id="date" name="date" required>
        </div>

        <div class="form-group">
            <label for="time">Preferred Time:</label>
            <input type="time" id="time" name="time" required>
        </div>

        <div class="form-group">
            <label for="notes">Special Notes:</label>
            <textarea id="notes" name="notes" rows="3"></textarea>
        </div>

        <button type="submit" class="booking-btn">Book Appointment</button>
    </form>
</div>

<footer>
