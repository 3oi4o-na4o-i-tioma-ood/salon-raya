<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - The Barber Shop</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">THE BARBER SHOP</div>
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
        <div class="footer-content">
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
            <p>&copy; 2023 The Barber Shop. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html> 