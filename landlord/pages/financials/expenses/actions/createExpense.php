<?php
// expense journal
include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/financials/expenses/actions/journals/expenseJournal.php';


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_expense'])) {
    try {
        // Show errors during development
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $pdo->beginTransaction();

        $expense_date = $_POST['date'] ?? null;
        $expense_no = $_POST['expense_no'] ?? null;
        $building_id = $_POST['building_id'] ?? null;
        $supplier_name = $_POST['supplier_name'] ?? null;
        $supplier_id = $_POST['supplier_id'] ?? null;
        $untaxedAmount = $_POST['untaxedAmount'] ?? 0.00;
        $totalTax = $_POST['totalTax'] ?? 0.00;
        $total = $_POST['total'] ?? 0.00;

        // Step 1: Insert into expenses
        $stmt = $pdo->prepare("INSERT INTO expenses (
            supplier_id, building_id, expense_date, expense_no, supplier,
            untaxed_amount, total_taxes, total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $supplier_id,
            $building_id,
            $expense_date,
            $expense_no,
            $supplier_name, // save visible name too (redundantly stored in 'supplier' column)
            $untaxedAmount,
            $totalTax,
            $total
        ]);

        $expense_id = $pdo->lastInsertId();

        // Step 3: Insert expense items
        $item_account_codes = $_POST['item_account_code'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $quantities = $_POST['qty'] ?? [];
        $unit_prices = $_POST['unit_price'] ?? [];
        $taxes = $_POST['taxes'] ?? [];
        $item_totals = $_POST['item_totalForStorage'] ?? [];
        $discounts = $_POST['discount'] ?? [];

        var_dump($item_totals);
        // exit;

        $stmtItem = $pdo->prepare("INSERT INTO expense_items (
            item_account_code, expense_id, building_id, description, qty,
            unit_price, item_untaxed_amount, taxes, item_total, discount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        for ($i = 0; $i < count($item_account_codes); $i++) {
            $item_untaxed_amount = $quantities[$i] * $unit_prices[$i];

            $stmtItem->execute([
                $item_account_codes[$i],
                $expense_id,
                $building_id,
                $descriptions[$i],
                $quantities[$i],
                $unit_prices[$i],
                $item_untaxed_amount,
                $taxes[$i],
                $item_totals[$i],
                $discounts[$i]
            ]);
        }

        // 4. Create journal entry
        $journalId = createJournalEntry($pdo, [
            'description'   => "Expense Transaction",
            'reference'     => $expense_no,
            'date'          => $expense_date,
            'source_table'  => 'expenses',
            'source_id'     => $expense_id
        ]);

        // 5. Journal lines
        for ($i = 0; $i < count($item_account_codes); $i++) {
            $item_untaxed_amount = $quantities[$i] * $unit_prices[$i];
            $tax_type = strtolower($taxes[$i]); // normalize for safety

            $tax_amount = 0.00;
            $amount = $item_untaxed_amount;

            if ($tax_type === 'exclusive') {
                $tax_amount = $item_untaxed_amount * 0.16;
                $amount = $item_untaxed_amount; // expense is untaxed
            } elseif ($tax_type === 'inclusive') {
                $tax_amount = $item_untaxed_amount * (16 / 116);
                $amount = $item_untaxed_amount - $tax_amount; // net of VAT
            } elseif ($tax_type === 'zero rated' || $tax_type === 'exempted') {
                $tax_amount = 0.00;
                $amount = $item_untaxed_amount; // whole amount is expense
            }

            // Debit expense
            addJournalLine($pdo, $journalId, $item_account_codes[$i], $amount, 0.00, 'expenses', $expense_id);

            // Credit Accounts Payable (gross)
            addJournalLine($pdo, $journalId, 300, 0.00, $amount + $tax_amount, 'expenses', $expense_id);

            // Debit VAT payable (only if tax exists)
            if ($tax_amount > 0) {
                addJournalLine($pdo, $journalId, 325, $tax_amount, 0.00, 'expenses', $expense_id);
            }
            echo $tax_amount;
        }


        $pdo->commit();
        echo "Inserted successfully with Expense ID: $expense_id";
    
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
