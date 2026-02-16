<?php
require_once __DIR__ . '/../../../db/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    $userId = (int)($_SESSION['user']['id'] ?? 0);
    if ($userId <= 0) {
        throw new Exception('User not authenticated.');
    }

    // Fetch landlord ID linked to the logged-in user
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord record not found.');
    }
    $landlordId = (int)$landlord['id'];

    /* -----------------------------
       Read filters (GET)
    ----------------------------- */
    $buildingId = trim((string)($_GET['building_id'] ?? ''));
    $dateFrom   = trim((string)($_GET['date_from'] ?? '')); // YYYY-MM-DD
    $dateTo     = trim((string)($_GET['date_to'] ?? ''));   // YYYY-MM-DD

    /* -----------------------------
       Build WHERE (apply only if set)
    ----------------------------- */
    $where  = ["jl.landlord_id = :landlord_id", "coa.account_type = 'Non-Current Assets'"];
    $params = [':landlord_id' => $landlordId];

    if ($buildingId !== '') {
        // If building_id is on journal_entries, switch to je.building_id
        $where[] = "jl.building_id = :building_id";
        $params[':building_id'] = (int)$buildingId;
    }

    if ($dateFrom !== '') {
        $where[] = "DATE(je.created_at) >= :date_from";
        $params[':date_from'] = $dateFrom;
    }

    if ($dateTo !== '') {
        $where[] = "DATE(je.created_at) <= :date_to";
        $params[':date_to'] = $dateTo;
    }

    $whereSql = implode(" AND ", $where);

    /* -----------------------------
       Non-Current Assets query (WITH filters)
    ----------------------------- */
    $sql = "
        SELECT
            coa.account_code AS account_id,
            coa.account_name AS name,
            (COALESCE(SUM(jl.debit),0) - COALESCE(SUM(jl.credit),0)) AS amount
        FROM journal_lines jl
        INNER JOIN journal_entries je
            ON je.id = jl.journal_entry_id
        INNER JOIN chart_of_accounts coa
            ON coa.account_code = jl.account_id
        WHERE {$whereSql}
        GROUP BY coa.account_code, coa.account_name
        ORDER BY coa.account_name
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $nonCurrentAssets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalNonCurrentAssets = 0.0;
    foreach ($nonCurrentAssets as $asset) {
        $totalNonCurrentAssets += (float)($asset['amount'] ?? 0);
    }

} catch (Throwable $e) {
    $nonCurrentAssets = [];
    $totalNonCurrentAssets = 0.0;
    $nonCurrentAssetsError = $e->getMessage();
}
