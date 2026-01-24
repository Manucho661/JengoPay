<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // ✅ Get assets balances directly from journal_lines & chart_of_accounts
    $sqlCurrent = "
  SELECT
    coa.account_code AS account_id,
    coa.account_name AS name,
    COALESCE(SUM(jl.debit),0) - COALESCE(SUM(jl.credit),0) AS amount
  FROM journal_lines jl
  JOIN chart_of_accounts coa ON coa.account_code = jl.account_id
  WHERE coa.account_type = 'Current Assets'
  GROUP BY coa.account_code, coa.account_name
  ORDER BY coa.account_name
";

    $sqlNonCurrent = "
  SELECT
    coa.account_code AS account_id,
    coa.account_name AS name,
    COALESCE(SUM(jl.debit),0) - COALESCE(SUM(jl.credit),0) AS amount
  FROM journal_lines jl
  JOIN chart_of_accounts coa ON coa.account_code = jl.account_id
  WHERE coa.account_type = 'Non-Current Assets'
  GROUP BY coa.account_code, coa.account_name
  ORDER BY coa.account_name
";

    $stmt = $pdo->prepare($sqlCurrent);
    $stmt->execute();
    $currentAssets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Non-Current Assets
    $stmt = $pdo->prepare($sqlNonCurrent);
    $stmt->execute();
    $nonCurrentAssets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Totals (force numeric)
    $totalCurrent = 0;
    foreach ($currentAssets as $a) $totalCurrent += (float)$a['amount'];

    $totalNonCurrent = 0;
    foreach ($nonCurrentAssets as $a) $totalNonCurrent += (float)$a['amount'];

    echo json_encode([
        'currentAssets' => $currentAssets,
        'nonCurrentAssets' => $nonCurrentAssets,
        'totalCurrent' => $totalCurrent,
        'totalNonCurrent' => $totalNonCurrent,
        'totalAssets' => $totalCurrent + $totalNonCurrent
    ]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
