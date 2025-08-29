<?php
function createPayInvoiceJournal($pdo, $paymentId, $invoiceId, $customerId, $paymentAmount, $paymentAccountId, $remaining, $total_amount)
{
    // Example COA IDs
    $accountsReceivableId = 130;
    $prepaidCustomerId    = 150; // Customer Advance/Prepaid (Liability)

    // Insert journal entry
    $stmt = $pdo->prepare("INSERT INTO journal_entries (reference, description) VALUES (?, ?)");
    $stmt->execute(["PAY-$paymentId", "Payment for Invoice #$invoiceId by Customer $customerId"]);
    $journalEntryId = $pdo->lastInsertId();
    // exit;

    $stmtLine = $pdo->prepare("INSERT INTO journal_lines (journal_entry_id, account_id, debit, credit) VALUES (?, ?, ?, ?)");
    error_log("Journal created: entry=$journalEntryId, debitAccount=$paymentAccountId, creditAccount=$accountsReceivableId, amount=$paymentAmount");

    // exit;
    if ($remaining>0) {

        $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00]);
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $paymentAmount]);
    } elseif ($remaining==0) {
        //     // ✅ Full Payment
        $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00]);
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $paymentAmount]);
    } else{
    
        // make the remaing amount a positive value for proper accounting.
        $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00]);
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $total_amount]);
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, abs($remaining)]);
    }
    // exit;
}
