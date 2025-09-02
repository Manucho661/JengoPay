<?php
header('Content-Type: application/json');

// Get incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);
$phone = $data['phone'];
$amount = $data['amount'];

// Daraja sandbox credentials
$consumerKey = 'SjTrAk1B7iHo8yH1DZKsyzG9HKDLRlvwKGR3tBJniWEtkkNc';
$consumerSecret = 'aibliGXFpGjaZblaZTT7J5608uHSGFGZz4vAiDMxwv3rTyFKAynI5f9lMxAJtyq9';
$BusinessShortCode = '174379'; // Sandbox shortcode
$Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // Sandbox passkey

$Timestamp = date('YmdHis');
$Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

// Step 1: Get Access Token
$credentials = base64_encode($consumerKey . ':' . $consumerSecret);
$token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$curl = curl_init($token_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$token = json_decode($response)->access_token;

// Step 2: Initiate STK Push
$stk_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$curl_post_data = [
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $phone,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $phone,
    'CallBackURL' => 'https://yourdomain.com/callback.php', // Update this to your callback script
    'AccountReference' => 'RentPayment',
    'TransactionDesc' => 'Pay Rent via M-Pesa'
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $stk_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
$response = curl_exec($curl);
$resp = json_decode($response, true);

// Response to frontend
if (isset($resp['ResponseCode']) && $resp['ResponseCode'] == "0") {
    echo json_encode(['success' => true, 'message' => 'STK Push sent']);
} else {
    echo json_encode(['success' => false, 'message' => $resp['errorMessage'] ?? 'Unknown error']);
}
