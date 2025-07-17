<?php
include '../db/connect.php';

// Retrieve basic invoice data
$invoice_number = $_POST['invoice_number'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? '';
$due_date = $_POST['due_date'] ?? '';
$building_id = $_POST['building_id'] ?? '';
$tenant_id = $_POST['tenant'] ?? ''; // user_id
$status = $_POST['status'] ?? 'sent';
$payment_status = $_POST['payment_status'] ?? 'unpaid';
$notes = $_POST['notes'] ?? '';
$terms_conditions = $_POST['terms_conditions'] ?? '';

// Retrieve item arrays
$account_items = $_POST['account_item'] ?? [];
$descriptions = $_POST['description'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$unit_prices = $_POST['unit_price'] ?? [];
$taxes = $_POST['taxes'] ?? [];

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

// Validate and format dates
try {
    $invoice_date = !empty($invoice_date) ? date('Y-m-d', strtotime($invoice_date)) : null;
    $due_date = !empty($due_date) ? date('Y-m-d', strtotime($due_date)) : null;

    if ($invoice_date === false) {
        die("Error: Invalid invoice date format.");
    }
    if ($due_date === false) {
        die("Error: Invalid due date format.");
    }
} catch (Exception $e) {
    die("Error processing dates: " . $e->getMessage());
}

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Prepare the insert statement with all fields
    $stmt = $pdo->prepare("INSERT INTO invoice
        (invoice_number, invoice_date, due_date, building_id, tenant,
         account_item, description, quantity, unit_price, taxes,
         sub_total, total, notes, terms_conditions, status, payment_status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    foreach ($account_items as $i => $item) {
        // Validate numeric fields
        if (!is_numeric($quantities[$i])) {
            throw new Exception("Quantity must be numeric for item " . ($i+1));
        }
        if (!is_numeric($unit_prices[$i])) {
            throw new Exception("Unit price must be numeric for item " . ($i+1));
        }

        // Calculate totals
        $quantity = (float)$quantities[$i];
        $unit_price = (float)$unit_prices[$i];
        $sub_total = $quantity * $unit_price;

        // Calculate tax if needed (adjust based on your tax logic)
        $tax_rate = ($taxes[$i] === 'inclusive') ? 1.1 : 1.0; // Example: 10% tax
        $total = $sub_total * $tax_rate;

        // Execute the insert with all values
        $stmt->execute([
            $invoice_number,
            $invoice_date,
            $due_date,
            $building_id,
            $tenant_id,
            $account_items[$i],
            $descriptions[$i],
            $quantity,
            $unit_price,
            $taxes[$i],
            $sub_total,
            $total,
            $notes,
            $terms_conditions,
            $status,
            $payment_status
        ]);
    }

    $invoice_id = $pdo->lastInsertId();

    // Fetch tenant name
    $nameStmt = $pdo->prepare("SELECT CONCAT(first_name, ' ', middle_name) AS full_name FROM users WHERE id = ?");
    $nameStmt->execute([$tenant_id]);
    $tenant_name = $nameStmt->fetchColumn() ?: 'Unknown';

    $pdo->commit();

    // Redirect with success parameters
    header("Location: inv1.php?success=1&invoice_id=$invoice_id&tenant_id=$tenant_id");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    $pdo->rollBack();
    die("Error: " . $e->getMessage());
}
?>