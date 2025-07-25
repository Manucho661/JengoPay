<?php
require_once '../db/connect.php';

// Check if required data is present
if (!isset($_POST['invoice_id']) || !isset($_POST['invoice_number'])) {
    header('Location: invoice.php');
    exit;
}

$invoiceId = (int)$_POST['invoice_id'];
$isDraftEdit = $_POST['is_draft_edit'] === '1';
$originalInvoiceNumber = $_POST['original_invoice_number'];

try {
    // Begin transaction
    $pdo->beginTransaction();

    // 1. Delete the old draft record if converting from draft
    if ($isDraftEdit) {
        $stmt = $pdo->prepare("DELETE FROM invoice WHERE invoice_number = ? AND status = 'draft'");
        $stmt->execute([$originalInvoiceNumber]);
    }

    // 2. Insert new invoice record
    $invoiceData = [
        'invoice_number' => $_POST['invoice_number'],
        'building_id' => $_POST['building_id'],
        'tenant' => $_POST['tenant'],
        'account_item' => $_POST['account_item'][0],
        'description' => $_POST['description'][0],
        'quantity' => $_POST['quantity'][0],
        'unit_price' => $_POST['unit_price'][0],
        'taxes' => $_POST['taxes'][0],
        'sub_total' => $_POST['quantity'][0] * $_POST['unit_price'][0],
        'total' => $_POST['quantity'][0] * $_POST['unit_price'][0], // Adjust for taxes if needed
        'invoice_date' => $_POST['invoice_date'],
        'due_date' => $_POST['due_date'],
        'notes' => $_POST['notes'] ?? '',
        'status' => 'sent',
        'payment_status' => 'unpaid'
    ];

    $stmt = $pdo->prepare("INSERT INTO invoice (
        invoice_number, building_id, tenant, account_item, description,
        quantity, unit_price, taxes, sub_total, total,
        invoice_date, due_date, notes, status, payment_status, created_at
    ) VALUES (
        :invoice_number, :building_id, :tenant, :account_item, :description,
        :quantity, :unit_price, :taxes, :sub_total, :total,
        :invoice_date, :due_date, :notes, :status, :payment_status, NOW()
    )");

    $stmt->execute($invoiceData);
    $newInvoiceId = $pdo->lastInsertId();

    // Commit transaction
    $pdo->commit();

    // Redirect to the new invoice
    header('Location: invoice.php?id=' . $newInvoiceId . '&converted=1');
    exit;

} catch (PDOException $e) {
    // Rollback on error
    $pdo->rollBack();

    // Redirect back with error message
    header('Location: invoice_edit.php?id=' . $invoiceId . '&error=1');
    exit;
}
?>