<?php
include '../db/connect.php';
header('Content-Type: application/json');

// Input validation
$threadId = isset($_GET['thread_id']) ? (int)$_GET['thread_id'] : null;
if (!$threadId) {
    echo json_encode(['error' => 'Missing thread_id']);
    exit;
}

// Fetch thread title
$stmtTitle = $pdo->prepare("SELECT title FROM communication WHERE thread_id = :thread_id");
$stmtTitle->execute(['thread_id' => $threadId]);
$titleRow = $stmtTitle->fetch(PDO::FETCH_ASSOC);
$title = $titleRow ? $titleRow['title'] : 'Not Found';

// Fetch messages and attachments
$stmt = $pdo->prepare("
    SELECT
        m.message_id,
        m.sender,
        m.content,
        m.timestamp,
        m.viewed,
        m.file_path AS single_file_path,
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
    
    // Viewed status indicator
    $tickIcon = $msg['viewed'] 
        ? "<i class='fas fa-check-double viewed-tick' title='Viewed'></i>" 
        : "<i class='fas fa-check-double unviewed-tick' title='Not viewed yet'></i>";

    // Merge file paths from message_files and messages.file_path
    $file_paths = [];
    $file_ids = [];

    if (!empty($msg['file_paths'])) {
        $file_paths = explode('|||', $msg['file_paths']);
        $file_ids = explode('|||', $msg['file_ids']);
    }

    if (!empty($msg['single_file_path'])) {
        $file_paths[] = $msg['single_file_path'];
        $file_ids[] = null;
    }

    $messagesHtml .= "<div class='message $class'>";
    $messagesHtml .= "<div class='bubble'>$content</div>";

    if (!empty($file_paths)) {
        $messagesHtml .= "<div class='attachments mt-2'>";
        foreach ($file_paths as $index => $file_path) {
            if (empty($file_path)) continue;

            $base_upload_dir = '/originalTwo/AdminLTE/dist/pages/communications/uploads/';
            $full_path = $_SERVER['DOCUMENT_ROOT'] . $base_upload_dir . basename($file_path);
            $basename = basename($file_path);
            $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            $file_id = $file_ids[$index] ?? '';

            if (file_exists($full_path) && is_readable($full_path)) {
                $fileData = file_get_contents($full_path);
                $base64 = base64_encode($fileData);
                $mimeType = mime_content_type($full_path);

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                    $messagesHtml .= "
                    <div class='attachment-image whatsapp-style' data-file-id='$file_id' data-filename='$basename'>
                        <div class='image-container'>
                            <img src='data:$mimeType;base64,$base64' alt='$basename' class='media-image'>
                            <div class='image-overlay'>
                                <a href='data:$mimeType;base64,$base64' download='$basename' class='download-icon' title='Download'>
                                    <i class='fas fa-download'></i>
                                </a>
                                <button class='delete-icon' title='Delete' onclick='deleteAttachment(this, \"$basename\", \"$file_id\")'>
                                    <i class='fas fa-trash-alt'></i>
                                </button>
                            </div>
                        </div>
                        <div class='file-name'>$basename</div>
                    </div>";
                } 
                elseif ($ext === 'pdf') {
                    $messagesHtml .= "
                    <div class='attachment-file whatsapp-style-file' data-file-id='$file_id' data-filename='$basename'>
                        <div class='file-container'>
                            <embed src='data:$mimeType;base64,$base64' type='$mimeType' class='file-preview' />
                            <a href='data:$mimeType;base64,$base64' download='$basename' class='download-icon' title='Download'>
                                <i class='fas fa-download'></i>
                            </a>
                            <button class='delete-icon' title='Delete' onclick='deleteAttachment(this, \"$basename\", \"$file_id\")'>
                                <i class='fas fa-trash-alt'></i>
                            </button>
                        </div>
                        <a href='data:$mimeType;base64,$base64' download='$basename' class='file-download-link'>
                            <i class='fas fa-file-pdf file-icon'></i>
                            <span class='file-name'>$basename</span>
                        </a>
                    </div>";
                } 
                else {
                    $messagesHtml .= "
                    <div class='attachment-generic mb-2' data-file-id='$file_id' data-filename='$basename'>
                        <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                            <i class='fas fa-download'></i> $basename
                        </a>
                        <button class='btn btn-sm btn-outline-danger delete-btn' onclick='deleteAttachment(this, \"$basename\", \"$file_id\")'>
                            <i class='fas fa-trash-alt'></i>
                        </button>
                        $tickIcon
                    </div>";
                }
            } else {
                $messagesHtml .= "
                <div class='attachment-error text-danger mb-2'>
                    <i class='fas fa-exclamation-triangle'></i> File not found: $basename
                </div>";
            }
        }
        $messagesHtml .= "</div>"; // end attachments
    }

    $messagesHtml .= "<div class='message-footer'>";
    $messagesHtml .= "<div class='timestamp small text-muted'>$timestamp</div>";
    $messagesHtml .= $tickIcon;
    $messagesHtml .= "</div>";
    $messagesHtml .= "</div>"; // end message
}

