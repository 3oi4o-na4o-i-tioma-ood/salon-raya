<?php
session_start();
require_once 'vendor/autoload.php';

// Log function
function logAuth($message) {
    $log_file = __DIR__ . '/google-auth.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
}

// Create directory for credentials if it doesn't exist
$credentialsDir = __DIR__ . '/credentials';
if (!file_exists($credentialsDir)) {
    mkdir($credentialsDir, 0755, true);
}

// Path to credential files
$credentialsPath = $credentialsDir . '/google-credentials.json';
$tokenPath = $credentialsDir . '/google-token.json';

// Check if credentials file exists
if (!file_exists($credentialsPath)) {
    die("Error: Google API credentials file not found. Please upload your credentials.json file to the credentials directory.");
}

// Create Google API client
$client = new Google_Client();
$client->setApplicationName('Salon Raya Appointments');
$client->setScopes(Google_Service_Calendar::CALENDAR);
$client->setAuthConfig($credentialsPath);
$client->setAccessType('offline');
$client->setPrompt('consent'); // Force re-prompt to get refresh token

// Set redirect URI - update this to match your exact domain and protocol
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$client->setRedirectUri($protocol . $_SERVER['HTTP_HOST'] . '/google-auth.php');

// Handle authentication flow
if (isset($_GET['code'])) {
    // Exchange authorization code for an access token
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);

        // Save the token to a file
        if (!isset($token['error'])) {
            file_put_contents($tokenPath, json_encode($token));
            logAuth("Authentication successful. Token saved.");
            
            // Redirect to success page
            header('Location: google-auth.php?success=1');
            exit;
        } else {
            logAuth("Error during token exchange: " . $token['error']);
            echo "Грешка при автентикацията: " . $token['error'];
        }
    } catch (Exception $e) {
        logAuth("Error: " . $e->getMessage());
        echo "Грешка: " . $e->getMessage();
    }
} elseif (isset($_GET['success'])) {
    // Show success message
    echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Google Календар Автентикация</title>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    padding: 20px;
                    max-width: 800px;
                    margin: 0 auto;
                    color: #333;
                }
                .success {
                    background-color: #d4edda;
                    color: #155724;
                    padding: 15px;
                    border-radius: 4px;
                    margin-bottom: 20px;
                }
                .btn {
                    display: inline-block;
                    background-color: #6B3FA0;
                    color: white;
                    padding: 10px 15px;
                    text-decoration: none;
                    border-radius: 4px;
                    margin-top: 20px;
                }
                .button-container {
                    display: flex;
                    justify-content: flex-end;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <h1>Google Календар Автентикация</h1>
            <div class='success'>
                <h2>Успешна Автентикация!</h2>
                <p>Вашият Google Календар е успешно свързан. Резервациите вече ще бъдат автоматично добавяни към вашия календар.</p>
            </div>
            <div class='button-container'>
                <a href='worker-dashboard.php' class='btn'>Връщане към Панела</a>
            </div>
        </body>
        </html>";
} elseif (isset($_GET['logout'])) {
    // Remove the saved token
    if (file_exists($tokenPath)) {
        unlink($tokenPath);
        logAuth("Token deleted. User logged out.");
    }
    
    echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Google Календар Автентикация</title>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    padding: 20px;
                    max-width: 800px;
                    margin: 0 auto;
                    color: #333;
                }
                .info {
                    background-color: #cce5ff;
                    color: #004085;
                    padding: 15px;
                    border-radius: 4px;
                    margin-bottom: 20px;
                }
                .btn {
                    display: inline-block;
                    background-color: #6B3FA0;
                    color: white;
                    padding: 10px 15px;
                    text-decoration: none;
                    border-radius: 4px;
                    margin-top: 20px;
                }
                .button-container {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                }
                @media (max-width: 600px) {
                    .button-container {
                        flex-direction: column;
                        gap: 10px;
                    }
                    .btn {
                        text-align: center;
                    }
                }
            </style>
        </head>
        <body>
            <h1>Google Календар Автентикация</h1>
            <div class='info'>
                <h2>Излизане</h2>
                <p>Връзката с вашия Google Календар е премахната.</p>
            </div>
            <div class='button-container'>
                <a href='google-auth.php' class='btn'>Свързване с Календар</a>
                <a href='worker-dashboard.php' class='btn'>Връщане към Панела</a>
            </div>
        </body>
        </html>";
} else {
    // Check token validity if it exists
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
        
        // If the token is expired but we have a refresh token, get a new access token
        if ($client->isAccessTokenExpired() && $client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            logAuth("Token refreshed using refresh token.");
            
            $status = "connected";
        } elseif ($client->isAccessTokenExpired()) {
            // No refresh token or expired
            $status = "expired";
        } else {
            $status = "connected";
        }
    } else {
        $status = "disconnected";
    }
    
    // Authorization URL
    $authUrl = $client->createAuthUrl();
    
    // Show authentication/status page
    echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Google Календар Интеграция</title>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    padding: 20px;
                    max-width: 800px;
                    margin: 0 auto;
                    color: #333;
                }
                .status {
                    padding: 15px;
                    border-radius: 4px;
                    margin-bottom: 20px;
                }
                .connected {
                    background-color: #d4edda;
                    color: #155724;
                }
                .disconnected {
                    background-color: #f8d7da;
                    color: #721c24;
                }
                .expired {
                    background-color: #fff3cd;
                    color: #856404;
                }
                .btn {
                    display: inline-block;
                    background-color: #6B3FA0;
                    color: white;
                    padding: 10px 15px;
                    text-decoration: none;
                    border-radius: 4px;
                    margin-top: 20px;
                }
                .disconnect {
                    background-color: #dc3545;
                }
                h1, h2 {
                    color: #6B3FA0;
                }
                .instructions {
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-radius: 4px;
                    margin: 20px 0;
                }
                .button-container {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                }
                @media (max-width: 600px) {
                    .button-container {
                        flex-direction: column;
                        gap: 10px;
                    }
                    .btn {
                        text-align: center;
                    }
                }
            </style>
        </head>
        <body>
            <h1>Google Календар Интеграция</h1>
            
            <div class='status " . $status . "'>
                <h2>Статус: " . ($status == "connected" ? "Свързан" : ($status == "expired" ? "Изтекъл" : "Несвързан")) . "</h2>";
                
    if ($status == "connected") {
        echo "<p>Вашият Google Календар е свързан. Резервациите ще бъдат автоматично добавяни към вашия календар.</p>";
    } elseif ($status == "expired") {
        echo "<p>Връзката с вашия Google Календар е изтекла. Моля, свържете се отново, за да продължите добавянето на резервации към вашия календар.</p>";
    } else {
        echo "<p>Вашият Google Календар не е свързан. Свържете се, за да добавяте автоматично резервации към вашия Google Календар.</p>";
    }
    
    echo "</div>
            
            <div class='instructions'>
                <h3>Как работи</h3>
                <p>Когато клиент направи резервация през вашия уебсайт, системата автоматично ще добави резервацията с име на клиента и детайли за резервацията към вашия Google Календар.</p>
            </div>
            
            <div class='button-container'>";
            
    if ($status == "connected") {
        echo "<a href='google-auth.php?logout=1' class='btn disconnect'>Прекъсване на връзката</a>";
    } else {
        echo "<a href='" . $authUrl . "' class='btn'>Свързване с Google Календар</a>";
    }
    
    echo "<a href='worker-dashboard.php' class='btn'>Връщане към Панела</a>
            </div>
        </body>
        </html>";
}
?> 