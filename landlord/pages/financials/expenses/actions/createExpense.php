<?php

// Session variables to use
$userId = $_SESSION['user']['id'];

// Fetch landlord ID linked to the logged-in user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
$stmt->execute([$userId]);
$landlord = $stmt->fetch();

// Check if landlord exists for the user
if (!$landlord) {
    throw new Exception("Landlord account not found for this user.");
}

$landlord_id = $landlord['id']; // Store the landlord_id from the session

// Expense journal helpers
require_once $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/financials/expenses/actions/journalHelpers/expenseJournal.php';

// If it's not a POST request, return early
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['create_expense'])) {
    return; // do nothing on GET
}

try {
    // DEV ONLY — remove in production
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $pdo->beginTransaction();

    /* -----------------------------
     * 1. Read & validate input
     * ----------------------------- */
    $expense_date  = $_POST['date'] ?? null;
    $expense_no    = $_POST['expense_no'] ?? null;
    $building_id   = $_POST['building_id'] ?? null;
    $supplier_id   = $_POST['supplier_id'] ?? null;

    $untaxedAmount = (float)($_POST['untaxedAmount'] ?? 0);
    $totalTax      = (float)($_POST['totalTax'] ?? 0);
    $total         = (float)($_POST['total'] ?? 0);

    if (!$expense_date || !$expense_no || !$building_id) {
        throw new Exception('Missing required expense details.');
    }

    /* -----------------------------
     * 2. Insert expense
     * ----------------------------- */
    $stmt = $pdo->prepare("
        INSERT INTO expenses (
            supplier_id, building_id, landlord_id, expense_date, expense_no,
            untaxed_amount, total_taxes, total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $supplier_id,
        $building_id,
        $landlord_id,  // Correctly using landlord_id
        $expense_date,
        $expense_no,
        $untaxedAmount,
        $totalTax,
        $total
    ]);

    $expense_id = (int)$pdo->lastInsertId();

    /* -----------------------------
     * 3. Insert expense items
     * ----------------------------- */
    $item_account_codes = $_POST['item_account_code'] ?? [];
    $descriptions       = $_POST['description'] ?? [];
    $quantities         = $_POST['qty'] ?? [];
    $unit_prices        = $_POST['unit_price'] ?? [];
    $taxes              = $_POST['taxes'] ?? [];
    $item_totals        = $_POST['item_totalForStorage'] ?? [];
    $discounts          = $_POST['discount'] ?? [];

    if (count($item_account_codes) === 0) {
        throw new Exception('Please add at least one expense item.');
    }

    $stmtItem = $pdo->prepare("
        INSERT INTO expense_items (
            item_account_code, expense_id, building_id, description, qty,
            unit_price, item_untaxed_amount, taxes, item_total, discount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    for ($i = 0; $i < count($item_account_codes); $i++) {
        $qty        = (float)($quantities[$i] ?? 0);
        $unit_price = (float)($unit_prices[$i] ?? 0);
        $discount   = (float)($discounts[$i] ?? 0);

        $item_untaxed_amount = $qty * $unit_price;

        $stmtItem->execute([
            $item_account_codes[$i],
            $expense_id,
            $building_id,
            $descriptions[$i] ?? '',
            $qty,
            $unit_price,
            $item_untaxed_amount,
            $taxes[$i] ?? '',
            (float)($item_totals[$i] ?? 0),
            $discount
        ]);
    }

    /* -----------------------------
     * 4. Create journal entry
     * ----------------------------- */
    $journalId = createJournalEntry($pdo, [
        'description'  => 'Expense Transaction',
        'reference'    => $expense_no,
        'date'         => $expense_date,
        'source_table' => 'expenses',
        'source_id'    => $expense_id
    ]);

    /* -----------------------------
     * 5. Journal lines
     * ----------------------------- */
    for ($i = 0; $i < count($item_account_codes); $i++) {
        $qty        = (float)($quantities[$i] ?? 0);
        $unit_price = (float)($unit_prices[$i] ?? 0);
        $tax_type   = strtolower(trim((string)($taxes[$i] ?? '')));

        $item_untaxed_amount = $qty * $unit_price;

        $tax_amount = 0.0;
        $total_item_amount = 0.0;

        if ($tax_type === 'exclusive') {
            $tax_amount = $item_untaxed_amount * 0.16;
            $total_item_amount = $item_untaxed_amount + $tax_amount;
        } elseif ($tax_type === 'inclusive') {
            $tax_amount = $item_untaxed_amount * (16 / 116);
            $amount -= $tax_amount;
            $total_item_amount = $item_untaxed_amount;
        }

        // Debit expense
        addJournalLine(
            $pdo,
            $journalId,
            $landlord_id,  // Ensure landlord_id is passed
            $item_account_codes[$i],
            $total_item_amount,
            0.0,
            'expenses',
            $expense_id
        );

        // Credit accounts payable
        addJournalLine(
            $pdo,
            $journalId,
            $landlord_id,  // Ensure landlord_id is passed
            300,
            0.0,
            $total_item_amount,
            'expenses',
            $expense_id
        );

        // VAT
        if ($tax_amount > 0) {
            addJournalLine(
                $pdo,
                $journalId,
                $landlord_id,  // Ensure landlord_id is passed
                325,
                $tax_amount,
                0.0,
                'expenses',
                $expense_id
            );
        }
    }

    /* -----------------------------
     * 6. Commit + flash success
     * ----------------------------- */
    $pdo->commit();

    $_SESSION['success'] =
        "Expense created successfully (Expense No: {$expense_no}).";

    header('Location: /Jengopay/landlord/pages/financials/expenses/expenses.php');
    exit;
} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['error'] =
        'Failed to create expense: ' . $e->getMessage();

    header('Location: /Jengopay/landlord/pages/financials/expenses/expenses.php');
    exit;
}
