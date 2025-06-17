<?php
// callback.php - handles Safaricom STK Push callbacks
header('Content-Type: application/json');

// 1. Get the callback JSON
$callbackJSONData = file_get_contents('php://input');
file_put_contents("mpesa_callback_response.json", $callbackJSONData . PHP_EOL, FILE_APPEND); // log it

$data = json_decode($callbackJSONData, true);

// 2. Extract relevant details
$resultCode = $data['Body']['stkCallback']['ResultCode'] ?? null;
$resultDesc = $data['Body']['stkCallback']['ResultDesc'] ?? null;
$amountPaid = $data['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'] ?? null;
$mpesaReceiptNumber = $data['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'] ?? null;
$phoneNumber = $data['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'] ?? null;

// 3. Proceed only if the payment was successful
if ($resultCode == 0) {
    // 4. Connect to the database
    include '../db/connect.php'; // This should set up the $pdo variable

    try {
      $stmt = $pdo->prepare("INSERT INTO payment_history
      (tenant_id, amount, payment_method, reference, status, created_at, payment_date, month, year, amount_paid, balances)
      VALUES (:tenant_id, :amount, :payment_method, :reference, :status, NOW(), :payment_date, :month, :year, :amount_paid, :balances)");

        $stmt->execute([
          'tenant_id' => 0, // Optional: Use actual tenant_id if matched from phone
          'amount' => $amountPaid,
          'payment_method' => 'mpesa',
          'reference' => $mpesaReceiptNumber,
          'status' => 'completed',
          'payment_date' => $today,
          'month' => $month,
          'year' => $year,
          'amount_paid' => $amountPaid,
          'balances' => 0  // You can calculate this if needed
        ]);
    } catch (PDOException $e) {
        file_put_contents("mpesa_db_errors.log", $e->getMessage() . PHP_EOL, FILE_APPEND);
    }
}

http_response_code(200); // Respond to Safaricom
