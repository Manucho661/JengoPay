<?php 
// ✅ Get total revenue
$sqlRevenue = "
    SELECT 
        SUM(jl.credit) - SUM(jl.debit) AS total_revenue
    FROM journal_lines jl
    JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
    WHERE coa.account_type LIKE '%Revenue%'
";

$stmtRev = $pdo->prepare($sqlRevenue);
$stmtRev->execute();
$totalRevenue = $stmtRev->fetchColumn();


?>