<?php

require_once 'C:\xampp\htdocs\originalTwo\lib\dompdf-3.1.0\dompdf\autoload.inc.php'; // adjust path if needed
include '../../db/connect.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Get filters
$building = $_GET['building'] ?? 'All Buildings';
$year     = $_GET['year'] ?? '';
$month    = $_GET['month'] ?? '';
$start    = $_GET['start_date'] ?? '';
$end      = $_GET['end_date'] ?? '';

// Build SQL with dynamic filters
$sql = "SELECT building_name, amount_collected, balances, penalties, arrears, overpayment, year, month, payment_date
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
if (!empty($start) && !empty($end)) {
    $sql .= " AND payment_date BETWEEN ? AND ?";
    $params[] = $start;
    $params[] = $end;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Title info
$filters = [];
if ($building !== 'All Buildings') $filters[] = "Building: " . htmlspecialchars($building);
if (!empty($year)) $filters[] = "Year: " . htmlspecialchars($year);
if (!empty($month)) $filters[] = "Month: " . htmlspecialchars($month);
if (!empty($start) && !empty($end)) $filters[] = "Period: " . htmlspecialchars($start) . " to " . htmlspecialchars($end);

$title = empty($filters) ? "All Rent Summaries" : implode(' | ', $filters);

// Build HTML
$html = '';

if (!empty($data)) {
    $firstBuilding = htmlspecialchars($data[0]['building_name']);
    $html .= '<h2 style="text-align: center;">Building: ' . $firstBuilding . '</h2>';
} else {
    $html .= '<h2 style="text-align: center;">Rent Report</h2>';
}

// Always show "Rent Report" after building
$html .= '<h2 style="text-align: center;">Rent Report</h2>';

// Filter summary line with period included
$html .= '<p style="text-align: center;">' . $title . '</p>';

$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
    <th>Building</th>
    <th>Amount Collected</th>
    <th>Balances</th>
    <th>Penalties</th>
    <th>Arrears</th>
    <th>Overpayment</th>
</tr>';

foreach ($data as $row) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['building_name']) . '</td>
        <td>KSH ' . number_format($row['amount_collected'], 2) . '</td>
        <td>KSH ' . number_format($row['balances'], 2) . '</td>
        <td>KSH ' . number_format($row['penalties'], 2) . '</td>
        <td>KSH ' . number_format($row['arrears'], 2) . '</td>
        <td>KSH ' . number_format($row['overpayment'], 2) . '</td>
    </tr>';
}
$html .= '</table>';

// Add branding footer (fixed to bottom)
$html .= '<div style="
    position: fixed;
    bottom: 20px;
    left: 0;
    right: 0;
    text-align: center;
    font-family: Arial, sans-serif;
">
    <b style="
        padding: 4px 10px;
        background-color: #FFC107;
        border: 2px solid #FFC107;
        border-top-left-radius: 5px;
        font-weight: bold;
        color: #00192D;
        display: inline-block;
    ">BT</b><b style="
        padding: 4px 10px;
        border: 2px solid #FFC107;
        border-bottom-right-radius: 5px;
        font-weight: bold;
        color: #FFC107;
        display: inline-block;
    ">JENGOPAY</b>
</div>';

// Generate PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$filename = "rent_summary_" . date("Ymd_His") . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
