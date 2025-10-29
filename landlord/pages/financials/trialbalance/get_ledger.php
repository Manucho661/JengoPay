<?php
include '../../db/connect.php';

header('Content-Type: application/json');

// Validate input
if (empty($_GET['account_code'])) {
    echo json_encode(['error' => 'Account code is required']);
    exit;
}

$accountCode = $_GET['account_code'];
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';

try {
    // ===========================
    // BUILD WHERE CONDITIONS
    // ===========================
    $whereConditions = [];
    $params = [':account_code' => $accountCode];

    // Date range filter
    if (!empty($fromDate) && !empty($toDate)) {
        $whereConditions[] = "(je.entry_date BETWEEN :from_date OR ei.created_at BETWEEN :from_date)";
        $whereConditions[] = "(je.entry_date BETWEEN :to_date OR ei.created_at BETWEEN :to_date)";
        $params[':from_date'] = $fromDate;
        $params[':to_date'] = $toDate;
    }

    $whereSql = $whereConditions ? "AND " . implode(" AND ", $whereConditions) : "";

    // ===========================
    // QUERY FOR JOURNAL ENTRIES
    // ===========================
    $sql = "
        -- Journal Entries
        SELECT 
            je.entry_date as entry_date,
            je.reference_number as reference,
            je.description as description,
            jl.debit as debit,
            jl.credit as credit,
            'journal_entry' as source_table,
            je.id as source_id
        FROM journal_lines jl
        INNER JOIN journal_entries je ON jl.journal_entry_id = je.id
        WHERE jl.account_id = :account_code
        {$whereSql}
        
        UNION ALL
        
        -- Expense Items
        SELECT 
            ei.created_at as entry_date,
            ei.expense_id as reference,
            ei.item_description as description,
            CAST(ei.item_total AS DECIMAL(20,2)) as debit,
            0 as credit,
            'expense_item' as source_table,
            ei.id as source_id
        FROM expense_items ei
        WHERE ei.item_account_code = :account_code
        {$whereSql}
        
        ORDER BY entry_date, source_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($transactions);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>