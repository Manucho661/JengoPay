<?php
include '../db/connect.php'; // Ensure $pdo is defined

// Get the thread ID
$threadId = isset($_GET['thread_id']) ? (int)$_GET['thread_id'] : 0;

if ($threadId <= 0) {
    echo "<div class='message error'>Invalid thread ID.</div>";
    exit;
}

$pdo->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = :thread_id AND is_read = 0")
    ->execute(['thread_id' => $threadId]);


// Fetch messages for the given thread
$stmt = $pdo->prepare("SELECT sender, content, timestamp FROM messages WHERE thread_id = :thread_id ORDER BY timestamp ASC");
$stmt->execute(['thread_id' => $threadId]);

// Output messages
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sender = htmlspecialchars($row['sender']);
    $content = htmlspecialchars($row['content']);
    $timestamp = date('H:i', strtotime($row['timestamp']));

    // Determine message style
    $class = ($sender === 'landlord') ? 'outgoing' : 'incoming';

    echo "<div class='message $class'>
            <div class='bubble'>$content</div>
            <div class='timestamp'>$timestamp</div>
          </div>";
}
?>
