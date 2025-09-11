<?php
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php';
require_once __DIR__ . '/../../../db/connect.php';

// Get invoice ID
$id = $_GET['id'] ?? 0;
if (!$id) {
    die('Invoice ID not provided');
}

// Fetch invoice data
$info = $pdo->prepare("
    SELECT
        i.*,
        CONCAT(u.first_name, ' ', u.middle_name) AS tenant_name,
        u.email AS tenant_email,
        u.phone AS tenant_phone
    FROM invoice i
    LEFT JOIN users u ON i.tenant = u.id
    WHERE i.id = ?
");
$info->execute([$id]);
$inv = $info->fetch(PDO::FETCH_ASSOC);

if (!$inv) {
    die('Invoice not found');
}

// Fetch line items
$itemsStmt = $pdo->prepare("
    SELECT description, quantity, unit_price, vat_type, taxes, total 
    FROM invoice_items 
    WHERE invoice_number = ?
");
$itemsStmt->execute([$inv['invoice_number']]);

// Totals
$subTotal = $vatTotal = $grandTotal = 0;
$lineRows = '';

while ($item = $itemsStmt->fetch(PDO::FETCH_ASSOC)) {
    $qty = (float)$item['quantity'];
    $price = (float)$item['unit_price'];
    $tax = (float)$item['taxes'];
    $lineTotal = (float)$item['total'];
    $vatLabel = ucfirst($item['vat_type']);

    $subTotal += $qty * $price;
    $vatTotal += $tax;
    $grandTotal += $lineTotal;

    $lineRows .= '
        <tr>
            <td style="border: 1px solid #FFC107; padding: 4px;">'.htmlspecialchars($item['description']).'</td>
            <td style="border: 1px solid #FFC107; padding: 4px; text-align: right;">'.$qty.'</td>
            <td style="border: 1px solid #FFC107; padding: 4px; text-align: right;">KES '.number_format($price, 2).'</td>
            <td style="border: 1px solid #FFC107; padding: 4px; text-align: right;">'.$vatLabel.'</td>
            <td style="border: 1px solid #FFC107; padding: 4px; text-align: right;">KES '.number_format($tax, 2).'</td>
            <td style="border: 1px solid #FFC107; padding: 4px; text-align: right;">KES '.number_format($lineTotal, 2).'</td>
        </tr>';
}

// Create PDF
$pdf = new TCPDF();
$pdf->SetCreator('Silver Spoon Towers');
$pdf->SetAuthor('Silver Spoon Towers');
$pdf->SetTitle('Invoice '.$inv['invoice_number']);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Payment status
$paymentStatus = strtolower($inv['payment_status'] ?? 'unpaid');
$paymentText = match($paymentStatus) {
    'paid' => 'Paid',
    'partial' => 'Partial',
    default => 'Unpaid'
};

// ✅ Optional: Add watermark
if ($paymentText) {
    $pdf->SetFont('helvetica', 'B', 40);
    $pdf->SetTextColor(200, 0, 0, 30); // faded red
    $pdf->StartTransform();
    $pdf->Rotate(45, 60, 190); // rotation angle
    $pdf->Text(35, 190, strtoupper($paymentText));
    $pdf->StopTransform();
}

// HTML content
$html = '
<style>
    .table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .table th { background-color: #00192D; color: #fff; padding: 5px; border: 1px solid #FFC107; font-size: small; }
    .table td { padding: 5px; border: 1px solid #FFC107; }
    .text-end { text-align: right; }
    .total-row { font-weight: bold; }
</style>

<table width="100%">
    <tr>
        <td width="50%">
            <img src="expenseLogo6.png" style="height: 80px;">
        </td>
        <td width="50%" style="text-align: right;">
            <strong>Silver Spoon Towers</strong><br>
            50303 Nairobi, Kenya<br>
            silver@gmail.com<br>
            +254 700 123456
        </td>
    </tr>
</table>

<table width="100%" style="margin-top: 20px;">
    <tr>
        <td width="50%"><strong>'.htmlspecialchars($inv['tenant_name']).'</strong></td>
        <td width="50%" style="text-align: right;">
            <h3>'.htmlspecialchars($inv['invoice_number']).'</h3>
        </td>
    </tr>
</table>

<table width="100%" style="margin: 10px 0;">
    <tr>
        <td><strong>Invoice Date:</strong> '.date('d/m/Y', strtotime($inv['invoice_date'])).'</td>
        <td style="text-align: right;"><strong>Due Date:</strong> '.date('d/m/Y', strtotime($inv['due_date'])).'</td>
    </tr>
</table>

<table class="table">
    <thead>
        <tr>
            <th>Description</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Unit Price</th>
            <th class="text-end">VAT</th>
            <th class="text-end">Taxes</th>
            <th class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        '.$lineRows.'
    </tbody>
</table>

<table width="100%" style="margin-top: 20px;">
    <tr>
        <td width="60%">
            <strong>Note:</strong><br>
            '.(!empty($inv['notes']) ? htmlspecialchars($inv['notes']) : 'This expense note belongs to Silver Spoon Towers.').'
        </td>
        <td width="40%">
            <table width="100%">
                <tr><td><strong>Subtotal:</strong></td><td class="text-end">KES '.number_format($subTotal, 2).'</td></tr>
                <tr><td><strong>VAT (16%):</strong></td><td class="text-end">KES '.number_format($vatTotal, 2).'</td></tr>
                <tr class="total-row"><td><strong>Total Amount:</strong></td><td class="text-end">KES '.number_format($grandTotal, 2).'</td></tr>
            </table>
        </td>
    </tr>
</table>

<hr>
<div style="text-align:center; font-size:10px; color:#666;">Thank you for your business!</div>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('invoice_'.$inv['invoice_number'].'.pdf', 'D'); // D = download, I = inline view
