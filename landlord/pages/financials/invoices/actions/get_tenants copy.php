<?php
// get_tenants.php
include "../../db/connect.php";
header('Content-Type: application/json');

if (isset($_GET['building_id'])) {
    $buildingId = intval($_GET['building_id']);
    
    // Get building name first
    $buildingQuery = "SELECT building_name FROM buildings WHERE id = ?";
    $buildingStmt = mysqli_prepare($conn, $buildingQuery);
    mysqli_stmt_bind_param($buildingStmt, "i", $buildingId);
    mysqli_stmt_execute($buildingStmt);
    $buildingResult = mysqli_stmt_get_result($buildingStmt);
    $building = mysqli_fetch_assoc($buildingResult);
    
    if ($building) {
        // Get tenants for this building
        $tenantQuery = "SELECT id, first_name, middle_name, last_name FROM tenants WHERE building = ? AND status = 'Active'";
        $tenantStmt = mysqli_prepare($conn, $tenantQuery);
        mysqli_stmt_bind_param($tenantStmt, "s", $building['building_name']);
        mysqli_stmt_execute($tenantStmt);
        $tenantResult = mysqli_stmt_get_result($tenantStmt);
        
        $tenants = [];
        while ($tenant = mysqli_fetch_assoc($tenantResult)) {
            $tenants[] = $tenant;
        }
        
        echo json_encode($tenants);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
