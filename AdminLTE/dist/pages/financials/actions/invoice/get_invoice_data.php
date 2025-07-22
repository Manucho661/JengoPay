<?php
require_once '../../db/connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM invoice WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($invoice);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid ID']);
}
