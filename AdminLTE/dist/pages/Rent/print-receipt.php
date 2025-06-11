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

$name = htmlspecialchars($tenant['tenant_name']);
$unit = htmlspecialchars($tenant['unit_code']);
$amount = number_format((float)($tenant['amount_paid']), 2);
$penalty = number_format((float)($tenant['penalty']), 2);
$penaltyDays = (int)$tenant['penalty_days'];
$arrears = number_format((float)($tenant['arrears']), 2);
$overpayment = number_format((float)($tenant['overpayment']), 2);
$date = !empty($tenant['payment_date']) ? date("d F Y", strtotime($tenant['payment_date'])) : date("d F Y");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tenant Receipt</title>
    <style>
        body { font-family: Arial; padding: 40px; }
        .receipt-box {
            border: 1px solid #ccc;
            padding: 20px;
            width: 500px;
            margin: auto;
        }
        .receipt-box h2 { text-align: center; }
        .receipt-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .receipt-table th, .receipt-table td {
            padding: 8px; text-align: left;
        }
        .print-button {
            display: block; text-align: center; margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="receipt-box">
    <h2>Payment Receipt</h2>
    <p><strong>Date:</strong> <?= $date ?></p>
    <p><strong>Tenant:</strong> <?= $name ?></p>
    <p><strong>Unit:</strong> <?= $unit ?></p>

    <table class="receipt-table">
        <tr>
            <th>Description</th>
            <th>Amount (KSH)</th>
        </tr>
        <tr>
            <td>Amount Paid</td>
            <td><?= $amount ?></td>
        </tr>
        <tr>
            <td>Penalty (<?= $penaltyDays ?> late days)</td>
            <td><?= $penalty ?></td>
        </tr>
        <tr>
            <td>Arrears</td>
            <td><?= $arrears ?></td>
        </tr>
        <tr>
            <td>Overpayment</td>
            <td><?= $overpayment ?></td>
        </tr>
    </table>

    <div class="print-button">
        <button onclick="window.print()" style="color:#FFC107; background-color:#00192D;">Print</button>
    </div>
</div>
</body>
</html>
