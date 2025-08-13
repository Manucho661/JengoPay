<?php
include '../../db/connect.php';

try {
    // Assuming $pdo is your PDO connection object

    $sql = "SELECT id, name, category, amount, created_at FROM assets ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Separate into categories
    $currentAssets = [];
    $nonCurrentAssets = [];

    foreach ($assets as $asset) {
        if ($asset['category'] === 'Current Asset') {
            $currentAssets[] = $asset;
        } else {
            $nonCurrentAssets[] = $asset;
        }
    }

    $totalCurrent = array_sum(array_column($currentAssets, 'amount'));
    $totalNonCurrent = array_sum(array_column($nonCurrentAssets, 'amount'));
    $totalAssets = $totalCurrent + $totalNonCurrent;

    // items that must be displayed on the balanceSheet.
    $mustDisplayedCurrentAssets = array('Accounts Receivable', 'M-pesa', 'Cash', 'Bank', 'Tenant Security Deposits (held)');
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
