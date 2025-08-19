<?php
function createPayInvoiceJournal($pdo, $paymentId, $invoiceId, $customerId, $paymentAmount, $paymentAccountId, $remaining) {
    // Example COA IDs
    $accountsReceivableId = 130; 
    // $prepaidCustomerId    = 2200; // Customer Advance/Prepaid (Liability)

    // Insert journal entry
    $stmt = $pdo->prepare("INSERT INTO journal_entries (reference, description) VALUES (?, ?)");
    $stmt->execute(["PAY-$paymentId", "Payment for Invoice #$invoiceId by Customer $customerId"]);
    $journalEntryId = $pdo->lastInsertId();
   // exit;

     $stmtLine = $pdo->prepare("INSERT INTO journal_lines (journal_entry_id, account_id, debit, credit) VALUES (?, ?, ?, ?)");
    error_log("Journal created: entry=$journalEntryId, debitAccount=$paymentAccountId, creditAccount=$accountsReceivableId, amount=$paymentAmount");

// exit;
    //  if ($paymentAmount == $remaining) {
         // ✅ Partial Payment
        $paymentAmount = $paymentAmount/2;
        $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00]); 
        $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $paymentAmount]);
    //  } 
    //  elseif ($paymentAmount == $remaining) {
    //     // ✅ Full Payment
    //     $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00]); 
    //     $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $paymentAmount]);

    // } elseif ($paymentAmount > $remaining) {
    //     // ✅ Overpayment
    //     // $stmtLine->execute([$journalEntryId, $paymentAccountId, $paymentAmount, 0.00]); 
    //     // $stmtLine->execute([$journalEntryId, $accountsReceivableId, 0.00, $remaining]); 
    //     // $stmtLine->execute([$journalEntryId, $prepaidCustomerId, 0.00, $paymentAmount - $remaining]); 
    // }
    // exit;
}
