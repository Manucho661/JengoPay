<?php
include '../db/connect.php';

function generateNextDraftNumber($pdo) {
    $stmt = $pdo->query("SELECT invoice_number FROM invoice WHERE invoice_number LIKE 'DFT%' ORDER BY id DESC LIMIT 1");
    $last = $stmt->fetchColumn();
    $next = 1;
    if ($last && preg_match('/DFT-?(\d+)/', $last, $matches)) {
        $next = (int)$matches[1] + 1;
    }
    return 'DFT-' . str_pad($next, 6, '0', STR_PAD_LEFT);
}

$isDraft = isset($_POST['is_draft']) && $_POST['is_draft'] == '1';

$invoice_number = $_POST['invoice_number'] ?? '';
$invoice_date = $_POST['invoice_date'] ?? null;
$due_date = $_POST['due_date'] ?? null;
$building_id = $_POST['building_id'] ?? null;
$tenant_id = $_POST['tenant'] ?? null;
$status = $isDraft ? 'draft' : ($_POST['status'] ?? 'sent');
$payment_status = $_POST['payment_status'] ?? 'unpaid';
$notes = $_POST['notes'] ?? '';
$terms_conditions = $_POST['terms_conditions'] ?? '';
$total_input = isset($_POST['total']) ? str_replace(',', '', $_POST['total']) : 0;


// Handle total/subtotal/tax from the summary inputs
$subtotal_input = isset($_POST['subtotal']) ? str_replace(',', '', $_POST['subtotal']) : 0;
$total_input = isset($_POST['total']) ? str_replace(',', '', $_POST['total']) : 0;
$taxes_input = $_POST['taxes'] ?? ['']; // May be multiple VAT entries

// Handle line items
$account_items = $_POST['account_item'] ?? [];
$descriptions = $_POST['description'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$unit_prices = $_POST['unit_price'] ?? [];
$vat_type = $_POST['vat_type'] ?? [];

try {
    $pdo->beginTransaction();

    if ($isDraft && (!$invoice_number || !str_starts_with($invoice_number, 'DFT'))) {
        $invoice_number = generateNextDraftNumber($pdo);
    }

    $stmt = $pdo->prepare("INSERT INTO invoice
        (invoice_number, invoice_date, due_date, payment_date, building_id, tenant,
         account_item, description, quantity, unit_price, vat_type,
         sub_total, taxes, total,
         notes, terms_conditions, created_at, updated_at, status, payment_status)
        VALUES (?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?)");

    foreach ($account_items as $i => $item) {
        $qty = is_numeric($quantities[$i]) ? (float)$quantities[$i] : 0;
        $price = is_numeric($unit_prices[$i]) ? (float)$unit_prices[$i] : 0;
        $sub_total = $qty * $price;

        // Use summary values only for the first item row
        $final_subtotal = ($i === 0) ? (float)$subtotal_input : 0;
        $final_total = ($i === 0) ? (float)$total_input : 0;
        $tax_value = ($i === 0 && isset($taxes_input[0])) ? str_replace(',', '', $taxes_input[0]) : '';

        $stmt->execute([
            $invoice_number,
            $invoice_date ?: null,
            $due_date ?: null,
            $building_id,
            $tenant_id,
            $item,
            $descriptions[$i],
            $quantities[$i],
            $unit_prices[$i],
            $vat_type[$i] ?? '',
            $final_subtotal,
            $tax_value,
            $final_total,
            $notes,
            $terms_conditions,
            $status,
            $payment_status
        ]);
    }

    $pdo->commit();

    if ($isDraft) {
        echo json_encode([
            'success' => true,
            'invoice_number' => $invoice_number,
            'redirect_url' => 'invoice.php?draft_saved=1'
        ]);
    } else {
        header("Location: invoice.php?success=1");
        exit;
    }

} catch (Exception $e) {
    $pdo->rollBack();
    if ($isDraft) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } else {
        die("Error: " . $e->getMessage());
    }
}
?>
