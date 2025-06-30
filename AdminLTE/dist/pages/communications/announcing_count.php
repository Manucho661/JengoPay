<?php
header('Content-Type: application/json');
require '../db/connect.php'; // Adjust the path as needed

try {
    $totalStmt = $pdo->query("SELECT COUNT(*) as count FROM announcements");
    $draftStmt = $pdo->query("SELECT COUNT(*) as count FROM announcements WHERE status = 'Draft'");
    $sentStmt = $pdo->query("SELECT COUNT(*) as count FROM announcements WHERE status = 'Sent'");
    $archivedStmt = $pdo->query("SELECT COUNT(*) as count FROM announcements WHERE status = 'Archived'");

    echo json_encode([
        'success' => true,
        'total' => $totalStmt->fetch()['count'],
        'draft' => $draftStmt->fetch()['count'],
        'sent' => $sentStmt->fetch()['count'],
        'archived' => $archivedStmt->fetch()['count'],
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
