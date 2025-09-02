<?php
function createInvoiceJournal($pdo, $invoiceId, $tenant_id, $item_account_codes, $quantities, $unit_prices, $vat_type, $total) {
   //  Chart of Accounts IDs 
    $accountsReceivableId = 130; // Accounts Receivable (Asset)
    $taxPayableId = 325;         // Tax Payable (Liability)

    // Insert Journal Entry
    $stmt = $pdo->prepare("INSERT INTO journal_entries (reference, description) VALUES (?, ?)");
    $stmt->execute(["INV-$invoiceId", "Invoice #$invoiceId issued to Tenant $tenant_id"]);
    $journalEntryId = $pdo->lastInsertId();

    // Insert Journal Lines
    $stmtLine = $pdo->prepare("INSERT INTO journal_lines (journal_entry_id, account_id, debit, credit) VALUES (?, ?, ?, ?)");

    // Debit Accounts Receivable for the FULL invoice
    $stmtLine->execute([$journalEntryId, $accountsReceivableId, $total, 0.00]);


    foreach ($item_account_codes as $i => $item) {
    $qty = floatval($quantities[$i]);
    $price = floatval($unit_prices[$i]);
    $sub_total = $qty * $price;
    $vat = trim($vat_type[$i] ?? '');
    $tax = 0.00;
    $revenueAmount = $sub_total;

    // Tax calculation
    if ($vat === 'exclusive') {
        // Revenue is subtotal, tax added on top
        $tax = round($sub_total * 0.16, 2);
    } elseif ($vat === 'inclusive') {
        // Subtotal includes tax → extract it
        $tax = round($sub_total * 16 / 116, 2);
        $revenueAmount = $sub_total - $tax; // ✅ remove tax from revenue
    }

    // Credit Revenue (excluding tax)
    if ($revenueAmount > 0) {
        $stmtLine->execute([$journalEntryId, trim($item), 0.00, $revenueAmount]);
    }

    // Credit Tax Payable (if applicable)
    if ($tax > 0) {
        $stmtLine->execute([$journalEntryId, $taxPayableId, 0.00, $tax]);
    }
}


}
?>