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
        $stmt = $pdo->prepare("INSERT INTO communication (title, message, files, unit_id, tenant, building_name, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
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


    //   if (!empty($uploaded_files)) {
    //     foreach ($uploaded_files as $file_path) {
    //         $stmt = $pdo->prepare("INSERT INTO messages  (message_id, thread_id, file_path) VALUES (?, ?, ?)");
    //         $stmt->execute([$message_id, $thread_id, $file_path]);
    //     }

    // } else {
    //     $stmt = $pdo->prepare("INSERT INTO message_files  (message_id, thread_id) VALUES (?, ?)");
    //     $stmt->execute([$message_id, $thread_id]);
    // }


      // Insert initial message (no file_path here)
    //   if (!empty($uploaded_files)) {
    //     $stmt_file = $pdo->prepare("INSERT INTO messages (thread_id, sender, content, timestamp, file_path) VALUES (?, ?, ?, ?, ?)");
    //     foreach ($uploaded_files as $file_path) {
    //         $stmt_file->execute([$thread_id, 'landlord', $message, $now,$file_path]);
    //     }
    // }

        // $stmt = $pdo->prepare("INSERT INTO messages (thread_id, sender, content, timestamp) VALUES (?, ?, ?, ?)");
        // $stmt->execute([$thread_id, 'landlord', $message, $now]);


        // Store attachments
        // if (!empty($uploaded_files)) {
        //     $stmt_file = $pdo->prepare("INSERT INTO message_files (message_id, thread_id, file_path) VALUES (?, ?, ?)");
        //     foreach ($uploaded_files as $file_path) {
        //         $stmt_file->execute([$message_id, $thread_id, $file_path]);
        //     }
        // }

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



<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE | Dashboard v2</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE | Dashboard v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
     <link rel="stylesheet" href="texts.css">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">






<!-- scripts for data_table -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
  body{
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

#filePreviews{
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
    background: rgba(0,0,0,0.7);
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
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
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
    background: rgba(0,0,0,0.5);
    color: #fff;
    padding: 4px;
    border-radius: 50%;
    text-decoration: none;
    font-size: 12px;
    transition: background 0.2s;
}

.download-icon:hover {
    background: rgba(0,0,0,0.7);
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
    color:#00192D;
}

.file-name {
    font-size: 14px;
    word-break: break-word;
}
.viewed-tick {
    color: #34B7F1; /* WhatsApp blue */
    font-size: 14px;
    margin-left: 15rem;
}

.unviewed-tick {
    color: #ccc; /* grey */
    font-size: 14px;
    margin-left: 25rem;
}
.tick-status {
    text-align: right;
    font-size: 0.9em;
    margin-top: 4px;
    color: grey;
}
.timestamp{
  margin-left: 25rem;
  font-size:12px;
}
.file-previews-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: center;
  align-items: center;
  margin: 0 auto;
  max-width: 100%;
}

