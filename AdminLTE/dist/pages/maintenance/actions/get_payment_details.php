<?php
include '../../db/connect.php';
header('Content-Type: application/json');

$maintenance_request_id = $_GET['maintenance_request_id'] ?? '';

if (empty($maintenance_request_id)) {
    echo json_encode(['success' => false, 'error' => 'Missing request ID']);
    exit;
}

// Fetch payment record based on the maintenance request ID
$stmt = $pdo->prepare("
    SELECT
        amount_paid,
        payment_method,
        payment_date,
        provider_id,
        cheque_number,
        invoice_number,
        payment_notes
    FROM
        maintenance_payments
    WHERE
        maintenance_request_id = ?
    LIMIT 1
");
$stmt->execute([$maintenance_request_id]);

$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if ($payment) {
    // Optional: Get provider name if needed
    // Example if you have a 'providers' table:
    // $providerStmt = $pdo->prepare("SELECT name FROM providers WHERE id = ?");
    // $providerStmt->execute([$payment['provider_id']]);
    // $provider = $providerStmt->fetchColumn();
    // $payment['service_provider'] = $provider ?: 'Unknown';

    echo json_encode(['success' => true, 'payment' => [
        'amount_paid'     => $payment['amount_paid'],
        'payment_method'  => $payment['payment_method'],
        'date_paid'       => $payment['payment_date'],
        'cheque_number'   => $payment['cheque_number'],
        'invoice_number'  => $payment['invoice_number'],
        'notes'           => $payment['payment_notes'],
        'receipt_url'     => null // No receipt_path field in current schema
    ]]);
} else {
    echo json_encode(['success' => false, 'error' => 'Payment not found']);
}

