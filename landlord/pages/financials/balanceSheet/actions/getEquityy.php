<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // Include your PDO connection

// Set a custom error handler to throw exceptions for errors
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Define the account_id
    $account_id = 400;

    // Total Debit for account_id 400 (credit amount in journal_lines)
    $sql = "
        SELECT SUM(credit) AS total_credit 
        FROM journal_lines 
        WHERE account_id = :account_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $totalEquity = $stmt->fetchColumn();  // Fetching the total credit amount

    // Query for total revenue (sum of credit - debit for revenue accounts)
    $sqlRevenue = "
        SELECT COALESCE(SUM(jl.credit) - SUM(jl.debit), 0) AS total_revenue
        FROM journal_lines jl
        JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        WHERE coa.account_type LIKE '%Revenue%'
    ";
    $stmtRev = $pdo->prepare($sqlRevenue);
    $stmtRev->execute();
    $totalRevenue = $stmtRev->fetchColumn();

    // Query for total expenses (sum of debit - credit for expense accounts)
    $sqlExpenses = "
        SELECT COALESCE(SUM(jl.debit) - SUM(jl.credit), 0) AS total_expenses
        FROM journal_lines jl
        JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        WHERE coa.account_type LIKE '%Expense%'
    ";
    $stmtExp = $pdo->prepare($sqlExpenses);
    $stmtExp->execute();
    $totalExpenses = $stmtExp->fetchColumn();

    // Calculate retained earnings (revenue - expenses)
    $retainedEarnings = $totalRevenue - $totalExpenses;

    // Return the response in JSON format
    echo json_encode([
        'total_equity' => $totalEquity,
        'retainedEarnings' => $retainedEarnings,
        'revenue' => $totalRevenue,
        'expenses' => $totalExpenses
    ]);

} catch (Throwable $e) {
    // Handle any exceptions or errors
    echo json_encode([
        'error' => 'An error occurred while processing the request.',
        'message' => $e->getMessage()
    ]);
}
?>
