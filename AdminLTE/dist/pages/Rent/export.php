<?php
include '../db/connect.php'; // Make sure $pdo is available

$type = $_GET['type'] ?? 'pdf';
$buildingFilter = $_GET['building'] ?? '';
$yearFilter = $_GET['year'] ?? '2025';
$monthFilter = $_GET['month'] ?? '4';

// Fetch data with filters (similar to main page)

if ($type === 'pdf') {
    // Generate PDF using a library like TCPDF or FPDF
    require_once 'tcpdf/tcpdf.php';
    // ... PDF generation code ...
    $pdf->Output('rental_report.pdf', 'D');
} elseif ($type === 'excel') {
    // Generate Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="rental_report.xls"');
    // ... Excel generation code ...
    exit;
}
?>

