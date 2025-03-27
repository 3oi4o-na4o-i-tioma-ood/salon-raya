<?php
require_once 'vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Your VAPID keys
$vapidKeys = [
    'publicKey' => 'BLshZ0sJ0I4ecFckVC_1q0kvQ9w_ikOwLuEdJsDpPYQWow1Kys2H3UxczprOEqV1RFsB2t36PQ7iP5bAWOkbR3s',
    'privateKey' => 'YOUR_PRIVATE_KEY' // You'll need to provide your private key here
];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'salon_raya');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

try {
    // Get all push subscriptions
    $stmt = $conn->prepare("SELECT endpoint, p256dh, auth FROM push_subscriptions");
    $stmt->execute();
    $result = $stmt->get_result();
    $subscriptions = $result->fetch_all(MYSQLI_ASSOC);

    if (empty($subscriptions)) {
        throw new Exception('No push subscriptions found');
    }

    // Initialize WebPush
    $webPush = new WebPush([
        'VAPID' => $vapidKeys
    ]);

    // Send notifications to all subscriptions
    foreach ($subscriptions as $subscription) {
        $report = $webPush->sendOneNotification(
            Subscription::create([
                'endpoint' => $subscription['endpoint'],
                'keys' => [
                    'p256dh' => $subscription['p256dh'],
                    'auth' => $subscription['auth']
                ]
            ]),
            json_encode([
                'message' => 'Нова резервация!'
            ])
        );

        // Check if subscription is still valid
        if ($report->isSubscriptionExpired()) {
            // Remove expired subscription
            $stmt = $conn->prepare("DELETE FROM push_subscriptions WHERE endpoint = ?");
            $stmt->bind_param("s", $subscription['endpoint']);
            $stmt->execute();
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log("Error sending push notification: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 