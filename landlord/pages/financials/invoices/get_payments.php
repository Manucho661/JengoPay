<?php
include "../../db/connect.php";

$month = $_GET['month'] ?? '';
$method = $_GET['method'] ?? '';

$sql = "SELECT * FROM payments WHERE 1=1";
$params = [];

if (!empty($month)) {
    $sql .= " AND DATE_FORMAT(payment_date, '%Y-%m') = :month";
    $params[':month'] = $month;
}

if (!empty($method)) {
    $sql .= " AND payment_method = :method";
    $params[':method'] = $method;
}

$sql .= " ORDER BY payment_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
