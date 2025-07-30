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

// Initialize variables
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
$paid_amount = 0.00; // Default to 0 unless you want to pass this in via POST

// Process total amount
$total = 0.00;
if (isset($_POST['total'])) {
    if (is_array($_POST['total'])) {
        foreach ($_POST['total'] as $t) {
            $total += floatval(str_replace([',', ' '], '', $t));
        }
    } else {
        $total = floatval(str_replace([',', ' '], '', $_POST['total']));
    }
}

// Subtotal and taxes
$subtotal_input = isset($_POST['subtotal']) ? floatval(str_replace([',', ' '], '', $_POST['subtotal'])) : 0.00;

$taxes_input = 0.00;
if (!empty($_POST['taxes']) && is_array($_POST['taxes'])) {
    foreach ($_POST['taxes'] as $tax) {
        $taxes_input += floatval(str_replace([',', ' '], '', $tax));
    }
}

// Line items
$account_items = $_POST['account_item'] ?? [];
$descriptions = $_POST['description'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$unit_prices = $_POST['unit_price'] ?? [];
$vat_type = $_POST['vat_type'] ?? [];

if (empty($account_items) || count($account_items) !== count($descriptions)) {
    die("Error: Invalid line items data");
}

try {
    $pdo->beginTransaction();

    if ($isDraft && (!$invoice_number || !str_starts_with($invoice_number, 'DFT'))) {
        $invoice_number = generateNextDraftNumber($pdo);
    }

    // Prepared insert with paid_amount included
    $stmt = $pdo->prepare("INSERT INTO invoice
        (invoice_number, invoice_date, due_date, payment_date, building_id, tenant,
         account_item, description, quantity, unit_price, vat_type,
         sub_total, taxes, total, paid_amount,
         notes, terms_conditions, created_at, updated_at, status, payment_status)
        VALUES (?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?)");

    foreach ($account_items as $i => $item) {
        $item = trim($item);
        $description = trim($descriptions[$i] ?? '');
        $qty = is_numeric($quantities[$i]) ? floatval($quantities[$i]) : 0.00;
        $price = is_numeric($unit_prices[$i]) ? floatval($unit_prices[$i]) : 0.00;
        $vat = trim($vat_type[$i] ?? '');

        $final_subtotal = ($i === 0) ? $subtotal_input : 0.00;
        $final_tax = ($i === 0) ? $taxes_input : 0.00;
        $final_total = ($i === 0) ? $total : 0.00;

        $stmt->execute([
            $invoice_number,
            $invoice_date ?: null,
            $due_date ?: null,
            $building_id,
            $tenant_id,
            $item,
            $description,
            $qty,
            $price,
            $vat,
            $final_subtotal,
            $final_tax,
            $final_total,
            $paid_amount, // NEW: required column
            $notes,
            $terms_conditions,
            $status,
            $payment_status
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
