<?php
header('Content-Type: application/json');
require '../db/connect.php';

$buildingId = $_GET['building_id'] ?? null;

try {
  if ($buildingId) {
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE recipient = ? ORDER BY created_at DESC");
    $stmt->execute([$buildingId]);
  } else {
    $stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC");
  }

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    'status' => 'success',
    'data' => $results
  ]);
} catch (PDOException $e) {
  echo json_encode([
    'status' => 'error',
    'message' => $e->getMessage()
  ]);
}
