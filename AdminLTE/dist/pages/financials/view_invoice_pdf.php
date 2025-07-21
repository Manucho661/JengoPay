<?php
// PHP can fetch data from a database or define it directly
$invoiceData = [
    'invoiceDate' => '07/16/2025',
    'invoiceNumber' => 'INV/2025/00013',
    'clientName' => 'Abigael Michael',
    'services' => [
        ['description' => 'Rental Income', 'quantity' => 1.00, 'unitPrice' => 20000.00, 'taxes' => '16%', 'amount' => 20000.00],
        ['description' => 'Garbage', 'quantity' => 1.00, 'unitPrice' => 5000.00, 'taxes' => '0%', 'amount' => 5000.00],
        ['description' => 'Water Charges', 'quantity' => 1.00, 'unitPrice' => 1000.00, 'taxes' => 'Exempt', 'amount' => 1000.00],
        ['description' => 'Electricity', 'quantity' => 1.00, 'unitPrice' => 5000.00, 'taxes' => '16%', 'amount' => 5000.00]
    ],
    'summary' => [
        'untaxedAmount' => 31000.00,
        'vat16Percent' => 4000.00,
        'vat0Percent' => 0.00,
        'total' => 35000.00
    ]
];

// Helper function to format currency
function formatCurrency($amount) {
    return number_format($amount, 2) . ' KSh';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo $invoiceData['invoiceNumber']; ?></title>
    <style>
        /* CSS styling as provided in the HTML section above */
        body { font-family: Arial, sans-serif; margin: 20px; }
        .invoice-container { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header, .footer { text-align: center; margin-bottom: 20px; }
        .company-info, .client-info { margin-bottom: 20px; }
        .invoice-details { display: flex; justify-content: space-between; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary-table { width: 50%; margin-left: auto; }
        .summary-table td:first-child { font-weight: bold; }
        .total-row { background-color: #f2f2f2; font-weight: bold; }
        .payment-info { margin-top: 20px; }
        hr { border: none; border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>COBBY LOGISTICS COMPANY LIMITED</h1>
            <p>P.O Box 29332-00625<br>Nairobi West District<br>Kenya</p>
            <p>cobbylogisticscompanylimited@gmail.com P052305684L</p>
        </div>

        <hr>

        <div class="invoice-details">
            <div>
                <strong>Invoice Date:</strong> <span id="invoiceDate"><?php echo $invoiceData['invoiceDate']; ?></span>
            </div>
            <div>
                <strong>Draft Invoice:</strong> <span id="invoiceNumber"><?php echo $invoiceData['invoiceNumber']; ?></span>
            </div>
        </div>

        <div class="client-info">
            <strong>Bill To:</strong><br>
            <span id="clientName"><?php echo $invoiceData['clientName']; ?></span>
        </div>

        <hr>

        <h3>Service Details</h3>
        <table>
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th>UNIT PRICE</th>
                    <th>TAXES</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody id="serviceDetailsBody">
                <?php foreach ($invoiceData['services'] as $service): ?>
                <tr>
                    <td><?php echo $service['description']; ?></td>
                    <td><?php echo number_format($service['quantity'], 2); ?></td>
                    <td><?php echo number_format($service['unitPrice'], 2); ?></td>
                    <td><?php echo $service['taxes']; ?></td>
                    <td><?php echo formatCurrency($service['amount']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <h3>Summary of Charges</h3>
        <table class="summary-table">
            <tr>
                <td>Untaxed Amount:</td>
                <td><?php echo formatCurrency($invoiceData['summary']['untaxedAmount']); ?></td>
            </tr>
            <tr>
                <td>VAT 16% on 25,000.00 KSh:</td>
                <td><?php echo formatCurrency($invoiceData['summary']['vat16Percent']); ?></td>
            </tr>
            <tr>
                <td>VAT 0% on 6,000.00 KSh:</td>
                <td><?php echo formatCurrency($invoiceData['summary']['vat0Percent']); ?></td>
            </tr>
            <tr class="total-row">
                <td>Total:</td>
                <td><?php echo formatCurrency($invoiceData['summary']['total']); ?></td>
            </tr>
        </table>

        <hr>

        <h3>Payment Information</h3>
        <p>Please reference <strong id="paymentReference"><?php echo $invoiceData['invoiceNumber']; ?></strong> for payment.</p>
        <p>Deposit to account: <strong>95960200000555 - BANK OF BARODA</strong></p>

        <div class="footer">
            <p>Page 1/1</p>
        </div>
    </div>
</body>
</html>