<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: sign-in.php');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '1111', 'salon_raya');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Fetch appointments with error checking
$sql = "SELECT * FROM appointments ORDER BY appointment_date DESC, appointment_time DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Debug information
echo "<!-- Database connection successful -->";
echo "<!-- Number of appointments: " . ($result ? $result->num_rows : 0) . " -->";
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панел - Salon Raya</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .admin-panel {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .appointments-table th,
        .appointments-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .appointments-table th {
            background: var(--accent-color);
            color: white;
        }
        .appointments-table tr:hover {
            background: #f9f9f9;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .logout-btn {
            padding: 0.5rem 1rem;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: var(--accent-dark);
        }
        .no-appointments {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .debug-info {
            background: #f5f5f5;
            padding: 1rem;
            margin-bottom: 1rem;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <div class="admin-header">
            <h1>Админ панел - Резервации</h1>
            <a href="logout.php" class="logout-btn">Изход</a>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Име</th>
                        <th>Телефон</th>
                        <th>Имейл</th>
                        <th>Услуга</th>
                        <th>Дата</th>
                        <th>Час</th>
                        <th>Коментар</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['service']); ?></td>
                            <td><?php echo date('d.m.Y', strtotime($row['appointment_date'])); ?></td>
                            <td><?php echo date('H:i', strtotime($row['appointment_time'])); ?></td>
                            <td><?php echo htmlspecialchars($row['comment'] ?? ''); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-appointments">
                <p>Няма намерени резервации.</p>
                <?php if (!$result): ?>
                    <p class="debug-info">Error: <?php echo $conn->error; ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?> 