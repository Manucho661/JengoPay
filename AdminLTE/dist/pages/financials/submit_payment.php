<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once '../db/connect.php';

try {
    // Collect and validate required data
    $invoice_id = $_POST['invoice_id'] ?? null;
    $payment_method = $_POST['payment_method'] ?? 'Cash'; // Default to Cash if not specified
    $payment_date = $_POST['payment_date'] ?? date('Y-m-d');
    $amount = floatval($_POST['amount'] ?? 0);
    $reference_number = $_POST['reference_number'] ?? null;
    $phone = $_POST['phone'] ?? null; // For MPESA payments
    $total_amount = floatval($_POST['total_amount'] ?? 0); // Expected total from invoice

    // Validate required fields
    if (empty($invoice_id) || empty($reference_number) || $amount <= 0) {
        throw new Exception("❌ Missing required fields: invoice ID, reference number, or valid amount.");
    }

    // ✅ 1. Insert payment record with status 'pending'
    $stmt = $pdo->prepare("
        INSERT INTO payments (
            invoice_id,
            amount,
            reference_number,
            phone,
            status,
            created_at
        ) VALUES (?, ?, ?, ?, 'pending', NOW())
    ");
    $stmt->execute([
        $invoice_id,
        $amount,
        $reference_number,
        $phone
    ]);

    // ✅ 2. Calculate total paid for this invoice (including this payment)
    $stmt = $pdo->prepare("
        SELECT SUM(amount) AS total_paid
        FROM payments
        WHERE invoice_id = ? AND status != 'failed'
    ");
    $stmt->execute([$invoice_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_paid = floatval($result['total_paid'] ?? 0);

    // ✅ 3. Determine payment status
    $payment_status = 'pending';
    if ($total_paid >= $total_amount) {
        $payment_status = 'completed';
    } elseif ($total_paid > 0) {
        $payment_status = 'partial';
    }

    // ✅ 4. Update payment status (for this transaction)
    $update = $pdo->prepare("
        UPDATE payments
        SET status = ?
        WHERE reference_number = ? AND invoice_id = ?
    ");
    $update->execute([$payment_status, $reference_number, $invoice_id]);

    // Return success response
    echo json_encode([
        'success' => true,
        'payment_status' => $payment_status,
        'total_paid' => $total_paid,
        'balance' => max(0, $total_amount - $total_paid), // Ensure balance doesn't go negative
        'message' => ($payment_status === 'completed')
            ? "✅ Payment completed! Invoice fully paid."
            : "⚠️ Partial payment received. Remaining balance: KES " . number_format(max(0, $total_amount - $total_paid), 2)
    ]);

} catch (Exception $e) {
    // Log error for debugging
    error_log("Payment Error: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
