<?php
include '../db/connect.php';

$stmt = $pdo->query("SELECT DISTINCT building_name FROM building_rent_summary");
$buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($buildings);
