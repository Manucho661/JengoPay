<?php
include '../db/connect.php';

$startDate = $_GET['startDate'] ?? '';
$endDate = $_GET['endDate'] ?? '';
$paymentStatus = $_GET['paymentStatus'] ?? '';

$where = [];
$params = [];

if ($startDate && $endDate) {
    $where[] = "DATE(invoice_date) BETWEEN ? AND ?";
    $params[] = $startDate;
    $params[] = $endDate;
}

if ($paymentStatus) {
    $where[] = "status = ?";
    $params[] = $paymentStatus;
}

$sql = "SELECT invoice_no, invoice_date, status, amount FROM invoices";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$row['invoice_no']}</td>
            <td>{$row['invoice_date']}</td>
            <td>{$row['status']}</td>
            <td>Ksh " . number_format($row['amount'], 2) . "</td>
          </tr>";
}
?>
