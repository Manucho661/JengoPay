<?php
// payExpenseJournal.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function recordExpensePaymentJournal($pdo, $expected_amount, $expense_id, $amount, $paymentAccountId, $payment_date, $expPayId)
{
    // Session variables to use
    $userId = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch();
    $landlord_id = $landlord['id']; // Store the landlord_id from the session

    try {
        // Insert into journal_entries
        $stmtEntry = $pdo->prepare("
            INSERT INTO journal_entries 
            (description, reference, entry_date, source_table, source_id, cashflow_category, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmtEntry->execute([
            "Payment for Expense #{$expense_id}",  // description
            "EXP-PAY-{$expense_id}",               // reference
            $payment_date,                         // entry_date
            "expenses_payments",                   // source_table
            $expPayId,                             // source_id
            "OPERATING"                            // cashflow_category
        ]);

        $journalEntryId = $pdo->lastInsertId();


        // Insert into journal_lines
        $stmtLine = $pdo->prepare("INSERT INTO journal_lines 
            (journal_entry_id, landlord_id, account_id, debit, credit, source_table, source_table_id, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

        if ($amount == $expected_amount) {
            // ✅ Full payment
            $stmtLine->execute([$journalEntryId, $landlord_id, $paymentAccountId, 0.00, $amount, 'expense_payments', $expPayId]); // Credit Bank
            $stmtLine->execute([$journalEntryId, $landlord_id, 300, $amount, 0.00, 'expense_payments', $expPayId]);              // Debit A/P
        } elseif ($amount < $expected_amount) {
            // ✅ Partial payment
            $stmtLine->execute([$journalEntryId, $landlord_id, $paymentAccountId, 0.00, $amount, 'expense_payments', $expPayId]); // Credit Bank
            $stmtLine->execute([$journalEntryId, $landlord_id, 300, $amount, 0.00, 'expense_payments', $expPayId]);              // Debit A/P
        } else {
            // ✅ Overpayment → Prepaid Expense
            $overpay = $amount - $expected_amount;

            // Pay off expected
            $stmtLine->execute([$journalEntryId, $landlord_id, $paymentAccountId, 0.00, $expected_amount, 'expense_payments', $expPayId]); // Credit Bank
            $stmtLine->execute([$journalEntryId, $landlord_id, 300, $expected_amount, 0.00, 'expense_payments', $expPayId]);              // Debit A/P

            // Record overpayment
            $stmtLine->execute([$journalEntryId, $landlord_id, $paymentAccountId, 0.00, $overpay, 'expense_payments', $expPayId]);        // Credit Bank
            $stmtLine->execute([$journalEntryId, $landlord_id, 150, $overpay, 0.00, 'expense_payments', $expPayId]);
        }

        return $journalEntryId;
    } catch (Exception $e) {
        throw new Exception("Journal error: " . $e->getMessage());
    }
}
