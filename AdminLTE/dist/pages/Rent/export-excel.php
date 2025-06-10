<?php
include '../db/connect.php';

// Set headers for download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=building_rent_details.csv');

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, ['Building', 'Collected', 'Penalties', 'Arrears', 'Overpayment']);

$stmt = $pdo->query("SELECT * FROM building_rent_summary");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['building_name'],
        $row['amount_collected'],
        $row['penalties'],
        $row['arrears'],
        $row['overpayment']
    ]);
}

fclose($output);
exit;
