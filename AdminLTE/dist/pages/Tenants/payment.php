<?php
require_once 'Daraja.php';
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['phone']) || empty($data['amount'])) {
        throw new Exception("Missing phone or amount");
    }

    $phone = preg_replace('/^0/', '254', $data['phone']);
    $phone = preg_replace('/^\+/', '', $phone);
    if (!preg_match('/^254[0-9]{9}$/', $phone)) {
        throw new Exception("Invalid phone number format");
    }

    $daraja = new Daraja([
        'consumer_key' => 'bCtzLBEV62ACBqGBZzUzhFHv5q4t7OIB',
        'consumer_secret' => 'rm0se3NF1sNsAMBi',
        'business_shortcode' => '174379',
        'lipa_na_mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2c2c5105b7b7c2d72bd1c1c8b8f1d9bd',
        'callback_url' => 'https://webhook.site/your-unique-id' // Replace with your test webhook URL
    ]);

    $response = $daraja->stkPush(
        $phone,
        $data['amount'],
        'RENT202506', // account ref
        'Rent Payment June 2025'
    );

    echo json_encode([
        'success' => true,
        'response' => $response
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
