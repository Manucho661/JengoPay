<?php
include '../db/connect.php';

// Retrieve basic invoice data
$invoice_number = $_POST['invoice_number'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? '';
$due_date = $_POST['due_date'] ?? '';
$building_id = $_POST['building_id'] ?? '';
$tenant_id = $_POST['tenant'] ?? ''; // user_id

// Retrieve item arrays
$account_items = $_POST['account_item'] ?? [];
$descriptions = $_POST['description'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$unit_prices = $_POST['unit_price'] ?? [];
$taxes = $_POST['taxes'] ?? [];
$totals = $_POST['total'] ?? [];

// Validate required fields
if (empty($invoice_number)) {
    die("Error: Invoice number is required.");
}
if (empty($tenant_id)) {
    die("Error: Tenant must be selected.");
}
if (count($account_items) === 0) {
    die("Error: At least one invoice item is required.");
}

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Loop through and insert each item
    $stmt = $pdo->prepare("INSERT INTO invoice
        (invoice_number, invoice_date, due_date, building_id, tenant,
         account_item, description, quantity, unit_price, taxes, total)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($account_items as $i => $item) {
        $stmt->execute([
            $invoice_number,
            $invoice_date,
            $due_date,
            $building_id,
            $tenant_id,
            $account_items[$i],
            $descriptions[$i],
            $quantities[$i],
            $unit_prices[$i],
            $taxes[$i],
            $totals[$i]
        ]);
    }

    $invoice_id = $pdo->lastInsertId(); // optional, just for redirect

    // Fetch tenant name
    $nameStmt = $pdo->prepare("SELECT CONCAT(first_name, ' ', middle_name) AS full_name FROM users WHERE id = ?");
    $nameStmt->execute([$tenant_id]);
    $tenant_name = $nameStmt->fetchColumn() ?: 'Unknown';

    $pdo->commit();

    // Redirect with tenant name & invoice_id
    // header("Location: inv1.php?success=1&invoice_id=$invoice_id&tenant=" . urlencode($tenant_name));
    header("Location: inv1.php?success=1&invoice_id=$invoice_id&tenant_id=$tenant_id");

    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Database error: " . $e->getMessage());
}
?>
