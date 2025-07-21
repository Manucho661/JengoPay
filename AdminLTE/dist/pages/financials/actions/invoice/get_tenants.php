<?php
require_once '../../db/connect.php';   // $pdo comes from here

header('Content-Type: application/json');

// Validate & cast the GET parameter
$buildingId = isset($_GET['building_id']) ? (int)$_GET['building_id'] : 0;

// No building? → return empty JSON array
if ($buildingId <= 0) {
    echo json_encode([]);
    exit;
}

/*
 |------------------------------------------------------------------
 | Fetch tenants for the chosen building
 |------------------------------------------------------------------
 |  • t.user_id      – keeps the same field you were already sending
 |  • name           – built from users.first_name + middle_name
 |  • Add / remove   – any extra columns you may need in the dropdown
 */
$sql = "
    SELECT
        t.user_id                        AS id,
        CONCAT(u.first_name, ' ', u.middle_name) AS name
    FROM tenants  AS t
    JOIN users    AS u ON u.id = t.user_id
    WHERE t.building_id = ?
    ORDER BY name
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$buildingId]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
