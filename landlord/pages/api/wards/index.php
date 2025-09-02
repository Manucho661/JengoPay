<?php
header('Content-Type: application/json');
include '../../db/connect.php';

$subCountyId = $_GET['sub_county_id'] ?? null;

if (!$subCountyId || !is_numeric($subCountyId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid sub-county ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, ward_name FROM wards WHERE sub_county_id = ?");
    $stmt->execute([$subCountyId]);
    $wards = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($wards);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>