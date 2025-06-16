<?php
header('Content-Type: application/json');
include '../../db/connect.php'; // Update this path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['thread_id'])) {
    $threadId = $_POST['thread_id'];

    try {
        // Check if the thread exists
        $stmt = $pdo->prepare("SELECT * FROM communication WHERE thread_id = ?");
        $stmt->execute([$threadId]);

        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'error' => 'Thread not found']);
            exit;
        }

        // Delete related records
        $pdo->prepare("DELETE FROM messages WHERE thread_id = ?")->execute([$threadId]);
        $pdo->prepare("DELETE FROM message_files WHERE thread_id = ?")->execute([$threadId]);
        $pdo->prepare("DELETE FROM communication WHERE thread_id = ?")->execute([$threadId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
