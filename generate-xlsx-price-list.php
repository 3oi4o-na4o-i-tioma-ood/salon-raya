<?php
session_start();

// Optional: restrict to authenticated admins if your panel requires it
// If your site requires auth, uncomment below lines:
// if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
//     header('HTTP/1.1 401 Unauthorized');
//     echo 'Unauthorized';
//     exit();
// }

// Convert BGN (лв.) to EUR with rounding up to cent
function convertToEuroCeil($priceLv)
{
    $euro = ceil(($priceLv / 1.95583) * 100) / 100;
    return number_format($euro, 2, '.', '');
}

// Collect services from both index.php and booking.php by parsing data-services JSON blocks
function collectServicesFromFiles()
{
    $filesToParse = ['index.php', 'booking.php'];
    $nameToPrice = [];

    foreach ($filesToParse as $file) {
        if (!file_exists($file)) {
            continue;
        }
        $content = file_get_contents($file);
        if ($content === false) {
            continue;
        }

        // Match data-services='[ ... ]' blocks (single-quoted attribute with JSON using double quotes)
        if (preg_match_all("/data-services='(\[.*?\])'/s", $content, $matches)) {
            foreach ($matches[1] as $jsonBlock) {
                $services = json_decode($jsonBlock, true);
                if (!is_array($services)) {
                    continue;
                }
                foreach ($services as $service) {
                    if (!isset($service['name']) || !isset($service['price'])) {
                        continue;
                    }

                    $baseName = trim((string)$service['name']);
                    $basePrice = (float)$service['price'];
                    $nameToPrice[$baseName] = $basePrice; // latest wins

                    // Options handling
                    if (isset($service['options']) && is_array($service['options'])) {
                        foreach ($service['options'] as $option) {
                            if (!isset($option['name']) || !isset($option['price'])) {
                                continue;
                            }
                            $optName = $baseName . ' (' . trim((string)$option['name']) . ')';
                            $optPrice = (float)$option['price'];
                            $nameToPrice[$optName] = $optPrice;
                        }
                    }
                }
            }
        }
    }

    // Convert to rows sorted by name for consistency
    ksort($nameToPrice, SORT_NATURAL | SORT_FLAG_CASE);

    $rows = [];
    foreach ($nameToPrice as $name => $priceLv) {
        // Only BGN as a number (no currency text)
        $rows[] = [$name, (float)$priceLv];
    }
    return $rows;
}

// Try to load PhpSpreadsheet if available
$hasSpreadsheet = false;
try {
    // Typical Composer autoload path
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    }
    if (class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
        $hasSpreadsheet = true;
    }
} catch (Throwable $e) {
    $hasSpreadsheet = false;
}

$rows = collectServicesFromFiles();

if ($hasSpreadsheet) {
    // Generate true XLSX via PhpSpreadsheet
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Ценова листа');

    // Headers
    $sheet->setCellValue('A1', 'Услуга');
    $sheet->setCellValue('B1', 'Цена');

    // Bold header
    $sheet->getStyle('A1:B1')->getFont()->setBold(true);

    // Auto width
    foreach (['A', 'B'] as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Data
    $rowIdx = 2;
    foreach ($rows as $row) {
        $sheet->setCellValue('A' . $rowIdx, $row[0]);
        // Set numeric BGN price only
        $sheet->setCellValueExplicit('B' . $rowIdx, $row[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $rowIdx++;
    }

    // Format as an Excel Table with banded rows and header filters
    $lastRow = $rowIdx - 1;
    if ($lastRow >= 1) {
        // Freeze header row
        $sheet->freezePane('A2');

        // Create table over the data range
        try {
            $tableRange = 'A1:B' . $lastRow;
            $table = new \PhpOffice\PhpSpreadsheet\Worksheet\Table($tableRange);
            $table->setName('PriceList');
            $table->setShowRowStripes(true);
            // Apply a medium table style
            if (class_exists('PhpOffice\\PhpSpreadsheet\\Style\\TableStyle')) {
                $table->setStyle(\PhpOffice\PhpSpreadsheet\Style\TableStyle::TABLE_STYLE_MEDIUM9);
            }
            $sheet->addTable($table);
        } catch (\Throwable $e) {
            // If Table feature isn't available in current library version, skip gracefully
        }

        // Ensure B column is formatted as integer numbers (no currency text)
        try {
            $sheet->getStyle('B2:B' . $lastRow)
                ->getNumberFormat()
                ->setFormatCode('0');
        } catch (\Throwable $e) {
            // ignore
        }
    }

    // Output
    $filename = 'salon-raya-price-list-' . date('Y-m-d') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// Fallback: if PhpSpreadsheet isn't installed, return a helpful message with a CSV download alternative
$filename = 'salon-raya-price-list-' . date('Y-m-d') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo "\xEF\xBB\xBF"; // UTF-8 BOM
echo "Услуга,Цена\n";
foreach ($rows as $row) {
    // Escape commas by wrapping in quotes; ensure pure numeric for price
    echo '"' . str_replace('"', '""', $row[0]) . '",' . (is_numeric($row[1]) ? $row[1] : 0) . "\n";
}
exit();

?>


