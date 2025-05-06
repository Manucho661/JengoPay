<?php
include '../db/connect.php';

if (isset($_GET['thread_id']) && is_numeric($_GET['thread_id'])) {
    $threadId = (int)$_GET['thread_id'];

    // Get the title
    $stmtTitle = $pdo->prepare("SELECT title FROM messages_threads WHERE id = :thread_id");
    $stmtTitle->execute(['thread_id' => $threadId]);
    $titleRow = $stmtTitle->fetch(PDO::FETCH_ASSOC);
    $title = htmlspecialchars($titleRow['title'] ?? 'Conversation');

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

    // Send back title + messages as JSON
    echo json_encode([
        'title' => $title,
        'messages' => $messagesHtml
    ]);
    exit;
}
