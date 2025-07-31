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

// --- Collect Main Form Data ---
$isDraft = isset($_POST['is_draft']) && $_POST['is_draft'] == '1';
$invoice_number = trim($_POST['invoice_number'] ?? '');
$invoice_date = $_POST['invoice_date'] ?? null;
$due_date = $_POST['due_date'] ?? null;
$building_id = $_POST['building_id'] ?? null;
$tenant_id = $_POST['tenant'] ?? null;
$status = $isDraft ? 'draft' : ($_POST['status'] ?? 'sent');
$payment_status = $_POST['payment_status'] ?? 'unpaid';
$notes = trim($_POST['notes'] ?? '');
$terms_conditions = trim($_POST['terms_conditions'] ?? '');
$paid_amount = 0.00;

// --- Calculate Total, Subtotal and Taxes ---
$total = 0.00;
if (isset($_POST['total']) && is_array($_POST['total'])) {
    foreach ($_POST['total'] as $t) {
        $total += floatval(str_replace([',', ' '], '', $t));
    }
}
$subtotal_input = isset($_POST['subtotal']) ? floatval(str_replace([',', ' '], '', $_POST['subtotal'])) : 0.00;
$taxes_input = isset($_POST['tax_total']) ? floatval(str_replace([',', ' '], '', $_POST['tax_total'])) : 0.00;

// --- Line Item Arrays ---
$account_items = $_POST['account_item'] ?? [];
$descriptions = $_POST['description'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$unit_prices = $_POST['unit_price'] ?? [];
$vat_type = $_POST['vat_type'] ?? [];
$totals = $_POST['total'] ?? [];

if (
    empty($account_items) ||
    count($account_items) !== count($descriptions) ||
    count($account_items) !== count($quantities) ||
    count($account_items) !== count($unit_prices)
) {
    die("Error: Invalid line item data");
}

try {
    $pdo->beginTransaction();

    // --- Generate Invoice Number if it's a draft ---
    if ($isDraft && (!$invoice_number || !str_starts_with($invoice_number, 'DFT'))) {
        $invoice_number = generateNextDraftNumber($pdo);
    }

    // --- Insert into invoice table ---
    $stmt = $pdo->prepare("
        INSERT INTO invoice
        (invoice_number, invoice_date, due_date, payment_date, building_id, tenant,
         sub_total, taxes, total, paid_amount,
         notes, terms_conditions, created_at, updated_at, status, payment_status)
        VALUES (?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?)
    ");

    $stmt->execute([
        $invoice_number,
        $invoice_date ?: null,
        $due_date ?: null,
        $building_id,
        $tenant_id,
        $subtotal_input,
        $taxes_input,
        $total,
        $paid_amount,
        $notes,
        $terms_conditions,
        $status,
        $payment_status
    ]);

    // --- Insert Line Items into invoice_items ---
    $itemStmt = $pdo->prepare("
        INSERT INTO invoice_items (
            invoice_number, account_item, description,
            quantity, unit_price, vat_type, taxes, total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($account_items as $i => $item) {
        $qty = floatval($quantities[$i]);
        $price = floatval($unit_prices[$i]);
        $subtotal = $qty * $price;
        $vat = trim($vat_type[$i] ?? '');
        $tax = 0.00;

        // Tax calculation
        if ($vat === 'exclusive') {
            $tax = round($subtotal * 0.16, 2);
        } elseif ($vat === 'inclusive') {
            $tax = round($subtotal * 16 / 116, 2); // Extract embedded VAT
        } elseif ($vat === 'zero' || $vat === 'exempted') {
            $tax = 0.00;
        }

        $lineTotal = ($vat === 'exclusive') ? $subtotal + $tax : $subtotal;

        $itemStmt->execute([
            $invoice_number,
            trim($item),
            trim($descriptions[$i]),
            $qty,
            $price,
            $vat,
            $tax,
            $lineTotal
        ]);
    }

    $pdo->commit();

    if ($isDraft) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'invoice_number' => $invoice_number,
            'redirect_url' => 'invoice.php?draft_saved=1'
        ]);
    } else {
        header("Location: invoice.php?success=1&invoice_number=" . urlencode($invoice_number));
        exit;
    }

} catch (Exception $e) {
    $pdo->rollBack();

    if ($isDraft) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    } else {
        die("Error: " . htmlspecialchars($e->getMessage()));
    }
}
?>
