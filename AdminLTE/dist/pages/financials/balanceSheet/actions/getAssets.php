<?php
include '../../db/connect.php';

try {
    // Assuming $pdo is your PDO connection object

    $sql = "SELECT id, name, category, amount, acquisition_date, description, created_at FROM assets ORDER BY acquisition_date DESC";
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

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
