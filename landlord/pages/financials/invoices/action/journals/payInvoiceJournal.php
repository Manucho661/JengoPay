<?php
function createPayInvoiceJournal($pdo, $paymentId, $invoiceId, $customerId, $paymentAmount, $paymentAccountId, $remaining, $total_amount)
{
    // Example COA IDs
    $accountsReceivableId = 130;
    $sourceTable = "invoice";

    // Insert journal entry
    $stmt = $pdo->prepare("INSERT INTO journal_entries (reference, description) VALUES (?, ?)");
    $stmt->execute(["PAY-$paymentId", "Payment for Invoice #$invoiceId by Customer $customerId"]);
    $journalEntryId = $pdo->lastInsertId();
    // exit;

    $stmtLine = $pdo->prepare("INSERT INTO journal_lines (journal_entry_id, account_id, debit, credit) VALUES (?, ?, ?, ?, ?)");
    error_log("Journal created: entry=$journalEntryId, debitAccount=$paymentAccountId, creditAccount=$accountsReceivableId, amount=$paymentAmount, source_table=$sourceTable");

    // exit;
    if ($remaining>0) {

        $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00, $sourceTable]);
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $paymentAmount, $sourceTable]);
    } elseif ($remaining==0) {
        //     // ✅ Full Payment
        $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00, $sourceTable]);
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $paymentAmount, $sourceTable]);
    } else{
    
        // make the remaing amount a positive value for proper accounting.
        $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00, $sourceTable]);
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $total_amount, $sourceTable]);
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, abs($remaining)], $sourceTable);
    }
    // exit;
}
