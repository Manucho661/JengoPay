<?php
require_once '../db/connect.php';
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['id'])) {
  $stmt = $pdo->prepare("UPDATE maintenance_requests SET is_read = 1 WHERE id = ?");
  $stmt->execute([$data['id']]);
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['error' => 'Invalid ID']);
}
?>