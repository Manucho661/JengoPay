<?php
 include '../db/connect.php';


header('Content-Type: application/json');

$building = isset($_GET['building']) ? $_GET['building'] : 'all';

if ($building === 'all') {
    $sql = "SELECT
                users.id,
                users.first_name,
                users.middle_name,
                users.email,
                tenants.phone_number,
                tenants.user_id,
                tenants.building_id,
                tenants.id_no,
                tenants.unit,
                tenants.status
                FROM tenants
                INNER JOIN users ON tenants.user_id = users.id
                ORDER BY
                CASE WHEN tenants.status = 'active' THEN 0 ELSE 1 END;
                ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}
 else {
    $sql = "SELECT users.id, users.first_name, users.middle_name, users.email,
               tenants.phone_number, tenants.user_id, tenants.building_id,
               tenants.id_no, tenants.unit, tenants.status
        FROM tenants
        INNER JOIN users ON tenants.user_id = users.id
        WHERE tenants.building_id = :building
        ORDER BY
            CASE WHEN tenants.status = 'active' THEN 0 ELSE 1 END";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['building' => $building]);
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);

?>


