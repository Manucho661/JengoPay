<?php 
// ✅ Get total revenue
$sqlRevenue = "
    SELECT 
        COALESCE(SUM(jl.credit) - SUM(jl.debit), 0) AS total_revenue
    FROM journal_lines jl
    JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
    WHERE coa.account_type LIKE '%Revenue%'
";
$stmtRev = $pdo->prepare($sqlRevenue);
$stmtRev->execute();
$totalRevenue = $stmtRev->fetchColumn();

// ✅ Get total expenses
$sqlExpenses = "
    SELECT 
        COALESCE(SUM(jl.debit) - SUM(jl.credit), 0) AS total_expenses
    FROM journal_lines jl
    JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
    WHERE coa.account_type LIKE '%Expense%'
";
$stmtExp = $pdo->prepare($sqlExpenses);
$stmtExp->execute();
$totalExpenses = $stmtExp->fetchColumn();

// ✅ Retained earnings = revenue - expenses
$retainedEarnings = $totalRevenue - $totalExpenses;
?>
