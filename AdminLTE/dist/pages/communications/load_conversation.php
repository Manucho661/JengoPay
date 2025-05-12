<?php
include '../db/connect.php';

if (isset($_GET['thread_id']) && $_GET['thread_id'] > 0) {
    $threadId = (int)$_GET['thread_id'];

    // Mark as read
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = :thread_id AND is_read = 0")
        ->execute(['thread_id' => $threadId]);

    // Fetch messages and their attachments
    $stmt = $pdo->prepare("
    SELECT m.id AS message_id, m.sender, m.content, m.timestamp, f.file_path
    FROM messages m
    LEFT JOIN message_files f ON m.thread_id = f.thread_id
    WHERE m.thread_id = :thread_id
    ORDER BY m.timestamp ASC
");

    $stmt->execute(['thread_id' => $threadId]);

    // Group messages with their files
    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['message_id'];
        if (!isset($messages[$id])) {
            $messages[$id] = [
                'sender' => htmlspecialchars($row['sender']),
                'content' => htmlspecialchars($row['content']),
                'files' => [],
                'timestamp' => date('H:i', strtotime($row['timestamp']))
            ];
        }



        if ($row['file_path']) {
            $messages[$id]['files'][] = htmlspecialchars($row['file_path']);
        }
    }

    // Display messages with any attachments
    foreach ($messages as $msg) {
      $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';

      echo "<div class='message $class'>
              <div class='bubble'>{$msg['content']}</div>";

      if (!empty($msg['files'])) {
          echo "<div class='attachments mt-2'>";
          foreach ($msg['files'] as $filePath) {
              $fileName = basename($filePath);
              $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

              switch ($ext) {
                  case 'jpg':
                  case 'jpeg':
                  case 'png':
                  case 'gif':
                      echo "<div><strong>$fileName</strong><br><img src='$filePath' style='max-width:200px; max-height:200px;'></div>";
                      break;
                  case 'pdf':
                      echo "<div><strong>$fileName</strong><br><embed src='$filePath' type='application/pdf' width='100%' height='400px'></div>";
                      break;
                  default:
                      echo "<a href='$filePath' target='_blank'>📎 $fileName</a>";
              }
          }
          echo "</div>";
      }

      // Move the timestamp display here
      echo "<div class='timestamp'>{$msg['timestamp']}</div>";

      echo "</div>";
  }
}
?>
