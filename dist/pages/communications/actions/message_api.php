<?php

include '../../db/connect.php'; // DB connection

header('Content-Type: application/json');

// Get messages for a conversation
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_messages') {
    $conversation_id = $_GET['conversation_id'];

    try {
        $stmt = $pdo->prepare("SELECT m.*, u.name as sender_name, u.profile_image as sender_image
                              FROM messages m
                              JOIN users u ON m.sender_id = u.user_id
                              WHERE m.conversation_id = ?
                              ORDER BY m.created_at ASC");
        $stmt->execute([$conversation_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'messages' => $messages]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// Send a new message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {
    $conversation_id = $_POST['conversation_id'];
    $sender_id = $_POST['sender_id'];
    $message_text = $_POST['message_text'];
    $image_path = null;

    // Handle image upload
    if (!empty($_FILES['message_image']['name'])) {
        $target_dir = "uploads/messages/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['message_image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['message_image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, sender_id, recipient_id, message_text, image_path)
                              VALUES (?, ?, (SELECT user_id FROM conversation_participants WHERE conversation_id = ? AND user_id != ? LIMIT 1), ?, ?)");
        $stmt->execute([$conversation_id, $sender_id, $conversation_id, $sender_id, $message_text, $image_path]);

        // Update conversation timestamp
        $pdo->prepare("UPDATE conversations SET updated_at = NOW() WHERE conversation_id = ?")->execute([$conversation_id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// Fetch units via AJAX when building is selected
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['building_id']) && !isset($_POST['action'])) {
  $building_id = $_POST['building_id'];
  try {
      $stmt = $pdo->prepare("SELECT unit_id, unit_number FROM units WHERE building_id = ?");
      $stmt->execute([$building_id]);
      $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
      echo json_encode(['units' => $units]);
  } catch (PDOException $e) {
      echo json_encode(['error' => $e->getMessage()]);
  }
  exit;
}


// Get recent conversations
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_conversations') {
    $user_id = $_GET['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT c.*,
                              (SELECT message_text FROM messages WHERE conversation_id = c.conversation_id ORDER BY created_at DESC LIMIT 1) as last_message,
                              (SELECT created_at FROM messages WHERE conversation_id = c.conversation_id ORDER BY created_at DESC LIMIT 1) as last_message_time,
                              (SELECT COUNT(*) FROM messages WHERE conversation_id = c.conversation_id AND is_read = FALSE AND sender_id != ?) as unread_count,
                              (SELECT name FROM users u JOIN conversation_participants cp ON u.user_id = cp.user_id WHERE cp.conversation_id = c.conversation_id AND cp.user_id != ? LIMIT 1) as other_participant_name
                              FROM conversations c
                              JOIN conversation_participants cp ON c.conversation_id = cp.conversation_id
                              WHERE cp.user_id = ?
                              ORDER BY c.updated_at DESC");
        $stmt->execute([$user_id, $user_id, $user_id]);
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'conversations' => $conversations]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>