.file-preview {
  position: relative;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 8px;
  background: #f9f9f9;
  width: 120px;
  height: 120px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.preview-image {
  max-width: 100%;
  max-height: 80px;
  display: block;
  margin-bottom: 5px;
}

/* .pdf-preview {
  text-align: center;
} */

.pdf-preview, .file-preview > div {
  text-align: center;
  width: 100%;
}

.pdf-icon, .file-icon {
  font-size: 40px;
  display: block;
  margin: 0 auto;
}

.pdf-icon { color: #e74c3c; }
.file-icon { color: #7f8c8d; }

.file-preview span {
  display: block;
  width: 100%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 12px;
  text-align: center;
}

.remove-preview {
  position: absolute;
  top: -8px;
  right: -8px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #e74c3c;
  color: white;
  border: none;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  padding: 0;
}

.remove-preview:hover {
  background: #c0392b;
}
</style>
</head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li>
            <!--end::Navbar Search-->
            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-chat-text"></i>
                <span class="navbar-badge badge text-bg-danger">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="../../../dist/assets/img/user1-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-end fs-7 text-danger"
                          ><i class="bi bi-star-fill"></i
                        ></span>
                      </h3>
                      <p class="fs-7">Call me whenever you can...</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="../../../dist/assets/img/user8-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        John Pierce
                        <span class="float-end fs-7 text-secondary">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">I got your message bro</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="../../../dist/assets/img/user3-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Nora Silvester
                        <span class="float-end fs-7 text-warning">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">The subject goes here</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
            </li>
            <!--end::Messages Dropdown Menu-->
            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-envelope me-2"></i> 4 new messages
                  <span class="float-end text-secondary fs-7">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-people-fill me-2"></i> 8 friend requests
                  <span class="float-end text-secondary fs-7">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                  <span class="float-end text-secondary fs-7">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
              </div>
            </li>
            <!--end::Notifications Dropdown Menu-->
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src="17.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline" style="color: #00192D;" > <b> JENGO PAY  </b>  </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="../../../dist/assets/img/user2-160x160.jpg"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    Alexander Pierce - Web Developer
                    <small>Member since Nov. 2023</small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Body-->
                <li class="user-body">
                  <!--begin::Row-->
                  <div class="row">
                    <div class="col-4 text-center"><a href="#">Followers</a></div>
                    <div class="col-4 text-center"><a href="#">Sales</a></div>
                    <div class="col-4 text-center"><a href="#">Friends</a></div>
                  </div>
                  <!--end::Row-->
                </li>
                <!--end::Menu Body-->
                <!--begin::Menu Footer-->
                <li class="user-footer">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                  <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="../../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">
            <a href="index3.html" class="brand-link">
        <!--<img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
        <span class="brand-text font-weight-light"><b class="p-2"
                style="background-color:#FFC107; border:2px solid #FFC107; border-top-left-radius:5px; font-weight:bold; color:#00192D;">BT</b><b
                class="p-2"
                style=" border-bottom-right-radius:5px; font-weight:bold; border:2px solid #FFC107; color: #FFC107;">JENGOPAY</b></span>
    </a>

            </span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div id="sidebar"></div> <!-- This is where the sidebar is inserted -->

        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-8">

                <h3 class="mb-0 contact_section_header"> <i class="fas fa-comments title-icon"></i></i> In App Messages</h3>
              </div>

              <div class="col-sm-4 d-flex justify-content-end">
                <button   class="btn automated_message">    Automated Messages</button>

              </div>

            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>

        <div class="app-content" style="flex-grow: 1;">
          <!--begin::Container-->
          <div class="container-fluid" style="flex-grow: 1; height:100%">

                <!--begin::Row-->
                <!-- Message Container -->

                <div class="row" style="height: 100%;">
                  <div class="col-md-12 message-container">
                      <!-- <div class="container" style="border:1px solid #E2E2E2" > -->
                      <div class="row filter-section" id="filter-section">
                          <div class="col-12 message-container-header-section">
                              <div class="row">
                                  <div class="col-md-8 col-12">
                                  <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; margin-bottom: 1rem;">
                                      <select id="categoryFilter"  name="building_id" class="categoryFilter form-select">
                                      <option value="">-- Select Building --</option>
                                      <?php foreach ($buildings as $b): ?>
                                        <option value="<?= htmlspecialchars($b['building_id']) ?>">
                                          <?= htmlspecialchars($b['building_name']) ?>
                                        </option>
                                      <?php endforeach; ?>
                                      </select>
                                      <input type="text" class="search-input" placeholder="Search Tenant...">
                                  </div>
                                  </div>
                                  <div class="col-md-4 col-12 RecentChatNewText-btns" style="align-items: center;">
                                      <div class="date d-flex date">
                                          <label class="form-label startDate">Start Date</label>
                                          <input type="date" class="form-control" id="endDate" placeholder="end">
                                      </div>
                                      <div class="date d-flex date">
                                          <label class="form-label endDate">End Date</label>
                                          <input type="date" class="form-control" id="endDate" placeholder="end">
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  <!-- </div> -->
              <!-- </div> -->
                        <!-- Start Row messages-summmary -->
                         <div class="row" style="display: none;" id="go-back" >
                          <div class="col-md-12 d-flex">
                            <button class="btn go-back mb-1" onclick="myBack()" > <i class="fa-solid fa-arrow-left"></i>  Go Back</button>
                          </div>

                         </div>

                         <!-- end row -->

                         <!-- Start Row -->


                         <!-- End Row -->

                         <!-- start row -->
                         <div class="row align-items-stretch all-messages-summary" id="all-messages-summary">
                          <div id="message-summary" class="col-md-12  message-summary">
                              <div class="message-list p-2" style="display: flex; justify-content: space-between;">
                                  <div class="recent-messages-header">Recent Messages</div>
                                  <div>
                                      <button id="new_text" onclick="opennewtextPopup()" class="btn new-text"><i class="bi bi-plus"></i> New Chat</button>
                                  </div>
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
  <tbody>
    <?php if (!empty($communications)): ?>
      <?php foreach ($communications as $comm):
        $datetime = new DateTime($comm['created_at'] ?? date('Y-m-d H:i:s'));
        $date = $datetime->format('d-m-Y');
        $time = $datetime->format('h:iA');
        $sender = htmlspecialchars($comm['tenant'] ?: 'Tenant');
        $email = ''; // Add email logic if needed
        $recipient = htmlspecialchars($comm['recipient'] ?? 'Sender Name'); // Adjust key as needed
        $title = htmlspecialchars($comm['title']);
        $threadId = $comm['thread_id'];
      ?>
        <tr class="table-row">
          <td class="timestamp">
            <div class="date"><?= $date ?></div>
            <div class="time"><?= $time ?></div>
          </td>
          <td class="title"><?= $title ?></td>
          <td><div class="recipient"><?= $recipient ?></div></td>
          <td>
            <div class="sender"><?= $sender ?></div>
            <div class="sender-email"><?= $email ?></div>
          </td>
          <td>
            <button class="btn btn-primary view" onclick="loadConversation(<?= $threadId ?>)">
              <i class="bi bi-eye"></i> View
            </button>
            <button class="btn btn-danger delete" data-thread-id="<?= $threadId ?>">
              <i class="bi bi-trash3"></i> Delete
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="5" class="text-center">No message available</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

                              </div>
                          </div>
                      </div>
                        <!-- End Row messages-summmary -->


                        <!-- start  -->

                        <!-- end -->

                      <div class="row h-100 align-items-stretch" id="individual-message-summmary" style="border:1px solid #E2E2E2; padding: 0 !important; display: none; max-height: 95%;">


                            <div id="message-profiles" class="col-md-4  message-profiles"  >

                              <div class="topic-profiles-header-section d-flex">
                                <div class="content d-flex">
                                  <div  class="individual-details-container">
                                    <div class="content d-flex">
                                      <div class="profile-initials" id="profile-initials">JM</div>

                                      <div class="individual-residence d-flex">
                                        <div class="individual-name body">Emmanuel,</div>
                                        <div  class="initial-topic-separator">|</div>
                                        <div class="residence mt-2"><?= htmlspecialchars($b['building_name'])?></div>
                                      </div>

                                    </div>
                                  </div>

                                </div>
                              </div>

                              <div class="h-80 other-topics-section">
  <?php foreach ($communications as $comm): ?>
    <div class="individual-topic-profiles d-flex"
         data-message-id="<?= $comm['thread_id'] ?>"
         onclick="loadConversation(this.dataset.messageId)">

      <div class="individual-topic-profile-container">
        <div class="individual-topic"><?= htmlspecialchars($comm['title']) ?></div>
        <div class="individual-message mt-2">
          <?= htmlspecialchars(mb_strimwidth($comm['last_message'], 0, 60, '...'))?>
        </div>
      </div>

      <div class="d-flex justify-content-end time-count">
        <div class="time">
          <?php
            $datetime = new DateTime($comm['created_at']);
            echo $datetime->format('d/m/y');
          ?>
        </div>
        <div class="message-count mt-2">
          <?= $comm['unread_count'] > 0 ? $comm['unread_count'] : '' ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>


                            </div>

                            <div id="messageBody" class="col-md-8 message-body" style="padding: 0 !important; height:100%;">
                              <div class="individual-message-body-header">
                                <div class="individual-details-container">
                                  <div class="content">
                                    <div class="individual-residence d-flex" style="align-items: center;" >
                                      <div class="profile-initials initials-topic" id="profile-initials-initials-topic" ><b>JM</b></div>
                                      <div id="initial-topic-separator" class="initial-topic-separator">|</div>
                                      <div class="individual-topic body">Rental Arrears</div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="individual-message-body" style="height: 100%;">
                                 <div class="messages" id="messages" >
                                   <div class="message incoming">
                                  <div class="message outgoing" style="height:20%; width:20%;">

                                  </div>
                                </div>
                                </div>

                                <div class="input-area">
                        <!-- Add this HTML where you want the previews to appear -->
                        <div id="filePreviews" class="file-previews-container mb-3"></div>


                              <!-- Attachment input -->
                              <input type="file" name="file[]"  id="fileInput"  class="form-control" style="display: none; ">
                                <button class="btn attach-button" onclick="document.getElementById('fileInput').click();">
                                  <i class="fa fa-paperclip"></i>
                                </button>

                                <div class="input-box" id="inputBox" contenteditable="true"></div>


                                <!-- File preview container (for selected files) -->
                                <div id="filePreviewContainer" class="preview-container"></div>


                                <!-- <div id="filePreviews" class="preview-container"></div> -->


                                      <!-- MESSAGE SEND BUTTON -->
                                  <div class="message-input-wrapper" >
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
                  <!-- /.col -->
              </div>
              <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2024&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;" >JENGO PAY</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->





<!-- Overlays -->

<!-- Script source File -->
<script src="text.js"></script>


<!-- PopUp Scripts -->




<!-- new text popup -->
                <div class="newtextpopup-overlay" id="newtextPopup">
                    <div  class="card" style="margin-top: 20px;">
                      <div class="card-header new-message-header">
                        New Message
                        <button class="close-btn" onclick="closenewtextPopup()">×</button>
                      </div>
                      <div class="card-body new-message-body">
                        <div class="row">
                        <form action="texts.php" method="POST" id="messageForm" enctype="multipart/form-data">
                          <div class="col-md-12" style="display: flex;">

                            <div id="field-group-first" class="field-group first">
                              <label for="recipient" style="color: black;">Recepient<i class="fas fa-mouse-pointer title-icon" style="transform: rotate(110deg);"></i>                                Building</label>
                              <select name="building_name"  id="recipient" onchange="toggleShrink()" class="recipient" >
                              <option value="">-- Select Building --</option>
                              <?php foreach ($buildings as $b): ?>
                              <option value="<?= htmlspecialchars($b['building_id']) ?>">
                                  <?= htmlspecialchars($b['building_name']) ?>
                              </option>
                              <?php endforeach; ?>
                              </select>
                              </div>

                            <div id="field-group-second" class="field-group second" style="display:block">
                            <label for="recipient-units">Unit</label>
                           <select name="unit_id">
                            <?php if (!empty($units) && is_array($units)): ?>
                              <?php foreach ($units as $unit): ?>
                                <option value="<?= htmlspecialchars($unit['unit_id']) ?>">
                                  <?= htmlspecialchars($unit['unit_number']) ?>
                                </option>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <option disabled>No units found</option>
                            <?php endif; ?>
                          </select>
                          </div>



                            <div id="field-group-third" class="field-group third" style="display:none" >
                              <label for="tenant">Tenant</label>
                              <select name="tenant" id="Tenant" onchange="toggleShrink2()" class="tenant" >
                                <option value="">Joseph</option>

                              </select>
                            </div>

                          </div>

                        </div>

                        <!-- <div class="field-group">
                          <label for="subject new-message">Subject (optional)</label>
                          <input name="subject" type="text" id="subject" class="subject" placeholder="Enter subject..." />
                        </div> -->

                        <div class="field-group">
                          <label for="title">Title</label>
                          <input name="title" type="text" id="title" class="title" placeholder="Enter title..." />
                        </div>


                        <div class="field-group">
                          <label for="message">Message</label>
                          <textarea name="message" id="message" placeholder="Type your message here..."></textarea>
                        </div>
                        <!-- File input for multiple file types -->

                        <div style="padding-bottom: 2%;">
                        <input  name="files[]"  type="file" id="fileInput" onchange="handleFiles(event)" class="form-control" multiple>

                        <!-- Section to display selected files' previews and sizes -->
                        <div id="filePreviews"></div>
                      </div>

                        <div class="actions d-flex justify-content-end">
                          <button class="draft-btn text-danger btn">Cancel</button>
                          <button class="draft-btn btn">Save Draft</button>
                          <button type="submit"  class="send-btn btn">Send</button>
                        </div>
</form>
                      </div>

                    </div>

                </div>


<!-- End NewText popup -->

<!-- PopUp Scripts -->



<!-- !-- create new text -->
<!-- Add this JavaScript -->
<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
  const previewContainer = document.getElementById('filePreviews');
  previewContainer.innerHTML = ''; // Clear previous previews
  
  Array.from(e.target.files).forEach(file => {
    const previewElement = createPreviewElement(file);
    previewContainer.appendChild(previewElement);
  });
});

function createPreviewElement(file) {
  const previewDiv = document.createElement('div');
  previewDiv.className = 'file-preview';
  previewDiv.dataset.filename = file.name;
  
  // Preview for images
  if (file.type.startsWith('image/')) {
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.className = 'preview-image';
    previewDiv.appendChild(img);
  }
  // Preview for PDFs
  else if (file.type === 'application/pdf') {
    const pdfIcon = document.createElement('div');
    pdfIcon.className = 'pdf-preview';
    pdfIcon.innerHTML = `
      <i class="fas fa-file-pdf pdf-icon"></i>
      <span>${file.name}</span>
    `;
    previewDiv.appendChild(pdfIcon);
  }
  // Generic file preview
  else {
    previewDiv.innerHTML = `
      <i class="fas fa-file-alt file-icon"></i>
      <span>${file.name}</span>
    `;
  }
  
  // Add remove button
  const removeBtn = document.createElement('button');
  removeBtn.className = 'remove-preview';
  removeBtn.innerHTML = '<i class="fas fa-times"></i>';
  removeBtn.onclick = () => previewDiv.remove();
  previewDiv.appendChild(removeBtn);
  
  return previewDiv;
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector('#newtextPopup form');

  form.addEventListener('submit', function (e) {
    e.preventDefault(); // Stop default form submission

    const formData = new FormData(form);

    fetch('texts.php', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (response.ok) {
        alert("Message Sent Successfully!");
        // Optionally reset form or close popup
        form.reset();
        closenewtextPopup(); // if this function hides the popup
      } else {
        alert("Failed to send message.");
      }
    })
    .catch(error => {
      console.error("Error:", error);
      alert("An error occurred while sending the message.");
    });
  });
});
</script>




