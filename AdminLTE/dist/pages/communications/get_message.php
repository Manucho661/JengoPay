<?php
include '../db/connect.php'; // Ensure $pdo is defined

// Fetch all messages ordered by time
$stmt = $pdo->query("SELECT sender, content FROM messages ORDER BY timestamp ASC");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $class = ($row['sender'] === 'landlord') ? 'outgoing' : 'incoming';
    $message = htmlspecialchars($row['content']); // sanitize output

    echo "<div class='message $class'>{$message}</div>";
}