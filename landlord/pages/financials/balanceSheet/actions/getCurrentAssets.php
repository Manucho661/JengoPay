<?php

require_once __DIR__ . '/../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Fetch Non-Current Assets only
    $sql = "
        SELECT
            coa.account_code AS account_id,
            coa.account_name AS name,
            COALESCE(SUM(jl.debit),0) - COALESCE(SUM(jl.credit),0) AS amount
        FROM journal_lines jl
        JOIN chart_of_accounts coa 
            ON coa.account_code = jl.account_id
        WHERE coa.account_type = 'Non-Current Assets'
        GROUP BY coa.account_code, coa.account_name
        ORDER BY coa.account_name
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $nonCurrentAssets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total (force numeric)
    $totalNonCurrentAssets = 0;
    foreach ($nonCurrentAssets as $asset) {
        $totalNonCurrentAssets += (float)$asset['amount'];
    }

} catch (Throwable $e) {
    $nonCurrentAssets = [];
    $totalNonCurrentAssets = 0;
    $nonCurrentAssetsError = $e->getMessage();
}
