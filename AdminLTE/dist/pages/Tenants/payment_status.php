<?php
include '../db/connect.php';

$phone = $_GET['phone'] ?? '';

if (!$phone) {
    echo "Missing phone number.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM payments WHERE phone = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$phone]);
$payment = $stmt->fetch();

if ($payment) {
    echo "Last payment: " . $payment['amount'] . " - Status: " . $payment['status'];
} else {
    echo "No payment found for this phone.";
}
