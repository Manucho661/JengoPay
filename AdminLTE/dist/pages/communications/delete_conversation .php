<?php
include '../db/connect.php'; // Ensure $pdo is available

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['thread_id'])) {
    $threadId = (int)$_POST['thread_id'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Delete message files if any
        $pdo->prepare("DELETE FROM message_files WHERE thread_id = ?")->execute([$threadId]);

        // Delete messages
        $pdo->prepare("DELETE FROM messages WHERE thread_id = ?")->execute([$threadId]);

        // Delete the thread
        $pdo->prepare("DELETE FROM communication WHERE thread_id = ?")->execute([$threadId]);

        $pdo->commit();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
