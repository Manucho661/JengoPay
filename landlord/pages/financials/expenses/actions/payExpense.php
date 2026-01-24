<?php
include '../../../db/connect.php';

// Pay Expense journal
include './journalHelpers/payExpenseJournal.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // 🔹 Step 1: Collect POST data
        $expenseId        = $_POST['expense_id'] ?? null;   // The expense we are paying
        $expectedAmount   = $_POST['expected_amount'] ?? null;
        $amountPaid       = $_POST['amountToPay'] ?? 0.00;
        $paymentDate      = $_POST['payment_date'] ?? date('Y-m-d');
        $paymentAccountId = $_POST['payment_account_id'] ?? '100';
        $referenceNo      = $_POST['reference'] ?? '';

        if (!$expenseId) {
            throw new Exception("Missing expense ID");
        }

        // 🔹 Step 2: Check if this expense already has payments
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM expense_payments WHERE expense_id = ?");
        $checkStmt->execute([$expenseId]);
        $hasPayments = $checkStmt->fetchColumn();

        // -------------------------------------------------------
        // CASE A: First payment for this expense
        // -------------------------------------------------------
        if ($hasPayments == 0) {
            // Insert new payment
            $insertPay = $pdo->prepare("
                INSERT INTO expense_payments (expense_id, amount_paid, reference_no, payment_account_id, payment_date) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $insertPay->execute([$expenseId, $amountPaid, $referenceNo, $paymentAccountId, $paymentDate]);

            $paymentId = $pdo->lastInsertId(); // ✅ new payment row id

            // Update expense status
            if ($amountPaid == 0) {
                $status = 'Unpaid';
            } elseif ($amountPaid == $expectedAmount) {
                $status = 'Paid';
            } elseif ($amountPaid > $expectedAmount) {
                $status = 'Overpaid';
            } else {
                $status = 'Partially Paid';
            }

            $updateExp = $pdo->prepare("UPDATE expenses SET status = ? WHERE id = ?");
            $updateExp->execute([$status, $expenseId]);

            // Record journals
            recordExpensePaymentJournal($pdo, $expectedAmount, $expenseId, $amountPaid, $paymentAccountId, $paymentDate, $paymentId);

            $pdo->commit();
            echo "✅ $status";

        // -------------------------------------------------------
        // CASE B: Expense already has previous payments
        // -------------------------------------------------------
        } else {
            // Calculate cumulative paid
            $stmt = $pdo->prepare("SELECT SUM(amount_paid) AS total_paid FROM expense_payments WHERE expense_id = ?");
            $stmt->execute([$expenseId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalPaid = $row['total_paid'] ?? 0;
            $newTotal = $totalPaid + $amountPaid;

            // Insert this new payment
            $insertPay = $pdo->prepare("
                INSERT INTO expense_payments (expense_id, amount_paid, reference_no, payment_account_id, payment_date) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $insertPay->execute([$expenseId, $amountPaid, $referenceNo, $paymentAccountId, $paymentDate]);
            $paymentId = $pdo->lastInsertId();

            // Record journals
            recordExpensePaymentJournal($pdo, $expectedAmount, $expenseId, $amountPaid, $paymentAccountId, $paymentDate, $paymentId);

            // Update status based on new total
            if ($newTotal == 0) {
                $status = 'Unpaid';
            } elseif ($newTotal == $expectedAmount) {
                $status = 'Paid';
            } elseif ($newTotal > $expectedAmount) {
                $status = 'Overpaid';
            } else {
                $status = 'Partially Paid';
            }

            $updateExp = $pdo->prepare("UPDATE expenses SET status = ? WHERE id = ?");
            $updateExp->execute([$status, $expenseId]);

            $pdo->commit();
            echo "✅ $status";
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
    }
}
