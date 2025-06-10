<script>
function loadConversation(threadId) {
    if (!threadId) {
        console.error('Invalid or missing threadId');
        return;
    }

    // ✅ Update the browser URL without reloading
    history.replaceState(null, '', '?thread_id=' + encodeURIComponent(threadId));

    activeThreadId = threadId;

    // Remove "active" class from all message thread entries
    document.querySelectorAll('.individual-topic-profiles').forEach(el => {
        el.classList.remove('active');
    });

    // Highlight the currently selected thread
    const selected = document.querySelector(`[data-thread-id="${threadId}"]`);
    if (selected) {
        selected.classList.add('active');
    }

    console.log('Loading thread:', threadId);

    // Fetch messages for the selected thread
    fetch('load_conversation.php?thread_id=' + encodeURIComponent(threadId))
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Server returned error:', data.error);
                document.getElementById('messages').innerHTML = `<div class="text-danger">${data.error}</div>`;
                return;
            }

            if (data.messages) {
                document.getElementById('messages').innerHTML = data.messages;

                // Scroll to bottom to show latest message
                const messagesDiv = document.getElementById('messages');
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            } else {
                document.getElementById('messages').innerHTML = '<div class="text-muted">No messages found in this thread.</div>';
            }
        })
        .catch(error => {
            console.error('Error loading conversation:', error);
            document.getElementById('messages').innerHTML = `<div class="text-danger">Error loading messages. Please try again later.</div>`;
        });
}
</script>



<?php
include '../db/connect.php'; // Make sure $pdo is available

// === HANDLE NEW THREAD SUBMISSION (POST) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title']) && !empty($_POST['message'])) {
    try {
        $title = $_POST['title'] ?? '';
        $unit_id = $_POST['unit_id'] ?? '';
        $tenant = $_POST['tenant'] ?? '';
        $building_name = $_POST['building_name'] ?? '';
        $message = $_POST['message'];
        $uploaded_files = [];
        $upload_dir = "uploads/";

        // Handle file uploads
        if (!empty($_FILES['files']['name'][0])) {
            foreach ($_FILES['files']['name'] as $key => $name) {
                $tmp_name = $_FILES['files']['tmp_name'][$key];
                $unique_name = uniqid() . '_' . basename($name);
                $target_file = $upload_dir . $unique_name;

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $uploaded_files[] = $target_file;
                }
            }
        }

        $files_json = json_encode($uploaded_files);
        $now = (new DateTime('now', new DateTimeZone('Africa/Nairobi')))->format('Y-m-d H:i:s');

        // Insert communication thread
        $stmt = $pdo->prepare("INSERT INTO communication (title, message, files, unit_id, tenant, building_name, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $message, $files_json, $unit_id, $tenant, $building_name, $now, $now]);

        $thread_id = $pdo->lastInsertId();
        $message_id = $pdo->lastInsertId(); // Get the message ID for attachments

      // Insert initial message (no file_path here)
        $stmt = $pdo->prepare("INSERT INTO messages (thread_id, sender, content, timestamp) VALUES (?, ?, ?, ?)");
        $stmt->execute([$thread_id, 'landlord', $message, $now]);


        // Store attachments
        if (!empty($uploaded_files)) {
            $stmt_file = $pdo->prepare("INSERT INTO message_files (message_id, thread_id, file_path) VALUES (?, ?, ?)");
            foreach ($uploaded_files as $file_path) {
                $stmt_file->execute([$message_id, $thread_id, $file_path]);
            }
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit;
    }
}

// === FETCH BUILDINGS ===
$stmt = $pdo->prepare("SELECT building_id, building_name FROM buildings");
$stmt->execute();
$buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === FETCH UNITS IF BUILDING SELECTED ===
$building_id = $_POST['building_id'] ?? null;
$units = [];

