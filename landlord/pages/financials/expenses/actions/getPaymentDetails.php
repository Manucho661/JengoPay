<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Get expense_id from query string
    if (!isset($_GET['expense_id']) || empty($_GET['expense_id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No expense_id provided'
        ]);
        exit;
    }

    $expenseId = $_GET['expense_id'];

    // Prepare and execute the query with JOIN to fetch account_name from chart_of_accounts
    $stmt = $pdo->prepare("
        SELECT ep.*, coa.account_name
        FROM expense_payments ep
        LEFT JOIN chart_of_accounts coa ON ep.payment_account_id = coa.account_code
        WHERE ep.expense_id = :expense_id
    ");
    $stmt->execute(['expense_id' => $expenseId]);

    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($payments) {
        echo json_encode([
            'status' => 'success',
            'details' => $payments
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No payments found for this expense'
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        'status' => 'error',
    'message' => $e->getMessage()
    ]);
}
?>
