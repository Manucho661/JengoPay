<?php
require_once '../../db/connect.php'; // your PDO connection file

// Fetch expenses (payables)
$sql = "SELECT id, expense_no, supplier, expense_date, total, status 
        FROM expenses";
$stmt = $pdo->query($sql);
$expenses = $stmt->fetchAll();

// Function to calculate days overdue
function daysOverdue($date) {
    $today = new DateTime();
    $expenseDate = new DateTime($date);
    return $today->diff($expenseDate)->days;
}

// Age buckets
$buckets = [
    '0-30 days' => [],
    '31-60 days' => [],
    '61-90 days' => [],
    '90+ days' => []
];

foreach ($expenses as $exp) {
    $days = daysOverdue($exp['expense_date']);
    if ($days <= 30) {
        $buckets['0-30 days'][] = $exp;
    } elseif ($days <= 60) {
        $buckets['31-60 days'][] = $exp;
    } elseif ($days <= 90) {
        $buckets['61-90 days'][] = $exp;
    } else {
        $buckets['90+ days'][] = $exp;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aged Payables</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>
    <h1>Aged Payables</h1>

    <?php foreach ($buckets as $range => $rows): ?>
        <h2><?php echo $range; ?></h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Expense No</th>
                <th>Supplier</th>
                <th>Expense Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Days Old</th>
            </tr>
            <?php if (count($rows) > 0): ?>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><?php echo $r['expense_no']; ?></td>
                        <td><?php echo $r['supplier']; ?></td>
                        <td><?php echo $r['expense_date']; ?></td>
                        <td><?php echo $r['status']; ?></td>
                        <td><?php echo number_format($r['total'], 2); ?></td>
                        <td><?php echo daysOverdue($r['expense_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No records</td></tr>
            <?php endif; ?>
        </table>
    <?php endforeach; ?>
</body>
</html>
