<?php
function createJournalEntry($pdo, $data) {
    $stmt = $pdo->prepare("
        INSERT INTO journal_entries (entry_date, reference, description,source_table, source_id) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['description'],
        $data['reference'],
        $data['date'],
        $data['source_table'],
        $data['source_id']
    ]);
    return $pdo->lastInsertId();
}

function addJournalLine($pdo, $journalId, $accountId, $debit, $credit) {
    $stmt = $pdo->prepare("
        INSERT INTO journal_lines (journal_entry_id, account_id, debit, credit) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$journalId, $accountId, $debit, $credit]);
}
