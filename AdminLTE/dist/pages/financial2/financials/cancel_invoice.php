<?php
require_once '../db/connect.php';

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invoice ID not provided']);
    exit;
}

$invoiceId = $_POST['id'];

try {
    // First check if invoice exists and is cancellable
    $stmt = $pdo->prepare("SELECT status FROM invoice WHERE id = ?");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        exit;
    }

    if ($invoice['status'] === 'paid' || $invoice['status'] === 'cancelled') {
        echo json_encode(['success' => false, 'message' => 'Invoice cannot be cancelled']);
        exit;
    }

    // Update the invoice status to cancelled
    $stmt = $pdo->prepare("UPDATE invoice SET status = 'cancelled' WHERE id = ?");
    $stmt->execute([$invoiceId]);

    // Get updated invoice data
    $stmt = $pdo->prepare("
        SELECT
            i.status,
            i.payment_status,
            (SELECT COALESCE(SUM(p.amount), 0) FROM payments p WHERE p.invoice_id = i.id) AS paid_amount,
            i.total
        FROM invoice i
        WHERE i.id = ?
    ");
    $stmt->execute([$invoiceId]);
    $updatedInvoice = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'invoice' => $updatedInvoice
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>