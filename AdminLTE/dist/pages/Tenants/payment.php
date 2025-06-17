<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$phone = $data['phone'] ?? '';
$amount = $data['amount'] ?? '';

if (!$phone || !$amount) {
    echo json_encode(['error' => 'Phone and amount are required']);
    exit;
}

include 'get_access_token.php';

$accessToken = getDarajaAccessToken();

$shortCode = '174379';
$passkey = 'YOUR_LIPA_NA_MPESA_PASSKEY'; // Replace with your actual passkey
$timestamp = date('YmdHis');
$password = base64_encode($shortCode . $passkey . $timestamp);
$callbackUrl = 'https://yourdomain.com/callback.php'; // Replace with your test callback or Ngrok URL

$stkPushPayload = [
    'BusinessShortCode' => $shortCode,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => (int)$amount,
    'PartyA' => $phone,
    'PartyB' => $shortCode,
    'PhoneNumber' => $phone,
    'CallBackURL' => $callbackUrl,
    'AccountReference' => 'Rent Payment',
    'TransactionDesc' => 'Paying Rent'
];

$ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stkPushPayload));

$response = curl_exec($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Output response to frontend
echo json_encode([
    'status' => $httpStatus,
    'response' => json_decode($response, true)
]);