<script>
let selectedFiles = [];

function handleFiles(event) {
  const newFiles = Array.from(event.target.files);
  const previewContainer = document.getElementById('filePreviews');

  // Merge and limit total to 10 files
  selectedFiles = [...selectedFiles, ...newFiles].slice(0, 10);

  previewContainer.innerHTML = ''; // Clear current previews

  selectedFiles.forEach((file, index) => {
    const fileType = file.type;

    const previewItem = document.createElement('div');
    previewItem.className = 'preview-item';

    // Close (remove) button
    const removeBtn = document.createElement('button');
    removeBtn.className = 'remove-button';
    removeBtn.innerHTML = '&times;';
    removeBtn.onclick = () => {
      selectedFiles.splice(index, 1);
      handleFiles({ target: { files: selectedFiles } }); // Re-render
    };

    previewItem.appendChild(removeBtn);

    // Preview logic
    if (fileType.startsWith('image/')) {
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.onload = () => URL.revokeObjectURL(img.src);
      previewItem.appendChild(img);
    } else if (fileType === 'application/pdf') {
      const pdfIcon = document.createElement('div');
      pdfIcon.textContent = '📄 PDF';
      pdfIcon.style.fontSize = '16px';
      previewItem.appendChild(pdfIcon);
    } else {
      const fileIcon = document.createElement('div');
      fileIcon.textContent = file.name;
      fileIcon.style.fontSize = '12px';
      fileIcon.style.padding = '4px';
      fileIcon.style.textAlign = 'center';
      previewItem.appendChild(fileIcon);
    }

    previewContainer.appendChild(previewItem);
  });
}
</script> -->



