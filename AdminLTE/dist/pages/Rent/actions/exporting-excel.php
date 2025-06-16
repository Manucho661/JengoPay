<?php
include '../../db/connect.php';

// Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=individual_building_rent_details.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Output column headings
fputcsv($output, [ 'Tenant Name','Paid Amount', 'Penalty', 'Penalty Days' ,'Arrears', 'Overpayment']);

// Pull data from database
$stmt = $pdo->query("SELECT * FROM tenant_rent_summary");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        // $row['building_name'],
        $row['tenant_name'],
        $row['amount_paid'],
        $row['penalty'],
        $row['penalty_days'],
        $row['arrears'],
        $row['overpayment']
    ]);
}

fclose($output);
exit;
