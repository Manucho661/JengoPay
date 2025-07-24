<?php
include '../db/connect.php';

function generateNextDraftNumber($pdo) {
    $stmt = $pdo->query("SELECT invoice_number FROM invoice WHERE invoice_number LIKE 'DFT%' ORDER BY id DESC LIMIT 1");
    $last = $stmt->fetchColumn();
    $next = 1;
    if ($last && preg_match('/DFT(\d+)/', $last, $matches)) {
        $next = (int)$matches[1] + 1;
    }
    return 'DFT' . str_pad($next, 3, '0', STR_PAD_LEFT);
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

$account_items = $_POST['account_item'] ?? [];
$descriptions = $_POST['description'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$unit_prices = $_POST['unit_price'] ?? [];
$taxes = $_POST['taxes'] ?? [];

try {
    $pdo->beginTransaction();

    if ($isDraft && (!$invoice_number || !str_starts_with($invoice_number, 'DFT'))) {
        $invoice_number = generateNextDraftNumber($pdo);
    }

    $stmt = $pdo->prepare("INSERT INTO invoice
        (invoice_number, invoice_date, due_date, building_id, tenant,
         account_item, description, quantity, unit_price, taxes,
         sub_total, total, notes, terms_conditions, status, payment_status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    foreach ($account_items as $i => $item) {
        $qty = is_numeric($quantities[$i]) ? (float)$quantities[$i] : 0;
        $price = is_numeric($unit_prices[$i]) ? (float)$unit_prices[$i] : 0;
        $sub_total = $qty * $price;
        $tax_rate = $taxes[$i] === 'inclusive' ? 1.16 : 1.0;
        $total = $sub_total * $tax_rate;

        $stmt->execute([
            $invoice_number,
            $invoice_date ?: null,
            $due_date ?: null,
            $building_id,
            $tenant_id,
            $item,
            $descriptions[$i],
            $qty,
            $price,
            $taxes[$i],
            $sub_total,
            $total,
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
