<?php
include '../db/connect.php'; // DB connection

if (!isset($_GET['thread_id']) || !(int)$_GET['thread_id']) {
    echo "Invalid thread ID.";
    exit;
}

$threadId = (int)$_GET['thread_id'];

// Mark messages as read
$pdo->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = :thread_id AND is_read = 0")
    ->execute(['thread_id' => $threadId]);

// Fetch messages with their attached files
$stmt = $pdo->prepare("
    SELECT
        m.message_id,
        m.sender,
        m.content,
        m.timestamp,
        mf.file_path
    FROM messages m
    LEFT JOIN message_files mf ON mf.message_id = m.message_id
    WHERE m.thread_id = :thread_id
    ORDER BY m.timestamp ASC
");
$stmt->execute(['thread_id' => $threadId]);

$messages = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['message_id'];

    if (!isset($messages[$id])) {
        $messages[$id] = [
            'sender' => htmlspecialchars($row['sender']),
            'content' => nl2br(htmlspecialchars($row['content'])),
            'timestamp' => date('H:i', strtotime($row['timestamp'])),
            'files' => [],
        ];
    }

    if (!empty($row['file_path'])) {
        $messages[$id]['files'][] = htmlspecialchars($row['file_path']);
    }
}

// Display the messages and their attachments
foreach ($messages as $msg) {
    $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';

    echo "<div class='message $class'>";
    echo "<div class='bubble'>{$msg['content']}</div>";

    if (!empty($msg['files'])) {
      echo "<div class='attachments mt-2'>";
      foreach ($msg['files'] as $file) {
          $fileName = basename($file);
          $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
          $safePath = htmlspecialchars($file);

          if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
              echo "<div class='attachment-image mb-2'>";
              echo "<div class='image-container'>";
              echo "<img src='$safePath' alt='$fileName'
                    class='msg-image img-thumbnail'
                    loading='lazy'
                    data-fullsize='$safePath'
                    style='max-width:200px; cursor:pointer'>";
              echo "</div>";
              echo "<small class='text-muted'>$fileName</small>";
              echo "</div>";
          }
          // ... rest of your file type handling
      }
      echo "</div>";
  }

    echo "<div class='timestamp'>{$msg['timestamp']}</div>";
    echo "</div>";
}
?>
