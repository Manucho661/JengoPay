<?php
include '../../db/connect.php';

try {
    // ✅ Get assets balances directly from journal_lines & chart_of_accounts
    $sql = "
        SELECT 
            coa.account_name AS name,
            coa.account_type AS category,
            SUM(jl.debit) - SUM(jl.credit) AS amount,
            MAX(je.entry_date) AS created_at
        FROM journal_lines jl
        JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        JOIN journal_entries je ON jl.journal_entry_id = je.id
        WHERE coa.account_type LIKE '%Assets%'
        GROUP BY coa.account_name, coa.account_type
        ORDER BY amount DESC, coa.account_type, coa.account_code
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Separate into categories
    $currentAssets = [];
    $nonCurrentAssets = [];

    foreach ($assets as $asset) {
        if ($asset['category'] === 'Current Assets') {
            $currentAssets[] = $asset;
        } else {
            $nonCurrentAssets[] = $asset;
        }
    }

    $totalCurrent = array_sum(array_column($currentAssets, 'amount'));
    $totalNonCurrent = array_sum(array_column($nonCurrentAssets, 'amount'));
    $totalAssets = $totalCurrent + $totalNonCurrent;
    
    // var_dump($currentAssets);
    // Items that must be displayed on the balance sheet.
    $mustDisplayedCurrentAssets = array('Accounts Receivable', 'M-pesa', 'Cash', 'Bank', 'Tenant Security Deposits (Held)', 'Prepayment');

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
