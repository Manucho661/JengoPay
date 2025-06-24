<?php
require_once 'C:\xampp\htdocs\originalTwo\lib\dompdf-3.1.0\dompdf\autoload.inc.php';
include '../../db/connect.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Get filters from request with validation
$building = isset($_GET['building']) ? htmlspecialchars(trim($_GET['building'])) : 'All Buildings';
$start = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Validate date format if provided
if (!empty($start) && !DateTime::createFromFormat('Y-m-d', $start)) {
    die("Invalid start date format. Please use YYYY-MM-DD format.");
}
if (!empty($end) && !DateTime::createFromFormat('Y-m-d', $end)) {
    die("Invalid end date format. Please use YYYY-MM-DD format.");
}

// Build SQL query with filters and prepared statements
$sql = "SELECT
            building_name,
            SUM(amount_collected) as amount_collected,
            SUM(balances) as balances,
            SUM(penalties) as penalties,
            SUM(arrears) as arrears,
            SUM(overpayment) as overpayment,
            DATE_FORMAT(payment_date, '%Y-%m') as month_year
        FROM building_rent_summary
        WHERE 1=1";
$params = [];

if ($building !== 'All Buildings') {
    $sql .= " AND building_name = ?";
    $params[] = $building;
}

if (!empty($start) && !empty($end)) {
    $sql .= " AND payment_date BETWEEN ? AND ?";
    $params[] = $start;
    $params[] = $end;
}

// Group by month and building
$sql .= " GROUP BY building_name, month_year ORDER BY month_year DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$totals = [
    'amount' => 0,
    'balance' => 0,
    'penalty' => 0,
    'arrears' => 0,
    'overpayment' => 0
];

foreach ($data as $row) {
    $totals['amount'] += $row['amount_collected'];
    $totals['balance'] += $row['balances'];
    $totals['penalty'] += $row['penalties'];
    $totals['arrears'] += $row['arrears'];
    $totals['overpayment'] += $row['overpayment'];
}

// Build HTML content with better styling
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rent Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .date-range {
            display: inline-block;
            background: #f5f5f5;
            padding: 8px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .date-range div { display: flex; justify-content: center; gap: 30px; font-size: 16px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th {
            background-color: #343a40;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
        }
        .brand {
            display: inline-block;
            margin: 0 5px;
        }
        .brand-primary {
            padding: 4px 10px;
            background-color: #FFC107;
            border: 2px solid #FFC107;
            border-top-left-radius: 5px;
            font-weight: bold;
            color: #00192D;
        }
        .brand-secondary {
            padding: 4px 10px;
            border: 2px solid #FFC107;
            border-bottom-right-radius: 5px;
            font-weight: bold;
            color: #FFC107;
        }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin-bottom: 5px;">' . htmlspecialchars($building) . '</h2>
        <h1 style="margin-top: 0; margin-bottom: 10px;">Rent Report</h1>

        <div class="date-range">
            <div>
                <div><strong style="color: #333;">From:</strong> <span style="font-weight: bold;">' .
                (!empty($start) ? htmlspecialchars(date('F j, Y', strtotime($start))) : 'Not specified') . '</span></div>
                <div><strong style="color: #333;">To:</strong> <span style="font-weight: bold;">' .
                (!empty($end) ? htmlspecialchars(date('F j, Y', strtotime($end))) : 'Not specified') . '</span></div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th class="text-right">Amount Collected</th>
                <th class="text-right">Balance</th>
                <th class="text-right">Penalty</th>
                <th class="text-right">Arrears</th>
                <th class="text-right">Overpayment</th>
            </tr>
        </thead>
        <tbody>';

foreach ($data as $row) {
    $month = !empty($row['month_year']) ? date('F Y', strtotime($row['month_year'].'-01')) : 'N/A';
    $html .= '
            <tr>
                <td>' . htmlspecialchars($month) . '</td>
                <td class="text-right">KSH ' . number_format($row['amount_collected'], 2) . '</td>
                <td class="text-right">KSH ' . number_format($row['balances'], 2) . '</td>
                <td class="text-right">KSH ' . number_format($row['penalties'], 2) . '</td>
                <td class="text-right">KSH ' . number_format($row['arrears'], 2) . '</td>
                <td class="text-right">KSH ' . number_format($row['overpayment'], 2) . '</td>
            </tr>';
}

$html .= '
            <tr class="total-row">
                <td>Totals</td>
                <td class="text-right">KSH ' . number_format($totals['amount'], 2) . '</td>
                <td class="text-right">KSH ' . number_format($totals['balance'], 2) . '</td>
                <td class="text-right">KSH ' . number_format($totals['penalty'], 2) . '</td>
                <td class="text-right">KSH ' . number_format($totals['arrears'], 2) . '</td>
                <td class="text-right">KSH ' . number_format($totals['overpayment'], 2) . '</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <span class="brand brand-primary">BT</span>
        <span class="brand brand-secondary">JENGOPAY</span>
    </div>
</body>
</html>';

// Generate PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Output the generated PDF
$filename = "Rent_Summary_" . htmlspecialchars($building) . "_" . date("Ymd_His") . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);