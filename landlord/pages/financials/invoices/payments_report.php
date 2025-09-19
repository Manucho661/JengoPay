<?php
include '../../db/connect.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=payments_report.csv');

$output = fopen('php://output', 'w');

// Headers
fputcsv($output, ['Tenant', 'Amount', 'Payment Method', 'Payment Date', 'Reference Number', 'Status']);

// Fetch data
$stmt = $pdo->query("SELECT tenant, amount, payment_method, payment_date, reference_number, status FROM payments ORDER BY payment_date DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
