<?php
require_once '../db/connect.php'; // Include your PDO connection file

header('Content-Type: application/json');

try {
    $unit_id = $_POST['unit_id'] ?? null;

    if (!$unit_id) {
        throw new Exception('Unit ID is required');
    }

    $query = "SELECT rent_amount FROM units WHERE unit_id = :unit_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':unit_id' => $unit_id]);

    $unit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$unit) {
        throw new Exception('Unit not found');
    }

    echo json_encode($unit);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>