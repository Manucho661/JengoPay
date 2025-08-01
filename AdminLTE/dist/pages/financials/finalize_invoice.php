<?php
include '../db/connect.php';

// 1. Collect data
$invoiceId    = $_POST['invoice_id'];
$buildingId   = $_POST['building_id'];
$tenantId     = $_POST['tenant'];
$invoiceDate  = $_POST['invoice_date'];
$dueDate      = $_POST['due_date'];
$notes        = $_POST['notes'] ?? '';
$terms        = ''; // optional

$accountItems = $_POST['account_item'];
$descriptions = $_POST['description'];
$quantities   = $_POST['quantity'];
$unitPrices   = $_POST['unit_price'];
$vatTypes     = $_POST['vat_type'];

// 2. Check current invoice number
$stmt = $pdo->prepare("SELECT invoice_number FROM invoice WHERE id = ?");
$stmt->execute([$invoiceId]);
$currentInvoiceNo = $stmt->fetchColumn();

// 3. Generate new invoice number if it's a draft
if (str_starts_with($currentInvoiceNo, 'DFT')) {
    $getLast = $pdo->query("SELECT invoice_number FROM invoice WHERE invoice_number LIKE 'INV%' ORDER BY id DESC LIMIT 1");
    $last = $getLast->fetchColumn();
    $next = 1;
    if ($last && preg_match('/INV(\d+)/', $last, $matches)) {
        $next = (int)$matches[1] + 1;
    }
    $newInvoiceNo = 'INV' . str_pad($next, 3, '0', STR_PAD_LEFT);
} else {
    $newInvoiceNo = $currentInvoiceNo;
}

// 4. Calculate Totals
$subTotal = 0;
$taxTotal = 0;
$grandTotal = 0;
$itemData = [];

foreach ($accountItems as $i => $itemCode) {
    $desc     = $descriptions[$i];
    $qty      = (float)$quantities[$i];
    $price    = (float)$unitPrices[$i];
    $vatType  = $vatTypes[$i];

    $lineBase = $qty * $price;
    $tax      = 0;
    $lineSub  = $lineBase;

    if ($vatType === 'inclusive') {
        $lineSub = round($lineBase / 1.16, 2);
        $tax     = round($lineBase - $lineSub, 2);
    } elseif ($vatType === 'exclusive') {
        $tax     = round($lineBase * 0.16, 2);
    } elseif ($vatType === 'zero' || $vatType === 'exempted') {
        $tax = 0;
    }

    $total = ($vatType === 'exclusive') ? ($lineBase + $tax) : $lineBase;

    $subTotal  += $lineSub;
    $taxTotal  += $tax;
    $grandTotal += $total;

    $itemData[] = [
        'account_item' => $itemCode,
        'description'  => $desc,
        'quantity'     => $qty,
        'unit_price'   => $price,
        'vat_type'     => $vatType,
        'sub_total'    => $lineSub,
        'taxes'        => $tax,
        'total'        => $total
    ];
}

// 5. Update invoice
$update = $pdo->prepare("
    UPDATE invoice SET
        invoice_number = ?,
        invoice_date = ?,
        due_date = ?,
        building_id = ?,
        tenant = ?,
        sub_total = ?,
        taxes = ?,
        total = ?,
        notes = ?,
        terms_conditions = ?,
        status = 'sent',
        payment_status = 'unpaid',
        updated_at = NOW()
    WHERE id = ?
");
$update->execute([
    $newInvoiceNo,
    $invoiceDate,
    $dueDate,
    $buildingId,
    $tenantId,
    $subTotal,
    $taxTotal,
    $grandTotal,
    $notes,
    $terms,
    $invoiceId
]);

// 6. Clear old items and re-insert
$pdo->prepare("DELETE FROM invoice_items WHERE invoice_number = ?")->execute([$currentInvoiceNo]);

$insert = $pdo->prepare("
    INSERT INTO invoice_items (invoice_number, account_item, description, quantity, unit_price, vat_type, sub_total, taxes, total)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

foreach ($itemData as $item) {
    $insert->execute([
        $newInvoiceNo,
        $item['account_item'],
        $item['description'],
        $item['quantity'],
        $item['unit_price'],
        $item['vat_type'],
        $item['sub_total'],
        $item['taxes'],
        $item['total']
    ]);
}

// ✅ Done
header("Location: invoice.php?message=Invoice finalized successfully!");
exit;
