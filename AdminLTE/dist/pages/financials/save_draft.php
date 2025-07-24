<?php
// save_draft.php
require_once '../db/connect.php';

header('Content-Type: application/json');

try {
    $pdo->beginTransaction();

    // Generate invoice number if not provided
    $invoiceNumber = $_POST['invoice_number'] ?? generateInvoiceNumber();

    // Insert invoice header
    $stmt = $pdo->prepare("INSERT INTO invoice
                          (invoice_number, building_id, tenant, invoice_date, due_date,
                           notes, status, created_at, updated_at)
                          VALUES (?, ?, ?, ?, ?, ?, 'draft', NOW(), NOW())");

    $stmt->execute([
        $invoiceNumber,
        $_POST['building_id'],
        $_POST['tenant'],
        $_POST['invoice_date'],
        $_POST['due_date'],
        $_POST['notes'] ?? null
    ]);

    $invoiceId = $pdo->lastInsertId();

    // Insert items
    $stmt = $pdo->prepare("INSERT INTO invoice_items
                          (invoice_id, account_code, description, quantity, unit_price, tax_type, total_amount)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($_POST['account_item'] as $index => $accountCode) {
        $total = $_POST['quantity'][$index] * $_POST['unit_price'][$index];
        // Adjust total for tax if needed
        if ($_POST['taxes'][$index] === 'inclusive') {
            $total = $total / 1.16; // Example for VAT inclusive
        }

        $stmt->execute([
            $invoiceId,
            $accountCode,
            $_POST['description'][$index],
            $_POST['quantity'][$index],
            $_POST['unit_price'][$index],
            $_POST['taxes'][$index],
            $total
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'invoice_id' => $invoiceId,
        'message' => 'Draft saved successfully'
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        'error' => 'Failed to save draft: ' . $e->getMessage()
    ]);
}

function generateInvoiceNumber() {
    // Implement your invoice number generation logic
    return 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());
}
?>