<?php
// Invoice data (you could also fetch this from a database)
$invoiceData = [
    'company_name' => 'COBBY LOGISTICS COMPANY LIMITED',
    'address' => 'P.O Box 29332-00625',
    'district' => 'Nairobi West District',
    'country' => 'Kenya',
    'tagline' => 'Your Cargo, Our Priority',
    'invoice_number' => 'INV/2025/00013',
    'customer_name' => 'Abigael Michael',
    'invoice_date' => '07/16/2025',
    'items' => [
        ['description' => 'Rental Income', 'quantity' => '1.00', 'unit_price' => '20,000.00', 'tax' => '16%', 'amount' => '20,000.00 KSh'],
        ['description' => 'Garbage', 'quantity' => '1.00', 'unit_price' => '5,000.00', 'tax' => '0%', 'amount' => '5,000.00 KSh'],
        ['description' => 'Water Charges', 'quantity' => '1.00', 'unit_price' => '1,000.00', 'tax' => 'Exempt', 'amount' => '1,000.00 KSh'],
        ['description' => 'Electricity', 'quantity' => '1.00', 'unit_price' => '5,000.00', 'tax' => '16%', 'amount' => '5,000.00 KSh'],
    ],
    'payment_communication' => 'INV/2025/00013 on this account: 95960200000555 - BANK OF BARODA',
    'untaxed_amount' => '31,000.00 KSh',
    'vat_16' => ['base' => '25,000.00 KSh', 'amount' => '4,000.00 KSh'],
    'vat_0' => ['base' => '6,000.00 KSh', 'amount' => '0.00 KSh'],
    'total' => '35,000.00 KSh',
    'email' => 'cobbylogisticscompanylimited@gmail.com',
    'code' => 'P052305684L'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo $invoiceData['invoice_number']; ?></title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            color: #000;
            font-size: 12pt;
            line-height: 1.2;
        }
        .container {
            width: 210mm;
            margin: 0 auto;
            padding: 10mm;
        }
        .company-header {
            text-align: center;
            margin-bottom: 10mm;
        }
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }
        .address {
            font-size: 10pt;
            margin-bottom: 1mm;
        }
        .tagline {
            font-style: italic;
            font-size: 10pt;
            margin-top: 3mm;
        }
        .invoice-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8mm;
            text-decoration: underline;
        }
        .customer-name {
            font-weight: bold;
            margin-bottom: 5mm;
            font-size: 11pt;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8mm;
            font-size: 10pt;
        }
        .invoice-table th {
            border: 0.5pt solid #000;
            padding: 2mm;
            text-align: left;
            font-weight: bold;
            background-color: #fff;
        }
        .invoice-table td {
            border: 0.5pt solid #000;
            padding: 2mm;
            text-align: left;
        }
        .totals-table {
            width: 60%;
            margin-left: auto;
            margin-bottom: 8mm;
            font-size: 10pt;
        }
        .totals-table td {
            padding: 1mm 0;
        }
        .totals-table .label {
            text-align: right;
            padding-right: 5mm;
        }
        .payment-info {
            margin-bottom: 5mm;
            font-size: 10pt;
            font-weight: bold;
        }
        .footer {
            margin-top: 10mm;
            font-size: 10pt;
            text-align: center;
        }
        .page-info {
            font-size: 10pt;
            text-align: right;
            margin-top: 5mm;
        }
        .invoice-date {
            text-align: right;
            margin-bottom: 3mm;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="company-header">
            <div class="company-name"><?php echo $invoiceData['company_name']; ?></div>
            <div class="address"><?php echo $invoiceData['address']; ?></div>
            <div class="address"><?php echo $invoiceData['district']; ?></div>
            <div class="address"><?php echo $invoiceData['country']; ?></div>
            <div class="tagline"><?php echo $invoiceData['tagline']; ?></div>
        </div>

        <div class="invoice-title"># Draft Invoice <?php echo $invoiceData['invoice_number']; ?></div>

        <div class="invoice-date">Invoice Date <?php echo $invoiceData['invoice_date']; ?></div>

        <div class="customer-name"><?php echo $invoiceData['customer_name']; ?></div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th>UNIT PRICE</th>
                    <th>TAXES</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoiceData['items'] as $item): ?>
                <tr>
                    <td><?php echo $item['description']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['unit_price']; ?></td>
                    <td><?php echo $item['tax']; ?></td>
                    <td><?php echo $item['amount']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="payment-info">Payment Communication: <?php echo $invoiceData['payment_communication']; ?></div>

        <table class="totals-table">
            <tr>
                <td class="label">Untaxed Amount</td>
                <td><?php echo $invoiceData['untaxed_amount']; ?></td>
            </tr>
            <tr>
                <td class="label">VAT 16% on <?php echo $invoiceData['vat_16']['base']; ?></td>
                <td><?php echo $invoiceData['vat_16']['amount']; ?></td>
            </tr>
            <tr>
                <td class="label">VAT 0% on <?php echo $invoiceData['vat_0']['base']; ?></td>
                <td><?php echo $invoiceData['vat_0']['amount']; ?></td>
            </tr>
            <tr>
                <td class="label">Total</td>
                <td><?php echo $invoiceData['total']; ?></td>
            </tr>
        </table>

        <div class="footer">
            <?php echo $invoiceData['email']; ?> <?php echo $invoiceData['code']; ?>
        </div>

        <div class="page-info">
            Page 1 / 1
        </div>
    </div>
</body>
</html>