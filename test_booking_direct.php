<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Direct Booking Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 300px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #000; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #333; }
        .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>Direct Booking Test</h1>
    <p>This bypasses all JavaScript and tests the booking system directly.</p>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<div class='result'>";
        
        // Test the simplified save script directly
        $_POST['service_details'] = '[{"name":"' . $_POST['service'] . '","price":50,"duration":"30"}]';
        
        echo "<h3>Testing save_appointment_simple.php:</h3>";
        
        // Capture output from the save script
        ob_start();
        include 'save_appointment_simple.php';
        $output = ob_get_clean();
        
        echo "<strong>Script Output:</strong><br>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
        
        echo "</div>";
    } else {
    ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="client_name">Name:</label>
            <input type="text" id="client_name" name="client_name" value="Test User" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="0876123456" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="test@example.com" required>
        </div>

        <div class="form-group">
            <label for="service">Service:</label>
            <select id="service" name="service" required>
                <option value="Дамско подстригване">Дамско подстригване</option>
                <option value="Мъжко подстригване">Мъжко подстригване</option>
                <option value="Спортен масаж">Спортен масаж</option>
            </select>
        </div>

        <div class="form-group">
            <label for="appointment_date">Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" value="2025-06-15" required>
        </div>

        <div class="form-group">
            <label for="appointment_time">Time:</label>
            <input type="time" id="appointment_time" name="appointment_time" value="14:00" required>
        </div>

        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment">Test booking comment</textarea>
        </div>

        <button type="submit">Test Booking</button>
    </form>

    <?php } ?>

    <hr>
    <h3>Quick Links:</h3>
    <ul>
        <li><a href="quick_test.php">Database Test</a></li>
        <li><a href="debug_booking.php">Debug Booking</a></li>
        <li><a href="worker-dashboard.php">Admin Panel</a></li>
        <li><a href="booking.php">Regular Booking Page</a></li>
    </ul>
</body>
</html> 