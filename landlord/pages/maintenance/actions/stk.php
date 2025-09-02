<?php
// Receive JSON input
$data = json_decode(file_get_contents("php://input"));

$phone = $data->phone;
$amount = $data->amount;

// Your sandbox credentials
$consumerKey = 'qXXEGR4YssCVWHeE6oIrDLtLAfKXznBzJdjcr6AJmGJk7SfF';
$consumerSecret = '6R9Os9EUR32Gh9RJti1HNJ9cFClNZvPRgI8A1dYiGfPaFeOvPCiIJK7kfo1qMmeL';
$shortcode = '174379';
$passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$callbackURL = 'https://98b3-102-89-12-46.ngrok.io/mpesa_callback.php'; // or use ngrok

// Get access token
$credentials = base64_encode($consumerKey . ':' . $consumerSecret);
$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Basic $credentials"]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$accessToken = json_decode($response)->access_token;

// Prepare STK Push request
$timestamp = date("YmdHis");
$password = base64_encode($shortcode . $passkey . $timestamp);

$stkUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

$stkData = [
    'BusinessShortCode' => $shortcode,
    'Password' => $password,
    'Timestamp' => $timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $phone,
    'PartyB' => $shortcode,
    'PhoneNumber' => $phone,
    'CallBackURL' => $callbackURL,
    'AccountReference' => 'Test123',
    'TransactionDesc' => 'Payment test'
];

$stkHeaders = [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $stkUrl);
curl_setopt($curl, CURLOPT_HTTPHEADER, $stkHeaders);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($stkData));

$response = curl_exec($curl);
// echo $response;
curl_close($curl);

 echo json_encode(['message' => 'STK Push Sent', 'response' => json_decode($response)]);