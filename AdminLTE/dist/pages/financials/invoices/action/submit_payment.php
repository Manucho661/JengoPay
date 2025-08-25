<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once '../../../db/connect.php';
include_once '../../actions/journals/payInvoiceJournal.php';

try {
    // Start transaction
    $pdo->beginTransaction();

    // Collect and validate required data
    $invoice_id = $_POST['invoice_id'] ?? null;
    $payment_method = $_POST['payment_method'] ?? null;
    $payment_date = $_POST['payment_date'] ?? date('Y-m-d');
    $amount = floatval($_POST['amount'] ?? 0);
    $reference_number = $_POST['reference_number'] ?? null;
    $total_amount = floatval($_POST['total_amount'] ?? 0);
    $tenant = $_POST['tenant'] ?? null;

    // Validate required fields
    if (empty($invoice_id)) {
        throw new Exception("Invoice ID is required");
    }
    if (empty($payment_method)) {
        throw new Exception("Payment method is required");
    }
    if ($amount <= 0) {
        throw new Exception("Amount must be greater than 0");
    }
    if (empty($reference_number)) {
        throw new Exception("Reference number is required");
    }

    // 1. Insert payment record
    $stmt = $pdo->prepare("
        INSERT INTO payments (
            invoice_id,
            tenant,
            payment_date,
            payment_method,
            amount,
            reference_number,
            status,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, 'completed', NOW())
    ");
    $stmt->execute([
        $invoice_id,
        $tenant,
        $payment_date,
        $payment_method,
        $amount,
        $reference_number
    ]);

    // 2. Calculate total paid for this invoice
    $stmt = $pdo->prepare("
        SELECT SUM(amount) AS total_paid
        FROM payments
        WHERE invoice_id = ? AND status = 'completed'
    ");
    $stmt->execute([$invoice_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_paid = floatval($result['total_paid'] ?? 0);


    $customerId = 10;
    
    $remaining  = $total_amount - $total_paid;
    
    createPayInvoiceJournal($pdo, $paymentId, $invoice_id, $customerId, $amount, $payment_method, $remaining, $total_amount);

    // 3. Determine invoice payment status
    $payment_status = 'unpaid';
    if ($total_paid >= $total_amount) {
        $payment_status = 'paid';
    } elseif ($total_paid > 0) {
        $payment_status = 'partial';
    }

    // 4. Update invoice payment status
    $update = $pdo->prepare("
        UPDATE invoice 
        SET payment_status = ?
        WHERE id = ?
    ");
    $update->execute([$payment_status, $invoice_id]);

    // Commit transaction
    $pdo->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'payment_status' => $payment_status,
        'total_paid' => $total_paid,
        'balance' => max(0, $total_amount - $total_paid),
        'message' => ($payment_status === 'paid')
            ? "Payment completed successfully! Invoice fully paid."
            : "Partial payment received. Remaining balance: KES " . number_format(max(0, $total_amount - $total_paid), 2)
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();

    // Log error for debugging
    error_log("Payment Error: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
