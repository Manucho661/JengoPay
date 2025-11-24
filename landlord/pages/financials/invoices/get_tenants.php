<?php 
include "../../db/connect.php";
header('Content-Type: application/json');

if (isset($_GET['building_id'])) {
    $buildingId = intval($_GET['building_id']);

    // 1. Get building name
    $buildingQuery = "SELECT building_name FROM buildings WHERE id = ?";
    $buildingStmt = mysqli_prepare($pdo, $buildingQuery);
    mysqli_stmt_bind_param($buildingStmt, "i", $buildingId);
    mysqli_stmt_execute($buildingStmt);
    $buildingResult = mysqli_stmt_get_result($buildingStmt);
    $building = mysqli_fetch_assoc($buildingResult);

    if ($building) {

        $buildingName = $building['building_name'];

        // 2. Query tenants from 3 tables
        $sql = "
            SELECT 
                tfirst_name AS first_name,
                tmiddle_name AS middle_name,
                tlast_name AS last_name,
                unit_number,
                'Bedsitter' AS unit_type
            FROM bedsitter_units
            WHERE building_link = ? AND tenant_status = 'Active'

            UNION ALL

            SELECT 
                tfirst_name AS first_name,
                tmiddle_name AS middle_name,
                tlast_name AS last_name,
                unit_number,
                'Single' AS unit_type
            FROM single_units
            WHERE building_link = ? AND tenant_status = 'Active'

            UNION ALL

            SELECT 
                tfirst_name AS first_name,
                tmiddle_name AS middle_name,
                tlast_name AS last_name,
                unit_number,
                'Multi-Room' AS unit_type
            FROM multi_rooms_units
            WHERE building_link = ? AND tenant_status = 'Active'
        ";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $buildingName, $buildingName, $buildingName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $tenants = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $tenants[] = $row;
        }

        echo json_encode($tenants);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