<script src="view.js"></script>

<script>
  // Function to open the complaint popup
  function opennewtextPopup() {
    document.getElementById("newtextPopup").style.display = "flex";
  }

  // Function to close the complaint popup
  function closenewtextPopup() {
    document.getElementById("newtextPopup").style.display = "none";
  }

  function toggleShrink() {
            let recipientBox = document.getElementById("recipient");
            let first_field_group = document.getElementById("field-group-first");
            let field_group_third = document.getElementById("field-group-third");
            let field_group_second = document.getElementById("field-group-second");

            if (recipientBox.value === "all") {
              first_field_group.classList.remove("shrink"); // shrink if the option is not all
              field_group_second.style.display= "none";
              field_group_third.style.display= "none";

          } else {
            first_field_group.classList.add("shrink"); // Do not shrink is the option is all
            field_group_second.style.display= "block";
          }

        }


        function toggleShrink1() {
            let recipientBox_units = document.getElementById("recipient-units");

            let field_group_second = document.getElementById("field-group-second");
            let field_group_third = document.getElementById("field-group-third");

            if (recipientBox_units.value === "all") {
              field_group_second.classList.remove("shrink"); // shrink if the option is not all
              field_group_third.style.display= "none";

          } else {
            field_group_second.classList.add("shrink"); // Do not shrink is the option is all
            field_group_third.style.display= "block";
          }

        }
