<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$expenses = [];
$monthlyTotals = [];
$errorMessage = null;

try {
    /* -------------------------------------------------
       1) Auth check
    ------------------------------------------------- */
    if (!isset($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated.');
    }

    $userId = (int) $_SESSION['user']['id'];

    /* -------------------------------------------------
       2) Resolve landlord_id for this user
    ------------------------------------------------- */
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord record not found.');
    }

    $landlordId = (int) $landlord['id'];

    /* -------------------------------------------------
       3) Fetch expenses for this landlord (with total_paid per expense)
       Assumption: expenses has landlord_id
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT 
            e.*,
            COALESCE(SUM(ep.amount_paid), 0) AS total_paid
        FROM expenses e
        LEFT JOIN expense_payments ep 
            ON e.id = ep.expense_id
        WHERE e.landlord_id = ?
        GROUP BY e.id
        ORDER BY e.created_at DESC
    ");
    $stmt->execute([$landlordId]);
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* -------------------------------------------------
       4) Summary values
    ------------------------------------------------- */
    $expenseItemsNumber = count($expenses);

    // Sum of paid amounts across the fetched expenses
    $totalAmount = 0.0;
    foreach ($expenses as $exp) {
        $totalAmount += (float) ($exp['total_paid'] ?? 0);
    }

    /* -------------------------------------------------
       5) Totals by status (filtered by landlord)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT
            SUM(CASE WHEN status = 'paid' THEN total ELSE 0 END) AS total_paid,
            SUM(CASE WHEN status = 'unpaid' THEN total ELSE 0 END) AS total_unpaid,
            SUM(CASE WHEN status = 'overpaid' THEN total ELSE 0 END) AS total_overpaid,
            SUM(CASE WHEN status = 'partially paid' THEN total ELSE 0 END) AS partially_paid
        FROM expenses
        WHERE landlord_id = ?
    ");
    $stmt->execute([$landlordId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    $fullyPaidExact       = (float) ($row['total_paid'] ?? 0);
    $fullyPaidOver        = (float) ($row['total_overpaid'] ?? 0);
    $unpaidTotal          = (float) ($row['total_unpaid'] ?? 0);
    $partiallyPaidTotal   = (float) ($row['partially_paid'] ?? 0);

    /* -------------------------------------------------
       6) Total amount paid (sum of all payments tied to this landlord's expenses)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(ep.amount_paid), 0) AS total_paid
        FROM expense_payments ep
        INNER JOIN expenses e ON e.id = ep.expense_id
        WHERE e.landlord_id = ?
    ");
    $stmt->execute([$landlordId]);
    $totalAmountPaid = (float) (($stmt->fetch(PDO::FETCH_ASSOC)['total_paid']) ?? 0);

    /* -------------------------------------------------
       7) Excess paid for overpaid expenses (single query)
       excess = sum(payments) - total, for each overpaid expense, summed and clipped at 0
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(GREATEST(paid_sum - total, 0)), 0) AS excess_amount
        FROM (
            SELECT e.id, e.total, COALESCE(SUM(ep.amount_paid), 0) AS paid_sum
            FROM expenses e
            LEFT JOIN expense_payments ep ON ep.expense_id = e.id
            WHERE e.landlord_id = ?
              AND e.status = 'overpaid'
            GROUP BY e.id
        ) t
    ");
    $stmt->execute([$landlordId]);
    $excess_amount = (float) (($stmt->fetch(PDO::FETCH_ASSOC)['excess_amount']) ?? 0);

    $totalAmountSend = $totalAmountPaid + $excess_amount;

    /* -------------------------------------------------
       8) Pending total for partially paid (single query)
       pending = total - sum(payments) for partially paid expenses (only positive)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(GREATEST(total - paid_sum, 0)), 0) AS pending_total
        FROM (
            SELECT e.id, e.total, COALESCE(SUM(ep.amount_paid), 0) AS paid_sum
            FROM expenses e
            LEFT JOIN expense_payments ep ON ep.expense_id = e.id
            WHERE e.landlord_id = ?
              AND e.status = 'partially paid'
            GROUP BY e.id
        ) t
    ");
    $stmt->execute([$landlordId]);
    $pendingTotal = (float) (($stmt->fetch(PDO::FETCH_ASSOC)['pending_total']) ?? 0);

    /* -------------------------------------------------
       9) Total remaining
    ------------------------------------------------- */
    $TotalRemaining = $unpaidTotal + $pendingTotal;

    /* -------------------------------------------------
       10) Monthly totals (filtered by landlord)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT
            MONTH(expense_date) AS month,
            SUM(total) AS total
        FROM expenses
        WHERE landlord_id = ?
        GROUP BY MONTH(expense_date)
    ");
    $stmt->execute([$landlordId]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthlyTotals[(int) $row['month']] = (float) $row['total'];
    }

} catch (Throwable $e) {
    $errorMessage = "❌ Failed to fetch expenses: " . $e->getMessage();
}
