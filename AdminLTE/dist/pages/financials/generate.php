<?php
require_once 'vendor/autoload.php'; // Require Composer autoload

use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $customerName = $_POST['customerName'];
    $invoiceNumber = $_POST['invoiceNumber'];
    $invoiceDate = $_POST['invoiceDate'];
    $paymentAccount = $_POST['paymentAccount'];
    $bankName = $_POST['bankName'];

    // Process items
    $items = [];
    $totalAmount = 0;
    $taxableAmount16 = 0;
    $taxableAmount0 = 0;

    foreach ($_POST['description'] as $index => $description) {
        $quantity = (float)$_POST['quantity'][$index];
        $unitPrice = (float)$_POST['unit_price'][$index];
        $taxType = $_POST['tax_type'][$index];
        $taxRate = (int)$_POST['tax_rate'][$index];
        $amount = $quantity * $unitPrice;

        $items[] = [
            'description' => $description,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'tax_type' => $taxType,
            'tax_rate' => $taxRate,
            'amount' => $amount
        ];

        $totalAmount += $amount;

        if ($taxRate == 16) {
            $taxableAmount16 += $amount;
        } else {
            $taxableAmount0 += $amount;
        }
    }

    $tax16 = $taxableAmount16 * 0.16;
    $untaxedAmount = $totalAmount - $tax16;

    // Create JSON structure
    $invoiceData = [
        'company' => [
            'name' => 'COBBY LOGISTICS COMPANY LIMITED',
            'address' => 'P.O Box 29332-00625',
            'district' => 'Nairobi West District',
            'country' => 'Kenya',
            'tagline' => 'Your Cargo, Our Priority',
            'email' => 'cobbylogisticscompanylimited@gmail.com',
            'license' => 'P052305684L'
        ],
        'invoice' => [
            'type' => 'Draft Invoice',
            'number' => $invoiceNumber,
            'date' => $invoiceDate,
            'customer' => $customerName,
            'items' => $items,
            'payment' => [
                'reference' => $invoiceNumber,
                'account_number' => $paymentAccount,
                'bank_name' => $bankName
            ],
            'totals' => [
                'untaxed_amount' => $untaxedAmount,
                'tax_breakdown' => [
                    [
                        'tax_rate' => 16,
                        'taxable_amount' => $taxableAmount16,
                        'tax_amount' => $tax16
                    ],
                    [
                        'tax_rate' => 0,
                        'taxable_amount' => $taxableAmount0,
                        'tax_amount' => 0
                    ]
                ],
                'total_amount' => $totalAmount
            ],
            'currency' => 'KSh'
        ]
    ];

    // Generate HTML from the data
    $html = generateInvoiceHtml($invoiceData);

    // Generate PDF
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Helvetica');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the generated PDF
    $dompdf->stream("invoice_{$invoiceNumber}.pdf", ["Attachment" => true]);
}

function generateInvoiceHtml($data) {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
            .header { margin-bottom: 20px; }
            .company-name { font-size: 18px; font-weight: bold; }
            .invoice-title { font-size: 20px; font-weight: bold; margin: 20px 0; }
            .customer { font-weight: bold; margin-bottom: 10px; }
            .invoice-date { text-align: right; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th { background-color: #555; color: white; padding: 8px; text-align: left; }
            td { padding: 8px; border: 1px solid #ddd; }
            .totals { text-align: right; margin-top: 20px; }
            .footer { margin-top: 50px; font-size: 12px; }
            .text-right { text-align: right; }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="company-name">' . htmlspecialchars($data['company']['name']) . '</div>
            <div>' . htmlspecialchars($data['company']['address']) . '</div>
            <div>' . htmlspecialchars($data['company']['district']) . ', ' . htmlspecialchars($data['company']['country']) . '</div>
            <div>' . htmlspecialchars($data['company']['tagline']) . '</div>
        </div>

        <div class="invoice-title">' . htmlspecialchars($data['invoice']['type']) . ' ' . htmlspecialchars($data['invoice']['number']) . '</div>

        <div class="customer">' . htmlspecialchars($data['invoice']['customer']) . '</div>
        <div class="invoice-date">Invoice Date: ' . htmlspecialchars($data['invoice']['date']) . '</div>

        <table>
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th>UNIT PRICE</th>
                    <th>TAXES</th>
                    <th class="text-right">AMOUNT</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($data['invoice']['items'] as $item) {
        $taxDisplay = ($item['tax_type'] == 'Exempt') ? 'Exempt' : $item['tax_rate'] . '%';
        $html .= '
                <tr>
                    <td>' . htmlspecialchars($item['description']) . '</td>
                    <td>' . number_format($item['quantity'], 2) . '</td>
                    <td>' . number_format($item['unit_price'], 2) . '</td>
                    <td>' . $taxDisplay . '</td>
                    <td class="text-right">' . number_format($item['amount'], 2) . ' ' . htmlspecialchars($data['invoice']['currency']) . '</td>
                </tr>';
    }

    $html .= '
            </tbody>
        </table>

        <div>Payment Communication: ' . htmlspecialchars($data['invoice']['payment']['reference']) . ' on this account: ' . htmlspecialchars($data['invoice']['payment']['account_number']) . ' - ' . htmlspecialchars($data['invoice']['payment']['bank_name']) . '</div>

        <div class="totals">
            <div>Untaxed Amount: ' . number_format($data['invoice']['totals']['untaxed_amount'], 2) . ' ' . htmlspecialchars($data['invoice']['currency']) . '</div>';

    foreach ($data['invoice']['totals']['tax_breakdown'] as $tax) {
        $html .= '
            <div>VAT ' . $tax['tax_rate'] . '% on ' . number_format($tax['taxable_amount'], 2) . ' ' . htmlspecialchars($data['invoice']['currency']) . ': ' . number_format($tax['tax_amount'], 2) . ' ' . htmlspecialchars($data['invoice']['currency']) . '</div>';
    }

    $html .= '
            <div style="font-weight: bold;">Total: ' . number_format($data['invoice']['totals']['total_amount'], 2) . ' ' . htmlspecialchars($data['invoice']['currency']) . '</div>
        </div>

        <div class="footer">
            <div>' . htmlspecialchars($data['company']['email']) . ' ' . htmlspecialchars($data['company']['license']) . '</div>
            <div>Page 1 / 1</div>
        </div>
    </body>
    </html>';

    return $html;
}