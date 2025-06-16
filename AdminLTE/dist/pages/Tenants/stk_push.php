<?php
include 'get_access_token.php';

$accessToken = getDarajaAccessToken();
$shortCode = '174379';
$passkey = 'FkOTOpThUHwXrwwEq3IOew+gqJRbRSpnjXFXz2u9Jf7sw2rkJme7ZxApL/3DUccU3KI5X9w3Xwl1ObpOLV+YWQT2EP1JUQGPn/65CSErScF1LuMCyZBwKjP8hVZs7ay2czdWthvQSICf551NOHllSQKSuAPieaTC6xfEpdApec3m8Xc340ZUV532RNJHaKKJNphuulYSe70C5BrmMCWBM80JXGjABksjRa2MWMA6GB9GFEqh0qIG5trQAsAKxuQy4RUZ2PcDXrd1sDUXJ8fGDzAP0kATzNCZ9sl5vhaxsQ3QMnicVZnJf4K9z/kjjqa4227I2gg1xC2k8lN5gw6CtQ==';
$timestamp = date('YmdHis');
$password = base64_encode($shortCode . $passkey . $timestamp);

$phone = '254713927050'; // Test phone
$amount = 10;
$callbackUrl = 'https://yourdomain.com/callback.php'; // Update when live

$stkPushPayload = [
    'BusinessShortCode' => $shortCode,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $phone,
    'PartyB' => $shortCode,
    'PhoneNumber' => $phone,
    'CallBackURL' => $callbackUrl,
    'AccountReference' => 'Rent Payment',
    'TransactionDesc' => 'Monthly Rent'
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
curl_close($ch);

echo $response;
