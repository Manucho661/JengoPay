<?php
header('Content-Type: application/json');
include '../db/connect.php';

// Get form data
$recipient = $_POST['recipient'] ?? '';
$priority = $_POST['priority'] ?? '';
$message = $_POST['message'] ?? '';

// Validate
if (empty($recipient) || empty($priority) || empty($message)) {
  echo json_encode(['success' => false, 'error' => 'All fields are required']);
  exit;
}

try {
  // Insert into database
  $stmt = $conn->prepare("INSERT INTO announcements
                         (building_name, priority, message, created_at, status)
                         VALUES (?, ?, ?, NOW(), 'sent')");

  $stmt->bind_param("sss", $recipient, $priority, $message);
  $stmt->execute();

  echo json_encode(['success' => true]);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
  $stmt->close();
  $conn->close();
}
?>