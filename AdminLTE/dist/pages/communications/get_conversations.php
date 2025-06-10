<?php
include '../db/connect.php';

$building_id = $_GET['building_id'] ?? null;

// ✅ Log to error log (check php_error_log)
error_log("building_id received: " . $building_id);

if (!$building_id) {
    echo '<tr><td colspan="5" class="text-center text-danger">No building selected.</td></tr>';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM communication WHERE building_name = ? ORDER BY created_at DESC");
$stmt->execute([$building_id]);
$communications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($communications)) {
    echo '<tr><td colspan="5" class="text-center text-danger">No conversations for this building.</td></tr>';
    exit;
}

foreach ($communications as $comm) {
    $datetime = new DateTime($comm['created_at'] ?? date('Y-m-d H:i:s'));
    $date = $datetime->format('d-m-Y');
    $time = $datetime->format('h:iA');
    $sender = htmlspecialchars($comm['tenant'] ?? 'Tenant');
    $recipient = htmlspecialchars($comm['recipient'] ?? 'Recipient');
    $title = htmlspecialchars($comm['title']);
    $threadId = htmlspecialchars($comm['thread_id']);

    echo "<tr class='table-row'>
        <td>
            <div class='date'>{$date}</div>
            <div class='time'>{$time}</div>
        </td>
        <td class='title'>{$title}</td>
        <td><div class='recipient'>{$recipient}</div></td>
        <td><div class='sender'>{$sender}</div></td>
        <td>
            <button class='btn btn-primary view' onclick='loadConversation({$threadId})'>
                <i class='bi bi-eye'></i> View
            </button>
            <button class='btn btn-danger delete' data-thread-id='{$threadId}'>
                <i class='bi bi-trash3'></i> Delete
            </button>
        </td>
    </tr>";
}
?>


<!-- loadConversation -->
<script>
//let activeThreadId = null;

function loadConversation(threadId) {
    if (!threadId) {
        console.error('Invalid or missing threadId');
        return;
    }

    // ✅ Update the browser URL without reloading
    history.replaceState(null, '', '?thread_id=' + encodeURIComponent(threadId));

    activeThreadId = threadId;

    // Remove "active" class from all thread entries
    document.querySelectorAll('.individual-topic-profiles').forEach(el => {
        el.classList.remove('active');
    });

    // Highlight the currently selected thread
    const selected = document.querySelector(`[data-thread-id="${threadId}"]`);
    if (selected) {
        selected.classList.add('active');
    }

    console.log('Loading thread:', threadId);

    // console.log('Loading thread:', threadId);

    // Fetch messages for the selected thread
    fetch('load_conversation.php?thread_id=' + encodeURIComponent(threadId))
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.messages) {
                //  alert(data.messages);
                const messagesDiv = document.getElementById('messages');
                messagesDiv.innerHTML = data.messages;
                messagesDiv.scrollTop = messagesDiv.scrollHeight; // Scroll to bottom
            } else {
                console.warn('No messages returned from server.');
            }
        })
        .catch(error => {
            console.error('Error loading conversation:', error);
        });
}
</script>


<!-- send & get message -->
<script>
let activeThreadId = null; // Declare globally

function selectThread(threadId) {
    activeThreadId = threadId;
    loadConversation(threadId);
}
// Function to send the message and files
function sendMessage() {
    const inputBox = document.getElementById('inputBox');
    const fileInput = document.getElementById('fileInput');
    const filePreviewContainer = document.getElementById('filePreviewContainer');

    if (!inputBox || !fileInput) {
        console.error("Required input elements not found.");
        return;
    }

    const messageText = inputBox.innerText.trim();
    const files = fileInput.files;

    if (!messageText && (!files || files.length === 0)) {
        alert("Please type a message or attach a file.");
        return;
    }

    if (!activeThreadId) {
        alert("Please select a conversation thread first.");
        return;
    }

    const formData = new FormData();
    formData.append('message', messageText);
    formData.append('thread_id', activeThreadId);
    formData.append('sender', 'landlord');

    // Append all selected files
    for (let i = 0; i < files.length; i++) {
        formData.append('file[]', files[i]);
    }

    // Send the message and files
    fetch('send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.error || 'Failed to send message.');
        }
        else{
          alert('Message Sent Successfully');
        }
        // Clear the input box and file previews after sending
        inputBox.innerText = '';
        fileInput.value = '';
        filePreviewContainer.innerHTML = ''; // Clear file preview after sending
        loadConversation(activeThreadId); // Reload conversation after message is sent
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    });
  }


  function previewFile() {
    const fileInput = document.getElementById('fileInput');
    const filePreviewContainer = document.getElementById('filePreviewContainer');
    filePreviewContainer.innerHTML = ''; // Clear previous previews

    const files = fileInput.files;
    if (files.length === 0) {
        return;
    }

    // Loop through selected files and create previews
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const fileType = file.type.split('/')[0]; // Get file type (image, pdf, etc.)
            const filePreview = document.createElement('div');
            filePreview.classList.add('file-preview');

            // Image files preview
            if (fileType === 'image') {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.maxWidth = '150px';
                img.style.maxHeight = '150px';
                filePreview.appendChild(img);
            }
            // PDF files preview
            else if (file.type === 'application/pdf') {
                const pdfPreview = document.createElement('div');
                pdfPreview.innerHTML = `<i class="fa fa-file-pdf"></i> ${file.name}`;
                filePreview.appendChild(pdfPreview);
            }
            // Other file types
            else {
                const fileNamePreview = document.createElement('div');
                fileNamePreview.innerHTML = `<i class="fa fa-file"></i> ${file.name}`;
                filePreview.appendChild(fileNamePreview);
            }

            // Append file preview to container
            filePreviewContainer.appendChild(filePreview);
        };
        reader.readAsDataURL(file); // Read file as Data URL for preview
    });

  }


function getMessage(messageId) {
    const messageContainer = document.getElementById('messageDetails');

    fetch('get_message.php?message_id=' + messageId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageContainer.innerHTML = data.message;
                messageContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                messageContainer.innerHTML = `<div class="alert alert-warning">${data.error}</div>`;
                console.warn('Fetch warning:', data.error);
            }
        })
        .catch(error => {
            messageContainer.innerHTML = `<div class="alert alert-danger">An error occurred while fetching the message.</div>`;
            console.error('Fetch error:', error);
        });
}
</script>
<script>
function toggleOptionsMenu(btn) {
    const menu = btn.nextElementSibling;
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';

    // Hide others
    document.querySelectorAll('.options-menu').forEach(m => {
        if (m !== menu) m.style.display = 'none';
    });
}

function deleteMessage(messageId) {
    if (!confirm("Delete this message?")) return;

    fetch('delete_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `message_id=${messageId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const el = document.querySelector(`.message[data-message-id='${messageId}']`);
            if (el) el.remove();
        } else {
            alert("Failed to delete.");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Error deleting message.");
    });
}
</script>
