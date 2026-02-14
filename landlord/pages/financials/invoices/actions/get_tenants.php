<?php
// get_tenants.php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    include '../../../db/connect.php';

    if (!isset($_GET['building_id']) || empty($_GET['building_id'])) {
        throw new Exception("Building ID is required");
    }

    $buildingId = filter_var($_GET['building_id'], FILTER_VALIDATE_INT);
    if (!$buildingId || $buildingId <= 0) {
        throw new Exception("Invalid building ID");
    }

    // 1. Get building name
    $stmt = $pdo->prepare("SELECT building_name FROM buildings WHERE id = ?");
    $stmt->execute([$buildingId]);
    $building = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$building) {
        echo json_encode([]);
        exit;
    }

    $buildingName = $building["building_name"];

    // 2. Fetch tenants from all 3 tables
    $sql = "
        SELECT
            id,
            tfirst_name AS first_name,
            tmiddle_name AS middle_name,
            tlast_name AS last_name,
            unit_number,
            CONCAT(
                tfirst_name, ' ',
                COALESCE(tmiddle_name, ''), ' ',
                tlast_name
            ) AS full_name,
            'Bedsitter' AS unit_type
        FROM bedsitter_units
        WHERE building_link = ? AND tenant_status = 'Active'

        UNION ALL

        SELECT
            id,
            tfirst_name AS first_name,
            tmiddle_name AS middle_name,
            tlast_name AS last_name,
            unit_number,
            CONCAT(
                tfirst_name, ' ',
                COALESCE(tmiddle_name, ''), ' ',
                tlast_name
            ) AS full_name,
            'Single' AS unit_type
        FROM single_units
        WHERE building_link = ? AND tenant_status = 'Active'

        UNION ALL

        SELECT
            id,
            tfirst_name AS first_name,
            tmiddle_name AS middle_name,
            tlast_name AS last_name,
            unit_number,
            CONCAT(
                tfirst_name, ' ',
                COALESCE(tmiddle_name, ''), ' ',
                tlast_name
            ) AS full_name,
            'Multi-Room' AS unit_type
        FROM multi_rooms_units
        WHERE building_link = ? AND tenant_status = 'Active'
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$buildingName, $buildingName, $buildingName]);
    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tenants);

} catch (PDOException $e) {
    error_log("PDO Error: " . $e->getMessage());
    echo json_encode(["error" => "Database error"]);
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    echo json_encode(["error" => $e->getMessage()]);
}
?>
