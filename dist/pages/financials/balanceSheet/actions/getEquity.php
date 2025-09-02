<?php
include '../../db/connect.php';

try {
    // ✅ Get owners' equity balances from journal_lines & chart_of_accounts
    $sql = "
        SELECT 
            coa.account_name AS name,
            SUM(jl.credit) - SUM(jl.debit) AS amount,
            MAX(je.entry_date) AS entry_date,
            GROUP_CONCAT(je.description SEPARATOR ', ') AS description
        FROM journal_lines jl
        JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        JOIN journal_entries je ON jl.journal_entry_id = je.id
        WHERE coa.account_type LIKE '%Equity%'
        GROUP BY coa.account_name
        ORDER BY je.entry_date ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $owners_equities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total equity
    $totalEquity = array_sum(array_column($owners_equities, 'amount'));

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