// Add the CSS and JavaScript for long-press and delete functionality
$messagesHtml .= "
<style>
    .whatsapp-style, .whatsapp-style-file {
        position: relative;
        touch-action: none;
        margin-bottom: 8px;
    }
    
    .delete-icon, .delete-btn {
        background: #ff3b30;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 10;
    }
    
    .delete-btn {
        opacity: 1;
        margin-left: 8px;
    }
    
    .whatsapp-style.long-pressed .delete-icon,
    .whatsapp-style-file.long-pressed .delete-icon {
        opacity: 1;
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .whatsapp-style:hover .image-overlay,
    .whatsapp-style.long-pressed .image-overlay {
        opacity: 1;
    }
    
    .download-icon {
        color: white;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
    }
</style>

<script>
    // Long press implementation
    document.addEventListener('DOMContentLoaded', function() {
        let longPressTimer;
        const longPressDuration = 800; // milliseconds
        
        document.querySelectorAll('.whatsapp-style, .whatsapp-style-file').forEach(item => {
            // Touch events for mobile
            item.addEventListener('touchstart', function(e) {
                longPressTimer = setTimeout(() => {
                    this.classList.add('long-pressed');
                    e.preventDefault();
                }, longPressDuration);
            });
            
            item.addEventListener('touchend', function() {
                clearTimeout(longPressTimer);
            });
            
            item.addEventListener('touchmove', function() {
                clearTimeout(longPressTimer);
                this.classList.remove('long-pressed');
            });
            
            // Mouse events for desktop
            item.addEventListener('mousedown', function(e) {
                if (e.button === 0) { // Left mouse button
                    longPressTimer = setTimeout(() => {
                        this.classList.add('long-pressed');
                    }, longPressDuration);
                }
            });
            
            item.addEventListener('mouseup', function() {
                clearTimeout(longPressTimer);
            });
            
            item.addEventListener('mouseleave', function() {
                clearTimeout(longPressTimer);
                this.classList.remove('long-pressed');
            });
            
            // Click outside to cancel
            document.addEventListener('click', function(e) {
                if (!item.contains(e.target)) {
                    item.classList.remove('long-pressed');
                }
            });
        });
    });
    
    function deleteAttachment(button, filename, fileId) {
        if (confirm('Are you sure you want to delete \"' + filename + '\"?')) {
            const fileItem = button.closest('.attachment-image, .attachment-file, .attachment-generic');
            fileItem.classList.add('deleting');
            
            // Make AJAX call to delete the file
            fetch('delete_attachment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    file_id: fileId,
                    filename: filename
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fileItem.remove();
                } else {
                    alert('Error deleting file: ' + (data.error || 'Unknown error'));
                    fileItem.classList.remove('deleting');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting file');
                fileItem.classList.remove('deleting');
            });
        }
    }
</script>";

echo json_encode([
    'title' => $title,
    'messages' => $messagesHtml
]);
exit;