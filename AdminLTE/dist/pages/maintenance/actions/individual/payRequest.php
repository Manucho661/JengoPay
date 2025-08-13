<?php
include '../../../db/connect.php';
// To update balanceSheet when you pay through Cash
include __DIR__ . '/../../../financials/balanceSheet/actions/handleExpenses/handleOwnerContribution.php';
include __DIR__ . '/../../../financials/balanceSheet/actions/handleExpenses/handleRetainedEarnings.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $amount = $_POST['amountToPay'] ?? 0.00;

        // ✅ Start the transaction
        $pdo->beginTransaction();

        handleOwnerContribution($amount);
        handleRetainedEarnings($amount);

        // ✅ Commit changes
        $pdo->commit();

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        // ✅ Roll back only if transaction started
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        http_response_code(500); // Tell fetch it's a server error
        echo json_encode(["error" => $e->getMessage()]);
    }
}
