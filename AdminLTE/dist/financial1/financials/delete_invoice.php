<?php
require_once '../db/connect.php';
header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invoice ID not provided']);
    exit;
}

$invoiceId = $_POST['id'];

try {
    // Fetch invoice status and payment info
    $stmt = $pdo->prepare("
        SELECT
            i.status,
            (SELECT COALESCE(SUM(p.amount), 0)
             FROM payments p
             WHERE p.invoice_id = i.id) AS paid_amount
        FROM invoice i
        WHERE i.id = ?
    ");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        exit;
    }

    if (!in_array($invoice['status'], ['draft', 'cancelled'])) {
        echo json_encode(['success' => false, 'message' => 'Only draft or cancelled invoices can be deleted']);
        exit;
    }

    if ($invoice['paid_amount'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete invoice with payments']);
        exit;
    }

    // OPTIONAL: Soft delete instead of hard delete
    // $stmt = $pdo->prepare("UPDATE invoice SET deleted_at = NOW() WHERE id = ?");
    // $stmt->execute([$invoiceId]);

    // Hard delete
    $stmt = $pdo->prepare("DELETE FROM invoice WHERE id = ?");
    $stmt->execute([$invoiceId]);

    echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
