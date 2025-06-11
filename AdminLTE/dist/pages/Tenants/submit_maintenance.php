<?php
header('Content-Type: application/json');
require_once '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_date = $_POST['request_date'] ?? null;
    $category = $_POST['category'] ?? null;
    $description = $_POST['description'] ?? null;
    $provider_id = $_POST['provider_id'] ?? null;
    $payment_date = $_POST['payment_date'] ?? null;

    try {
        $stmt = $pdo->prepare("INSERT INTO maintenance_requests 
            (request_date, category, description, created_at) 
            VALUES (?, ?, ?, ?)");

        $stmt->execute([
            $request_date,
            $category,
            $description,
            $payment_date
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
