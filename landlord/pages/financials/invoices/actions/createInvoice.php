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
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['create_invoice'])) {
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
    $invoice_no    = $_POST['invoice_no'] ?? null;
    $issue_date  = $_POST['issue_date'] ?? null;
    $due_date  = $_POST['due_date'] ?? null;
    $building_id   = $_POST['building_id'] ?? null;
    $unit_id   = $_POST['unit_id'] ?? null;
    $tenant_id   = $_POST['tenant_id'] ?? null;


    $untaxedAmount = (float)($_POST['untaxedAmount'] ?? 0);
    $totalTax      = (float)($_POST['totalTax'] ?? 0);
    $total         = (float)($_POST['total'] ?? 0);

   
    /* -----------------------------
     * 2. Insert invoice
     * ----------------------------- */
    $stmt = $pdo->prepare("
        INSERT INTO invoices (
            tenant_id, unit_id, invoice_no, invoice_date, due_date, building_id,
            untaxed_amount, taxes, total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $tenant_id,
        $unit_id,
        $invoice_no,
        $issue_date,
        $due_date,  // Correctly using landlord_id
        8,
        $untaxedAmount,
        1000,
        $total
    ]);

    $invoice_id = (int)$pdo->lastInsertId();

    /* -----------------------------
     * 3. Insert expense items
     * ----------------------------- */
//     $item_account_codes = $_POST['item_account_code'] ?? [];
//     $descriptions       = $_POST['description'] ?? [];
//     $quantities         = $_POST['qty'] ?? [];
//     $unit_prices        = $_POST['unit_price'] ?? [];
//     $taxes              = $_POST['taxes'] ?? [];
//     $item_totals        = $_POST['item_totalForStorage'] ?? [];
//     $discounts          = $_POST['discount'] ?? [];

//     if (count($item_account_codes) === 0) {
//         throw new Exception('Please add at least one expense item.');
//     }

//     $stmtItem = $pdo->prepare("
//         INSERT INTO invoice_items (
//         landlord_id, item_account_code, expense_id, building_id, description, qty,
//             unit_price, item_untaxed_amount, taxes, item_total, discount
//         ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
//     ");

//     for ($i = 0; $i < count($item_account_codes); $i++) {
//         $qty        = (float)($quantities[$i] ?? 0);
//         $unit_price = (float)($unit_prices[$i] ?? 0);
//         $discount   = (float)($discounts[$i] ?? 0);

//         $item_untaxed_amount = $qty * $unit_price;

//         $stmtItem->execute([
//             $landlord_id,
//             $item_account_codes[$i],
//             $expense_id,
//             $building_id,
//             $descriptions[$i] ?? '',
//             $qty,
//             $unit_price,
//             $item_untaxed_amount,
//             $taxes[$i] ?? '',
//             (float)($item_totals[$i] ?? 0),
//             $discount
//         ]);
//     }

//     /* -----------------------------
//  * 4. Create journal entry
//  * ----------------------------- */
//     $journalId = createJournalEntry($pdo, [
//         'description'  => 'Invoice Transaction',
//         'reference'    => $invoice_no,
//         'date'         => $invoice_date,
//         'source_table' => 'invoices',
//         'source_id'    => $invoice_id
//     ]);

//     /* -----------------------------
//  * 5. Journal lines (FIXED)
//  * ----------------------------- */
//     $vatRate = 0.16;

//     // (optional) accumulate totals if you ever want to post AP as one line
//     // $totalNet = 0.0;
//     // $totalVat = 0.0;
//     // $totalGross = 0.0;

//     for ($i = 0; $i < count($item_account_codes); $i++) {

//         $accountCode = (int)($item_account_codes[$i] ?? 0);
//         $qty         = (float)($quantities[$i] ?? 0);
//         $unit_price  = (float)($unit_prices[$i] ?? 0);
//         $tax_type    = strtolower(trim((string)($taxes[$i] ?? 'exclusive')));

//         if ($accountCode <= 0 || $qty <= 0 || $unit_price < 0) {
//             continue; // skip invalid lines safely
//         }

//         // "lineTotal" is the amount as entered on the line (could be net or gross depending on tax type)
//         $lineTotal = $qty * $unit_price;

//         $netAmount   = 0.0;
//         $vatAmount   = 0.0;
//         $grossAmount = 0.0;

//         if ($tax_type === 'exclusive') {
//             // Entered amount is NET
//             $netAmount   = $lineTotal;
//             $vatAmount   = $netAmount * $vatRate;
//             $grossAmount = $netAmount + $vatAmount;
//         } elseif ($tax_type === 'inclusive') {
//             // Entered amount is GROSS
//             $grossAmount = $lineTotal;
//             $vatAmount   = $grossAmount * ($vatRate / (1 + $vatRate)); // 16/116
//             $netAmount   = $grossAmount - $vatAmount;
//         } else {
//             // If tax type unknown, treat as no VAT
//             $netAmount   = $lineTotal;
//             $vatAmount   = 0.0;
//             $grossAmount = $lineTotal;
//         }

//         // Optional rounding to 2dp to avoid tiny float leftovers
//         $netAmount   = round($netAmount, 2);
//         $vatAmount   = round($vatAmount, 2);
//         $grossAmount = round($grossAmount, 2);

//         /* 1) Debit expense (NET) */
//         addJournalLine(
//             $pdo,
//             $journalId,
//             $building_id,
//             $landlord_id,
//             $accountCode,
//             $netAmount,
//             0.0,
//             'expenses',
//             $expense_id
//         );

//         /* 2) Debit VAT receivable/input VAT (VAT) */
//         if ($vatAmount > 0) {
//             addJournalLine(
//                 $pdo,
//                 $journalId,
//                 $building_id,
//                 $landlord_id,
//                 325,          // VAT Input / VAT Receivable account code
//                 $vatAmount,
//                 0.0,
//                 'expenses',
//                 $expense_id
//             );
//         }

//         /* 3) Credit Accounts Payable (GROSS) */
//         addJournalLine(
//             $pdo,
//             $journalId,
//             $building_id,
//             $landlord_id,
//             300,            // Accounts Payable account code
//             0.0,
//             $grossAmount,
//             'expenses',
//             $expense_id
//         );

//         // Totals if needed later
//         // $totalNet += $netAmount;
//         // $totalVat += $vatAmount;
//         // $totalGross += $grossAmount;
//     }


//     /* -----------------------------
//      * 6. Commit + flash success
//      * ----------------------------- */
    $pdo->commit();

    $_SESSION['success'] =
        "Invoice created successfully (Expense No: {$expense_no}).";

    header('Location: /Jengopay/landlord/pages/financials/invoices/invoices.php');
    exit;
} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['error'] =
        'Failed to create invoices: ' . $e->getMessage();

    header('Location: /Jengopay/landlord/pages/financials/invoices/invoices.php');
    exit;
}
