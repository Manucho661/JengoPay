<?php
include '../../../db/connect.php';

//To update the prepaid expense
include '../../balanceSheet/actions/handleExpenses/handlePrepaidExpense.php';

//pay Expense journal
include './journals/payExpenseJournal.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Step 1: Get POST data
        $expense_id     = $_POST['expense_id'] ?? null;
        $expected_amount = $_POST['expected_amount'] ?? null;
        $amount         = $_POST['amountToPay'] ?? 0.00;
        $payment_date   = $_POST['payment_date'] ?? date('Y-m-d');
        $paymentAccountId = $_POST['payment_account_id'] ?? '100';
        $reference      = $_POST['reference'] ?? '';

        if (!$expense_id) {
            throw new Exception("Missing expense ID");
        }

        // Step 2: Check if expense_id already exists in expense_payments
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM expense_payments WHERE expense_id = ?");
        $checkStmt->execute([$expense_id]);
        $exists = $checkStmt->fetchColumn();

        if ($exists == 0) {
            // Step 3: Insert into expense_payments
            $stmt = $pdo->prepare("INSERT INTO expense_payments (expense_id, amount_paid, reference_no, payment_account_id, payment_date) 
                               VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$expense_id, $amount, $reference, $paymentAccountId, $payment_date]);
            // Step 4: Update the expenses table
            if ($amount == 0) {
                $status = 'Unpaid';
            } elseif ($amount == $expected_amount) {
                $status = 'Paid';
            } 
            elseif($amount > $expected_amount){
                $status = 'Overpaid';
            }
            else {
                $status = 'partially paid';
            }
            // update status
            $update = $pdo->prepare("UPDATE expenses SET status = ? WHERE id = ?");
            $update->execute([$status, $expense_id]);

            // Handle Journal entries
            recordExpensePaymentJournal($pdo, $expected_amount, $expense_id, $amount, $paymentAccountId, $payment_date);
            $pdo->commit();

            echo "✅ $status";
        } else {
            // Expense already has a payment entry
            $stmt = $pdo->prepare("SELECT amount_paid FROM expense_payments WHERE expense_id = ?");
            $stmt->execute([$expense_id]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($payment) {
                $existing_amount = $payment['amount_paid'];
                $total_amount = $existing_amount + $amount;

                if ($total_amount >= $expected_amount) {
                    // Update the payment amount
                    $updateStmt = $pdo->prepare("UPDATE expense_payments SET amount_paid = ? WHERE expense_id = ?");
                    $updateStmt->execute([$total_amount, $expense_id]);

                    // Update the expenses table status to 'paid'
                    $statusUpdate = $pdo->prepare("UPDATE expenses SET status = 'paid' WHERE id = ?");
                    $statusUpdate->execute([$expense_id]);
                } else {
                    // Only update the payment amount, no status change
                    $updateStmt = $pdo->prepare("UPDATE expense_payments SET amount_paid = ? WHERE expense_id = ?");
                    $updateStmt->execute([$total_amount, $expense_id]);
                }
            } else {
                echo "⚠️ No payment found for this expense.";
            }
             recordExpensePaymentJournal($pdo, $expected_amount, $expense_id, $amount, $paymentAccountId, $payment_date);
            $pdo->commit();
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