</script>
<!-- end create new text -->

<!-- image preview -->
<script>
  document.getElementById('imageUpload').addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
              const preview = document.getElementById('imagePreview');
              preview.src = e.target.result;
              preview.style.display = 'block';
          }
          reader.readAsDataURL(file);
      }
  });
</script>

<!-- end -->

<script>

  // Major Variables.
  const all_messages_summary =  document.getElementById('all-messages-summary');
  const individual_message_summary = document.getElementById('individual-message-summmary');


  // const   next_text = document.getElementById('respond_btn');
  const   respond_window = document.getElementById('respond');
  const   close_text_overlay = document.getElementById("closeTextWindow");

  next_text.addEventListener('click', ()=>{

     respond_window.style.display= "flex";
     document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
     const now = new Date();
            const formattedTime = now.toLocaleString(); // Format the date and time
            timestamp.textContent = `Sent on: ${formattedTime}`;


  });

  close_text_overlay.addEventListener('click', ()=>{

    respond_window.style.display= "none";
     document.querySelector('.app-wrapper').style.opacity = '1';
     });

</script>



<!--Begin sidebar script -->
<script>
  fetch('../bars/sidebar.html')  // Fetch the file
      .then(response => response.text()) // Convert it to text
      .then(data => {
          document.getElementById('sidebar').innerHTML = data; // Insert it
      })
      .catch(error => console.error('Error loading the file:', error)); // Handle errors
