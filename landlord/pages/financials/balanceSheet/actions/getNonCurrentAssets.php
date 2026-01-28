<?php

require_once __DIR__ . '/../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Fetch Non-Current Assets only for the logged-in landlord
    $userId = $_SESSION['user']['id']; // Assuming session is active and user_id is set

    // Fetch landlord ID linked to the logged-in user
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord record not found.');
    }
    $landlordId = (int) $landlord['id'];

    // Query for Non-Current Assets for the current landlord
    $sql = "
        SELECT
            coa.account_code AS account_id,
            coa.account_name AS name,
            COALESCE(SUM(jl.debit),0) - COALESCE(SUM(jl.credit),0) AS amount
        FROM journal_lines jl
        JOIN chart_of_accounts coa 
            ON coa.account_code = jl.account_id
        WHERE coa.account_type = 'Non-Current Assets'
        AND jl.landlord_id = :landlord_id  -- Add landlord_id filter
        GROUP BY coa.account_code, coa.account_name
        ORDER BY coa.account_name
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['landlord_id' => $landlordId]);
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
