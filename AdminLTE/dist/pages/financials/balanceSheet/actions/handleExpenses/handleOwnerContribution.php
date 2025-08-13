<?php
// include function to handle retained earnings
function handleOwnerContribution($outGoingCash)
{
    global $pdo;
    // 1. Get current cash from assets
    $stmt = $pdo->query("SELECT amount FROM assets WHERE name = 'Cash' LIMIT 1");
    $cashRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cashRow) {
        echo "⚠️ 'Cash' asset not found.";
        return;
    }

    $availableCash = $cashRow['amount'];

    // 3. Check if available cash is enough
    if ($availableCash >= $outGoingCash) {
        echo "✅ Payment can proceed. Cash is sufficient.";
    } else {
        // 4. Calculate shortfall
        $shortfall = $outGoingCash - $availableCash;

        // 5. Update 'Owner Contribution' in owners_equity
        $stmt = $pdo->prepare("SELECT amount FROM owners_equity WHERE name = 'Owner Contribution' LIMIT 1");
        $stmt->execute();
        $ownerRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ownerRow) {

            $newOwnerAmount = $ownerRow['amount'] + $shortfall;

            $updateOwner = $pdo->prepare("UPDATE owners_equity SET amount = :amount, entry_date = :entry_date, description = :description WHERE name = 'Owner Contribution'");
            $updateOwner->execute([
                'amount' => $newOwnerAmount,
                'entry_date' => date('Y-m-d'),
                'description' => 'Cash shortfall covered by owner'
            ]);
            
        } else {
            echo "⚠️ 'Owner Contribution' row not found.";
            return;
        }

    }
}

?>