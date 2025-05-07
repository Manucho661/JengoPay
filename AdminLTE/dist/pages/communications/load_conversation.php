<?php
include '../db/connect.php'; // ensure $pdo is available

if (isset($_GET['thread_id']) && $_GET['thread_id'] > 0) {
    $threadId = (int)$_GET['thread_id'];

    // Mark messages as read
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = :thread_id AND is_read = 0")
        ->execute(['thread_id' => $threadId]);

    // Fetch and display messages
    $stmt = $pdo->prepare("SELECT sender, content, timestamp FROM messages WHERE thread_id = :thread_id ORDER BY timestamp ASC");
    $stmt->execute(['thread_id' => $threadId]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sender = htmlspecialchars($row['sender']);
        $content = htmlspecialchars($row['content']);
        $timestamp = date('H:i', strtotime($row['timestamp']));
        $class = ($sender === 'landlord') ? 'outgoing' : 'incoming';

        echo "<div class='message $class'>
                <div class='bubble'>$content</div>
                <div class='timestamp'>$timestamp</div>
              </div>";
    }

    exit;
}
?>
