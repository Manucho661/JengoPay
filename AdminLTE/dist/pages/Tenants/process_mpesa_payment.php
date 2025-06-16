<?php
include '../db/connect.php';

require_once 'Daraja.php'; // Replace with your Daraja SDK

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (empty($data['phone']) || empty($data['amount']) || empty($data['tenant_id'])) {
        throw new Exception("Missing required fields");
    }

    // Format phone number (ensure 254 format)
    $phone = preg_replace('/^0/', '254', $data['phone']);
    $phone = preg_replace('/^\+/', '', $phone);

    if (!preg_match('/^254[0-9]{9}$/', $phone)) {
        throw new Exception("Invalid phone number format");
    }

    // Initialize Daraja API
    $daraja = new Daraja([
      'consumer_key' => 'bCtzLBEV62ACBqGBZzUzhFHv5q4t7OIB',
      'consumer_secret' => 'rm0se3NF1sNsAMBi',
      'business_shortcode' => '174379', // Standard sandbox PayBill number
      'lipa_na_mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2c2c5105b7b7c2d72bd1c1c8b8f1d9bd',
      'callback_url' => 'https://webhook.site/your-unique-id' // or your actual public endpoint
    ]);


    // Initiate STK push
    $response = $daraja->stkPush(
        $phone,
        $data['amount'],
        'RENT' . date('Ym'), // Account reference
        'Rent Payment for ' . $data['month'] . ' ' . $data['year'] // Transaction description
    );

    if ($response['ResponseCode'] !== '0') {
        throw new Exception($response['ResponseDescription'] ?? 'Failed to initiate payment');
    }

    // Save transaction to database
    $stmt = $pdo->prepare("INSERT INTO mpesa_transactions
                          (tenant_id, phone, amount, checkout_request_id, merchant_request_id, status, created_at)
                          VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([
        $data['tenant_id'],
        $phone,
        $data['amount'],
        $response['CheckoutRequestID'],
        $response['MerchantRequestID']
    ]);

    echo json_encode([
        'success' => true,
        'checkoutRequestID' => $response['CheckoutRequestID'],
        'message' => 'Payment initiated successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}