<?php
// payExpenseJournal.php

function recordExpensePaymentJournal($pdo, $expected_amount, $expense_id, $amount, $paymentAccountId, $payment_date, $expPayId) {

    echo "yoyoyoy";
    try {
        // Insert into journal_entries
        $stmtEntry = $pdo->prepare("INSERT INTO journal_entries 
            (description, reference, entry_date, source_table, source_id, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())");

        $stmtEntry->execute([
            "Payment for Expense #{$expense_id}",
            "EXP-PAY-{$expense_id}",
            $payment_date,
            "expenses_payments",
            $expPayId 
        ]);

        $journalEntryId = $pdo->lastInsertId();

        // $remaining = $expected_amount - $amount;

        // Insert into journal_lines
        $stmtLine = $pdo->prepare("INSERT INTO journal_lines 
            (journal_entry_id, account_id, debit, credit, source_table, source_table_id, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())");

        if ($amount == $expected_amount) {
            // ✅ Full payment
            $stmtLine->execute([$journalEntryId, $paymentAccountId, 0.00, $amount, 'expense_payments', $expPayId]); // Credit Bank
            $stmtLine->execute([$journalEntryId, 300, $amount, 0.00,'expense_payments', $expPayId ]);              // Debit A/P

        } elseif ($amount < $expected_amount) {
            // ✅ Partial payment
            $stmtLine->execute([$journalEntryId, $paymentAccountId, 0.00, $amount, 'expense_payments', $expPayId]); // Credit Bank
            $stmtLine->execute([$journalEntryId, 300, $amount, 0.00, 'expense_payments', $expPayId]);              // Debit A/P

        } else {
            // ✅ Overpayment → Prepaid Expense
    $overpay = $amount - $expected_amount;

    // Pay off expected
    $stmtLine->execute([$journalEntryId, $paymentAccountId, 0.00, $expected_amount, 'expense_payments', $expPayId]); // Credit Bank
    $stmtLine->execute([$journalEntryId, 300, $expected_amount, 0.00, 'expense_payments', $expPayId]);              // Debit A/P

    // Record overpayment
    $stmtLine->execute([$journalEntryId, $paymentAccountId, 0.00, $overpay, 'expense_payments', $expPayId]);        // Credit Bank
    $stmtLine->execute([$journalEntryId, 150, $overpay, 0.00, 'expense_payments', $expPayId]);                     
        }

        return $journalEntryId;

    } catch (Exception $e) {
        throw new Exception("Journal error: " . $e->getMessage());
    }
}
?>