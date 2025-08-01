<?php
include '../db/connect.php';

// Sanitize inputs
$invoice_number = $_POST['invoice_number_hidden'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? date('Y-m-d');
$building = $_POST['building'] ?? '';
$tenant_info = $_POST['tenant_information'] ?? '';
$status = 'pending'; // default

// You must convert tenant_info to unit_number if needed
// For this example, let's assume the tenant selection passes the unit_number directly
$unit_number = $tenant_info; // adjust if needed

// Insert into invoices table
$stmt = $pdo->prepare("INSERT INTO invoices (unit_number, created_at, status) VALUES (?, ?, ?)");
if ($stmt->execute([$unit_number, $invoice_date, $status])) {
    echo "Invoice saved successfully.";
} else {
    echo "Failed to save invoice.";
}
?>
