<?php
// process_mpesa_payment.php

// Database connection and authentication checks should be here
require_once '../db/connect.php';

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($data['phone']) || empty($data['amount']) || empty($data['tenant_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// M-Pesa API credentials (should be stored securely, not hardcoded)
$consumerKey = 'SjTrAk1B7iHo8yH1DZKsyzG9HKDLRlvwKGR3tBJniWEtkkNc';
$consumerSecret = 'aibliGXFpGjaZblaZTT7J5608uHSGFGZz4vAiDMxwv3rTyFKAynI5f9lMxAJtyq9';
$businessShortCode = '174379';
$lipaNaMpesaPasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$callbackUrl = 'https://yourdomain.com/callback.php';

// 1. Get access token
$accessToken = getAccessToken($consumerKey, $consumerSecret);

// 2. Initiate STK push
$checkoutRequestID = initiateSTKPush(
    $accessToken,
    $businessShortCode,
    $lipaNaMpesaPasskey,
    $data['amount'],
    $data['phone'],
    $callbackUrl
);

// Save the transaction to your database
try {
    $stmt = $pdo->prepare("INSERT INTO mpesa_transactions
                          (tenant_id, phone, amount, checkout_request_id, status, created_at)
                          VALUES (?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([
        $data['tenant_id'],
        $data['phone'],
        $data['amount'],
        $checkoutRequestID
    ]);

    echo json_encode([
        'success' => true,
        'checkoutRequestID' => $checkoutRequestID,
        'message' => 'Payment initiated successfully'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

function getAccessToken($consumerKey, $consumerSecret) {
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $credentials = base64_encode($consumerKey . ':' . $consumerSecret);

    $headers = [
        'Authorization: Basic ' . $credentials
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response);
    return $data->access_token ?? null;
}

function initiateSTKPush($accessToken, $businessShortCode, $passkey, $amount, $phone, $callbackUrl) {
    $timestamp = date('YmdHis');
    $password = base64_encode($businessShortCode . $passkey . $timestamp);

    $payload = [
        'BusinessShortCode' => $businessShortCode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $phone,
        'PartyB' => $businessShortCode,
        'PhoneNumber' => $phone,
        'CallBackURL' => $callbackUrl,
        'AccountReference' => 'PropertyProRent',
        'TransactionDesc' => 'Rent Payment'
    ];

    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response);
    return $data->CheckoutRequestID ?? null;
}
?>