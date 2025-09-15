<?php
require 'vendor/autoload.php';   // PhpSpreadsheet autoload
include '../../db/connect.php';        // your DB connection

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Fetch payments
$stmt = $pdo->query("SELECT id, tenant, amount, payment_method, payment_date, reference_number, status FROM payments ORDER BY payment_date DESC");
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$headers = ["ID", "Tenant", "Amount", "Payment Method", "Payment Date", "Reference Number", "Status"];
$col = "A";
foreach ($headers as $header) {
    $sheet->setCellValue($col . "1", $header);
    $col++;
}

// Data
$row = 2;
foreach ($payments as $payment) {
    $sheet->setCellValue("A$row", $payment['id']);
    $sheet->setCellValue("B$row", $payment['tenant']);
    $sheet->setCellValue("C$row", $payment['amount']);
    $sheet->setCellValue("D$row", $payment['payment_method']);
    $sheet->setCellValue("E$row", $payment['payment_date']);
    $sheet->setCellValue("F$row", $payment['reference_number']);
    $sheet->setCellValue("G$row", $payment['status']);
    $row++;
}

// Send file to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="payments_report.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
