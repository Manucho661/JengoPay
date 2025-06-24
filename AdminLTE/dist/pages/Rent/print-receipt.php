<?php
include '../db/connect.php';

$tenantId = $_GET['tenant_id'] ?? null;

if (!$tenantId) {
    echo "Invalid tenant ID.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tenant_rent_summary WHERE id = ?");
$stmt->execute([$tenantId]);
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tenant) {
    echo "Tenant not found.";
    exit;
}

// Format data
$name = htmlspecialchars($tenant['tenant_name']);
$unit = htmlspecialchars($tenant['unit_code']);
$property = htmlspecialchars($tenant['property_name'] ?? 'XXX');
$amount = number_format((float)($tenant['amount_paid']), 2);
$penalty = number_format((float)($tenant['penalty']), 2);
$penaltyDays = (int)$tenant['penalty_days'];
$arrears = number_format((float)($tenant['arrears']), 2);
$overpayment = number_format((float)($tenant['overpayment']), 2);
$balance = number_format((float)($tenant['balances']), 2);
$paymentMode = htmlspecialchars($tenant['payment_mode'] ?? 'Mpesa');
$reference = htmlspecialchars($tenant['reference_number'] ?? 'TCO2X12E80');
$date = !empty($tenant['payment_date']) ? date("d/m/Y", strtotime($tenant['payment_date'])) : date("d/m/Y");
$printDate = date("d/m/Y H:i");
$receiptNo = "RC".str_pad($tenantId, 5, '0', STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tenant Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: white;
        }
        .receipt-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
        }
        .company-header {
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        .company-header h1 {
            font-size: 18px;
            margin: 5px 0;
            color: black;
        }
        .company-header p {
            font-size: 12px;
            margin: 2px 0;
            color: black;
        }
        .receipt-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid black;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 14px;
        }
        .receipt-table td {
            padding: 5px;
            border: none;
            vertical-align: top;
        }
        .receipt-table td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .amount-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 14px;
        }
        .amount-table td {
            padding: 5px;
            border: none;
        }
        .amount-table td:last-child {
            text-align: right;
        }
        .divider {
            border-top: 1px dashed black;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }
        .signature-line {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        @media print {
            .print-button {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
<div class="receipt-container">
    <div class="company-header">
        <h1>SUNLAND REAL ESTATES LTD</h1>
        <p>P.O BOX 37987 - 00100 - 8TH FLOOR</p>
        <p>INTERNATIONAL LIFE HSE, MAMA NGINA ST.</p>
        <p>TEL: 0733717726, 0202136440, 0202225507/8,</p>
        <p>EMAIL: PROPERTYMANAGEMENT@SUNLAND.CO.KE</p>
    </div>

    <div class="divider"></div>

    <div class="receipt-title">RECEIPT</div>

    <table class="receipt-table">
        <tr>
            <td>Received From:</td>
            <td><?= $name ?></td>
            <td>Receipt No.:</td>
            <td><?= $receiptNo ?></td>
        </tr>
        <tr>
            <td>A/c NO:</td>
            <td>TID<?= $tenantId ?></td>
            <td>Date:</td>
            <td><?= $date ?></td>
        </tr>
        <tr>
            <td>Unit No:</td>
            <td><?= $unit ?></td>
            <td>Payment Mode:</td>
            <td><?= $paymentMode ?></td>
        </tr>
        <tr>
            <td>Property:</td>
            <td><?= $property ?></td>
            <td>Reference No.:</td>
            <td><?= $reference ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Amount (KES):</td>
            <td><?= $amount ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Balance:</td>
            <td><?= $balance ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="receipt-title">DESCRIPTION</div>

    <table class="amount-table">
        <tr>
            <td>Water</td>
            <td></td>
        </tr>
        <tr>
            <td>Rent prepayment</td>
            <td><?= $amount ?></td>
        </tr>
        <tr>
            <td></td>
            <td><?= $balance ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="receipt-table">
        <tr>
            <td>Received By:</td>
            <td>N/A</td>
            <td>Signature:</td>
            <td></td>
        </tr>
    </table>

    <div class="footer">
        <p>Thank You For Your Business</p>
        <div class="divider"></div>
        <p>PRINTED: <?= $printDate ?></p>
    </div>
</div>

<div class="print-button">
    <button onclick="window.print()" style="
        background-color: #00192D;
        color: #FFC107;
        padding: 10px 25px;
        border: 2px solid #FFC107;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin: 20px auto;
        display: block;
    ">Print Receipt</button>
</div>
</body>
</html>