<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/db/connect.php';

// Ensure the session is started

try {
    // 1. Fetch landlord_id from the session
    if (!isset($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated.');
    }

    $userId = (int) $_SESSION['user']['id'];

    // Fetch landlord ID linked to the logged-in user
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord record not found.');
    }

    $landlordId = (int) $landlord['id'];

    // ✅ Get current liabilities balances directly from journal_lines & chart_of_accounts
    $sqlCurrent = "
        SELECT
            coa.account_code AS account_id,
            coa.account_name,
            COALESCE(SUM(jl.credit),0) - COALESCE(SUM(jl.debit),0) AS amount
        FROM journal_lines jl
        JOIN chart_of_accounts coa 
            ON coa.account_code = jl.account_id
        WHERE coa.account_type = 'Current Liabilities'  -- Only current liabilities
            AND jl.landlord_id = :landlord_id  -- Filter by landlord_id
        GROUP BY coa.account_code, coa.account_name
        ORDER BY coa.account_name
    ";

    // Current Liabilities
    $stmt = $pdo->prepare($sqlCurrent);
    $stmt->execute(['landlord_id' => $landlordId]);
    $currentLiabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Total Current Liabilities
    $totalCurrentLiabilities = 0;
    foreach ($currentLiabilities as $row) {
        $totalCurrentLiabilities += (float)$row['amount'];
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
