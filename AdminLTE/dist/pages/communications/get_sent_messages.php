<?php
require_once '../db/connect.php'; // Adjust as needed

$stmt = $pdo->prepare("SELECT recipient, priority, message, created_at FROM announcements WHERE status = 'Sent' ORDER BY created_at DESC");
$stmt->execute();

$sent = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($sent);
?>
