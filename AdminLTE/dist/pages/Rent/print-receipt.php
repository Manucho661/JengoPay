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
     body {
    font-family: 'Arial', sans-serif;
    background-color: #f5f5f5;
    text-align: center;
}

.receipt-box-centered {
    max-width: 600px;
    margin: 40px auto;
    padding: 30px;
    border-radius: 10px;
    border: 1px solid #ddd;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.receipt-header {
    width: 100%;
    background-color: #00192D;
    color: #FFC107;
    padding: 15px 0;
    border-radius: 8px;
}

.receipt-header h2 {
    margin: 0;
    font-size: 22px;
}

.receipt-details {
    margin: 20px 0;
}

.receipt-details p {
    margin: 5px 0;
    font-size: 15px;
}

.divider {
    width: 80%;
    height: 1px;
    background-color: #ccc;
    border: none;
    margin: 20px 0;
}

.receipt-table-centered {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.receipt-table-centered th,
.receipt-table-centered td {
    padding: 10px;
    border: 1px solid #ccc;
}

.receipt-table-centered th {
    background-color: #00192D;
    color: #FFC107;
}

.receipt-table-centered td:first-child {
    text-align: left;
}

.receipt-table-centered td:last-child {
    text-align: right;
}

    </style>
</head>
<body>
<div class="receipt-box-centered">
    <div class="receipt-header">
        <h2>Payment Receipt</h2>
    </div>

    <div class="receipt-details">
        <p><strong>Date:</strong> <?= $date ?></p>
        <p><strong>Tenant Name:</strong> <?= $name ?></p>
        <p><strong>Unit Code:</strong> <?= $unit ?></p>
    </div>

    <hr class="divider">

    <table class="receipt-table-centered">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (KSH)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rent</td>
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
        </tbody>
    </table>
<!-- </div> -->
<!-- </div> -->

<p>Thankyou for The Payment!</p>

    <!-- Branding Footer -->
<div style="margin-top: 50px; text-align: center;">
    <span style="font-family: Arial, sans-serif;">
        <b style="
            padding: 4px 10px;
            background-color: #FFC107;
            border: 2px solid #FFC107;
            border-top-left-radius: 5px;
            font-weight: bold;
            color: #00192D;
            display: inline-block;
        ">BT</b><b style="
            padding: 4px 10px;
            border: 2px solid #FFC107;
            border-bottom-right-radius: 5px;
            font-weight: bold;
            color: #FFC107;
            display: inline-block;
        ">JENGOPAY</b>
    </span>
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
        transition: background-color 0.3s ease, color 0.3s ease;
    "
    onmouseover="this.style.backgroundColor='#FFC107'; this.style.color='#00192D';"
    onmouseout="this.style.backgroundColor='#00192D'; this.style.color='#FFC107';"
    >Print Receipt</button>
</div>

</body>
</html>
