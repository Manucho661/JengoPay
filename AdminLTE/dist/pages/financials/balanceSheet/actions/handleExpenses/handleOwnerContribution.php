<?php

function handleOwnerContribution($expenseAmount){
    global $pdo;
    try {
        
        // Get Current Prepaid expense
        $stmt = $pdo->prepare("SELECT amount FROM owners_equity WHERE name = 'Owner Contribution' LIMIT 1");
        $stmt->execute();
        $currentAmount = $stmt->fetchColumn();

        // Update Prepaid
        if ($currentAmount !== false) {
            $newAmount = $currentAmount + $expenseAmount;
            $updateAssets = $pdo->prepare("UPDATE assets SET amount = :amount WHERE name = 'Owner Contribution'");
            $updateAssets->execute([
                'amount' => $newAmount
            ]);
            echo json_encode("✅ Owner Contribution Successfully updated");
            
        } else {
            echo json_encode("⚠️ 'Owner Contribution' row not found.");
            return;
        }
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
}

?>