</script>
<!-- end sidebar script -->


                      <!-- SCRIPT FOR MESSAGES -->
    <!-- Begin -->

  <!-- End  -->


<script>
document.getElementById('sendMessage').addEventListener('click', function() {
    let messageInput = document.getElementById('messageInput').value;
    if (messageInput.trim() !== "") {
        // Create a new message div
        let newMessage = document.createElement('div');
        newMessage.classList.add('message', 'outgoing');
        newMessage.innerHTML = `<p>${messageInput}</p>`;

        // Append the message to the chat container
        document.getElementById('chatContainer').appendChild(newMessage);

        // Clear input field
        document.getElementById('messageInput').value = '';

        // Optionally scroll to the bottom after sending a message
        document.getElementById('chatContainer').scrollTop = document.getElementById('chatContainer').scrollHeight;
    }
});
</script>





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
                 alert(data.messages);
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
document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.btn.view');
    const messagesContainer = document.getElementById('messages-container');
    const messageThread = document.getElementById('message-thread');
    const threadTitle = document.getElementById('thread-title');

    viewButtons.forEach(button => {
        button.addEventListener('click', () => {
            const threadId = button.getAttribute('data-thread-id');

            // Show loading
            messagesContainer.innerHTML = '<p>Loading...</p>';
            threadTitle.innerText = '';
            messageThread.style.display = 'block';

            // Fetch JSON
            fetch(`get_message.php?thread_id=${threadId}`)
                .then(response => response.json())
                .then(data => {
                    threadTitle.innerText = data.title;
                    messagesContainer.innerHTML = data.messages;
                })
                .catch(error => {
                    messagesContainer.innerHTML = '<p>Error loading messages.</p>';
                    console.error('Error:', error);
                });
        });
    });
});
</script>

  <!-- End  -->


</script>
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="../../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.msg-image').forEach(img => {
        img.addEventListener('click', function() {
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0,0,0,0.8)';
            modal.style.zIndex = '1000';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';

            const fullImg = document.createElement('img');
            fullImg.src = this.dataset.fullsize;
            fullImg.style.maxHeight = '90vh';
            fullImg.style.maxWidth = '90vw';

            modal.appendChild(fullImg);
            modal.addEventListener('click', () => document.body.removeChild(modal));

            document.body.appendChild(modal);
        });
    });
});
    </script>
    <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>
    <script>
window.addEventListener("error", function(e) {
    console.error("JavaScript error:", e.message, "at", e.filename + ":" + e.lineno + ":" + e.colno);
});
</script>


    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
