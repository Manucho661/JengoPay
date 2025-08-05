<?php
include '../db/connect.php';

$status = $_POST['status'] ?? '';
$payment = $_POST['payment'] ?? '';
$dateFrom = $_POST['dateFrom'] ?? '';
$dateTo = $_POST['dateTo'] ?? '';

// Build SQL query dynamically
$sql = "SELECT * FROM invoice_items i
        JOIN invoice inv ON inv.invoice_number = i.invoice_number
        WHERE 1=1";

$params = [];

if ($status !== '') {
    $sql .= " AND inv.status = ?";
    $params[] = $status;
}

if ($payment !== '') {
    $sql .= " AND inv.payment_status = ?";
    $params[] = $payment;
}

if ($dateFrom !== '' && $dateTo !== '') {
    $sql .= " AND DATE(inv.invoice_date) BETWEEN ? AND ?";
    $params[] = $dateFrom;
    $params[] = $dateTo;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();

if ($items) {
    echo "<table class='table table-striped'>";
    echo "<thead><tr>
            <th>Invoice #</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Total</th>
        </tr></thead><tbody>";

    foreach ($items as $item) {
        echo "<tr>
                <td>{$item['invoice_number']}</td>
                <td>{$item['status']}</td>
                <td>{$item['payment_status']}</td>
                <td>{$item['description']}</td>
                <td>{$item['quantity']}</td>
                <td>KES " . number_format($item['total'], 2) . "</td>
            </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p style='color:white;'>No results found.</p>";
}
?>
