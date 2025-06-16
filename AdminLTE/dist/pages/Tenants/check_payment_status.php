<?php
include '../db/connect.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['checkoutRequestID'])) {
        throw new Exception("Missing checkout request ID");
    }

    // Check database for transaction status
    $stmt = $pdo->prepare("SELECT status, result_code FROM mpesa_transactions WHERE checkout_request_id = ?");
    $stmt->execute([$data['checkoutRequestID']]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transaction) {
        throw new Exception("Transaction not found");
    }

    if ($transaction['status'] === 'completed') {
        echo json_encode([
            'success' => true,
            'status' => 'completed',
            'message' => 'Payment completed successfully'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'status' => 'pending',
            'message' => 'Payment still pending'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}