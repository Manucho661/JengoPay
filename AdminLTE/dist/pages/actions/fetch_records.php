<?php
 include '../db/connect.php';


header('Content-Type: application/json');


$sql = "SELECT
users.id,
users.name,
users.email,
tenants.phone_number,
tenants.user_id,
tenants.residence,
tenants.id_no,
tenants.unit,
tenants.status
FROM tenants
INNER JOIN users ON tenants.user_id = users.id";

$stmt = $pdo->query($sql);
$tenants = $stmt->fetchAll(PDO::FETCH_ASSOC); // IMPORTANT: Fetch as Associative Array

    echo json_encode($tenants);


?>
