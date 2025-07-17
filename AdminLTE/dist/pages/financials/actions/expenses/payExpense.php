<?php
 include '../../../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Step 1: Get POST data
        $expense_id    = $_POST['expense_id'] ?? null;
        $amount        = $_POST['amount'] ?? 0.00;
        $payment_date  = $_POST['payment_date'] ?? date('Y-m-d');
        $payment_method = $_POST['payment_method'] ?? 'cash';
        $reference     = $_POST['reference'] ?? '';

        if (!$expense_id) {
            throw new Exception("Missing expense ID");
        }

        // Step 2: Insert into expense_payments
        $stmt = $pdo->prepare("INSERT INTO expense_payments (expense_no, amount, reference_no, payment_method, payment_date) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$expense_id, $amount, $reference, $payment_method, $payment_date]);

        // Step 3: Update the expenses table
        $update = $pdo->prepare("UPDATE expenses SET status = 'paid' WHERE id = ?");
        $update->execute([$expense_id]);

        $pdo->commit();

        echo "Payment recorded and expense marked as paid.";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
