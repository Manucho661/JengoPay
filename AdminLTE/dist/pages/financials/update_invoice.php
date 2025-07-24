<?php
// update_invoice.php
require_once 'db_config.php';

// Get POST data
$invoiceId = $_POST['invoice_id'];
$invoiceData = [
    'building_id' => $_POST['building_id'],
    'tenant_id' => $_POST['tenant'],
    'invoice_date' => $_POST['invoice_date'],
    'due_date' => $_POST['due_date'],
    'notes' => $_POST['notes'] ?? null,
    // Add other fields as needed
];

try {
    $pdo->beginTransaction();

    // Update invoice header
    $stmt = $pdo->prepare("UPDATE invoices SET
                          building_id = :building_id,
                          tenant_id = :tenant_id,
                          invoice_date = :invoice_date,
                          due_date = :due_date,
                          notes = :notes,
                          status = 'draft',
                          updated_at = NOW()
                          WHERE id = :id");
    $invoiceData['id'] = $invoiceId;
    $stmt->execute($invoiceData);

    // Delete existing items
    $stmt = $pdo->prepare("DELETE FROM invoice_items WHERE invoice_id = ?");
    $stmt->execute([$invoiceId]);

    // Insert new items
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
    header("Location: invoices.php?success=Invoice updated successfully");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    header("Location: invoices.php?error=Failed to update invoice: " . $e->getMessage());
    exit;
}
?>