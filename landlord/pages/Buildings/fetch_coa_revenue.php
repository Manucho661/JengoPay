<?php
require_once "../db/connect.php";

$stmt = $pdo->prepare("
    SELECT account_code, account_name
    FROM chart_of_accounts
    WHERE account_type = 'Revenue'
    ORDER BY account_name ASC
");
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
