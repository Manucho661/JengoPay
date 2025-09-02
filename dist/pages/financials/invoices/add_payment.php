<?php
include '../../db/connect.php';
header('Content-Type: application/json');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $invoiceId = $_POST['invoice_id'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $method = $_POST['payment_method'] ?? null;
    $reference = $_POST['reference_number'] ?? null;

    if (!$invoiceId || !$amount || !$method || !$reference) {
        throw new Exception("Missing required fields");
    }

    $sql = "INSERT INTO payments (invoice_id, amount, payment_method, reference_number, payment_date, status) 
            VALUES (:invoice_id, :amount, :method, :reference, NOW(), 'completed')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':invoice_id' => $invoiceId,
        ':amount' => $amount,
        ':method' => $method,
        ':reference' => $reference
    ]);

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
