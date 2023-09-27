<?php
session_start();
include('conf/checklogin.php');
check_login();
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Create the worksheet
$worksheet = $spreadsheet->getActiveSheet();

// Set the column headers
$worksheet->setCellValue('A1', 'Client Name');
$worksheet->setCellValue('B1', 'Client Number');
$worksheet->setCellValue('C1', 'Contact');
$worksheet->setCellValue('D1', 'National ID No.');
$worksheet->setCellValue('E1', 'Email');
$worksheet->setCellValue('F1', 'Password');
$worksheet->setCellValue('G1', 'Address');

// Set the filename for download
$filename = 'client_template.xlsx';

// Redirect output to a client's web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