if ($building_id) {
    $stmt = $pdo->prepare("SELECT unit_id, unit_number FROM units WHERE building_id = ?");
    $stmt->execute([$building_id]);
    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// === FETCH COMMUNICATION THREADS ===
$stmt = $pdo->prepare("
    SELECT
        c.thread_id,
        c.title,
        c.tenant,
        c.created_at,
        c.building_name,
        c.message,
        (SELECT content FROM messages WHERE thread_id = c.thread_id ORDER BY timestamp DESC LIMIT 1) AS last_message,
        (SELECT timestamp FROM messages WHERE thread_id = c.thread_id ORDER BY timestamp DESC LIMIT 1) AS last_time,
        (SELECT COUNT(*) FROM messages WHERE thread_id = c.thread_id AND is_read = 0) AS unread_count,
        (SELECT file_path FROM message_files WHERE thread_id = c.thread_id LIMIT 1) AS preview_file
    FROM communication c
    ORDER BY last_time DESC
");
$stmt->execute();
$communications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



<!-- new load_conversation.php  -->
<?php
include '../db/connect.php';
header('Content-Type: application/json');

// Debug: log request method and query string to debug_log.txt
file_put_contents('debug_log.txt', "METHOD: " . $_SERVER['REQUEST_METHOD'] . "\nQUERY_STRING: " . $_SERVER['QUERY_STRING'] . "\nGET: " . print_r($_GET, true), FILE_APPEND);

// Input
$threadId = isset($_GET['thread_id']) ? (int)$_GET['thread_id'] : 0;
$attachmentId = isset($_GET['attachment_id']) ? (int)$_GET['attachment_id'] : null;

if (!$threadId) {
    echo json_encode(['error' => 'Missing thread_id']);
    exit;
}

// === Fetch thread details ===
$stmtThread = $pdo->prepare("SELECT title, tenant, building_name, created_at FROM communication WHERE thread_id = :thread_id");
$stmtThread->execute(['thread_id' => $threadId]);
$threadDetails = $stmtThread->fetch(PDO::FETCH_ASSOC);

if (!$threadDetails) {
    echo json_encode(['error' => 'Thread not found']);
    exit;
}

// === Fetch messages ===
if ($attachmentId) {
    // Fetch single message with specific attachment
    $stmt = $pdo->prepare("
        SELECT m.message_id, m.sender, m.content, m.timestamp,
               mf.file_path, mf.file_id
        FROM messages m
        JOIN message_files mf ON m.message_id = mf.message_id
        WHERE m.thread_id = :thread_id AND mf.file_id = :attachment_id
    ");
    $stmt->execute([
        'thread_id' => $threadId,
        'attachment_id' => $attachmentId
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch all messages in the thread
    $stmt = $pdo->prepare("
        SELECT m.message_id, m.sender, m.content, m.timestamp,
               GROUP_CONCAT(DISTINCT mf.file_path SEPARATOR '|||') AS file_paths,
               GROUP_CONCAT(DISTINCT mf.file_id SEPARATOR '|||') AS file_ids
        FROM messages m
        LEFT JOIN message_files mf ON mf.message_id = m.message_id
        WHERE m.thread_id = :thread_id
        GROUP BY m.message_id, m.sender, m.content, m.timestamp
        ORDER BY m.timestamp ASC
    ");
    $stmt->execute(['thread_id' => $threadId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// === Build HTML for messages ===
$messagesHtml = '';

foreach ($messages as $msg) {
    $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';
    $content = nl2br(htmlspecialchars($msg['content']));
    $timestamp = date('H:i', strtotime($msg['timestamp']));

    $filePaths = [];
    $fileIds = [];

    error_log(print_r($msg, true));

    if (!empty($msg['file_paths'])) {
        $filePaths = explode('|||', $msg['file_paths']);
        $fileIds = explode('|||', $msg['file_ids']);
    } elseif (!empty($msg['file_path'])) {
        $filePaths[] = $msg['file_path'];
        $fileIds[] = $msg['file_id'];
    }

    $messagesHtml .= "<div class='message $class'>
        <div class='bubble'>$content</div>";

    if (!empty($filePaths)) {
        $messagesHtml .= "<div class='attachments mt-2'>";
        foreach ($filePaths as $index => $filePath) {
            $fullPath = htmlspecialchars($filePath);
            $basename = basename($fullPath);
            $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            $fileId = $fileIds[$index] ?? '';

            error_log("Attempting to read file: " . $fullPath);
            if (file_exists($fullPath) && is_readable($fullPath)) {
                try {
                    $fileData = file_get_contents($fullPath);
                    if ($fileData === false) {
                        throw new Exception("Failed to read file");
                    }
                    $base64 = base64_encode($fileData);
                    $mimeType = mime_content_type($fullPath);

                    // Use a switch statement for better readability
                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                        case 'webp':
                        case 'bmp':
                            $messagesHtml .= "<div class='attachment-image mb-2'>
                                <img src='data:$mimeType;base64,$base64' alt='$basename' class='img-thumbnail' style='max-width:200px; max-height:150px;'>
                                <div class='file-name small'>$basename</div>
                            </div>";
                            break;
                        case 'pdf':
                            $messagesHtml .= "<div class='attachment-file mb-2'>
                                <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                                    <i class='fas fa-file-pdf'></i> $basename
                                </a>
                            </div>";
                            break;
                        default:
                            $messagesHtml .= "<div class='attachment-file mb-2'>
                                <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                                    <i class='fas fa-download'></i> $basename
                                </a>
                            </div>";
                            break;
                    }
                } catch (Exception $e) {
                    error_log("Error reading file: " . $e->getMessage());
                    $messagesHtml .= "<div class='attachment-error mb-2 text-danger'>
                        <i class='fas fa-exclamation-triangle'></i> Error reading file: $basename
                    </div>";
                }
            } else {
                $messagesHtml .= "<div class='attachment-error mb-2 text-danger'>
                    <i class='fas fa-exclamation-triangle'></i> File not found: $basename
                </div>";
            }
        }
        $messagesHtml .= "</div>";
    }

    $messagesHtml .= "<div class='timestamp small text-muted'>$timestamp</div></div>";
}

// === Return as JSON ===
echo json_encode([
    'thread_id'     => $threadId,
    'title'         => $threadDetails['title'],
    'tenant'        => $threadDetails['tenant'],
    'building_name' => $threadDetails['building_name'],
    'created_at'    => $threadDetails['created_at'],
    'messages'      => $messagesHtml,
    'files'         => $messages, // raw messages with file_paths/file_ids
]);
exit;
?>



<?php
include '../db/connect.php';
header('Content-Type: application/json');

$threadId = isset($_GET['thread_id']) ? (int)$_GET['thread_id'] : null;
if (!$threadId) {
    echo json_encode(['error' => 'Missing thread_id']);
    exit;
}

// Get thread title
$stmtTitle = $pdo->prepare("SELECT title FROM communication WHERE thread_id = :thread_id");
$stmtTitle->execute(['thread_id' => $threadId]);
$titleRow = $stmtTitle->fetch(PDO::FETCH_ASSOC);
$title = $titleRow ? $titleRow['title'] : 'Not Found';

// Get messages
$stmt = $pdo->prepare("
    SELECT
        m.message_id,
        m.sender,
        m.content,
        m.timestamp,
        m.file_path AS single_file_path,  -- NEW: directly stored file
        GROUP_CONCAT(mf.file_path SEPARATOR '|||') AS file_paths,
        GROUP_CONCAT(mf.file_id SEPARATOR '|||') AS file_ids
    FROM messages m
    LEFT JOIN message_files mf ON m.message_id = mf.message_id
    WHERE m.thread_id = :thread_id
    GROUP BY m.message_id
    ORDER BY m.timestamp ASC
");
$stmt->execute(['thread_id' => $threadId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$messagesHtml = '';

foreach ($messages as $msg) {
    $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';
    $content = nl2br(htmlspecialchars($msg['content']));
    $timestamp = date('H:i', strtotime($msg['timestamp']));

    $file_paths = [];
    $file_ids = [];

    if (!empty($msg['file_paths'])) {
        $file_paths = explode('|||', $msg['file_paths']);
        $file_ids = explode('|||', $msg['file_ids']);
    }

    if (!empty($msg['single_file_path'])) {
        $file_paths[] = $msg['single_file_path']; // Add direct file_path
        $file_ids[] = null;
    }

    $messagesHtml .= "<div class='message $class'>";
    $messagesHtml .= "<div class='bubble'>$content</div>";

    if (!empty($file_paths)) {
        $messagesHtml .= "<div class='attachments mt-2'>";
        foreach ($file_paths as $index => $file_path) {
            if (empty($file_path)) continue;

            // Make absolute path for server-side access
            // $full_path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($file_path, '/');
            $base_upload_dir = '/originalTwo/AdminLTE/dist/pages/communications/uploads/';
            $full_path = $_SERVER['DOCUMENT_ROOT'] . $base_upload_dir . basename($file_path);
            error_log("FULL PATH: " . $full_path);

            $basename = basename($file_path);
            $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            $file_id = $file_ids[$index] ?? '';

            if (file_exists($full_path) && is_readable($full_path)) {
                $fileData = file_get_contents($full_path);
                $base64 = base64_encode($fileData);
                $mimeType = mime_content_type($full_path);

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                  $messagesHtml .= "
                  <div class='attachment-image whatsapp-style'>
                      <div class='image-container'>
                          <a href='data:$mimeType;base64,$base64' download='$basename' class='download-icon' title='Download'>
                              <i class='fas fa-download'></i>
                          </a>

                          <img src='data:$mimeType;base64,$base64' alt='$basename' class='media-image'>

                          </div>
                      <div class='file-name'>$basename</div>

              </div>";

                } elseif ($ext === 'pdf') {
                  $messagesHtml .= "<div class='attachment-file whatsapp-style-file' data-filename='$basename'>
                  <div class='file-container'>
                      <embed src='data:$mimeType;base64,$base64' type='$mimeType' class='file-preview' />
                      <a href='data:$mimeType;base64,$base64' download='$basename' class='download-icon' title='Download'>
                          <i class='fas fa-download'></i>
                      </a>
                      <button class='delete-icon' title='Delete' onclick='deleteAttachment(this, \"$basename\")'>
                          <i class='fas fa-trash-alt'></i>
                      </button>
                  </div>
                  <a href='data:$mimeType;base64,$base64' download='$basename' class='file-download-link'>
                      <i class='fas fa-file-pdf file-icon'></i>
                      <span class='file-name'>$basename</span>
                  </a>
              </div>";
                } else {
                    $messagesHtml .= "<div class='attachment-file mb-2'>
                        <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                            <i class='fas fa-download'></i> $basename
                        </a>
                    </div>";

                }
            } else {
                $messagesHtml .= "<div class='attachment-error text-danger mb-2'>
                    <i class='fas fa-exclamation-triangle'></i> File not found: $basename
                </div>";
            }
        }
        $messagesHtml .= "</div>"; // attachments
    }

    // Timestamp + tick logic
   // Your existing loop for messages
// ...
$messagesHtml .= "<div class='timestamp small text-muted d-flex align-items-center message-footer'>"; // Added 'message-meta' class
$messagesHtml .= "$timestamp";

if ($msg['sender'] === 'landlord') {
    if ($msg['viewed']) {
        // Seen → double blue ticks
        $messagesHtml .= "<i class='fas fa-check-double text-primary ms-2' title='Seen'></i>";
    } else {
        // Sent but not seen → single grey tick
        $messagesHtml .= "<i class='fas fa-check text-muted ms-2' title='Sent, not seen'></i>";
    }
}

$messagesHtml .= "</div>"; // timestamp
$messagesHtml .= "</div>"; // message container
// ...
}
echo json_encode([
    'title' => $title,
    'messages' => $messagesHtml
]);
exit;
