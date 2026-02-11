<?php

// Fetch landlord_id from session (ensure this is correct)
$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
$stmt->execute([$userId]);
$landlord = $stmt->fetch();
$landlord_id = $landlord['id']; // Ensure landlord_id is correctly retrieved

// Function to create journal entry
function createJournalEntry($pdo, $data)
{
    $stmt = $pdo->prepare("
        INSERT INTO journal_entries (entry_date, reference, description, source_table, source_id) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['date'],
        $data['reference'],
        $data['description'],
        $data['source_table'],
        $data['source_id']
    ]);
    return $pdo->lastInsertId(); // Return the last inserted journal entry ID
}

// Function to add a journal line
function addJournalLine($pdo, $journalId, $building_id, $landlord_id, $accountId, $debit, $credit, $source_table, $source_id)
{
    // Correct order: journal_entry_id, landlord_id comes immediately after it
    $stmt = $pdo->prepare("
        INSERT INTO journal_lines (journal_entry_id, building_id, landlord_id, account_id, debit, credit, source_table, source_table_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$journalId, $building_id, $landlord_id,  $accountId, $debit, $credit, $source_table, $source_id]);
}
?>
