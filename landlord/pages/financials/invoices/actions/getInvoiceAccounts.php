<?php
include '../../db/connect.php';

try {
    $invoiceItemsQuery = $pdo->prepare("SELECT account_name, account_code FROM chart_of_accounts WHERE account_type = :type LIMIT 8");
    $invoiceItemsQuery->execute(['type' => 'revenue']);
    $accountItems = $invoiceItemsQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = "❌ Failed to fetch invoice items: " . $e->getMessage();
}
?>