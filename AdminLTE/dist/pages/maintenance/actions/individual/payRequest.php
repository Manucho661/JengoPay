<?php
include '../../../db/connect.php';
// To update balanceSheet when you pay through Cash
include __DIR__ . '/../../../financials/balanceSheet/actions/handleCashPay.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
try{
$amount = $_POST['amountToPay'] ?? 0.00;
    handleCashPay($amount);
     $pdo->commit();

}
catch(Exception $e){
    $pdo->rollBack();
    http_response_code(500); // Tell fetch it's a server error
    echo json_encode(["error" => $e->getMessage()]);
}
}
