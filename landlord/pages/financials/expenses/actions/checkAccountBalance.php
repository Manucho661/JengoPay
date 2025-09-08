<?php

include '../../../db/connect.php';

header('Content-Type: application/json');

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    if (!isset($_GET['account_id']) || !isset($_GET['amount'])) {
        echo json_encode(["error" => "Missing account_id or amount"]);
        exit;
    }
    $accountId = intval($_GET['account_id']);
    $amountToPay = floatval($_GET['amount']); // amount user is trying to pay


    // ✅ Calculate balance
    $sql = "
        SELECT 
            COALESCE(SUM(debit), 0) AS total_debit, 
            COALESCE(SUM(credit), 0) AS total_credit
        FROM journal_lines
        WHERE account_id = :account_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([":account_id" => $accountId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $balance = $row['total_debit'] - $row['total_credit'];


    // ✅ Compare balance with payment amount
    $isEnough = $balance >= $amountToPay;

    echo json_encode([
        "balance" => $balance,
        "total_debit" => $row['total_debit'],
        "total_credit" => $row['total_credit'],
        "amount_requested" => $amountToPay,
        "is_enough" => $isEnough,
        "error" => null
    ]);
} catch (Throwable $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
