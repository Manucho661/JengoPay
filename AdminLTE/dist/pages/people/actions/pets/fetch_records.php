<?php 
include '../../../db/connect.php';
header('Content-Type: application/json');

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing user_id']);
    exit;
}

try {
    $user_id = $_GET['user_id'];

    // Get tenant ID
    $stmt1 = $pdo->prepare("SELECT id FROM tenants WHERE user_id = ?");
    $stmt1->execute([$user_id]);
    $tenantRow = $stmt1->fetch(PDO::FETCH_ASSOC);

    if (!$tenantRow) {
        http_response_code(404);
        echo json_encode([]);
        exit;
    }

    $tenant_id = $tenantRow['id'];
    // Fetch pets
    $stmt = $pdo->prepare("SELECT type, weight, license FROM pets WHERE tenant_id = ?");
    $stmt->execute([$tenant_id]);
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($pets);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
