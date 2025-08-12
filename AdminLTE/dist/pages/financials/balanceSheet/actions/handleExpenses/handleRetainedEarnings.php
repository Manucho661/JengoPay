<?php
function handleRetainedEarnings($expenseAmount)
{
    global $pdo;
    // 2. Get retained earnings from owners_equity
    $stmt = $pdo->prepare("SELECT amount FROM owners_equity WHERE name = 'Retained Earnings' LIMIT 1");
    $stmt->execute();
    $retainedRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$retainedRow) {
        echo "⚠️ 'Retained Earnings' row not found.";
        return;
    }

    $newRetainedEarningAmount = $retainedRow['amount'] - $expenseAmount;

    $updateRE = $pdo->prepare("UPDATE owners_equity SET amount = :amount WHERE name = 'Retained Earnings'");
    $updateRE->execute(['amount' => $newRetainedEarningAmount]);
}
