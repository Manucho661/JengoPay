<?php
include '../db/connect.php';

// Log the callback for debugging
file_put_contents('mpesa_callback.log', file_get_contents('php://input'), FILE_APPEND);

$callbackData = json_decode(file_get_contents('php://input'), true);

try {
    if (!isset($callbackData['Body']['stkCallback'])) {
        throw new Exception("Invalid callback data");
    }

    $callback = $callbackData['Body']['stkCallback'];
    $checkoutRequestID = $callback['CheckoutRequestID'];
    $resultCode = $callback['ResultCode'];

    // Update transaction status in database
    $status = ($resultCode == 0) ? 'completed' : 'failed';

    $stmt = $pdo->prepare("UPDATE mpesa_transactions SET
                          status = ?,
                          result_code = ?,
                          result_description = ?,
                          updated_at = NOW()
                          WHERE checkout_request_id = ?");
    $stmt->execute([
        $status,
        $resultCode,
        $callback['ResultDesc'],
        $checkoutRequestID
    ]);

    // If payment was successful, update tenant records
    if ($resultCode == 0) {
        $metadata = $callback['CallbackMetadata']['Item'] ?? [];
        $amount = $metadata[0]['Value'] ?? 0;
        $mpesaReceiptNumber = $metadata[1]['Value'] ?? '';
        $phoneNumber = $metadata[4]['Value'] ?? '';

        // Get tenant ID from transaction
        $stmt = $pdo->prepare("SELECT tenant_id FROM mpesa_transactions WHERE checkout_request_id = ?");
        $stmt->execute([$checkoutRequestID]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            // Update tenant rent summary
            $stmt = $pdo->prepare("UPDATE tenant_rent_summary
                                  SET amount_paid = amount_paid + ?,
                                      payment_date = NOW(),
                                      balances = balances - ?,
                                      penalty = 0,
                                      penalty_days = 0
                                  WHERE id = ?");
            $stmt->execute([$amount, $amount, $transaction['tenant_id']]);

            // Record the payment in payment history
            $stmt = $pdo->prepare("INSERT INTO payment_history
                                  (tenant_id, amount, payment_method, reference, status, created_at)
                                  VALUES (?, ?, 'mpesa', ?, 'completed', NOW())");
            $stmt->execute([
                $transaction['tenant_id'],
                $amount,
                $mpesaReceiptNumber
            ]);
        }
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    file_put_contents('mpesa_error.log', $e->getMessage(), FILE_APPEND);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}