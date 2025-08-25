<?php
session_start();
require_once 'includes/db_config.php';

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Неоторизиран достъп']);
    exit();
}

// Check if file was uploaded
if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Няма качен файл или възникна грешка']);
    exit();
}

$uploadedFile = $_FILES['excel_file'];
$fileName = $uploadedFile['name'];
$tmpName = $uploadedFile['tmp_name'];

// Validate file type
$allowedExtensions = ['csv'];
$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($fileExtension, $allowedExtensions)) {
    // Give specific error for Excel formats
    if (in_array($fileExtension, ['xlsx', 'xls'])) {
        echo json_encode(['success' => false, 'message' => 'XLSX/XLS форматът не се приема. Моля, качете CSV файл.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Невалиден тип файл. Разрешен е само CSV файл.']);
    }
    exit();
}

try {
    // Read and parse the uploaded file
    $services = [];

    if ($fileExtension === 'csv') {
        // Handle CSV files (expected format: two columns: Service, Price)
        if (($handle = fopen($tmpName, "r")) !== FALSE) {
            // Read header row if present
            $firstRow = fgetcsv($handle);
            // If header not detected (only one column or non-text), rewind and read from start
            if ($firstRow !== false && count($firstRow) < 2) {
                rewind($handle);
            }

            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) >= 2 && isset($data[0]) && isset($data[1])) {
                    $serviceName = trim((string)$data[0]);
                    if ($serviceName === '') { continue; }
                    $priceCell = trim((string)$data[1]);
                    // Accept numeric or numeric with currency text; extract leading number
                    if (is_numeric($priceCell)) {
                        $price = (int)round((float)$priceCell);
                        $services[] = ['name' => $serviceName, 'price' => $price];
                    } elseif (preg_match('/^(\d+(?:[\.,]\d+)?)\b/', $priceCell, $m)) {
                        $price = (int)round((float)str_replace(',', '.', $m[1]));
                        $services[] = ['name' => $serviceName, 'price' => $price];
                    }
                }
            }
            fclose($handle);
        }
    }
    
    if (empty($services)) {
        echo json_encode(['success' => false, 'message' => 'Не са намерени валидни услуги в файла']);
        exit();
    }
    
    // Create updated services configuration
    $updatedConfig = generateUpdatedConfig($services);
    
    // Create backup of current files
    $backupDir = 'backups';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0777, true);
    }
    
    $timestamp = date('Y-m-d_H-i-s');
    copy('index.php', $backupDir . '/index_' . $timestamp . '.php');
    copy('booking.php', $backupDir . '/booking_' . $timestamp . '.php');
    
    // Update index.php
    updateServicePrices('index.php', $services);
    
    // Update booking.php  
    updateServicePrices('booking.php', $services);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Цените са успешно актуализирани',
        'updated_services' => count($services)
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Грешка при обработката: ' . $e->getMessage()]);
}

function updateServicePrices($filename, $services) {
    $content = file_get_contents($filename);
    
    // Create a map of service names to new prices
    $priceMap = [];
    foreach ($services as $service) {
        $priceMap[$service['name']] = $service['price'];
    }
    
    // Update base service prices
    $content = preg_replace_callback(
        '/(\["name"\s*=>\s*"([^"]+)",\s*"duration"\s*=>\s*"[^"]*",\s*"price"\s*=>\s*")(\d+)(")/u',
        function($matches) use ($priceMap) {
            $serviceName = $matches[2];
            if (isset($priceMap[$serviceName])) {
                return $matches[1] . $priceMap[$serviceName] . $matches[4];
            }
            return $matches[0];
        },
        $content
    );
    
    // Update option prices within services
    $content = preg_replace_callback(
        '/(\["name"\s*=>\s*"([^"]+)",\s*"duration"\s*=>\s*"[^"]*",\s*"price"\s*=>\s*")(\d+)(")/u',
        function($matches) use ($priceMap) {
            $optionName = $matches[2];
            // Try to find exact match or partial match for service options
            if (isset($priceMap[$optionName])) {
                return $matches[1] . $priceMap[$optionName] . $matches[4];
            }
            
            // Check for service options that include the base service name
            foreach ($priceMap as $serviceName => $price) {
                if (strpos($serviceName, '(') !== false) {
                    // Extract the option part from service name like "Service (option)"
                    preg_match('/\(([^)]+)\)/', $serviceName, $optionMatches);
                    if (!empty($optionMatches[1]) && $optionMatches[1] === $optionName) {
                        return $matches[1] . $price . $matches[4];
                    }
                }
            }
            return $matches[0];
        },
        $content
    );
    
    // Also update JSON format in data-services attributes
    $content = preg_replace_callback(
        '/(\{"name":\s*"([^"]+)",\s*"duration":\s*"[^"]*",\s*"price":\s*")(\d+)(")/u',
        function($matches) use ($priceMap) {
            $serviceName = $matches[2];
            if (isset($priceMap[$serviceName])) {
                return $matches[1] . $priceMap[$serviceName] . $matches[4];
            }
            return $matches[0];
        },
        $content
    );
    
    file_put_contents($filename, $content);
}

function generateUpdatedConfig($services) {
    // This function could be expanded to generate a more sophisticated configuration
    // For now, it just returns the services array
    return $services;
}
?>
