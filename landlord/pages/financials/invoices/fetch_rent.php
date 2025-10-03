<?php
include '../../db/connect.php'; // adjust path to your db connect

header('Content-Type: application/json');

if (!empty($_POST['tenant_id'])) {
    $tenantId = $_POST['tenant_id'];

    $stmt = $pdo->prepare("
        SELECT u.unit_number, u.monthly_rent
        FROM tenants t
        JOIN units u ON t.unit_id = u.unit_id
        WHERE t.tenant_id = :tenant_id
    ");
    $stmt->execute(['tenant_id' => $tenantId]);
    $rent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rent) {
        echo json_encode([
            "success" => true,
            "rent_amount" => $rent['monthly_rent'],
            "unit_number" => $rent['unit_number']
        ]);
    } else {
        echo json_encode(["success" => false]);
    }
} else {
    echo json_encode(["success" => false]);
}
