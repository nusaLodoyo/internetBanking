<?php
// Include PhpSpreadsheet library
require_once 'vendor/autoload.php';

// Initialize PHPSpreadsheet
$excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

// Create a new Excel worksheet
$excel->setActiveSheetIndex(0);
$sheet = $excel->getActiveSheet();

// Define column headers
$column_headers = array('Nama Santri', 'Nomor Induk', 'Kontak', 'NIK', 'Email', 'Password', 'Kelas', 'Aksi');

// Set column headers and style
$col = 'A';
foreach ($column_headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Create a writer to save the Excel file
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
$file_name = 'Santri_Template.xlsx';

// Send headers to download the Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file_name . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
