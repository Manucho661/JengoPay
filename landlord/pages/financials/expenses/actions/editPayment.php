<?php
header('Content-Type: application/json');
require_once '../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    $payment_id = $_POST['payment_id'] ?? null;
    $amount     = $_POST['amount'] ?? null;
    $date       = $_POST['payment_date'] ?? null;
    $account_id = $_POST['payment_account_id'] ?? null;
    $reference  = $_POST['reference'] ?? null;

    if (!$payment_id || !$amount || !$date || !$account_id) {
        echo json_encode([
            "success" => false,
            "message" => "Missing required fields",
            "received" => $_POST
        ]);
        exit;
    }

    // ✅ Update expense_payments
    $sql = "
        UPDATE expense_payments
        SET 
            amount_paid = :amount,
            payment_date = :payment_date,
            reference_no = :reference,
            payment_account_id = :account_id,
            date = NOW()
        WHERE id = :payment_id
    ";
    $stmt = $pdo->prepare($sql);
    $ok1 = $stmt->execute([
        ':amount'      => $amount,
        ':payment_date'=> $date,
        ':reference'   => $reference,
        ':account_id'  => $account_id,
        ':payment_id'  => $payment_id
    ]);

    // ✅ Update journal_lines → Credit bank/cash account
    $sqlCredit = "
        UPDATE journal_lines
        SET credit = :amount, debit = 0
        WHERE source_table_id = :payment_id
          AND account_id = :account_id
    ";
    $stmtC = $pdo->prepare($sqlCredit);
    $okC = $stmtC->execute([
        ':amount'     => $amount,
        ':payment_id' => $payment_id,
        ':account_id' => $account_id
    ]);

    // ✅ Update journal_lines → Debit the other side (e.g., A/P)
    $sqlDebit = "
        UPDATE journal_lines
        SET debit = :amount, credit = 0
        WHERE source_table_id = :payment_id
          AND account_id != :account_id
    ";
    $stmtD = $pdo->prepare($sqlDebit);
    $okD = $stmtD->execute([
        ':amount'     => $amount,
        ':payment_id' => $payment_id,
        ':account_id' => $account_id
    ]);

    echo json_encode([
        "success" => $ok1 && $okC && $okD,
        "message" => ($ok1 && $okC && $okD) 
            ? "Payment & Journal updated successfully" 
            : "One of the updates failed",
        "updated_id" => $payment_id
    ]);

} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
