<?php
include '../../db/connect.php'; // adjust path if needed

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $invoice_id = $_POST['invoice_id'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $tenant = $_POST['tenant'] ?? null;
    $payment_method = $_POST['payment_method'] ?? null;
    $payment_date = $_POST['payment_date'] ?? null;
    $reference_number = $_POST['reference_number'] ?? null;

    if (!$id || !$amount || !$tenant || !$payment_method || !$payment_date || !$reference_number) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE payments 
            SET invoice_id = :invoice_id,
                amount = :amount,
                tenant = :tenant,
                payment_method = :payment_method,
                payment_date = :payment_date,
                reference_number = :reference_number,
                status = 'completed'
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
            ':invoice_id' => $invoice_id,
            ':amount' => $amount,
            ':tenant' => $tenant,
            ':payment_method' => $payment_method,
            ':payment_date' => $payment_date,
            ':reference_number' => $reference_number
        ]);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
