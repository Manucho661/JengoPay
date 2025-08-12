<?php

function handlePrepaidExpense($expenseAmount){
    global $pdo;
    try {

        // Get Current Prepaid expense
        $stmt = $pdo->prepare("SELECT amount FROM assets WHERE name = 'Prepaid Expense' LIMIT 1");
        $stmt->execute();
        $currentAmount = $stmt->fetchColumn();

        // Update Prepaid
        if ($currentAmount !== false) {
            $newAmount = $currentAmount + $expenseAmount;
            $updateAssets = $pdo->prepare("UPDATE assets SET amount = :amount WHERE name = 'Prepaid Expense'");
            $updateAssets->execute([
                'amount' => $newAmount
            ]);
            echo json_encode("✅ Prepaid Expense Successfully updated");
            
        } else {
            echo json_encode("⚠️ 'Prepaid Expense' row not found.");
            return;
        }
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>
