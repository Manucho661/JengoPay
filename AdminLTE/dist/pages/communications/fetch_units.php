<?php
include '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['building_id'])) {
    $building_id = $_POST['building_id'];

    $stmt = $pdo->prepare("SELECT unit_id, unit_number FROM units WHERE building_id = ?");
    $stmt->execute([$building_id]);
    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($units);
}
?>
