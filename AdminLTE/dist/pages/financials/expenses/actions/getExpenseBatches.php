<?php
include '../../db/connect.php';

try {
    // Fetch all expenses and include the paid amount (if any)
    $stmt = $pdo->prepare("
        SELECT
            expenses.*,
            expense_payments.amount_paid AS amount_paid
        FROM expenses
        LEFT JOIN expense_payments
        ON expenses.id = expense_payments.expense_id
        ORDER BY expenses.created_at DESC
    ");
    $stmt->execute();
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // For summary section
    $expenseItemsNumber = count($expenses);
    $totalAmount = 0;
    foreach ($expenses as $exp) {
        $totalAmount += $exp['total'];
    }

    $stmt = $pdo->prepare("
        SELECT
            SUM(CASE WHEN status = 'paid' THEN total ELSE 0 END) AS total_paid,
            SUM(CASE WHEN status = 'unpaid' THEN total ELSE 0 END) AS total_unpaid,
            SUM(CASE WHEN status = 'partially paid' THEN total ELSE 0 END) AS partially_paid
        FROM expenses
    ");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalPaid = $row['total_paid'] ?? 0;
    $totalUnpaid = $row['total_unpaid'] ?? 0;
    $totalPartiallyPaid = $row['partially_paid'] ?? 0;

    $pending = $totalUnpaid + $totalPartiallyPaid;

    // Monthly totals
    $stmt = $pdo->prepare("
        SELECT
            MONTH(expense_date) as month,
            SUM(total) as total
        FROM expenses
        GROUP BY MONTH(expense_date)
    ");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthlyTotals[(int)$row['month']] = (float)$row['total'];
    }
} catch (PDOException $e) {
    $errorMessage = "❌ Failed to fetch expenses: " . $e->getMessage();
}

?>