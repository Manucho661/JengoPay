<?php
require_once 'C:\xampp\htdocs\originalTwo\lib\dompdf-3.1.0\dompdf\autoload.inc.php'; // Adjust path if needed
include '../../db/connect.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Get building filter from URL
$building = $_GET['building'] ?? 'All Buildings';

// Build SQL with optional building filter
$sql = "SELECT building_name, tenant_name, unit_code, unit_type, amount_paid, payment_date, penalty, penalty_days, arrears, overpayment
        FROM tenant_rent_summary";
$params = [];

if ($building !== 'All Buildings') {
    $sql .= " WHERE building_name = ?";
    $params[] = $building;
}

$sql .= " ORDER BY building_name, tenant_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build HTML
$html = '<h2 style="text-align:center;">Tenant Rent Summary - ' . htmlspecialchars($building) . '</h2>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
<thead style="background-color:#f2f2f2;">
<tr>
    <th>Building</th>
    <th>Tenant</th>
    <th>Unit Code</th>
    <th>Unit Type</th>
    <th>Amount Paid</th>
    <th>Payment Date</th>
    <th>Penalty</th>
    <th>Penalty Days</th>
    <th>Arrears</th>
    <th>Overpayment</th>
</tr>
</thead>
<tbody>';

foreach ($data as $row) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['building_name']) . '</td>
        <td>' . htmlspecialchars($row['tenant_name']) . '</td>
        <td>' . htmlspecialchars($row['unit_code']) . '</td>
        <td>' . htmlspecialchars($row['unit_type']) . '</td>
        <td>KSH ' . number_format($row['amount_paid'], 2) . '</td>
        <td>' . htmlspecialchars($row['payment_date']) . '</td>
        <td>KSH ' . number_format($row['penalty'], 2) . '</td>
        <td>' . (int)$row['penalty_days'] . '</td>
        <td>KSH ' . number_format($row['arrears'], 2) . '</td>
        <td>KSH ' . number_format($row['overpayment'], 2) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Generate PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$filename = "tenant_rent_summary_" . str_replace(' ', '_', $building) . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
