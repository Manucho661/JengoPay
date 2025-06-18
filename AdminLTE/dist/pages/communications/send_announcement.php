<?php
header('Content-Type: application/json');
include '../db/connect.php'; // This defines $pdo (NOT $conn)

try {
    // Sanitize inputs
    $recipient = isset($_POST['recipient']) ? trim($_POST['recipient']) : '';
    $priority  = isset($_POST['priority']) ? trim($_POST['priority']) : '';
    $message   = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate inputs
    if ($recipient === '' || $priority === '' || $message === '') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required fields.'
        ]);
        exit;
    }

    // Prepare and execute insert using $pdo
    $stmt = $pdo->prepare("
        INSERT INTO announcements (recipient, priority, message, created_at)
        VALUES (:recipient, :priority, :message, NOW())
    ");

    $stmt->execute([
        ':recipient' => $recipient,
        ':priority'  => $priority,
        ':message'   => $message
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Announcement sent successfully.'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
