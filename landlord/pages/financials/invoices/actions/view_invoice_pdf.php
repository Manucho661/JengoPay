<?php
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('../../../db/connect.php');

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

// Calculate totals
$subTotal   = 0;
$vatTotal   = 0;
$grandTotal = 0;
$lineRows   = '';

while ($item = $itemsStmt->fetch(PDO::FETCH_ASSOC)) {
    $qty       = (float)$item['quantity'];
    $price     = (float)$item['unit_price'];
    $tax       = (float)$item['taxes'];
    $lineTotal = (float)$item['total'];
    $vatLabel  = ucfirst($item['vat_type']);

    $subTotal   += $qty * $price;
    $vatTotal   += $tax;
    $grandTotal += $lineTotal;

    $lineRows .= '
        <tr style="height:35px;">
            <td width="16.7%" style="height:35px; border: 1px solid #FFC107; padding: 6px;">'
                .htmlspecialchars($item['description']).'</td>
            <td width="16.7%" style="height:35px; border: 1px solid #FFC107; text-align: right; padding: 6px;">'
                .$qty.'</td>
            <td width="16.7%" style="height:35px; border: 1px solid #FFC107; text-align: right; padding: 6px;">KES '
                .number_format($price, 2).'</td>
            <td width="16.7%" style="height:35px; border: 1px solid #FFC107; text-align: right; padding: 6px;">'
                .$vatLabel.'</td>
            <td width="16.7%" style="height:35px; border: 1px solid #FFC107; text-align: right; padding: 6px;">KES '
                .number_format($tax, 2).'</td>
            <td width="16.7%" style="height:35px; border: 1px solid #FFC107; text-align: right; padding: 6px;">KES '
                .number_format($qty * $price, 2).'</td>
        </tr>';
}

// Create PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document info
$pdf->SetCreator('Silver Spoon Towers');
$pdf->SetAuthor('Silver Spoon Towers');
$pdf->SetTitle('Invoice '.$inv['invoice_number']);
$pdf->SetSubject('Invoice');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Payment status
$paymentStatus = strtolower($inv['payment_status'] ?? 'unpaid');
$paymentText   = match ($paymentStatus) {
    'paid'    => 'Paid',
    'partial' => 'Partial',
    default   => 'Unpaid'
};

// HTML content
$html = '
<style>
    .header { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
    .table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .table th { background-color: #00192D; color: white; padding: 5px; border: 1px solid #FFC107; font-size: small; }
    .table td { padding: 5px; border: 1px solid #FFC107; }
    .text-end { text-align: right; }
    .total-row { font-weight: bold; }
    .info-box { background-color: #f0f0f0; padding: 10px; border-radius: 8px; }
    .date-box { border: 1px solid #FFC107; background-color: #FFF4CC; border-radius: 8px; }
</style>

<div style="position: relative;">

    <!-- Logo & Company Info -->
    <table width="100%">
        <tr>
            <td width="50%">
                <img src="expenseLogo6.png" alt="JengoPay Logo" style="height: 100px;">
            </td>
            <td width="50%" style="text-align: right;">
                <div class="info-box">
                    <strong>Silver Spoon Towers</strong><br>
                    50303 Nairobi, Kenya<br>
                    silver@gmail.com<br>
                    +254 700 123456
                </div>
            </td>
        </tr>
    </table>

    <!-- Invoice Info -->
    <table width="100%" style="margin-top: 20px;">
        <tr>
            <td width="50%" style="line-height: 1.2;">
                <div style="font-size: 11px;">
                    <div><strong>'.htmlspecialchars($inv['tenant_name']).'</strong></div>
                    <div>'.htmlspecialchars($inv['tenant_email']).'</div>
                    <div>'.htmlspecialchars($inv['tenant_phone']).'</div>
                    <div><strong>0713927050</strong></div>
                    <div><strong>B20</strong></div>
                </div>
            </td>
            <td width="50%" style="text-align: right; vertical-align: top;">
                <h3 style="margin: 0;"><strong>'.htmlspecialchars($inv['invoice_number']).'</strong></h3>
            </td>
        </tr>
    </table>
    
    <!-- Dates -->
    <table width="100%" style="margin: 10px 0;">
        <tr>
            <td width="100%">
                <div class="date-box" style="padding: 10px;">
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <span><strong>Invoice Date</strong></span><br>
                                '.date('d/m/Y', strtotime($inv['invoice_date'])).'
                            </td>
                            <td width="50%">
                                <span><strong>Due Date</strong></span><br>
                                '.date('d/m/Y', strtotime($inv['due_date'])).'
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="table">
        <thead>
            <tr style="height: 30px;">
                <th width="16.7%" style="height:30px;">Description</th>
                <th width="16.7%" style="height:30px;" class="text-end">Qty</th>
                <th width="16.7%" style="height:30px;" class="text-end">Unit Price</th>
                <th width="16.7%" style="height:30px;" class="text-end">VAT</th>
                <th width="16.7%" style="height:30px;" class="text-end">Taxes</th>
                <th width="16.7%" style="height:30px;" class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            '.$lineRows.'
        </tbody>
    </table>

    <!-- Totals -->
    <table width="100%" style="margin-top: 20px;">
        <tr>
            <td width="60%">
                <strong>Note:</strong><br>
                '.(!empty($inv['notes']) ? htmlspecialchars($inv['notes']) : 'This expense note belongs to Silver Spoon Towers.').'
            </td>
            <td width="40%">
                <table width="100%">
                    <tr>
                        <td><strong>Subtotal:</strong></td>
                        <td style="text-align: right;">KES '.number_format($subTotal, 2).'</td>
                    </tr>
                    <tr>
                        <td><strong>VAT (16%):</strong></td>
                        <td style="text-align: right;">KES '.number_format($vatTotal, 2).'</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Total Amount:</strong></td>
                        <td style="text-align: right;"><strong>KES '.number_format($grandTotal, 2).'</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
    <div style="text-align: center; font-size: 10px; color: #666;">
        Thank you for your business!
    </div>
</div>';

// Output PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('invoice_'.$inv['invoice_number'].'.pdf', 'D');
