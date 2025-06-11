<?php

require_once 'C:\xampp\htdocs\originalTwo\lib\dompdf-3.1.0\dompdf\autoload.inc.php'; // adjust path if needed

include '../db/connect.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$building = $_GET['building'] ?? 'All Buildings';

$sql = "SELECT building_name, amount_collected, penalties, arrears, overpayment
        FROM building_rent_summary";
if ($building !== 'All Buildings') {
    $sql .= " WHERE building_name = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$building]);
} else {
    $stmt = $pdo->query($sql);
}
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build HTML
$html = '<h2>Rent Summary - ' . htmlspecialchars($building) . '</h2>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
    <th>Building</th>
    <th>Amount Collected</th>
    <th>Penalties</th>
    <th>Arrears</th>
    <th>Overpayment</th>
</tr>';

foreach ($data as $row) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['building_name']) . '</td>
        <td>KSH ' . number_format($row['amount_collected'], 2) . '</td>
        <td>KSH ' . number_format($row['penalties'], 2) . '</td>
        <td>KSH ' . number_format($row['arrears'], 2) . '</td>
        <td>KSH ' . number_format($row['overpayment'], 2) . '</td>
    </tr>';
}
$html .= '</table>';

// Generate PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("rent_summary_" . str_replace(' ', '_', $building) . ".pdf", ["Attachment" => true]);
