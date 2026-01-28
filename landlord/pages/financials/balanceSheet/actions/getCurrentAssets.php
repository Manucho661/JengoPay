<?php

require_once __DIR__ . '/../../../db/connect.php';

// Session variables to use
$userId = $_SESSION['user']['id'];

// Fetch landlord ID linked to the logged-in user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
$stmt->execute([$userId]);
$landlord = $stmt->fetch();
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Ensure landlord_id is available
    if (!$landlord) {
        throw new Exception("Landlord record not found for this user.");
    }
    $landlordId = (int) $landlord['id'];

    // Fetch Current Assets only for the logged-in landlord
    $sql = "
        SELECT
            coa.account_code AS account_id,
            coa.account_name AS name,
            COALESCE(SUM(jl.debit),0) - COALESCE(SUM(jl.credit),0) AS amount
        FROM journal_lines jl
        JOIN chart_of_accounts coa 
            ON coa.account_code = jl.account_id
        WHERE coa.account_type = 'Current Assets'
        AND jl.landlord_id = :landlord_id  -- Add landlord_id filter
        GROUP BY coa.account_code, coa.account_name
        ORDER BY coa.account_name
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['landlord_id' => $landlordId]);
    $CurrentAssets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total (force numeric)
    $totalCurrentAssets = 0;
    foreach ($CurrentAssets as $asset) {
        $totalCurrentAssets += (float)$asset['amount'];
    }
} catch (Throwable $e) {
    $CurrentAssets = [];
    $totalCurrentAssets = 0;
    $CurrentAssetsError = $e->getMessage();
}
