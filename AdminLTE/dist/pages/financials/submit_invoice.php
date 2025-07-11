<?php
include '../db/connect.php';

// Retrieve basic invoice data
$invoice_number = $_POST['invoice_number'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? '';
$building_id = $_POST['building_id'] ?? '';
$tenants = $_POST['tenants'] ?? [];

// Retrieve item arrays
$account_items = $_POST['account_item'] ?? [];
$descriptions = $_POST['description'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$unit_prices = $_POST['unit_price'] ?? [];
$taxes = $_POST['taxes'] ?? [];
$totals = $_POST['total'] ?? [];

// Validate required fields
if (empty($invoice_number) || empty($tenant)) {
    die("Error: Missing invoice number or tenant.");
}

// Save to invoice table
$stmt = $pdo->prepare("INSERT INTO invoice (invoice_number, invoice_date, building_id, tenant, account_item, description, quantity, unit_price, taxes, total)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->execute([
  $invoice_number,
  $invoice_date,
  $building_id,
  $tenant,
  $account_items[0] ?? '',
  $descriptions[0] ?? '',
  $quantities[0] ?? '',
  $unit_prices[0] ?? '',
  $taxes[0] ?? '',
  $totals[0] ?? 0
]);

$invoice_id = $pdo->lastInsertId();

// Loop through each item
for ($i = 0; $i < count($account_items); $i++) {
    $item_code = $account_items[$i];
    $desc = $descriptions[$i];
    $qty = $quantities[$i];
    $price = $unit_prices[$i];
    $tax = $taxes[$i];
    $total = $totals[$i];

    // TODO: Save to `invoice_items` table (if created)
    // $stmt = $pdo->prepare("INSERT INTO invoice_items (...) VALUES (...)");
    // $stmt->execute([...]);
}

// Redirect with success message
header("Location: invoices2.php?success=1");
exit;
?>
