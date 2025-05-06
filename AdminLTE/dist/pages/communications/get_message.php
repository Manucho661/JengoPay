<?php
include '../db/connect.php';

if (isset($_GET['thread_id']) && is_numeric($_GET['thread_id'])) {
    $threadId = (int)$_GET['thread_id'];

    // Mark messages as read
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = :thread_id AND is_read = 0")
        ->execute(['thread_id' => $threadId]);

    // Fetch messages for this thread
    $stmt = $pdo->prepare("SELECT sender, content, timestamp FROM messages WHERE thread_id = :thread_id ORDER BY timestamp ASC");
    $stmt->execute(['thread_id' => $threadId]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $class = ($row['sender'] === 'landlord') ? 'outgoing' : 'incoming';
        $message = htmlspecialchars($row['content']);
        $timestamp = date('H:i', strtotime($row['timestamp']));

        echo "<div class='message $class'>
                <div class='bubble'>$message</div>
                <div class='timestamp'>$timestamp</div>
              </div>";
    }

    exit; // ⛔ Don't render the rest of the page
}
?>
