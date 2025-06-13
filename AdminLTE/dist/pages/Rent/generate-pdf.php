<?php

require_once 'C:\xampp\htdocs\originalTwo\lib\dompdf-3.1.0\dompdf\autoload.inc.php'; // adjust path if needed
include '../db/connect.php';

use Dompdf\Dompdf;
use Dompdf\Options;


$building = $_GET['building'] ?? 'All Buildings';
$year     = $_GET['year'] ?? '';
$month    = $_GET['month'] ?? '';

// Build SQL with dynamic filters
$sql = "SELECT building_name, amount_collected, balances, penalties, arrears, overpayment, year, month
        FROM building_rent_summary
        WHERE 1 = 1";

$params = [];

if ($building !== 'All Buildings') {
    $sql .= " AND building_name = ?";
    $params[] = $building;
}
if (!empty($year)) {
    $sql .= " AND year = ?";
    $params[] = $year;
}
if (!empty($month)) {
    $sql .= " AND month = ?";
    $params[] = $month;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Title info
$filters = [];
if ($building !== 'All Buildings') $filters[] = "Building: " . htmlspecialchars($building);
if (!empty($year)) $filters[] = "Year: " . htmlspecialchars($year);
if (!empty($month)) $filters[] = "Month: " . htmlspecialchars($month);
$title = empty($filters) ? "All Rent Summaries" : implode(' | ', $filters);

// Build HTML
$html = '<h2>Rent Summary</h2>';
$html .= '<p>' . $title . '</p>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
    <th>Building</th>
    <th>Amount Collected</th>
    <th>Balances</th>
    <th>Penalties</th>
    <th>Arrears</th>
    <th>Overpayment</th>
    <th>Year</th>
    <th>Month</th>
</tr>';

foreach ($data as $row) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['building_name']) . '</td>
        <td>KSH ' . number_format($row['amount_collected'], 2) . '</td>
        <td>KSH ' . number_format($row['balances'], 2) . '</td>
        <td>KSH ' . number_format($row['penalties'], 2) . '</td>
        <td>KSH ' . number_format($row['arrears'], 2) . '</td>
        <td>KSH ' . number_format($row['overpayment'], 2) . '</td>
        <td>' . htmlspecialchars($row['year']) . '</td>
        <td>' . htmlspecialchars($row['month']) . '</td>
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

$filename = "rent_summary_" . date("Ymd_His") . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
