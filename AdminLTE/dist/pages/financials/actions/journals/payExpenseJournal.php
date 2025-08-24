<?php
// payExpenseJournal.php

function recordExpensePaymentJournal($pdo, $expected_amount, $expense_id, $amount, $paymentAccountId, $payment_date) {
    try {
        // Insert into journal_entries
        $stmtEntry = $pdo->prepare("INSERT INTO journal_entries 
            (description, reference, entry_date, source_table, source_id, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())");

        $stmtEntry->execute([
            "Payment for Expense #{$expense_id}",
            "EXP-PAY-{$expense_id}",
            $payment_date,
            "expenses",
            $expense_id
        ]);

        $journalEntryId = $pdo->lastInsertId();

        // $remaining = $expected_amount - $amount;

        // Insert into journal_lines
        $stmtLine = $pdo->prepare("INSERT INTO journal_lines 
            (journal_entry_id, account_id, debit, credit, created_at) 
            VALUES (?, ?, ?, ?, NOW())");

        if ($amount == $expected_amount) {
            // ✅ Full payment
            $stmtLine->execute([$journalEntryId, $paymentAccountId, 0.00, $amount]); // Credit Bank
            $stmtLine->execute([$journalEntryId, 300, $amount, 0.00]);              // Debit A/P

        } elseif ($amount < $expected_amount) {
            // ✅ Partial payment
            $stmtLine->execute([$journalEntryId, $paymentAccountId, 0.00, $amount]); // Credit Bank
            $stmtLine->execute([$journalEntryId, 300, $amount, 0.00]);              // Debit A/P

        } else {
            // ✅ Overpayment → Prepaid Expense
            $overpay = $amount - $expected_amount;

            // Pay off remaining
            $stmtLine->execute([$journalEntryId, $paymentAccountId, 0.00, $expected_amount]); // Credit Bank
            $stmtLine->execute([$journalEntryId, 300, $expected_amount, 0.00]);              // Debit A/P

            // Overpayment
            $stmtLine->execute([$journalEntryId, $paymentAccountId, 0.00, $overpay]);   // Credit Bank
            $stmtLine->execute([$journalEntryId, 150, $overpay, 0.00]);                // Debit Prepaid Expense
        }

        return $journalEntryId;

    } catch (Exception $e) {
        throw new Exception("Journal error: " . $e->getMessage());
    }
}
?>