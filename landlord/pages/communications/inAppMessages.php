<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

// success and error messages
$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

include '../db/connect.php'; // adjust path
// actions

// get messages
require_once "./actions/getMessages.php";
require_once "./actions/startNewChat.php";
?>
<?php

include '../db/connect.php';

try {
  $stmt = $pdo->query("SELECT id, building_name, category FROM buildings ORDER BY building_name ASC");
  $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

?>



<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE | Dashboard v2</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE | Dashboard v2" />

  <!--begin::Fonts-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
    crossorigin="anonymous" />

  <link rel="stylesheet" href="../../../landlord/assets/main.css" />
  <!-- <link rel="stylesheet" href="../../../landlord/css/adminlte.css" /> -->
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      font-size: 16px;

    }

    .app-main {
      position: relative;
      display: flex;
      flex-direction: column;
      grid-area: lte-app-main;
      max-width: 100vw;
      padding-bottom: 0.75rem;
      transition: 0.3s ease-in-out;
      flex-grow: 1;

    }

    .app-main .app-content-header {
      padding: 1rem 0.5rem;
    }

    img {
      max-width: 100%;
      max-height: 100%;
      display: none;
    }

    #filePreviews {
      display: flex;
    }

    .attachments ul {
      list-style: none;
      padding-left: 0;
    }

    .attachments li {
      margin-bottom: 5px;
    }

    .attachments a {
      color: #007BFF;
      text-decoration: underline;
    }

    .attachment-image.whatsapp-style {
      background-color: #f0f0f0;
      border-radius: 12px;
      padding: 8px;
      display: inline-block;
      max-width: 220px;
      margin-bottom: 8px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
      font-family: sans-serif;
      position: relative;
    }

    .image-container {
      position: relative;
      display: inline-block;
    }

    .media-image {
      max-width: 200px;
      max-height: 150px;
      border-radius: 10px;
      display: block;
    }

    .download-icon {
      position: absolute;
      bottom: 6px;
      right: 6px;
      /* background: rgba(0,0,0,0.5); */
      background-color: #00192D;
      color: white;
      padding: 4px;
      border-radius: 50%;
      text-decoration: none;
      font-size: 12px;
      transition: background 0.2s;
    }

    .download-icon:hover {
      background: rgba(0, 0, 0, 0.7);
    }

    .file-name {
      font-size: 12px;
      color: #555;
      text-align: center;
      margin-top: 6px;
      word-break: break-all;
    }

    .attachment-file.whatsapp-style-file {
      background-color: #f0f0f0;
      border-radius: 12px;
      padding: 8px;
      display: inline-block;
      max-width: 250px;
      font-family: sans-serif;
      margin-bottom: 10px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .file-container {
      position: relative;
      width: 100%;
      height: 140px;
      overflow: hidden;
      border-radius: 8px;
      margin-bottom: 8px;
    }

    .file-preview {
      width: 100%;
      height: 100%;
      border: none;
      display: block;
      background-color: #fff;
    }

    .download-icon {
      position: absolute;
      bottom: 6px;
      right: 6px;
      background: rgba(0, 0, 0, 0.5);
      color: #fff;
      padding: 4px;
      border-radius: 50%;
      text-decoration: none;
      font-size: 12px;
      transition: background 0.2s;
    }

    .download-icon:hover {
      background: rgba(0, 0, 0, 0.7);
    }

    .file-download-link {
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      color: #333;
    }

    .file-icon {
      font-size: 18px;
      color: #00192D;
    }

    .file-name {
      font-size: 14px;
      word-break: break-word;
    }

    .viewed-tick {
      color: #34B7F1;
      /* WhatsApp blue */
      font-size: 14px;
      margin-left: 15rem;
    }

    .unviewed-tick {
      color: #ccc;
      /* grey */
      font-size: 14px;
      margin-left: 25rem;
    }

    .tick-status {
      text-align: right;
      font-size: 0.9em;
      margin-top: 4px;
      color: grey;
    }

    /* .timestamp{
  margin-left: 25rem;
  font-size:12px;
} */
    .file-previews-container {
      display: none;
      /* Hidden by default */
      flex-wrap: wrap;
      gap: 12px;
      justify-content: center;
      padding: 10px;
      background: #f5f5f5;
      border-radius: 10px;
      margin: 10px 0;
    }

    .file-preview {
      width: 120px;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .preview-image-container {
      height: 100px;
      position: relative;
    }

    .preview-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .preview-document,
    .preview-generic {
      height: 100px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      background: #f0f2f5;
    }

    .pdf-icon {
      font-size: 40px;
      color: #e74c3c;
    }

    .file-icon {
      font-size: 40px;
      color: #7f8c8d;
    }

    .preview-overlay {
      position: absolute;
      top: 5px;
      right: 5px;
      opacity: 0;
      transition: opacity 0.2s;
    }

    .file-preview:hover .preview-overlay {
      opacity: 1;
    }

    .remove-preview {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .preview-footer {
      padding: 8px;
      font-size: 12px;
    }

    .file-name {
      display: block;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .file-size {
      color: #666;
      font-size: 11px;
    }

    .input-box[contenteditable="true"]:empty:before {
      content: attr(placeholder);
      color: #999;
      pointer-events: none;
      display: block;
    }

    #filesPreviewList div:hover {
      background: #e9e9e9;
      cursor: pointer;
    }

    /* Base styles (apply to all screen sizes) */
    /* Base styles for the message footer (applies to all screen sizes) */
    .message-footer {
      /* Ensures the timestamp and ticks stay on one line if possible */
      white-space: nowrap;
      /* Vertically align items in the flex container */
      align-items: center;
      /* Add some padding or margin if they are too close to the message content */
      padding-top: 5px;
    }

    /* Adjustments specifically for small screens (e.g., mobile phones) */
    @media (max-width: 576px) {

      /* This breakpoint targets screens up to 576px wide (typical mobile portrait) */
      .message-footer {
        /* Reduce font size to save space */
        font-size: 0.7rem !important;
        /* Slightly smaller than default 'small' */

        /* Ensure it doesn't get squished if its parent is a flex container */
        flex-shrink: 0;
        flex-grow: 0;
      }

      /* Reduce margin between the time and the tick icon for tighter spacing */
      .message-footer .ms-2 {
        margin-left: 0.3rem !important;
        /* Reduce the default Bootstrap margin-left */
      }
    }

    /* Optional: Even smaller screens, if you need to fine-tune further */
    @media (max-width: 375px) {

      /* Example for very small phone screens */
      .message-footer {
        font-size: 0.65rem !important;
      }
    }

    .message-options {
      position: relative;
      float: right;
      margin-left: 10px;
    }

    .options-btn {
      background: transparent;
      border: none;
      font-size: 12px;
      cursor: pointer;
    }

    .options-menu {
      position: absolute;
      top: 20px;
      right: 0;
      background: #fff;
      border: 1px solid #ddd;
      padding: 5px;
      z-index: 999;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .alert-box {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: white;
      padding: 15px 25px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: space-between;
      min-width: 300px;
      font-size: 16px;
      animation: fadeInSlideDown 0.5s ease;
    }

    .close-btn {
      background: transparent;
      border: none;
      color: white;
      font-size: 20px;
      cursor: pointer;
      margin-left: 15px;
    }

    @keyframes fadeInSlideDown {
      from {
        opacity: 0;
        transform: translate(-50%, -20px);
      }

      to {
        opacity: 1;
        transform: translate(-50%, 0);
      }
    }

    .file-preview {
      color: #666;
      font-size: 0.9em;
      display: inline-block;
      max-width: 100%;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .file-preview i {
      margin-right: 5px;
      color: #6c757d;
    }

    a {
      text-decoration: none;
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">

    <?php if (!empty($error)): ?>
      <div id="flashToastError"
        class="toast align-items-center text-bg-danger border-0"
        role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body small">
            <?= htmlspecialchars($error) ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto"
            data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div id="flashToastSuccess"
        class="toast align-items-center text-bg-success border-0"
        role="alert" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body small">
            <?= htmlspecialchars($success) ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto"
            data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>

  </div>
  <div id="welcomeNotification" class="alert-box">
    <span>👋 Welcome! Glad Your Here!</span>
    <button onclick="dismissWelcome()" class="close-btn">&times;</button>
  </div>

  <!--begin::App Wrapper-->
  <div class="app-wrapper">

    <!--begin::Header-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
    <!--end::Sidebar-->

    <!--begin::App Main-->
    <main class="main">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
          <div class="col-12">

            <h3 class="mb-0 contact_section_header"> <i class="fas fa-comments title-icon"></i></i> In App Messages</h3>
          </div>
        </div>

        <!-- Second Row -->
        <div class="row">
          <div class="col-md-6">
            <p class="text-muted">Send messages, receive messages and create announcements</p>
          </div>
        </div>

        <!-- message stats -->
        <div class="row">
          <div class="col-md-3 stat-item">
            <div class="number">7</div>
            <div class="label">Total Conversations</div>
          </div>
          <div class="col-md-3 stat-item">
            <div class="number">8</div>
            <div class="label">Unread Messages</div>
          </div>
          <div class="col-md-3 stat-item">
            <div class="number">9</div>
            <div class="label">Tenant Messages</div>
          </div>
          <div class="col-md-3 stat-item">
            <div class="number">10</div>
            <div class="label">Service Provider</div>
          </div>
        </div>
        <!-- First Row: Search and Buttons -->
        <div class="row mb-2">
          <div class="col-md-12">
            <div class="card border-0 mb-4">
              <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-filter"></i> Filter Messages</h5>
                <form method="GET">
                  <div class="row g-3 mb-3" style="flex-wrap: nowrap; overflow-x: auto;">
                    <div class="col-md-3" style="min-width: 200px;">
                      <label class="form-label text-muted small">Search</label>
                      <input type="text" name="search" class="form-control" placeholder="Search by name...">
                    </div>
                    <div class="col-md-3" style="min-width: 200px;">
                      <label class="form-label text-muted small">Recipient Type</label>
                      <select name="recipient_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="tenant">Tenants</option>
                        <option value="service_provider">Service Providers</option>
                        <option value="admin">System Admin</option>
                      </select>
                    </div>
                    <div class="col-md-3" style="min-width: 200px;">
                      <label class="form-label text-muted small">Status</label>
                      <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                      </select>
                    </div>
                    <div class="col-md-3" style="min-width: 200px;">
                      <label class="form-label text-muted small">Date From</label>
                      <input type="date" name="date_from" class="form-control">
                    </div>
                  </div>
                  <div class="d-flex gap-2 justify-content-end">
                    <button type="reset" class="btn btn-secondary">
                      <i class="fas fa-redo"></i> Reset
                    </button>
                    <button type="submit" class="actionBtn">
                      <i class="fas fa-search"></i> Apply Filters
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-sm-12 col-md-12">
            <div class="card border-0">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center border-bottom">
                  <h5 class="card-title mb-0">
                    <i class="fas fa-inbox text-warning"></i> Recent Conversations
                  </h5>

                  <button class="actionBtn mb-2" data-bs-toggle="modal" data-bs-target="#newChatModal">
                    <i class="fas fa-plus"></i> New Chat
                  </button>
                </div>

                <?php if (empty($conversations)): ?>
                  <!-- Empty state -->
                  <div class="text-center py-5">
                    <div class="mb-3" style="font-size:42px; opacity:.85;">
                      <i class="fas fa-comments"></i>
                    </div>
                    <h6 class="mb-2">No conversations yet</h6>
                    <p class="text-muted mb-4" style="max-width:520px; margin:0 auto;">
                      Start a chat to connect with tenants, landlords, or service providers. Your recent conversations will appear here.
                    </p>

                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal">
                      <i class="fas fa-plus"></i> Start Chat
                    </button>
                  </div>
                <?php else: ?>

                  <div class="table-responsive">
                    <table class="table table-hover table-summary-messages" style="border-radius: 20px; flex-grow: 1;">
                      <thead>
                        <tr>
                          <th>DATE</th>
                          <th>TITLE</th>
                          <th>CREATED BY</th>
                          <th>SENT TO</th>
                          <th>ACTION</th>
                        </tr>
                      </thead>

                      <tbody id="conversationTableBody">
  <?php if (empty($conversations)): ?>
    <tr>
      <td colspan="5">
        <div class="text-center py-5">
          <div class="mb-3" style="font-size:42px; opacity:.85;">
            <i class="fas fa-comments"></i>
          </div>
          <h6 class="mb-2">No conversations yet</h6>
          <p class="text-muted mb-4" style="max-width:520px; margin:0 auto;">
            Start a chat to connect with tenants, landlords, or service providers. Your recent conversations will appear here.
          </p>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal">
            <i class="fas fa-plus"></i> Start Chat
          </button>
        </div>
      </td>
    </tr>
  <?php else: ?>

    <?php $meId = (int)($_SESSION['user']['id'] ?? 0); ?>

    <?php foreach ($conversations as $conversation): ?>
      <?php
        $cid       = (int)($conversation['conversation_id'] ?? 0);
        $title     = (string)($conversation['title'] ?? '');
        $createdAt = (string)($conversation['created_at'] ?? '');

        $creatorId    = (int)($conversation['created_by'] ?? 0);
        $creatorName  = (string)($conversation['creator']['name'] ?? '');
        $creatorEmail = (string)($conversation['creator']['email'] ?? '');

        $createdByLabel = ($creatorId === $meId) ? 'Me' : ($creatorName ?: 'Unknown');

        $sentTo = trim((string)($conversation['sent_to_display'] ?? ''));
        if ($sentTo === '') {
          // If it's a conversation with only you (or data issue), show a friendly fallback
          $sentTo = 'No other participants';
        }

        $datePart = $createdAt ? substr($createdAt, 0, 10) : '';
        $timePart = $createdAt ? substr($createdAt, 11, 5) : '';
      ?>

      <tr class="table-row">
        <td class="timestamp">
          <div class="date"><?= htmlspecialchars($datePart) ?></div>
          <div class="time"><?= htmlspecialchars($timePart) ?></div>
        </td>

        <td class="title">
          <?= htmlspecialchars($title !== '' ? $title : 'Untitled conversation') ?>
          <div class="text-muted" style="font-size:12px;">
            Thread #<?= $cid ?>
          </div>
        </td>

        <td>
          <div class="sender"><?= htmlspecialchars($createdByLabel) ?></div>
          <?php if ($creatorId !== $meId && $creatorEmail !== ''): ?>
            <div class="sender-email"><?= htmlspecialchars($creatorEmail) ?></div>
          <?php endif; ?>
        </td>

        <td>
          <div class="recipient"><?= htmlspecialchars($sentTo) ?></div>
        </td>

        <td>
          <button class="btn btn-primary view" data-conversation-id="<?= $cid ?>">
            <i class="bi bi-eye"></i> View
          </button>
          <button class="btn btn-danger delete" data-thread-id="<?= $cid ?>">
            <i class="bi bi-trash3"></i> Delete
          </button>
        </td>
      </tr>
    <?php endforeach; ?>

  <?php endif; ?>
</tbody>


                    </table>
                  </div>

                <?php endif; ?>
              </div>
            </div>

          </div>
        </div>
      </div>
    </main>
    <!--end::App Main-->

    <!--begin::Footer-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!--end::Footer-->
  </div>


  </div>

  <!-- Modals -->
  <!-- New Chat Modal -->
  <!-- New Chat Modal -->
  <div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Start New Conversation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- IMPORTANT: method + enctype + action -->
          <form id="newChatForm" method="POST" action="" enctype="multipart/form-data">
            <!-- the backend checks for this -->
            <input type="hidden" name="start_new_chat" value="1">

            <!-- Recipient Type -->
            <div class="row g-2 align-items-end">
              <div class="col-12 col-md-6">
                <label class="form-label mb-1">Recipient Type</label>
                <select class="form-select" id="recipientType" name="recipient_type">
                  <option value="">Choose type...</option>
                  <option value="tenant">Tenant</option>
                  <option value="admin">System Admin</option>
                </select>
              </div>

              <!-- Keep it (even if admin auto-selects); hidden most of the time -->
              <div class="col-12 col-md-6" id="recipientSelectDiv" style="display:none;">
                <label class="form-label mb-1">Select Recipient</label>
                <select class="form-select" id="recipientSelect" name="admin_id"></select>
              </div>
            </div>

            <!-- Tenant flow -->
            <div class="mt-2" id="tenantFlow" style="display:none;">
              <div class="row g-2">
                <div class="col-12 col-md-6">
                  <label class="form-label mb-1">Building</label>
                  <select class="form-select" id="buildingSelect" name="building_id">
                    <option value="">Choose building...</option>
                    <?php foreach ($buildings as $building): ?>
                      <option value="<?= (int)$building['id'] ?>">
                        <?= htmlspecialchars($building['building_name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-12 col-md-6" id="unitDiv" style="display:none;">
                  <label class="form-label mb-1">Unit</label>
                  <select class="form-select" id="unitSelect" name="unit_id">
                    <option value="">Choose unit...</option>
                  </select>
                </div>

                <div class="col-12" id="tenantNameDiv" style="display:none;">
                  <label class="form-label mb-1">Tenant</label>
                  <input class="form-control" id="tenantNameInput" type="text" readonly />
                  <!-- this is what PHP needs -->
                  <input type="hidden" id="tenantIdInput" name="tenant_id" />
                </div>
              </div>
            </div>

            <!-- Attach Image -->
            <div class="mt-3">
              <label class="form-label mb-1">Attach Image</label>

              <div id="uploadBox" class="border rounded p-3 d-flex align-items-center justify-content-between" style="cursor:pointer;">
                <div class="d-flex align-items-center gap-2">
                  <i class="fas fa-image fs-4"></i>
                  <div>
                    <div class="fw-semibold">Choose an image</div>
                    <small class="text-muted">PNG, JPG, JPEG (max ~5MB)</small>
                  </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm">Browse</button>
              </div>

              <!-- IMPORTANT: must have name="attachment" for PHP $_FILES['attachment'] -->
              <input
                type="file"
                id="attachmentInput"
                name="attachment"
                class="d-none"
                accept="image/png,image/jpeg,image/jpg" />

              <div id="previewWrap" class="mt-2" style="display:none;">
                <div class="d-flex align-items-start gap-2">
                  <img id="imgPreview" alt="preview" class="border rounded"
                    style="width:160px; height:160px; object-fit:cover;" />
                  <div class="flex-grow-1">
                    <div class="fw-semibold" id="fileNameText"></div>
                    <small class="text-muted" id="fileMetaText"></small>

                    <div class="mt-2 d-flex gap-2">
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAttachment()">Remove</button>
                      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="triggerFilePicker()">Change</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Message -->
            <div class="mt-3">
              <label class="form-label mb-1">Title</label>
              <!-- Optional: title (if you want it in DB). Hidden for now, but supported -->
              <input type="text" class="form-control" name="title" id="chatTitle" value="" placeholder="Rental arreas...">
            </div>

            <!-- Message -->
            <div class="mt-3">
              <label class="form-label mb-1">Message</label>
              <!-- IMPORTANT: name="message" -->
              <textarea class="form-control" name="message" rows="4" placeholder="Type your message..."></textarea>
            </div>

            <!-- Keep these if your JS uses them, but backend uses above fields -->
            <input type="hidden" id="targetType" />
            <input type="hidden" id="targetId" />
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

          <!-- IMPORTANT: type="submit" and form="newChatForm" -->
          <button type="submit" form="newChatForm" class="actionBtn">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="view.js"></script>
  <script src="../../../landlord/assets/main.js"></script>
  <script type="module" src="js/main.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>

  <script>
    function dismissWelcome() {
      const box = document.getElementById('welcomeNotification');
      box.style.display = 'none';
    }
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        dismissWelcome();
      }, 5000); // 5000ms = 5 seconds
    });
  </script>


  <script>
    let currentPreviewUrl = null;

    function triggerFilePicker() {
      const input = document.getElementById("attachmentInput");
      if (input) input.click();
    }

    function revokePreviewUrl() {
      if (currentPreviewUrl) {
        URL.revokeObjectURL(currentPreviewUrl);
        currentPreviewUrl = null;
      }
    }

    function clearAttachment() {
      const input = document.getElementById("attachmentInput");
      if (input) input.value = "";

      revokePreviewUrl();

      const img = document.getElementById("imgPreview");
      if (img) {
        img.removeAttribute("src");
        img.alt = "preview";
      }

      const fileNameText = document.getElementById("fileNameText");
      const fileMetaText = document.getElementById("fileMetaText");
      const previewWrap = document.getElementById("previewWrap");

      if (fileNameText) fileNameText.textContent = "";
      if (fileMetaText) fileMetaText.textContent = "";
      if (previewWrap) previewWrap.style.display = "none";
    }

    function formatBytes(bytes) {
      if (!bytes && bytes !== 0) return "";
      const sizes = ["B", "KB", "MB", "GB"];
      const i = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), sizes.length - 1);
      const val = bytes / Math.pow(1024, i);
      return `${val.toFixed(val >= 10 || i === 0 ? 0 : 1)} ${sizes[i]}`;
    }

    document.addEventListener("DOMContentLoaded", () => {
      const uploadBox = document.getElementById("uploadBox");
      if (uploadBox) uploadBox.addEventListener("click", triggerFilePicker);

      const fileInput = document.getElementById("attachmentInput");
      if (fileInput) {
        fileInput.addEventListener("change", (e) => {
          const file = e.target.files && e.target.files[0];

          if (!file) {
            clearAttachment();
            return;
          }

          if (!file.type || !file.type.startsWith("image/")) {
            clearAttachment();
            alert("Please select an image file (PNG/JPG).");
            return;
          }

          // Clear old preview URL then create a new one
          revokePreviewUrl();
          currentPreviewUrl = URL.createObjectURL(file);

          const img = document.getElementById("imgPreview");
          if (img) {
            img.src = currentPreviewUrl;
            img.alt = file.name || "Selected image";
            img.loading = "lazy";
            img.style.display = "block";
          }

          const fileNameText = document.getElementById("fileNameText");
          const fileMetaText = document.getElementById("fileMetaText");
          const previewWrap = document.getElementById("previewWrap");

          if (fileNameText) fileNameText.textContent = file.name;
          if (fileMetaText) fileMetaText.textContent = `${file.type || "image"} • ${formatBytes(file.size)}`;

          if (previewWrap) previewWrap.style.display = "block";
        });
      }

      // Reset everything on modal close
      const modal = document.getElementById("newChatModal");
      if (modal) {
        modal.addEventListener("hidden.bs.modal", () => {
          const recipientType = document.getElementById("recipientType");
          const recipientSelectDiv = document.getElementById("recipientSelectDiv");
          const tenantFlow = document.getElementById("tenantFlow");

          if (recipientType) recipientType.value = "";
          if (recipientSelectDiv) recipientSelectDiv.style.display = "none";
          if (tenantFlow) tenantFlow.style.display = "none";

          // Only call if it exists
          if (typeof resetTenantFlowUI === "function") resetTenantFlowUI();

          clearAttachment();
        });
      }
    });
  </script>



  <!-- Toast message -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const successEl = document.getElementById("flashToastSuccess");
      const errorEl = document.getElementById("flashToastError");

      if (successEl && window.bootstrap) {
        new bootstrap.Toast(successEl, {
          delay: 8000,
          autohide: true
        }).show();
      }

      if (errorEl && window.bootstrap) {
        new bootstrap.Toast(errorEl, {
          delay: 10000,
          autohide: true
        }).show();
      }
    });
  </script>
</body>
<!--end::Body-->

</html>