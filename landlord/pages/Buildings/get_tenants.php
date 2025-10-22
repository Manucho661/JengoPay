<?php
include '../db/connect.php';

try {
    $query = "SELECT id, first_name, middle_name, last_name, building FROM tenants WHERE status = 'Active'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tenants);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
