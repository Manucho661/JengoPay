<?php

require_once '../db/connect.php';

$input = json_decode(file_get_contents('php://input'), true);
$checkoutRequestID = $input['checkoutRequestID'];

if (empty($checkoutRequestID)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing checkoutRequestID']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT status, amount, receipt_number FROM mpesa_transactions WHERE checkout_request_id = ?");
    $stmt->execute([$checkoutRequestID]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
        echo json_encode([
            'success' => true,
            'status' => $transaction['status'],
            'amount' => $transaction['amount'],
            'receiptNumber' => $transaction['receipt_number']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>