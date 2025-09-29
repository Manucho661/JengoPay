<?php
header('Content-Type: application/json');
require '../../../db/connect.php'; // adjust path as needed

try {
    // Count sent announcements
    $sentStmt = $pdo->query("SELECT COUNT(*) AS sent_count FROM announcements WHERE status = 'Sent'");
    $sentCount = $sentStmt->fetchColumn();

    // Count draft announcements
    $draftStmt = $pdo->query("SELECT COUNT(*) AS draft_count FROM announcements WHERE status = 'Draft'");
    $draftCount = $draftStmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'sent' => $sentCount,
        'drafts' => $draftCount
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
