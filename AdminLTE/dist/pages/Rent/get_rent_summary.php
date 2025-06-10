<?php
include '../db/connect.php';

$building = $_GET['building'] ?? '';

if ($building === 'All Buildings' || $building === '') {
    $stmt = $pdo->query("SELECT * FROM building_rent_summary");
} else {
    $stmt = $pdo->prepare("SELECT * FROM building_rent_summary WHERE building_name = ?");
    $stmt->execute([$building]);
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
