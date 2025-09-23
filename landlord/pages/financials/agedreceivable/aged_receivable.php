<?php
require_once '../../db/connect.php'; // your PDO connection file

// Fetch invoices (receivables)
$sql = "SELECT id, invoice_number, tenant, description, created_at, total
        FROM invoice_items";
$stmt = $pdo->query($sql);
$invoices = $stmt->fetchAll();

// Function to calculate days overdue
function daysOverdue($date) {
    $today = new DateTime();
    $invoiceDate = new DateTime($date);
    return $today->diff($invoiceDate)->days;
}

// Age buckets
$buckets = [
    '0-30 days' => [],
    '31-60 days' => [],
    '61-90 days' => [],
    '90+ days' => []
];

foreach ($invoices as $inv) {
    $days = daysOverdue($inv['created_at']);
    if ($days <= 30) {
        $buckets['0-30 days'][] = $inv;
    } elseif ($days <= 60) {
        $buckets['31-60 days'][] = $inv;
    } elseif ($days <= 90) {
        $buckets['61-90 days'][] = $inv;
    } else {
        $buckets['90+ days'][] = $inv;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aged Receivables</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>
    <h1>Aged Receivables</h1>

    <?php foreach ($buckets as $range => $rows): ?>
        <h2><?php echo $range; ?></h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Invoice No</th>
                <th>Tenant (ID)</th>
                <th>Description</th>
                <th>Invoice Date</th>
                <th>Total</th>
                <th>Days Old</th>
            </tr>
            <?php if (count($rows) > 0): ?>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><?php echo $r['invoice_number']; ?></td>
                        <td><?php echo $r['tenant']; ?></td>
                        <td><?php echo $r['description']; ?></td>
                        <td><?php echo $r['created_at']; ?></td>
                        <td><?php echo number_format($r['total'], 2); ?></td>
                        <td><?php echo daysOverdue($r['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No records</td></tr>
            <?php endif; ?>
        </table>
    <?php endforeach; ?>
</body>
</html>
