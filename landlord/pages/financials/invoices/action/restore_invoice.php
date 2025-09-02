<?php
require_once '../../db/connect.php';

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invoice ID not provided']);
    exit;
}

$invoiceId = $_POST['id'];

try {
    // First check if invoice exists and is restorable
    $stmt = $pdo->prepare("SELECT status FROM invoice WHERE id = ?");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        exit;
    }

    if ($invoice['status'] !== 'cancelled') {
        echo json_encode(['success' => false, 'message' => 'Only cancelled invoices can be restored']);
        exit;
    }

    // Restore the invoice to sent status
    $stmt = $pdo->prepare("UPDATE invoice SET status = 'sent' WHERE id = ?");
    $stmt->execute([$invoiceId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>