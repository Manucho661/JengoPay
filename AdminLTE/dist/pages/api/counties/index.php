<?php
header('Content-Type: application/json');
include '../../db/connect.php';

try {
    $stmt = $pdo->query("SELECT id, county_name FROM counties");
    $counties = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($counties);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>