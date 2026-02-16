<?php
require_once '../../../db/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    if (empty($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated.');
    }

    $userId = (int)$_SESSION['user']['id'];

    // Resolve landlord_id
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord not found for the logged-in user.');
    }

    $landlordId = (int)$landlord['id'];

    /* -----------------------------
       Read filters (GET)
    ----------------------------- */
    $buildingId = trim((string)($_GET['building_id'] ?? ''));
    $dateFrom   = trim((string)($_GET['date_from'] ?? '')); // YYYY-MM-DD
    $dateTo     = trim((string)($_GET['date_to'] ?? ''));   // YYYY-MM-DD

    /* -----------------------------
       Build shared WHERE (apply only if set)
    ----------------------------- */
    $whereShared = ["jl.landlord_id = :landlord_id"];
    $paramsShared = [':landlord_id' => $landlordId];

    if ($buildingId !== '') {
        // If building_id is on journal_lines, change to jl.building_id
        $whereShared[] = "jl.building_id = :building_id";
        $paramsShared[':building_id'] = (int)$buildingId;
    }

    if ($dateFrom !== '') {
        $whereShared[] = "DATE(je.created_at) >= :date_from";
        $paramsShared[':date_from'] = $dateFrom;
    }

    if ($dateTo !== '') {
        $whereShared[] = "DATE(je.created_at) <= :date_to";
        $paramsShared[':date_to'] = $dateTo;
    }

    $whereSqlShared = implode(" AND ", $whereShared);

    /* -----------------------------
       Owner's Capital (account_id 400)
    ----------------------------- */
    $account_id = 400;

    $sqlCapital = "
        SELECT COALESCE(SUM(jl.credit), 0) AS total_credit
        FROM journal_lines jl
        INNER JOIN journal_entries je ON je.id = jl.journal_entry_id
        WHERE {$whereSqlShared}
          AND jl.account_id = :account_id
    ";

    $stmtCap = $pdo->prepare($sqlCapital);
    $stmtCap->execute(array_merge($paramsShared, [':account_id' => $account_id]));
    $owners_capital = (float)($stmtCap->fetchColumn() ?? 0);

    /* -----------------------------
       Total Revenue
    ----------------------------- */
    $sqlRevenue = "
        SELECT COALESCE(SUM(jl.credit) - SUM(jl.debit), 0) AS total_revenue
        FROM journal_lines jl
        INNER JOIN journal_entries je ON je.id = jl.journal_entry_id
        INNER JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        WHERE {$whereSqlShared}
          AND coa.account_type LIKE '%Revenue%'
    ";

    $stmtRev = $pdo->prepare($sqlRevenue);
    $stmtRev->execute($paramsShared);
    $totalRevenue = (float)($stmtRev->fetchColumn() ?? 0);

    /* -----------------------------
       Total Expenses
    ----------------------------- */
    $sqlExpenses = "
        SELECT COALESCE(SUM(jl.debit) - SUM(jl.credit), 0) AS total_expenses
        FROM journal_lines jl
        INNER JOIN journal_entries je ON je.id = jl.journal_entry_id
        INNER JOIN chart_of_accounts coa ON jl.account_id = coa.account_code
        WHERE {$whereSqlShared}
          AND coa.account_type LIKE '%Expense%'
    ";

    $stmtExp = $pdo->prepare($sqlExpenses);
    $stmtExp->execute($paramsShared);
    $totalExpenses = (float)($stmtExp->fetchColumn() ?? 0);

    /* -----------------------------
       Equity calcs
    ----------------------------- */
    $retainedEarnings = $totalRevenue - $totalExpenses;
    $totalEquity = $retainedEarnings + $owners_capital;

} catch (Throwable $e) {
    $errorMessage = "An error occurred while processing the request: " . $e->getMessage();
    echo $errorMessage;
}
?>
