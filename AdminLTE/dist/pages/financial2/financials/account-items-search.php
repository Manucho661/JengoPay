<?php
$q = $_GET['q'] ?? '';
$stmt = $pdo->prepare('SELECT account_code, account_name
                       FROM chart_of_accounts
                       WHERE account_name LIKE ? LIMIT 20');
$stmt->execute(["%$q%"]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>