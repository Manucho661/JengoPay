<?php
header('Content-Type: application/json');
require_once '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? null;
  $availability = $_POST['availability'] ?? null;

  if ($id && in_array($availability, ['available', 'unavailable'])) {
    try {
      $stmt = $pdo->prepare("UPDATE maintenance_requests SET availability = :availability WHERE id = :id");
      $stmt->bindParam(':availability', $availability);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        echo json_encode(['success' => true]);
      } else {
        echo json_encode(['success' => false, 'error' => 'Database update failed']);
      }
    } catch (PDOException $e) {
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
  } else {
    echo json_encode(['success' => false, 'error' => 'Invalid ID or availability value']);
  }
} else {
  echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
