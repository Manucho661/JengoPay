<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once '../db/connect.php';

try {
    // Collect and validate required data
    $invoice_id      = $_POST['invoice_id']; // from hidden input
    $payment_method  = $_POST['payment_method']; // may be optional in DB
    $payment_date    = $_POST['payment_date'];   // optional if not used
    $amount          = $_POST['amount'];
    $reference_number   = $_POST['reference_number']; // MPESA or bank slip code

    // Optional validation (basic)
    if (empty($invoice_id) || empty($amount) || empty($reference_number)) {
        throw new Exception("Missing required fields.");
    }

    // ✅ Insert into `payments` table (matching your structure)
    $stmt = $pdo->prepare("INSERT INTO payments (amount, reference_number, invoice_id) VALUES (?, ?, ?)");
    $stmt->execute([$amount, $reference_number, $invoice_id]);

    // ✅ Optionally update invoice status to 'paid'
    $update = $pdo->prepare("UPDATE invoice SET payment_status = 'paid' WHERE id = ?");
    $update->execute([$invoice_id]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

