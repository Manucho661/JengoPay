<?php
include '../../db/connect.php';

try {
    // ✅ Get liabilities balances directly from journal_lines & chart_of_accounts
    $sqlCurrent = "
  SELECT
    coa.account_code AS account_id,
    coa.account_name,
    COALESCE(SUM(jl.credit),0) - COALESCE(SUM(jl.debit),0) AS amount
  FROM journal_lines jl
  JOIN chart_of_accounts coa 
       ON coa.account_code = jl.account_id
  WHERE coa.account_type = 'Current Liabilities'
  GROUP BY coa.account_code, coa.account_name
  ORDER BY coa.account_name
";

    $sqlNonCurrent = "
  SELECT
    coa.account_code AS account_id,
    coa.account_name,
    COALESCE(SUM(jl.credit),0) - COALESCE(SUM(jl.debit),0) AS amount
  FROM journal_lines jl
  JOIN chart_of_accounts coa 
       ON coa.account_code = jl.account_id
  WHERE coa.account_type = 'Non-Current Liabilities'
  GROUP BY coa.account_code, coa.account_name
  ORDER BY coa.account_name
";


    // Current
    $stmt = $pdo->prepare($sqlCurrent);
    $stmt->execute();
    $currentLiabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Non-current
    $stmt = $pdo->prepare($sqlNonCurrent);
    $stmt->execute();
    $nonCurrentLiabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Totals (force numeric)
    $totalCurrent = 0;
    foreach ($currentLiabilities as $row) {
        $totalCurrent += (float)$row['amount'];
    }

    $totalNonCurrent = 0;
    foreach ($nonCurrentLiabilities as $row) {
        $totalNonCurrent += (float)$row['amount'];
    }

    echo json_encode([
        'currentLiabilities' => $currentLiabilities,
        'totalCurrent' => $totalCurrent,
        'nonCurrentLiabilities' => $nonCurrentLiabilities,
        'totalNonCurrent' => $totalNonCurrent,
        'totalLiabilities' => $totalCurrent + $totalNonCurrent
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
