<?php
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../db/connect.php';

// Expense journal helpers
require_once $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/financials/expenses/actions/journalHelpers/expenseJournal.php';

// Only handle the form submit
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['create_expenseOne'])) {
    return; // included file: allow the page to continue
}

try {
    // DEV ONLY — remove in production
    ini_set('display_errors', '1');
    error_reporting(E_ALL);

    /* -------------------------------------------------
       0) Resolve landlord_id
    ------------------------------------------------- */
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int)$_SESSION['user']['id'];

    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord_id = (int)($stmt->fetchColumn() ?: 0);

    if ($landlord_id <= 0) {
        throw new Exception("Landlord account not found for this user.");
    }

    $pdo->beginTransaction();

    /* -------------------------------------------------
       1) Read header input + validate
    ------------------------------------------------- */
    $expense_date = trim((string)($_POST['date'] ?? ''));
    $expense_no   = trim((string)($_POST['expense_no'] ?? ''));
    $building_id  = (int)($_POST['building_id'] ?? 0);
    $supplier_id  = (int)($_POST['supplier_id'] ?? 0);

    $untaxedAmount = (float)($_POST['untaxedAmount'] ?? 0);
    $totalTax      = (float)($_POST['totalTax'] ?? 0);
    $total         = (float)($_POST['total'] ?? 0);

    $missingHeader = [];

    if ($expense_date === '') $missingHeader[] = 'date';
    if ($expense_no === '')   $missingHeader[] = 'expense_no';
    if ($building_id <= 0)    $missingHeader[] = 'building_id';
    if ($supplier_id <= 0) $missingHeader[] = 'supplier_id';

    if (!empty($missingHeader)) {
        throw new Exception("Missing required expense details: " . implode(', ', $missingHeader));
    }

    /* -------------------------------------------------
       2) Validate item rows (and build clean rows list)
    ------------------------------------------------- */
    $item_account_codes = $_POST['item_account_code'] ?? [];
    $descriptions       = $_POST['description'] ?? [];
    $quantities         = $_POST['qty'] ?? [];
    $unit_prices        = $_POST['unit_price'] ?? [];
    $taxes              = $_POST['taxes'] ?? [];
    $item_totals        = $_POST['item_totalForStorage'] ?? [];
    $discounts          = $_POST['discount'] ?? [];

    if (!is_array($item_account_codes) || count($item_account_codes) === 0) {
        throw new Exception('Please add at least one expense item.');
    }

    $rows = [];
    $rowErrors = [];

    $rowCount = count($item_account_codes);

    for ($i = 0; $i < $rowCount; $i++) {
        $accountCode = trim((string)($item_account_codes[$i] ?? ''));
        $desc        = trim((string)($descriptions[$i] ?? ''));
        $qtyRaw      = $quantities[$i] ?? '';
        $priceRaw    = $unit_prices[$i] ?? '';
        $taxType     = trim((string)($taxes[$i] ?? 'exclusive'));
        $discount    = (float)($discounts[$i] ?? 0);
        $itemTotal   = (float)($item_totals[$i] ?? 0);

        // Detect completely empty line (common UI artifact) and skip it
        $isEmptyLine =
            $accountCode === '' &&
            $desc === '' &&
            trim((string)$qtyRaw) === '' &&
            trim((string)$priceRaw) === '';

        if ($isEmptyLine) {
            continue;
        }

        $missingRow = [];

        // Required per line
        if ($accountCode === '' || (int)$accountCode <= 0) $missingRow[] = 'account_code';
        if ($desc === '')                                  $missingRow[] = 'description';

        $qty = (float)$qtyRaw;
        if ($qty <= 0)                                     $missingRow[] = 'qty';

        $unitPrice = (float)$priceRaw;
        if ($unitPrice < 0)                                $missingRow[] = 'unit_price';

        if (!empty($missingRow)) {
            $rowErrors[] = "Item row " . ($i + 1) . ": missing/invalid " . implode(', ', $missingRow);
            continue;
        }

        $rows[] = [
            'account_code' => (int)$accountCode,
            'description'  => $desc,
            'qty'          => $qty,
            'unit_price'   => $unitPrice,
            'tax_type'     => strtolower($taxType),
            'discount'     => $discount,
            'item_total'   => $itemTotal,
        ];
    }

    if (!empty($rowErrors)) {
        throw new Exception("Please fix the following item rows:\n- " . implode("\n- ", $rowErrors));
    }

    if (count($rows) === 0) {
        throw new Exception('Please add at least one valid expense item.');
    }

    /* -------------------------------------------------
       3) Insert expense header
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        INSERT INTO expenses (
            supplier_id, building_id, landlord_id, expense_date, expense_no,
            untaxed_amount, total_taxes, total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $supplier_id ?: null,  // allow null if not required
        $building_id,
        $landlord_id,
        $expense_date,
        $expense_no,
        $untaxedAmount,
        $totalTax,
        $total
    ]);

    $expense_id = (int)$pdo->lastInsertId();

    /* -------------------------------------------------
       4) Insert expense items
    ------------------------------------------------- */
    $stmtItem = $pdo->prepare("
        INSERT INTO expense_items (
            item_account_code, expense_id, description, qty,
            unit_price, item_untaxed_amount, taxes, item_total, discount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($rows as $r) {
        $itemUntaxed = round($r['qty'] * $r['unit_price'], 2);

        $stmtItem->execute([
            $r['account_code'],
            $expense_id,
            $r['description'],
            $r['qty'],
            $r['unit_price'],
            $itemUntaxed,
            $r['tax_type'],
            (float)$r['item_total'],
            (float)$r['discount'],
        ]);
    }

    /* -------------------------------------------------
       5) Create journal entry + lines
    ------------------------------------------------- */
    $journalId = createJournalEntry($pdo, [
        'description'  => 'Expense Transaction',
        'reference'    => $expense_no,
        'date'         => $expense_date,
        'source_table' => 'expenses',
        'source_id'    => $expense_id
    ]);

    $vatRate = 0.16;

    foreach ($rows as $r) {
        $accountCode = (int)$r['account_code'];
        $lineTotal   = $r['qty'] * $r['unit_price'];
        $tax_type    = $r['tax_type'];

        $netAmount = $vatAmount = $grossAmount = 0.0;

        if ($tax_type === 'exclusive') {
            $netAmount   = $lineTotal;
            $vatAmount   = $netAmount * $vatRate;
            $grossAmount = $netAmount + $vatAmount;
        } elseif ($tax_type === 'inclusive') {
            $grossAmount = $lineTotal;
            $vatAmount   = $grossAmount * ($vatRate / (1 + $vatRate)); // 16/116
            $netAmount   = $grossAmount - $vatAmount;
        } else {
            $netAmount   = $lineTotal;
            $vatAmount   = 0.0;
            $grossAmount = $lineTotal;
        }

        $netAmount   = round($netAmount, 2);
        $vatAmount   = round($vatAmount, 2);
        $grossAmount = round($grossAmount, 2);

        // 1) Debit expense (NET)
        addJournalLine($pdo, $journalId, $building_id, $landlord_id, $accountCode, $netAmount, 0.0, 'expenses', $expense_id);

        // 2) Debit VAT input (VAT)
        if ($vatAmount > 0) {
            addJournalLine($pdo, $journalId, $building_id, $landlord_id, 325, $vatAmount, 0.0, 'expenses', $expense_id);
        }

        // 3) Credit Accounts Payable (GROSS)
        addJournalLine($pdo, $journalId, $building_id, $landlord_id, 300, 0.0, $grossAmount, 'expenses', $expense_id);
    }

    /* -------------------------------------------------
       6) Commit + redirect
    ------------------------------------------------- */
    $pdo->commit();

    $_SESSION['success'] = "Expense created successfully (Expense No: {$expense_no}).";
    header('Location: /Jengopay/landlord/pages/financials/expenses/expenses.php');
    exit;

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Preserve line breaks for readability (you can nl2br() when displaying)
    $_SESSION['error'] = 'Failed to create expense: ' . $e->getMessage();
    header('Location: /Jengopay/landlord/pages/financials/expenses/expenses.php');
    exit;
}
