<?php
include '../../db/connect.php';

if (!isset($_GET['account_id'])) {
    echo json_encode(['error' => 'Missing account_id']);
    exit;
}

$accountId = $_GET['account_id'];

// Fetch ledger details with join
$sql = "
    SELECT 
        je.entry_date,
        je.reference,
        je.description,
        jl.debit,
        jl.credit
    FROM journal_lines jl
    JOIN journal_entries je ON je.id = jl.journal_entry_id
    WHERE jl.account_id = :account_id
    ORDER BY je.entry_date ASC, je.id ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':account_id' => $accountId]);
$ledger = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($ledger);
