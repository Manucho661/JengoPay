<?php
include '../../db/connect.php';

try {
    // ✅ Get liabilities balances directly from journal_lines & chart_of_accounts
    $sql = "
        SELECT 
            coa.account_name AS liability_name,
            coa.account_type AS category,
            SUM(jl.credit) - SUM(jl.debit) AS amount,
            MAX(je.entry_date) AS due_date
        FROM journal_lines jl
        JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        JOIN journal_entries je ON jl.journal_entry_id = je.id
        WHERE coa.account_type LIKE '%Liabilities%'
        GROUP BY coa.account_name, coa.account_type
        ORDER BY coa.account_type, coa.account_code
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $liabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Separate into categories
    $currentLiabilities = [];
    $nonCurrentLiabilities = [];

    foreach ($liabilities as $liability) {
        if ($liability['category'] === 'Current Liabilities') {
            $currentLiabilities[] = $liability;
        } else {
            $nonCurrentLiabilities[] = $liability;
        }
    }

    var_dump($currentLiabilities);
    $totalCurrentLiabilities = array_sum(array_column($currentLiabilities, 'amount'));
    $totalNonCurrentLiabilities = array_sum(array_column($nonCurrentLiabilities, 'amount'));
    $totalLiabilities = $totalCurrentLiabilities + $totalNonCurrentLiabilities;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
