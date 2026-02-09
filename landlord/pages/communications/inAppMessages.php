<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

include '../db/connect.php'; // adjust path

try {
  $sql = "
        SELECT id AS id, entity_name AS building_name, 'building_units' AS source_table
        FROM building_units
        GROUP BY entity_name

        UNION

        SELECT id AS id, entity_name AS building_name, 'building_units' AS source_table
        FROM building_units
        GROUP BY entity_name

        UNION

        SELECT id AS id, entity_name AS building_name, 'building_units' AS source_table
        FROM building_units
        GROUP BY entity_name
    ";

  $stmt = $pdo->query($sql);
  $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
<?php
include '../db/connect.php';

try {
  $stmt = $pdo->query("SELECT id, building_name, building_type FROM buildings ORDER BY building_name ASC");
  $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
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
    // $uploadDir = "C:/xampp/htdocs/originalTwo/AdminLTE/dist/pages/communications/uploads/";

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
    $stmt = $pdo->prepare("INSERT INTO communication (title, message, files, unit_id, tenant, building_name, created_at, updated_at) VALUES (?, ?,  ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $message, $files_json, $unit_id, $tenant, $building_name, $now, $now]);

    $thread_id = $pdo->lastInsertId();
    $message_id = $pdo->lastInsertId(); // Get the message ID for attachments

    if (!empty($uploaded_files)) {
      foreach ($uploaded_files as $file_path) {
        $stmt = $pdo->prepare("INSERT INTO messages (thread_id, sender, content, timestamp, file_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$thread_id, 'landlord', $message, $now, $file_path]);
      }
    } else {
      $stmt = $pdo->prepare("INSERT INTO messages (thread_id, sender, content, timestamp) VALUES (?, ?, ?, ?)");
      $stmt->execute([$thread_id, 'landlord', $message, $now]);
    }


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
$stmt = $pdo->prepare("SELECT id, building_name FROM buildings");
$stmt->execute();
$buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === FETCH UNITS IF BUILDING SELECTED ===
$id = $_POST['id'] ?? null;
$units = [];

if ($id) {
  $stmt = $pdo->prepare("SELECT unit_id, unit_number FROM units WHERE id = ?");
  $stmt->execute([$id]);
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
        (SELECT file_path FROM messages WHERE thread_id = c.thread_id ORDER BY timestamp DESC LIMIT 1) AS last_file,
        (SELECT timestamp FROM messages WHERE thread_id = c.thread_id ORDER BY timestamp DESC LIMIT 1) AS last_time,
        (SELECT COUNT(*) FROM messages WHERE thread_id = c.thread_id AND is_read = 0) AS unread_count
    FROM communication c
    ORDER BY last_time DESC
");
$stmt->execute();
$communications = $stmt->fetchAll(PDO::FETCH_ASSOC);
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


  <!--end::Fonts-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
    integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../../../landlord/assets/main.css" />
  <!-- <link rel="stylesheet" href="../../../landlord/css/adminlte.css" /> -->
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link rel="stylesheet" href="texts.css">
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
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
            <div class="card shadow-sm mb-4">
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
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-search"></i> Apply Filters
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-12 message-container">
          <!-- Start Row messages-summmary -->
          <div class="row" style="display: none;" id="go-back">
            <div class="col-md-12 d-flex">
              <button class="btn go-back mb-1" onclick="myBack()"> <i class="fa-solid fa-arrow-left"></i> Go Back</button>
            </div>
          </div>
          <!-- end row -->
          <!-- start row -->
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="card-title mb-0"><i class="fas fa-inbox"></i> Recent Messages</h5>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newChatModal">
                      <i class="fas fa-plus"></i> New Chat
                    </button>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover table-summary-messages" style="border-radius: 20px; flex-grow: 1;">
                      <thead>
                        <tr>
                          <th>DATE</th>
                          <th>TITLE</th>
                          <th>SENT BY</th>
                          <th>SENT TO</th>
                          <th>ACTION</th>
                        </tr>
                      </thead>
                      <tbody id="conversationTableBody">
                        <?php
                        // if (!empty($communications)): 
                        ?>
                        <?php
                        // foreach ($communications as $comm):
                        //   $datetime = new DateTime($comm['created_at'] ?? date('Y-m-d H:i:s'));
                        //   $date = $datetime->format('d-m-Y');
                        //   $time = $datetime->format('h:iA');
                        //   $sender = htmlspecialchars($comm['tenant'] ?: 'Tenant');
                        //   $email = ''; // Add email logic if needed
                        //   $recipient = htmlspecialchars($comm['recipient'] ?? 'Sender Name'); // Adjust key as needed
                        //   $title = htmlspecialchars($comm['title']);
                        //   $threadId = $comm['thread_id'];
                        ?>
                        <tr class="table-row" data-date="">
                          <td class="timestamp">
                            <div class="date"></div>
                            <div class="time"></div>
                          </td>
                          <td class="title"></td>
                          <td>
                            <div class="recipient"></div>
                          </td>
                          <td>
                            <div class="sender"></div>
                            <div class="sender-email"></div>
                          </td>
                          <td>
                            <button class="btn btn-primary view">
                              <i class="bi bi-eye"></i> View
                            </button>
                            <button class="btn btn-danger delete" data-thread-id="">
                              <i class="bi bi-trash3"></i> Delete
                            </button>
                          </td>
                        </tr>
                        <?php
                        // endforeach; 
                        ?>
                        <tr id="noResultsRow" style="display: none;">
                          <td colspan="5" class="text-center text-danger">No matching results found.</td>
                        </tr>

                        <?php
                        // else: 
                        ?>
                        <tr>
                          <td colspan="5" class="text-center">No message available</td>
                        </tr>
                        <?php
                        // endif;
                        ?>

                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Row messages-summmary -->

          <div class="row h-100 align-items-stretch" id="individual-message-summmary" style="border:1px solid #E2E2E2; padding: 0 !important; display: none; max-height: 95%;">
            <div id="message-profiles" class="col-md-4  message-profiles">

              <div class="topic-profiles-header-section d-flex">
                <div class="content d-flex">
                  <div class="individual-details-container">
                    <div class="content d-flex">
                      <div class="profile-initials" id="profile-initials">JM</div>

                      <div class="individual-residence d-flex">
                        <div class="individual-name body">Emmanuel,</div>
                        <div class="initial-topic-separator">|</div>
                        <div class="residence mt-2">

                        </div>
                      </div>

                    </div>
                  </div>

                </div>
              </div>

              <div class="h-80 other-topics-section">
                <?php
                // foreach ($communications as $comm): 
                ?>
                <div class="individual-topic-profiles d-flex">

                  <div class="individual-topic-profile-container">
                    <div class="individual-topic">
                    </div>
                    <div class="individual-message mt-2">
                      <?php
                      // if (!empty($comm['last_file'])): 
                      ?>
                      <!-- Show file preview if last message is a file -->
                      <span class="file-preview">
                        <i class="fas fa-paperclip"></i>
                        <?php
                        // $filename = basename($comm['last_file']);
                        // echo htmlspecialchars(mb_strimwidth($filename, 0, 30, '...'));
                        ?>
                      </span>
                      <?php
                      // elseif (!empty($comm['last_message'])): 
                      ?>
                      <!-- Show message preview if last message is text -->

                      <?php
                      // else: 
                      ?>
                      <!-- Fallback if neither exists -->
                      <span class="text-muted">No messages yet</span>
                      <?php
                      // endif; 
                      ?>
                    </div>
                  </div>

                  <div class="d-flex justify-content-end time-count">
                    <div class="time">

                    </div>
                    <div class="message-count mt-2">

                    </div>
                  </div>
                </div>
                <?php
                // endforeach; 
                ?>
              </div>



            </div>

            <div id="messageBody" class="col-md-8 message-body" style="padding: 0 !important; height:100%;">
              <div class="individual-message-body-header">
                <div class="individual-details-container">
                  <div class="content">
                    <div class="individual-residence d-flex" style="align-items: center;">
                      <div class="profile-initials initials-topic" id="profile-initials-initials-topic"><b>JM</b></div>
                      <div id="initial-topic-separator" class="initial-topic-separator">|</div>
                      <div class="individual-topic body">Rental Arrears</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="individual-message-body" style="height: 100%;">
                <div class="messages" id="messages">
                  <div class="message incoming">
                    <div class="message outgoing">
                    </div>
                  </div>
                </div>

                <div class="input-area">
                  <!-- Attachment input -->
                  <input
                    type="file"
                    name="file[]"
                    id="fileInput"
                    class="form-control"
                    style="display: none;"
                    onchange="showFilePreview()"
                    multiple
                    accept=".jpg,.jpeg,.png,.gif,.webp,.bmp,.xls,.xlsx,.pdf,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/*">

                  <button class="btn attach-button" onclick="document.getElementById('fileInput').click();">
                    <i class="fa fa-paperclip"></i>
                  </button>

                  <!-- File preview container -->
                  <div id="filePreviewContainer" style="display: none; margin-right: 10px; max-width: 200px; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; align-items: center; background: #f5f5f5; padding: 5px; border-radius: 4px;">
                      <!-- Image thumbnail (shown only for image files) -->
                      <img id="fileThumbnail" src="" style="max-height: 40px; max-width: 40px; margin-right: 8px; display: none;">
                      <!-- File info -->
                      <div style="flex-grow: 1;">
                        <div id="fileName" style="font-size: 12px; color: #333;"></div>
                        <div style="font-size: 10px; color: #666;">Click to remove</div>
                      </div>
                      <button onclick="clearFileSelection()" style="background: none; border: none; color: #999; cursor: pointer; margin-left: 5px;">×</button>
                    </div>
                  </div>

                  <div class="input-box" id="inputBox" contenteditable="true" placeholder="Type your message..."></div>

                  <!-- MESSAGE SEND BUTTON -->
                  <div class="message-input-wrapper">
                    <button name="incoming_message" class="btn message-send-button" onclick="sendMessage()">
                      <i class="fa fa-paper-plane"></i>
                    </button>
                  </div>
                </div>



              </div>
            </div>
          </div>
        </div>
        <!--end::Row-->
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
          <form>
            <!-- Recipient Type + (Admin recipient auto, Tenant flow below) -->
            <div class="row g-2 align-items-end">
              <div class="col-12 col-md-6">
                <label class="form-label mb-1">Recipient Type</label>
                <select class="form-select" id="recipientType" onchange="updateRecipientOptions()">
                  <option value="">Choose type...</option>
                  <option value="tenant">Tenant</option>
                  <option value="admin">System Admin</option>
                </select>
              </div>

              <!-- Shows only for non-tenant types that need a dropdown.
         For admin, we won't show this because admin is auto-selected. -->
              <div class="col-12 col-md-6" id="recipientSelectDiv" style="display:none;">
                <label class="form-label mb-1">Select Recipient</label>
                <select class="form-select" id="recipientSelect"></select>
              </div>
            </div>

            <!-- Tenant cascading flow (packed horizontally) -->
            <div class="mt-2" id="tenantFlow" style="display:none;">
              <div class="row g-2">
                <div class="col-12 col-md-6">
                  <label class="form-label mb-1">Building</label>
                  <select class="form-select" id="buildingSelect" onchange="onBuildingChange()">
                    <option value="">Choose building...</option>
                  </select>
                </div>

                <div class="col-12 col-md-6" id="unitDiv" style="display:none;">
                  <label class="form-label mb-1">Unit</label>
                  <select class="form-select" id="unitSelect" onchange="onUnitChange()">
                    <option value="">Choose unit...</option>
                  </select>
                </div>

                <div class="col-12" id="tenantNameDiv" style="display:none;">
                  <label class="form-label mb-1">Tenant</label>
                  <input class="form-control" id="tenantNameInput" type="text" readonly />
                  <input type="hidden" id="tenantIdInput" />
                </div>
              </div>
            </div>

            <!-- File/Image attachment (VISUAL preview) -->
            <div class="mt-3">
              <label class="form-label mb-1">Attach Image</label>

              <!-- Clickable upload box -->
              <div id="uploadBox" class="border rounded p-3 d-flex align-items-center justify-content-between"
                style="cursor:pointer;">
                <div class="d-flex align-items-center gap-2">
                  <i class="fas fa-image fs-4"></i>
                  <div>
                    <div class="fw-semibold">Choose an image</div>
                    <small class="text-muted">PNG, JPG, JPEG (max ~5MB)</small>
                  </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm">Browse</button>
              </div>

              <!-- Hidden file input -->
              <input
                type="file"
                id="attachmentInput"
                class="d-none"
                accept="image/png,image/jpeg,image/jpg" />

              <!-- Preview area -->
              <div id="previewWrap" class="mt-2" style="display:none;">
                <div class="d-flex align-items-start gap-2">
                  <img
                    id="imgPreview"
                    alt="preview"
                    class="border rounded"
                    style="width:160px; height:160px; object-fit:cover;" />

                  <div class="flex-grow-1">
                    <div class="fw-semibold" id="fileNameText"></div>
                    <small class="text-muted" id="fileMetaText"></small>

                    <div class="mt-2 d-flex gap-2">
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAttachment()">
                        Remove
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="triggerFilePicker()">
                        Change
                      </button>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <!-- Message -->
            <div class="mt-3">
              <label class="form-label mb-1">Message</label>
              <textarea class="form-control" rows="4" placeholder="Type your message..."></textarea>
            </div>

            <!-- Optional hidden target values (useful when sending) -->
            <input type="hidden" id="targetType" />
            <input type="hidden" id="targetId" />
          </form>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="view.js"></script>
  <script src="../../../landlord/assets/main.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>

  <!-- <script>
    function dismissWelcome() {
      const box = document.getElementById('welcomeNotification');
      box.style.display = 'none';
    }
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        dismissWelcome();
      }, 5000); // 5000ms = 5 seconds
    });
  </script> -->

  <!-- Bootstrap Bundle with Popper -->

  <!--end::Script-->

  <script>
    // Dummy tenant directory data
    const tenantDirectory = [{
        id: "b1",
        name: "Hindocha Tower",
        units: [{
            id: "u204",
            number: "204",
            tenant: {
              id: "t1",
              name: "Sarah Johnson"
            }
          },
          {
            id: "u205",
            number: "205",
            tenant: {
              id: "t2",
              name: "Brian Otieno"
            }
          },
        ],
      },
      {
        id: "b2",
        name: "Vista Apartments",
        units: [{
            id: "u305",
            number: "305",
            tenant: {
              id: "t3",
              name: "Michael Chen"
            }
          },
          {
            id: "u306",
            number: "306",
            tenant: {
              id: "t4",
              name: "Amina Ali"
            }
          },
        ],
      },
      {
        id: "b3",
        name: "Green Valley Homes",
        units: [{
          id: "u102",
          number: "102",
          tenant: {
            id: "t5",
            name: "Emma Wilson"
          }
        }, ],
      },
    ];

    const el = (id) => document.getElementById(id);

    // store current preview object URL so we can revoke it
    let currentPreviewUrl = null;

    function safeHide(id) {
      const node = el(id);
      if (node) node.style.display = "none";
    }

    function safeShow(id) {
      const node = el(id);
      if (node) node.style.display = "block";
    }

    function resetTenantFlowUI() {
      if (el("buildingSelect")) {
        el("buildingSelect").innerHTML = '<option value="">Choose building...</option>';
        el("buildingSelect").value = "";
      }

      safeHide("unitDiv");
      if (el("unitSelect")) el("unitSelect").innerHTML = '<option value="">Choose unit...</option>';

      safeHide("tenantNameDiv");
      if (el("tenantNameInput")) el("tenantNameInput").value = "";
      if (el("tenantIdInput")) el("tenantIdInput").value = "";

      if (el("targetType")) el("targetType").value = "";
      if (el("targetId")) el("targetId").value = "";
    }

    function populateBuildings() {
      const buildingSelect = el("buildingSelect");
      if (!buildingSelect) return;

      let html = '<option value="">Choose building...</option>';
      tenantDirectory.forEach((b) => {
        html += `<option value="${b.id}">${b.name}</option>`;
      });
      buildingSelect.innerHTML = html;
    }

    // MAIN: called on recipientType change
    function updateRecipientOptions() {
      const type = el("recipientType")?.value;

      const recipientSelectDiv = el("recipientSelectDiv");
      const recipientSelect = el("recipientSelect");
      const tenantFlow = el("tenantFlow");

      if (recipientSelectDiv) recipientSelectDiv.style.display = "none";
      if (tenantFlow) tenantFlow.style.display = "none";
      if (recipientSelect) recipientSelect.innerHTML = "";

      resetTenantFlowUI();

      if (!type) return;

      if (type === "tenant") {
        if (tenantFlow) tenantFlow.style.display = "block";
        populateBuildings();
        if (el("targetType")) el("targetType").value = "tenant";
        return;
      }

      if (type === "admin") {
        // System Admin is auto-selected (no dropdown)
        if (el("targetType")) el("targetType").value = "admin";
        if (el("targetId")) el("targetId").value = "admin1"; // dummy id
        return;
      }
    }

    function onBuildingChange() {
      const buildingId = el("buildingSelect")?.value;

      safeHide("unitDiv");
      if (el("unitSelect")) el("unitSelect").innerHTML = '<option value="">Choose unit...</option>';

      safeHide("tenantNameDiv");
      if (el("tenantNameInput")) el("tenantNameInput").value = "";
      if (el("tenantIdInput")) el("tenantIdInput").value = "";
      if (el("targetId")) el("targetId").value = "";

      if (!buildingId) return;

      const building = tenantDirectory.find((b) => b.id === buildingId);
      if (!building) return;

      let unitOptions = '<option value="">Choose unit...</option>';
      building.units.forEach((u) => {
        unitOptions += `<option value="${u.id}">Unit ${u.number}</option>`;
      });

      if (el("unitSelect")) el("unitSelect").innerHTML = unitOptions;
      safeShow("unitDiv");
    }

    function onUnitChange() {
      const buildingId = el("buildingSelect")?.value;
      const unitId = el("unitSelect")?.value;

      safeHide("tenantNameDiv");
      if (el("tenantNameInput")) el("tenantNameInput").value = "";
      if (el("tenantIdInput")) el("tenantIdInput").value = "";
      if (el("targetId")) el("targetId").value = "";

      if (!buildingId || !unitId) return;

      const building = tenantDirectory.find((b) => b.id === buildingId);
      const unit = building?.units.find((u) => u.id === unitId);
      if (!unit?.tenant) return;

      if (el("tenantNameInput")) el("tenantNameInput").value = unit.tenant.name;
      if (el("tenantIdInput")) el("tenantIdInput").value = unit.tenant.id;

      if (el("targetType")) el("targetType").value = "tenant";
      if (el("targetId")) el("targetId").value = unit.tenant.id;

      safeShow("tenantNameDiv");
    }

    // ------- Attachment preview logic (fixed + reliable) -------
    function triggerFilePicker() {
      const input = el("attachmentInput");
      if (input) input.click();
    }

    function revokePreviewUrl() {
      if (currentPreviewUrl) {
        URL.revokeObjectURL(currentPreviewUrl);
        currentPreviewUrl = null;
      }
    }

    function clearAttachment() {
      const input = el("attachmentInput");
      if (input) input.value = "";

      revokePreviewUrl();

      const img = el("imgPreview");
      if (img) {
        img.removeAttribute("src");
        img.alt = "preview";
      }

      if (el("fileNameText")) el("fileNameText").textContent = "";
      if (el("fileMetaText")) el("fileMetaText").textContent = "";
      safeHide("previewWrap");
    }

    function formatBytes(bytes) {
      if (!bytes && bytes !== 0) return "";
      const sizes = ["B", "KB", "MB", "GB"];
      const i = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), sizes.length - 1);
      const val = bytes / Math.pow(1024, i);
      return `${val.toFixed(val >= 10 || i === 0 ? 0 : 1)} ${sizes[i]}`;
    }

    document.addEventListener("DOMContentLoaded", () => {
      // Make upload box clickable (only if it exists)
      const uploadBox = el("uploadBox");
      if (uploadBox) uploadBox.addEventListener("click", triggerFilePicker);

      const fileInput = el("attachmentInput");
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

          const img = el("imgPreview");
          if (img) {
            img.src = currentPreviewUrl;
            img.alt = file.name || "Selected image";
            img.loading = "lazy";

            // Force the browser to render it (helps in some modal/layout cases)
            img.style.display = "block";
          }

          if (el("fileNameText")) el("fileNameText").textContent = file.name;
          if (el("fileMetaText")) el("fileMetaText").textContent =
            `${file.type || "image"} • ${formatBytes(file.size)}`;

          safeShow("previewWrap");
        });
      }

      // Reset everything on modal close
      const modal = el("newChatModal");
      if (modal) {
        modal.addEventListener("hidden.bs.modal", () => {
          if (el("recipientType")) el("recipientType").value = "";
          safeHide("recipientSelectDiv");
          safeHide("tenantFlow");
          resetTenantFlowUI();
          clearAttachment();
        });
      }
    });
  </script>



</body>
<!--end::Body-->

</html>