<?php
require_once '../../../db/connect.php'; // Include your PDO connection

// Set a custom error handler to throw exceptions for errors
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Ensure session is started
    // session_start();

    // Fetch landlord ID from session
    if (!isset($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated.');
    }

    $userId = (int)$_SESSION['user']['id'];

    // Get the landlord ID linked to the logged-in user
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord not found for the logged-in user.');
    }

    $landlordId = $landlord['id'];

    // Define account_id (example: owners capital account)
    $account_id = 400;

    // Total credit for account_id 400 (credit amount in journal_lines)
    $sql = "
        SELECT SUM(credit) AS total_credit 
        FROM journal_lines 
        WHERE account_id = :account_id AND landlord_id = :landlord_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->bindParam(':landlord_id', $landlordId, PDO::PARAM_INT);
    $stmt->execute();
    $owners_capital = $stmt->fetchColumn();  // Fetching the total credit amount

    // Query for total revenue (sum of credit - debit for revenue accounts)
    $sqlRevenue = "
        SELECT COALESCE(SUM(jl.credit) - SUM(jl.debit), 0) AS total_revenue
        FROM journal_lines jl
        JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        WHERE coa.account_type LIKE '%Revenue%' AND jl.landlord_id = :landlord_id
    ";
    $stmtRev = $pdo->prepare($sqlRevenue);
    $stmtRev->bindParam(':landlord_id', $landlordId, PDO::PARAM_INT);
    $stmtRev->execute();
    $totalRevenue = $stmtRev->fetchColumn();

    // Query for total expenses (sum of debit - credit for expense accounts)
    $sqlExpenses = "
        SELECT COALESCE(SUM(jl.debit) - SUM(jl.credit), 0) AS total_expenses
        FROM journal_lines jl
        JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        WHERE coa.account_type LIKE '%Expense%' AND jl.landlord_id = :landlord_id
    ";
    $stmtExp = $pdo->prepare($sqlExpenses);
    $stmtExp->bindParam(':landlord_id', $landlordId, PDO::PARAM_INT);
    $stmtExp->execute();
    $totalExpenses = $stmtExp->fetchColumn();

    // Calculate retained earnings (revenue - expenses)
    $retainedEarnings = $totalRevenue - $totalExpenses;
    $totalEquity = $retainedEarnings + $owners_capital;

    // Now you can use the variables for further processing, logging, or other operations
    // For example:
    // - Store them in session variables
    // - Pass them to another function
    // - Log the results for auditing

    // Make sure that everything is properly logged or returned for debugging
    

} catch (Throwable $e) {
    // Handle any exceptions or errors
    // You may choose to log the error or perform any error-specific action here
    $errorMessage = "An error occurred while processing the request: " . $e->getMessage();
    echo $errorMessage; // You can log this error if needed
}
?>
