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
            SUM(CASE WHEN status = 'overpaid' THEN total ELSE 0 END) AS total_overpaid,
            SUM(CASE WHEN status = 'partially paid' THEN total ELSE 0 END) AS partially_paid
        FROM expenses
    ");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $fullyPaidExact = $row['total_paid'] ?? 0; // Exactly paid
    $fullyPaidOver = $row['total_overpaid'] ?? 0; // Paid with extra
    $unpaidTotal = $row['total_unpaid'] ?? 0;
    $partiallyPaidTotal = $row['partially_paid'] ?? 0;

    $pendingTotal = $unpaidTotal + $partiallyPaidTotal;


    // Total paid
    $totalAmountPaid = 0;
    try {
        // Prepare and execute the query
        $stmt = $pdo->prepare("SELECT SUM(amount_paid) AS total_paid FROM expense_payments");
        $stmt->execute();

        // Fetch the result
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalAmountPaid = $row['total_paid'] ?? 0;

    } catch (PDOException $e) {
        echo "❌ Error fetching total paid: " . $e->getMessage();
    }

    // extra amount paid
    $excess_amount = 0;
    try {
        // Step 1: Get all expenses marked as 'overpaid'
        $stmt = $pdo->prepare("SELECT id, total FROM expenses WHERE status = 'overpaid'");
        $stmt->execute();
        $overpaidExpenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($overpaidExpenses as $expense) {
            $expenseId = $expense['id'];
            $total = $expense['total'];

            // Step 2: Get the amount_paid from expense_payments
            $payStmt = $pdo->prepare("SELECT amount_paid FROM expense_payments WHERE expense_id = :expense_id LIMIT 1");
            $payStmt->execute(['expense_id' => $expenseId]);
            $payment = $payStmt->fetch(PDO::FETCH_ASSOC);

            if ($payment) {
                $amountPaid = $payment['amount_paid'];
                $overpaidAmount = $amountPaid - $total;

                if ($overpaidAmount > 0) {
                    $excess_amount += $overpaidAmount;
                }
            }
        }
    } catch (PDOException $e) {
        echo "❌ Error calculating overpaid amounts: " . $e->getMessage();
    }

    // total Money send to suppliers
    $totalAmountSend = $totalAmountPaid + $excess_amount; // Net total actually received

    // pending payment
    $pendingTotal =0; 
try {
        // Step 1: Get all expenses marked as 'overpaid'
        $stmt = $pdo->prepare("SELECT id, total FROM expenses WHERE status = 'partially paid'");
        $stmt->execute();
        $partiallyPaidExpenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($partiallyPaidExpenses as $expense) {
            $expenseId = $expense['id'];
            $expectedAmount = $expense['total'];

            // Step 2: Get the amount_paid from expense_payments
            $payStmt = $pdo->prepare("SELECT amount_paid FROM expense_payments WHERE expense_id = :expense_id LIMIT 1");
            $payStmt->execute(['expense_id' => $expenseId]);
            $payment = $payStmt->fetch(PDO::FETCH_ASSOC);

            if ($payment) {
                $amountPaid = $payment['amount_paid'];
                $pendingAmount = $expectedAmount - $amountPaid;

                if ($pendingAmount > 0) {
                    $pendingTotal += $pendingAmount;
                }
            }
        }
    } catch (PDOException $e) {
        echo "❌ Error calculating overpaid amounts: " . $e->getMessage();
    }

    // total remaining amount

    $TotalRemaining = $unpaidTotal + $pendingTotal;
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
