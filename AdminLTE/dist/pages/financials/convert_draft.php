<?php
require_once '../db/connect.php';

// Check if required data is present
if (!isset($_POST['invoice_id']) || !isset($_POST['invoice_number'])) {
    header('Location: invoices.php?error=missing_data');
    exit;
}

$invoiceId = (int)$_POST['invoice_id'];
$newInvoiceNumber = $_POST['invoice_number'];
$originalInvoiceNumber = $_POST['original_invoice_number'];

try {
    $pdo->beginTransaction();

    // 1. Delete the old draft record
    $deleteStmt = $pdo->prepare("DELETE FROM invoice WHERE id = ? AND invoice_number = ? AND status = 'draft'");
    $deleteStmt->execute([$invoiceId, $originalInvoiceNumber]);

    if ($deleteStmt->rowCount() === 0) {
        throw new Exception("Draft invoice not found or already converted");
    }

    // 2. Insert new invoice record with the new number
    $insertStmt = $pdo->prepare("INSERT INTO invoice (
        invoice_number, invoice_date, due_date, building_id, tenant,
        account_item, description, quantity, unit_price, taxes,
        sub_total, total, notes, terms_conditions, status, payment_status
    ) SELECT
        ?, invoice_date, due_date, building_id, tenant,
        account_item, description, quantity, unit_price, taxes,
        sub_total, total, notes, terms_conditions, 'sent', 'unpaid'
    FROM invoice WHERE id = ?");

    $insertStmt->execute([$newInvoiceNumber, $invoiceId]);
    $newInvoiceId = $pdo->lastInsertId();

    $pdo->commit();

    // Redirect to the new invoice
    header("Location: view_invoice.php?id=$newInvoiceId&converted=1");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Error converting draft: " . $e->getMessage());
    header("Location: invoice_edit.php?id=$invoiceId&error=conversion_failed");
    exit;
}
?>