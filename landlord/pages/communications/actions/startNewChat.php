<?php
require_once '../../db/connect.php'; // PDO connection in $pdo

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

function redirect_with_message(string $url, string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,      // success | danger | warning | info
        'message' => $message
    ];
    header("Location: $url");
    exit;
}

// Only accept POST submit
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['start_new_chat'])) {
    return; // do nothing on GET
}

try {
    // Ensure session exists (connect.php might already do this, but safe)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Must be logged in
    if (!isset($_SESSION['user']['id'])) {
        redirect_with_message($_SERVER['REQUEST_URI'], 'danger', 'You must be logged in to start a new conversation.');
    }

    $userId = (int) $_SESSION['user']['id'];

    // Collect inputs
    $recipientType = trim((string)($_POST['recipient_type'] ?? '')); // tenant | admin
    $title         = trim((string)($_POST['title'] ?? ''));          // conversation title
    $messageBody   = trim((string)($_POST['message'] ?? ''));        // first message

    // Tenant flow inputs (if tenant selected)
    $tenantId   = (string)($_POST['tenant_id'] ?? '');  // e.g. t1 (dummy) or actual users.id
    $buildingId = (string)($_POST['building_id'] ?? '');
    $unitId     = (string)($_POST['unit_id'] ?? '');
    $maintenanceRequestId = $_POST['maintenance_request_id'] ?? null;



    $recipientUserId = 0;


    // Check if 'recipient_type' exists and is 'admin'
    if (isset($_POST['recipient_type']) && $_POST['recipient_type'] === 'admin') {

        // Query to get the user id where the role is 'admin'
        $stmt = $pdo->prepare("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
        $stmt->execute();
        // Fetch the result
        $adminUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($adminUser) {
            // Set the recipientUserId to the admin's user ID
            $recipientUserId = $adminUser['id'];

            // Output the result (for debugging purposes)
            echo "Recipient User ID: " . $recipientUserId;
        } else {
            echo "No admin found.";
        }
    } else {
        // If no admin, use tenant_id from POST as recipientUserId and ensure it's an integer
        if (isset($_POST['tenant_id'])) {
            $recipientUserId = (int) $_POST['tenant_id'];  // Casting to integer
            echo "Recipient User ID (tenant): " . $recipientUserId;
        } else {
            echo "No recipient specified.";
        }
    }


    // --- DB transaction: create conversation, participants, first message, optional attachment ---
    $pdo->beginTransaction();

    // 1) create conversation
    $stmtConv = $pdo->prepare("
        INSERT INTO conversations (title, created_by, created_at, updated_at)
        VALUES (:title, :created_by, NOW(), NOW())
    ");
    $stmtConv->execute([
        ':title'      => $title,
        ':created_by' => $userId,
    ]);

    $conversationId = (int)$pdo->lastInsertId();

    // 1) Prepare base SQL
    $sql = "
    INSERT INTO conversation_links
    (conversation_id, building_id, unit_id, maintenance_request_id)
    VALUES (:conversation_id, :building_id, :unit_id, :maintenance_request_id)
";

    $stmtLink = $pdo->prepare($sql);

    // 2) Bind values (maintenance_request_id can be NULL)
    $stmtLink->execute([
        ':conversation_id'        => $conversationId,
        ':building_id'            => !empty($buildingId) ? (int)$buildingId : null,
        ':unit_id'                => !empty($unitId) ? (int)$unitId : null,
        ':maintenance_request_id' => !empty($maintenanceRequestId) ? (int)$maintenanceRequestId : null,
    ]);


    // 2) add participants (creator + recipient)
    $stmtPart = $pdo->prepare("
        INSERT INTO conversation_participants (conversation_id, user_id, joined_at)
        VALUES (:conversation_id, :user_id, NOW())
    ");

    $stmtPart->execute([
        ':conversation_id' => $conversationId,
        ':user_id'         => $userId,
    ]);

    $stmtPart->execute([
        ':conversation_id' => $conversationId,
        ':user_id'         => $recipientUserId,
    ]);


    // // 3) insert first message
    $messageType = 'text';
    $stmtMsg = $pdo->prepare("
        INSERT INTO conversation_messages (conversation_id, sender_id, body, message_type, created_at)
        VALUES (:conversation_id, :sender_id, :body, :message_type, NOW())
    ");
    $stmtMsg->execute([
        ':conversation_id' => $conversationId,
        ':sender_id'       => $userId,
        ':body'            => $messageBody,
        ':message_type'    => $messageType,
    ]);


    $messageId = (int)$pdo->lastInsertId();

    // // 4) optional attachment upload (image)
    // Frontend should submit <input type="file" name="attachment">
    if (!empty($_FILES['attachment']['name']) && is_uploaded_file($_FILES['attachment']['tmp_name'])) {
        $file = $_FILES['attachment'];

        // Basic checks
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload failed with error code: ' . $file['error']);
        }

        // Validate mime
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/jpg' => 'jpg'];

        if (!isset($allowed[$mime])) {
            throw new RuntimeException('Only JPG/PNG images are allowed.');
        }

        // Size limit (5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new RuntimeException('Image is too large. Max size is 5MB.');
        }

        // Save
        $ext = $allowed[$mime];
        $uploadsDir = __DIR__ . '/../../uploads/chat_attachments';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0775, true);
        }

        $safeName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', (string)$file['name']);
        $newName  = time() . '_' . bin2hex(random_bytes(6)) . '_' . $safeName;
        if (pathinfo($newName, PATHINFO_EXTENSION) === '') {
            $newName .= '.' . $ext;
        }

        $destPath = $uploadsDir . '/' . $newName;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        // Save attachment row (assuming message_attachments table exists)
        // Store a relative path for easier use in HTML
        $relativeUrl = 'uploads/chat_attachments/' . $newName;

        // $stmtAtt = $pdo->prepare("
        //     INSERT INTO message_attachments (message_id, file_url, file_name, mime_type, file_size, created_at)
        //     VALUES (:message_id, :file_url, :file_name, :mime_type, :file_size, NOW())
        // ");
        // $stmtAtt->execute([
        //     ':message_id' => $messageId,
        //     ':file_url'   => $relativeUrl,
        //     ':file_name'  => $file['name'],
        //     ':mime_type'  => $mime,
        //     ':file_size'  => (int)$file['size'],
        // ]);

        // // Update message type to image
        // $pdo->prepare("UPDATE conversation_messages SET message_type = 'image' WHERE id = :id")
        //     ->execute([':id' => $messageId]);
    }

    // Update conversation updated_at (optional)
    $pdo->prepare("UPDATE conversations SET updated_at = NOW() WHERE id = :id")
        ->execute([':id' => $conversationId]);

    $pdo->commit();

    $_SESSION['success'] = "Conversation started successfully.";
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
} catch (Throwable $e) {
    // if (isset($pdo) && $pdo->inTransaction()) {
    //     $pdo->rollBack();
    // }

    $_SESSION['error'] = 'Failed to create a new chat: ' . $e->getMessage();
    exit;
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
