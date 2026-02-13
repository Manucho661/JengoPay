<?php

require_once __DIR__ . '/../../../db/connect.php';

$userId = $_SESSION['user']['id'];

// Fetch landlord ID linked to the logged-in user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
$stmt->execute([$userId]);
$landlord = $stmt->fetch();

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {

    if (!$landlord) {
        throw new Exception("Landlord record not found for this user.");
    }

    $landlordId = (int) $landlord['id'];

    /* -------------------------------------------------
       PROFIT & LOSS QUERY (NO DATE FILTER)
    ------------------------------------------------- */
    $sqlPL = "
        SELECT
            coa.account_type,
            coa.account_code,
            coa.account_name,
            CASE
                WHEN coa.account_type = 'Revenue'
                    THEN COALESCE(SUM(jl.credit - jl.debit), 0)
                WHEN coa.account_type = 'Expenses'
                    THEN COALESCE(SUM(jl.debit - jl.credit), 0)
                ELSE 0
            END AS amount
        FROM journal_lines jl
        INNER JOIN journal_entries je ON je.id = jl.journal_entry_id
        INNER JOIN chart_of_accounts coa ON coa.account_code = jl.account_id
        WHERE jl.landlord_id = :landlord_id
          AND coa.account_type IN ('Revenue','Expenses')
        GROUP BY coa.account_type, coa.account_code, coa.account_name
        HAVING amount <> 0
        ORDER BY coa.account_type, coa.account_code
    ";

    $stmtPL = $pdo->prepare($sqlPL);
    $stmtPL->execute([
        ':landlord_id' => $landlordId
    ]);

    $profitLossRows = $stmtPL->fetchAll(PDO::FETCH_ASSOC);
    /* -------------------------------------------------
       SPLIT INTO INCOME / EXPENSE
    ------------------------------------------------- */
    $incomeRows = [];
    $expenseRows = [];
    $totalIncome = 0.0;
    $totalExpenses = 0.0;

    foreach ($profitLossRows as $r) {
        $amount = (float)($r['amount'] ?? 0);

        if (($r['account_type'] ?? '') === 'INCOME') {
            $incomeRows[] = $r;
            $totalIncome += $amount;
        } else {
            $expenseRows[] = $r;
            $totalExpenses += $amount;
        }
    }

    $netProfit = $totalIncome - $totalExpenses;

} catch (Throwable $e) {
    echo $e->getMessage();
    echo $e->getLine();
}
