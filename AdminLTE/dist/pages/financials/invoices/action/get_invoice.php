<?php
header('Content-Type: application/json');
require_once '../../db/connect.php';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invoice ID required']);
    exit;
}

$invoiceId = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM invoice WHERE id = ?");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo json_encode(['success' => false, 'message' => 'Invoice not found']);
        exit;
    }

    // Format dates for HTML input
    $invoice['invoice_date'] = $invoice['invoice_date'] == '0000-00-00' ? '' : $invoice['invoice_date'];
    $invoice['due_date'] = $invoice['due_date'] == '0000-00-00' ? '' : $invoice['due_date'];

    echo json_encode([
        'success' => true,
        'data' => $invoice
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>