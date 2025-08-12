<?php
include '../../db/connect.php';

try {
    // Assuming $pdo is your PDO connection object

    $sql = "SELECT id, liability_name, category, amount, due_date, description, created_at FROM liabilities ORDER BY due_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $liabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Separate into categories
    $currentLiabilities = [];
    $nonCurrentLiabilities = [];

    foreach ($liabilities as $liability) {
        if ($liability['category'] === 'Current Liability') {
            $currentLiabilities[] = $liability;
        } else {
            $nonCurrentLiabilities[] = $liability;
        }
    }

    $totalCurrentLiabilities = array_sum(array_column($currentLiabilities, 'amount'));
    $totalNonCurrentLiabilities = array_sum(array_column($nonCurrentLiabilities, 'amount'));
    $totalLiabilities = $totalCurrentLiabilities + $totalNonCurrentLiabilities;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}