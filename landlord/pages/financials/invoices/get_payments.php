<?php
include "../../db/connect.php";

$stmt = $pdo->query("SELECT * FROM payments ORDER BY payment_date DESC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
