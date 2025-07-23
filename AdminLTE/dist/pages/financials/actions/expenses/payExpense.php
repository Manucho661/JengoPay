<?php
 include '../../../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
    $pdo->beginTransaction();

    // Step 1: Get POST data
    $expense_id     = $_POST['expense_id'] ?? null;
    $expected_amount    = $_POST['expected_amount'] ?? null;
    $amount         = $_POST['amount'] ?? 0.00;
    $payment_date   = $_POST['payment_date'] ?? date('Y-m-d');
    $payment_method = $_POST['payment_method'] ?? 'cash';
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
        $stmt = $pdo->prepare("INSERT INTO expense_payments (expense_id, amount, reference_no, payment_method, payment_date) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$expense_id, $amount, $reference, $payment_method, $payment_date]);

        // Step 4: Update the expenses table
        $status = ($amount == $expected_amount) ? 'paid' : 'partially paid';
        $update = $pdo->prepare("UPDATE expenses SET status = ? WHERE id = ?");
        $update->execute([$status, $expense_id]);

        $pdo->commit();

        echo "✅ Payment recorded and expense marked as paid.";
    } else {
        // Expense already has a payment entry
        // Leave this else block empty for now
        $pdo->commit();
    }
}  catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
