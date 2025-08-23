<?php
require_once '../../../db/connect.php';

$searchTerm = $_GET['q'] ?? '';

$stmt = $pdo->prepare("
  SELECT account_code, account_name
  FROM chart_of_accounts
  WHERE account_name LIKE ?
  LIMIT 20
");
$stmt->execute(["%$searchTerm%"]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($results);
