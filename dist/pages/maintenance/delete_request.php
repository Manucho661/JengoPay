<?php
include '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['request_id'] ?? '';

    if (!empty($id)) {
        $stmt = $pdo->prepare("DELETE FROM maintenance_requests WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo "success";
            exit;
        }
    }
}
echo "error";
?>
