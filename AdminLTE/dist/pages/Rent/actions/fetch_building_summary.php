<?php
include '../../db/connect.php';

$building = isset($_GET['building']) ? $_GET['building'] : '';

$query = "SELECT * FROM building_rent_summary";
$params = [];

if ($building !== '' && strtolower($building) !== 'all buildings') {
    $query .= " WHERE LOWER(building_name) = LOWER(?)";
    $params[] = $building;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);
