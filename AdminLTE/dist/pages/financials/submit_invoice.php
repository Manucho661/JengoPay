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







<?php
include '../db/connect.php';

$tenantId = $_GET['tenant_id'] ?? null;
if (!$tenantId) { echo "Invalid tenant ID."; exit; }

$stmt = $pdo->prepare("SELECT * FROM tenant_rent_summary WHERE id = ?");
$stmt->execute([$tenantId]);
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$tenant) { echo "Tenant not found."; exit; }

/* ───────── 1. Numbers & gate flags ───────── */
$amountPaid  = (float) $tenant['amount_paid'];
$penaltyAmt  = (float) $tenant['penalty'];
$arrearsAmt  = (float) $tenant['arrears'];
$rawBalance  = (float) $tenant['balances'];       // +ve = still owed, –ve = over‑payment

$showPenalty = $penaltyAmt > 0;
$showArrears = $arrearsAmt > 0;                   // add 30‑day check if required
$showBalance = $rawBalance != 0;                  // show both +ve and –ve cases

/*  ↓↓↓  CHANGED LINES  ↓↓↓  */
$balanceLabel     = 'Balance';                    // always “Balance”
$formattedBalance = number_format($rawBalance, 2);/* keeps ± sign intact */
/*  ↑↑↑             ↑↑↑  */

/* ───────── 2. Display strings ───────── */
$name         = htmlspecialchars($tenant['tenant_name']);
$unit         = htmlspecialchars($tenant['unit_code']);
$property     = htmlspecialchars($tenant['building_name'] ?? 'XXX');
$amount       = number_format($amountPaid, 2);
$penalty      = number_format($penaltyAmt, 2);
$penaltyDays  = (int) $tenant['penalty_days'];
$arrears      = number_format($arrearsAmt, 2);
$paymentMode  = htmlspecialchars($tenant['payment_mode'] ?? 'Mpesa');
$reference    = htmlspecialchars($tenant['reference_number'] ?? 'TCO2X12E80');
$date         = !empty($tenant['payment_date'])
                ? date("d/m/Y", strtotime($tenant['payment_date']))
                : date("d/m/Y");
$printDate    = date("d/m/Y H:i");
$receiptNo    = "RC" . str_pad($tenantId, 5, '0', STR_PAD_LEFT);
$accountNo    = !empty($tenant['account_no'])
                ? htmlspecialchars($tenant['account_no'])
                : $unit;

/* ───────── 3. Re‑calculate TOTAL ───────── */
$total = $amountPaid
       + ($showPenalty  ? $penaltyAmt  : 0)
       + ($showArrears  ? $arrearsAmt  : 0)
       + ($rawBalance   > 0 ? $rawBalance : 0);  // add only when tenant still owes

$totalAmountFormatted = number_format($total, 2);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tenant Receipt</title>
    <style>
        body{font-family:Arial,sans-serif;margin:0;padding:20px;background:#fff}
        .receipt-container{max-width:600px;margin:0 auto}
        .company-header{text-align:center;margin-bottom:15px;line-height:1.3}
        .company-header h1{font-size:18px;margin:5px 0}
        .company-header p{font-size:12px;margin:2px 0}
        .receipt-title{text-align:center;font-size:16px;font-weight:bold;margin:10px 0;
                       padding-bottom:5px;border-bottom:1px solid #000}
        table{border-collapse:collapse;width:100%}
        .receipt-table{margin:10px 0;font-size:13px}
        .receipt-table td{padding:2px 5px;white-space:nowrap}
        .receipt-table td:first-child,.receipt-table td:nth-child(3){font-weight:bold}
        .amount-table{width:100%;border-collapse:collapse;margin:15px 0;font-size:14px}
        .amount-table td{padding:5px;border:none}
        .amount-table td:last-child{text-align:right}
        .amount-table td.negative{color:red;font-weight:bold}   /* highlight over‑pay */
        .divider{border-top:1px dashed #000;margin:10px 0}
        .footer{text-align:center;margin-top:20px;font-size:12px}
        @media print{.print-button{display:none}body{padding:0}}
    </style>
</head>
<body>
<div class="receipt-container">
    <div class="company-header">
        <h1>BT JENGOPAY</h1>
        <p>P.O BOX 37987 – 00100 – 8TH FLOOR</p>
        <p>INTERNATIONAL LIFE HSE, MAMA NGINA ST.</p>
        <p>TEL: 0733717726</p>
        <p>EMAIL: PROPERTYMANAGEMENT@BTJENGOPAY.CO.KE</p>
    </div>

    <div class="divider"></div>

    <div class="receipt-title">RECEIPT</div>

    <table class="receipt-table">
        <tr>
            <td>Received From:</td><td><?= $name ?></td>
            <td>Receipt No:</td><td><?= $receiptNo ?></td>
        </tr>
        <tr>
            <td>A/c NO:</td><td><?= $accountNo ?></td>
            <td>Date:</td><td><?= $date ?></td>
        </tr>
        <tr>
            <td>Unit No:</td><td><?= $unit ?></td>
            <td>Payment Mode:</td><td><?= $paymentMode ?></td>
        </tr>
        <tr>
            <td>Property:</td><td><?= $property ?></td>
            <td>Reference No:</td><td><?= $reference ?></td>
        </tr>
        <tr>
            <td></td><td></td>
            <td>Amount (KES):</td><td><?= $totalAmountFormatted ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="receipt-title">DESCRIPTION</div>

    <table class="amount-table">
        <tr>
            <td>Rent Payment</td><td><?= $amount ?></td>
        </tr>

        <?php if ($showPenalty): ?>
        <tr>
            <td>Penalty (<?= $penaltyDays ?> days)</td><td><?= $penalty ?></td>
        </tr>
        <?php endif; ?>

        <?php if ($showArrears): ?>
        <tr>
            <td>Arrears</td><td><?= $arrears ?></td>
        </tr>
        <?php endif; ?>

        <?php if ($showBalance): ?>
        
        <?php endif; ?>
    </table>

    <div class="divider"></div>

    <table class="amount-table">
        <tr><td>TOTAL (KES)</td><td><?= $totalAmountFormatted ?></td></tr>
    </table>

    <div class="divider"></div>

    <table class="receipt-table">
        <tr>
            <td>Received By:</td><td>N/A</td>
            <td>Signature:</td><td></td>
        </tr>
    </table>

    <div class="footer">
        <p>Thank You For Your Business</p>
        <div class="divider"></div>
        <p>PRINTED: <?= $printDate ?></p>
    </div>
</div>

<div class="print-button">
    <button onclick="window.print()" style="
        background:#00192D;color:#FFC107;padding:10px 25px;
        border:2px solid #FFC107;border-radius:8px;
        font-size:16px;font-weight:bold;cursor:pointer;
        margin:20px auto;display:block">
        Print Receipt
    </button>
</div>
</body>
</html>
