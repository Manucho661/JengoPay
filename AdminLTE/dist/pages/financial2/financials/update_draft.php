<?php
require_once '../db/connect.php';

// Check if required data is present
if (!isset($_POST['invoice_id']) || !isset($_POST['invoice_number'])) {
    header('Location: invoice.php');
    exit;
}

// Collect all form data
$invoiceId = (int)$_POST['invoice_id'];
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
    'status' => 'sent' // Change status from draft to sent
];

try {
    // Prepare SQL update
    $stmt = $pdo->prepare("UPDATE invoice SET
        invoice_number = :invoice_number,
        building_id = :building_id,
        tenant = :tenant,
        account_item = :account_item,
        description = :description,
        quantity = :quantity,
        unit_price = :unit_price,
        taxes = :taxes,
        sub_total = :sub_total,
        total = :total,
        invoice_date = :invoice_date,
        due_date = :due_date,
        notes = :notes,
        status = :status
        WHERE id = :id");

    // Execute with parameters
    $stmt->execute(array_merge([':id' => $invoiceId], $invoiceData));

    // Redirect back to invoices with success message
    header('Location: invoice.php?success=1');
    exit;

} catch (PDOException $e) {
    // Redirect back with error message
    header('Location: invoice_edit.php?id=' . $invoiceId . '&error=1');
    exit;
}
?>