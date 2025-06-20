<?php
require_once '../db/connect.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT recipient, priority, message FROM announcements WHERE id = ? AND status = 'Draft'");
$stmt->execute([$id]);
$draft = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($draft);
?>
