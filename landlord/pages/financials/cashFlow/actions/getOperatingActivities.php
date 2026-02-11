<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


try {
    /* -------------------------------------------------
       1) Auth check
    ------------------------------------------------- */
    if (!isset($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated.');
    }

    $userId = (int) $_SESSION['user']['id'];

    /* -------------------------------------------------
       2) Resolve landlord_id for this user
    ------------------------------------------------- */
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord record not found.');
    }

    $landlordId = (int) $landlord['id'];



    /* -------------------------------------------------
       3) Date range (optional)
    ------------------------------------------------- */
    $from = $_GET['from'] ?? date('Y-m-01');
    $to   = $_GET['to']   ?? date('Y-m-t');

    /* -------------------------------------------------
       4) Operating cashflow rows
    ------------------------------------------------- */
    $sqlRows = "
        SELECT
            je.entry_date,
            je.description,
            je.reference,
            coa.account_code,
            coa.account_name AS cash_account,
            jl.debit  AS inflow,
            jl.credit AS outflow
        FROM journal_lines jl
        INNER JOIN journal_entries je ON je.id = jl.journal_entry_id
        INNER JOIN chart_of_accounts coa ON coa.account_code = jl.account_id
        WHERE jl.landlord_id = :landlord_id
          AND je.cashflow_category = 'OPERATING'
          AND coa.is_cash = 1
          AND je.entry_date BETWEEN :from_date AND :to_date
        ORDER BY je.entry_date ASC, jl.id ASC
    ";

    $stmtRows = $pdo->prepare($sqlRows);
    $stmtRows->execute([
        ':landlord_id' => $landlordId,
        ':from_date'   => $from,
        ':to_date'     => $to,
    ]);

    $operatingCashflowRows = $stmtRows->fetchAll(PDO::FETCH_ASSOC);
    // Build inflows/outflows arrays from the cashflow rows
    $operatingInflows = [];
    $operatingOutflows = [];

    foreach ($operatingCashflowRows as $row) {
        $in  = (float) ($row['inflow'] ?? 0);
        $out = (float) ($row['outflow'] ?? 0);

        // Label used in your table
        $itemType = $row['description'] ?? 'Operating activity';

        if ($in > 0) {
            $operatingInflows[] = [
                'item_type' => $itemType,
                'total_amount' => $in,
            ];
        }

        if ($out > 0) {
            $operatingOutflows[] = [
                'item_type' => $itemType,
                'total_amount' => $out,
            ];
        }
    }


    /* -------------------------------------------------
       5) Totals
    ------------------------------------------------- */
    $sqlTotals = "
        SELECT
            COALESCE(SUM(jl.debit), 0)  AS total_inflow,
            COALESCE(SUM(jl.credit), 0) AS total_outflow,
            COALESCE(SUM(jl.debit - jl.credit), 0) AS net_operating_cash
        FROM journal_lines jl
        INNER JOIN journal_entries je ON je.id = jl.journal_entry_id
        INNER JOIN chart_of_accounts coa ON coa.account_code = jl.account_id
        WHERE jl.landlord_id = :landlord_id
          AND je.cashflow_category = 'OPERATING'
          AND coa.is_cash = 1
          AND je.entry_date BETWEEN :from_date AND :to_date
    ";

    $stmtTotals = $pdo->prepare($sqlTotals);
    $stmtTotals->execute([
        ':landlord_id' => $landlordId,
        ':from_date'   => $from,
        ':to_date'     => $to,
    ]);

    $operatingCashflowTotals = $stmtTotals->fetch(PDO::FETCH_ASSOC);

    // opening cash
    $sqlOpening = "
        SELECT
            COALESCE(SUM(jl.debit - jl.credit), 0) AS opening_cash
        FROM journal_lines jl
        INNER JOIN journal_entries je ON je.id = jl.journal_entry_id
        INNER JOIN chart_of_accounts coa ON coa.account_code = jl.account_id
        WHERE jl.landlord_id = :landlord_id
            AND coa.is_cash = 1
            AND je.entry_date < :from_date
        ";
    $stmtOpening = $pdo->prepare($sqlOpening);
    $stmtOpening->execute([
        ':landlord_id' => $landlordId,
        ':from_date'   => $from, // e.g. 2026-02-01
    ]);

    $openingCashRow = $stmtOpening->fetch(PDO::FETCH_ASSOC);
    $openingCash = (float)($openingCashRow['opening_cash'] ?? 0);
} catch (Throwable $e) {
    $errorMessage = "❌ Failed to fetch operating cashflow: " . $e->getMessage();
    echo $errorMessage;
}
