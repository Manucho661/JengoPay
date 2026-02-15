<?php
require_once dirname(__DIR__, 5) . '/config/config.php';
require_once BASE_PATH . '/db/connect.php';

if (session_status() === PHP_SESSION_NONE) session_start();

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int)$_SESSION['user']['id'];

    // Fetch landlord ID linked to the logged-in user
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception("Landlord record not found for this user.");
    }

    $landlordId = (int)$landlord['id'];

    /* -----------------------------
       Read filters (GET)
    ----------------------------- */
    $buildingId = trim((string)($_GET['building_id'] ?? ''));
    $dateFrom   = trim((string)($_GET['date_from'] ?? '')); // YYYY-MM-DD
    $dateTo     = trim((string)($_GET['date_to'] ?? ''));   // YYYY-MM-DD

    /* -----------------------------
       Build WHERE for P&L filters
       (apply only if set)
    ----------------------------- */
    $wherePL  = ["jl.landlord_id = :landlord_id"];
    $paramsPL = [':landlord_id' => $landlordId];

    if ($buildingId !== '') {
        // IMPORTANT: change je.building_id to jl.building_id if your schema uses that
        $wherePL[] = "jl.building_id = :building_id";
        $paramsPL[':building_id'] = (int)$buildingId;
    }

    if ($dateFrom !== '') {
        $wherePL[] = "DATE(je.created_at) >= :date_from";
        $paramsPL[':date_from'] = $dateFrom;
    }

    if ($dateTo !== '') {
        $wherePL[] = "DATE(je.created_at) <= :date_to";
        $paramsPL[':date_to'] = $dateTo;
    }

    $whereSqlPL = implode(" AND ", $wherePL);

    /* -----------------------------
       PROFIT & LOSS QUERY (WITH FILTERS)
    ----------------------------- */
    $sqlPL = "
        SELECT
            coa.account_type,
            coa.account_code,
            coa.account_name,
            CASE
                WHEN coa.account_type = 'Revenue'  THEN COALESCE(SUM(jl.credit - jl.debit), 0)
                WHEN coa.account_type = 'Expenses' THEN COALESCE(SUM(jl.debit - jl.credit), 0)
                ELSE 0
            END AS amount
        FROM journal_lines jl
        INNER JOIN journal_entries je ON je.id = jl.journal_entry_id
        INNER JOIN chart_of_accounts coa ON coa.account_code = jl.account_id
        WHERE {$whereSqlPL}
          AND coa.account_type IN ('Revenue','Expenses')
        GROUP BY coa.account_type, coa.account_code, coa.account_name
        HAVING amount <> 0
        ORDER BY coa.account_type, coa.account_code
    ";

    $stmtPL = $pdo->prepare($sqlPL);
    $stmtPL->execute($paramsPL);
    $profitLossRows = $stmtPL->fetchAll(PDO::FETCH_ASSOC);

    /* -----------------------------
       SPLIT INTO REVENUE / EXPENSE
       (Fix: you were checking 'INCOME' but query uses 'Revenue')
    ----------------------------- */
    $incomeRows = [];
    $expenseRows = [];
    $totalIncome = 0.0;
    $totalExpenses = 0.0;

    foreach ($profitLossRows as $r) {
        $amount = (float)($r['amount'] ?? 0);

        if (($r['account_type'] ?? '') === 'Revenue') {
            $incomeRows[] = $r;
            $totalIncome += $amount;
        } else { // Expenses
            $expenseRows[] = $r;
            $totalExpenses += $amount;
        }
    }

    $netProfit = $totalIncome - $totalExpenses;

} catch (Throwable $e) {
    echo $e->getMessage();
    echo " on line " . $e->getLine();
}
