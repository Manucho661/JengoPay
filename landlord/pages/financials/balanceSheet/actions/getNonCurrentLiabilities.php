<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/db/connect.php';

try {
    // ✅ Get non-current liabilities balances directly from journal_lines & chart_of_accounts
    $sqlNonCurrent = "
        SELECT
            coa.account_code AS account_id,
            coa.account_name AS name,
            COALESCE(SUM(jl.credit), 0) - COALESCE(SUM(jl.debit), 0) AS amount
        FROM journal_lines jl
        JOIN chart_of_accounts coa
            ON coa.account_code = jl.account_id
        WHERE coa.account_type = 'Non-Current Liabilities'  -- Only non-current liabilities
        GROUP BY coa.account_code, coa.account_name
        ORDER BY coa.account_name
    ";

    // Non-current liabilities query
    $stmt = $pdo->prepare($sqlNonCurrent);
    $stmt->execute();
    $nonCurrentLiabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Total non-current liabilities (force numeric)
    $totalNonCurrentLiabilities = 0;
    foreach ($nonCurrentLiabilities as $row) {
        $totalNonCurrentLiabilities += (float) $row['amount'];
    }

    // Now you can use $nonCurrentLiabilities and $totalNonCurrent for further processing
    // For example, you can store these in session variables, assign to other variables, etc.
} catch (PDOException $e) {
    // Handle any errors
    // You can store the error in a variable, log it, or do further handling as needed
    $errorMessage = "Error: " . $e->getMessage();
}
