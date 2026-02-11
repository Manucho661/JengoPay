<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/connect.php';


try {
    // 1) Get landlord_id from the logged-in user
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int)$_SESSION['user']['id'];

    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception("Landlord account not found for this user.");
    }

    $landlord_id = (int)$landlord['id'];

    // 2) Get all counts in a single query
    $stmt = $pdo->prepare("
        SELECT
            (SELECT COUNT(*) FROM buildings WHERE landlord_id = ?) AS buildingCount,
            (SELECT COUNT(*) FROM tenants WHERE landlord_id = ? AND status = 'active') AS tenantCount,
            (SELECT COUNT(*) FROM maintenance_requests WHERE landlord_id = ? AND status = 'submitted') AS requestCount
    ");
    $stmt->execute([$landlord_id, $landlord_id, $landlord_id]);
    $counts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extract variables
    $buildingCount = (int)$counts['buildingCount'];
    $tenantCount = (int)$counts['tenantCount'];
    $requestCount = (int)$counts['requestCount'];

    // Now you can use $buildingCount, $tenantCount, $requestCount
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
?>
