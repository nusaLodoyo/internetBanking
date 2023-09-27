<?php
// Include PhpSpreadsheet library
require 'vendor/autoload.php';

// Create a new Spreadsheet object
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();

// Add header row to the Excel sheet
$worksheet->setCellValue('A1', 'Transaction Code');
$worksheet->setCellValue('B1', 'Account ID');
$worksheet->setCellValue('C1', 'Account Name');
$worksheet->setCellValue('D1', 'Account Number');
$worksheet->setCellValue('E1', 'Account Type');
$worksheet->setCellValue('F1', 'Client ID');
$worksheet->setCellValue('G1', 'Client Name');
$worksheet->setCellValue('H1', 'Client National ID');
$worksheet->setCellValue('I1', 'Transaction Amount');
$worksheet->setCellValue('J1', 'Client Phone');
$worksheet->setCellValue('K1', 'Keterangan');

// Function to generate a random unique transaction code
function generateTransactionCode($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// Generate a random unique transaction code
$transactionCode = generateTransactionCode(10);

// Set the transaction code in the Excel sheet
$worksheet->setCellValue('A2', $transactionCode);

// Set the response headers for downloading the Excel template
header("Content-Disposition: attachment; filename=\"withdrawal_template.xlsx\"");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// Save the Excel template
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

exit();
?>
