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
$property = htmlspecialchars($tenant['building_name'] ?? 'XXX');
$amount = number_format((float)($tenant['amount_paid']), 2);
$penalty = number_format((float)($tenant['penalty']), 2);
$penaltyDays = (int)$tenant['penalty_days'];
$arrears = number_format((float)($tenant['arrears']), 2);
$overpayment = number_format((float)($tenant['overpayment']), 2);
$rawBalance = (float)$tenant['balances'];
$balanceLabel = $rawBalance < 0 ? 'Overpayment' : 'Balance';
$formattedBalance = number_format(abs($rawBalance), 2);
$paymentMode = htmlspecialchars($tenant['payment_mode'] ?? 'Mpesa');
$reference = htmlspecialchars($tenant['reference_number'] ?? 'TCO2X12E80');
$date = !empty($tenant['payment_date']) ? date("d/m/Y", strtotime($tenant['payment_date'])) : date("d/m/Y");
$printDate = date("d/m/Y H:i");
$receiptNo = "RC" . str_pad($tenantId, 5, '0', STR_PAD_LEFT);

// Use tenant-specific A/C NO or fallback to unit
$accountNo = !empty($tenant['account_no']) ? htmlspecialchars($tenant['account_no']) : $unit;

// Calculate total: amount paid + penalty + arrears
$total = (float)$tenant['amount_paid'] + (float)$tenant['penalty'] + (float)$tenant['arrears'];

// Add balance only if it's a positive balance (not overpayment)
if ($rawBalance > 0) {
    $total += $rawBalance;
}

// Use total as the main amount for display
$totalAmountFormatted = number_format($total, 2);
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
        }

        .company-header {
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .company-header h1 {
            font-size: 18px;
            margin: 5px 0;
        }

        .company-header p {
            font-size: 12px;
            margin: 2px 0;
        }

        .receipt-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .receipt-table {
            margin: 10px 0;
            font-size: 13px;
        }

        .receipt-table td {
            padding: 2px 5px;
            white-space: nowrap;
        }

        .receipt-table td:first-child,
        .receipt-table td:nth-child(3) {
            font-weight: bold;
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
        <h1>BT JENGOPAY</h1>
        <p>P.O BOX 37987 - 00100 - 8TH FLOOR</p>
        <p>INTERNATIONAL LIFE HSE, MAMA NGINA ST.</p>
        <p>TEL: 0733717726</p>
        <p>EMAIL: PROPERTYMANAGEMENT@BTJENGOPAY.CO.KE</p>
    </div>

    <div class="divider"></div>

    <div class="receipt-title">RECEIPT</div>

    <table class="receipt-table">
        <tr>
            <td>Received From:</td>
            <td><?= $name ?></td>
            <td>Receipt No:</td>
            <td><?= $receiptNo ?></td>
        </tr>
        <tr>
            <td>A/c NO:</td>
            <td><?= $accountNo ?></td>
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
            <td>Reference No:</td>
            <td><?= $reference ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Amount (KES):</td>
            <td><?= $totalAmountFormatted ?></td> <!-- Updated this to totalAmountFormatted -->
        </tr>
    </table>

    <div class="divider"></div>

    <div class="receipt-title">DESCRIPTION</div>

    <table class="amount-table">
        <tr>
            <td>Rent Payment</td>
            <td><?= $amount ?></td>
        </tr>
        <tr>
            <td>Penalty (<?= $penaltyDays ?> days)</td>
            <td><?= $penalty ?></td>
        </tr>
        <tr>
            <td>Arrears</td>
            <td><?= $arrears ?></td>
        </tr>
        <tr>
            <td><?= $balanceLabel ?></td>
            <td><?= $formattedBalance ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="amount-table">
        <tr>
            <td>TOTAL (KES)</td>
            <td><?= $totalAmountFormatted ?></td>
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
