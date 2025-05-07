<?php
include '../db/connect.php';

header('Content-Type: application/json');

if (isset($_GET['thread_id']) && is_numeric($_GET['thread_id'])) {
    $threadId = (int)$_GET['thread_id'];

    // Get thread title
    $stmtTitle = $pdo->prepare("SELECT title FROM messages_threads WHERE id = :thread_id");
    $stmtTitle->execute(['thread_id' => $threadId]);
    $titleRow = $stmtTitle->fetch(PDO::FETCH_ASSOC);

    if (!$titleRow) {
        echo json_encode([
            'title' => 'Not Found',
            'messages' => "<div class='message error'>Thread not found.</div>"
        ]);
        exit;
    }

    $title = htmlspecialchars($titleRow['title']);

    // Mark messages as read
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = :thread_id AND is_read = 0")
        ->execute(['thread_id' => $threadId]);

    // Fetch messages
    $stmt = $pdo->prepare("SELECT sender, content, timestamp FROM messages WHERE thread_id = :thread_id ORDER BY timestamp ASC");
    $stmt->execute(['thread_id' => $threadId]);

    $messagesHtml = '';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $class = ($row['sender'] === 'landlord') ? 'outgoing' : 'incoming';
        $message = htmlspecialchars($row['content']);
        $timestamp = date('H:i', strtotime($row['timestamp']));

        $messagesHtml .= "<div class='message $class'>
                            <div class='bubble'>$message</div>
                            <div class='timestamp'>$timestamp</div>
                          </div>";
    }

    echo json_encode([
        'title' => $title,
        'messages' => $messagesHtml
    ]);
    exit;
}
