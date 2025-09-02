<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});


header('Content-Type: application/json');
session_start();
require '../../../db/connect.php'; // adjust path if needed

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request");
    }

    $tenant_id = $_POST['tenant_id'] ?? null;
    $type = $_POST['type'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $license = $_POST['license'] ?? null;

    if (!$type || !$weight || !$license) {
        throw new Exception("Missing required fields.");
    }

    $stmt = $pdo->prepare("INSERT INTO pets (tenant_id, type, weight, license) VALUES (?, ?, ?, ?)");
    $stmt->execute([$tenant_id, $type, $weight, $license]);

    echo json_encode([
        'success' => true,
        'message' => 'Pet added',
        'pet' => [
            'id' => $pdo->lastInsertId(),
            'type' => $type,
            'weight' => $weight,
            'license' => $license
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
