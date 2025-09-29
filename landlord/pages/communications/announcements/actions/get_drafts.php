<?php
require_once '../../../db/connect.php'; // Adjust as needed

$stmt = $pdo->prepare("SELECT id, recipient, priority, message, created_at FROM announcements WHERE status = 'Draft' ORDER BY created_at DESC");
$stmt->execute();

$drafts = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($drafts);
?>
