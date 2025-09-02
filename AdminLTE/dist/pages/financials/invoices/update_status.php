<?php
include '../../db/connect.php';

$invoiceId = $_POST['invoice_id'] ?? 0;

// Get invoice totals
$stmt = $pdo->prepare("SELECT total, COALESCE(SUM(amount),0) as paid 
                       FROM payments 
                       WHERE invoice_id = ?");
$stmt->execute([$invoiceId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$status = 'unpaid';
if ($row['paid'] == 0) {
    $status = 'unpaid';
} elseif ($row['paid'] < $row['total']) {
    $status = 'partial';
} elseif ($row['paid'] >= $row['total']) {
    $status = 'complete';
}

// Update invoice status
$update = $pdo->prepare("UPDATE invoices SET payment_status=? WHERE id=?");
$update->execute([$status, $invoiceId]);

echo json_encode(['status' => $status]);
