<?php
include '../../db/connect.php';

try {
    $ExpenseItemsQuery = $pdo->prepare("SELECT account_name, account_code FROM chart_of_accounts WHERE account_type = :type LIMIT 8");
    $ExpenseItemsQuery->execute(['type' => 'expenses']);
    $accountItems = $ExpenseItemsQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = "❌ Failed to fetch expense items: " . $e->getMessage();
}
?>