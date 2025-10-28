<?php
// get_tenants.php
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Include your PDO configuration
    include '../../../db/connect.php';
    
    // Check if building_id is provided and valid
    if (!isset($_GET['building_id']) || empty($_GET['building_id'])) {
        throw new Exception('Building ID is required');
    }
    
    $buildingId = filter_var($_GET['building_id'], FILTER_VALIDATE_INT);
    if ($buildingId === false || $buildingId <= 0) {
        throw new Exception('Invalid building ID');
    }
    
    // Debug: Log the request
    error_log("Fetching tenants for building ID: " . $buildingId);
    
    // Get building name first
    $stmt = $pdo->prepare("SELECT id, building_name FROM buildings WHERE id = ?");
    $stmt->execute([$buildingId]);
    $building = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$building) {
        echo json_encode([]);
        exit;
    }
    
    // Debug: Log building found
    error_log("Building found: " . $building['building_name']);
    
    // Get tenants for this building
    $stmt = $pdo->prepare("
        SELECT 
            id, 
            first_name, 
            middle_name, 
            last_name,
            CONCAT(
                first_name, 
                ' ', 
                COALESCE(middle_name, ''), 
                ' ', 
                last_name
            ) as full_name 
        FROM tenants 
        WHERE building = ? AND status = 'Active'
        ORDER BY first_name, last_name
    ");
    
    $stmt->execute([$building['building_name']]);
    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug: Log tenants found
    error_log("Tenants found: " . count($tenants));
    
    echo json_encode($tenants);
    
} catch (PDOException $e) {
    error_log("PDO Error in get_tenants.php: " . $e->getMessage());
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("General Error in get_tenants.php: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>