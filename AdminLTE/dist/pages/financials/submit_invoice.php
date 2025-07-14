<?php
include '../db/connect.php';

// Retrieve basic invoice data
$invoice_number = $_POST['invoice_number'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? '';
$due_date = $_POST['due_date'] ?? '';
$building_id = $_POST['building_id'] ?? '';
$tenant = $_POST['tenant'] ?? ''; // Changed from tenants[] to tenant

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

if (empty($tenant)) {
    die("Error: Tenant must be selected.");
}

// Check if we have at least one item
if (count($account_items) === 0) {
    die("Error: At least one invoice item is required.");
}

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Save to invoice table (only once per invoice)
    $stmt = $pdo->prepare("INSERT INTO invoice
                          (invoice_number, invoice_date, due_date, building_id, tenant,
                           account_item, description, quantity, unit_price, taxes, total)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Insert first item along with invoice header
    $stmt->execute([
        $invoice_number,
        $invoice_date,
        $due_date,
        $building_id,
        $tenant,
        $account_items[0],
        $descriptions[0],
        $quantities[0],
        $unit_prices[0],
        $taxes[0],
        $totals[0]
    ]);

    $invoice_id = $pdo->lastInsertId();

    // // Insert remaining items (if any) as separate records
    // if (count($account_items) > 1) {
    //     $itemStmt = $pdo->prepare("INSERT INTO invoice
    //                               (invoice_number, invoice_date, due_date, building_id, tenant,
    //                                account_item, description, quantity, unit_price, taxes, total)
    //                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    //     for ($i = 1; $i < count($account_items); $i++) {
    //         $itemStmt->execute([
    //             $invoice_number,
    //             $invoice_date,
    //             $due_date,
    //             $building_id,
    //             $tenant,
    //             $account_items[$i],
    //             $descriptions[$i],
    //             $quantities[$i],
    //             $unit_prices[$i],
    //             $taxes[$i],
    //             $totals[$i]
    //         ]);
    //     }
    // }

    $pdo->commit();
    header("Location: inv1.php?success=1&invoice_id=".$invoice_id);
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Database error: " . $e->getMessage());
}
?>