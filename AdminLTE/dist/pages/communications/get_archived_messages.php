<?php
require_once '../db/connect.php';

$stmt = $pdo->prepare("SELECT id, recipient, priority, message, created_at FROM announcements WHERE status = 'Archived' ORDER BY created_at DESC");
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
