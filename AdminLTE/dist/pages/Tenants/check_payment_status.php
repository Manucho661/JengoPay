<?php
// check_payment_status.php

require_once '../db/connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['checkoutRequestID'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing checkoutRequestID']);
    exit;
}

try {
    // Check database for payment status
    $stmt = $pdo->prepare("SELECT status FROM mpesa_transactions
                          WHERE checkout_request_id = ?");
    $stmt->execute([$data['checkoutRequestID']]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transaction) {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'status' => $transaction['status'],
        'message' => 'Status retrieved'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>