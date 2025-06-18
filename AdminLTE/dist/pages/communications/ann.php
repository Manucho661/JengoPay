<?php
include '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = $_POST['recipient'] ?? '';
    $priority = $_POST['priority'] ?? 'Normal';
    $message = $_POST['message'] ?? '';
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($recipient) || empty($message)) {
        die("Recipient and message are required fields.");
    }

    try {
        // Insert announcement into database
        $stmt = $pdo->prepare("INSERT INTO announcements (recipient, priority, message, created_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$recipient, $priority, $message, $created_at]);
        $announcement_id = $pdo->lastInsertId();

        // Handle file uploads if any
        if (!empty($_FILES['attachments']['name'][0])) {
            $uploadDir = '../uploads/announcements/';

            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
                $fileName = $_FILES['attachments']['name'][$key];
                $fileSize = $_FILES['attachments']['size'][$key];
                $fileType = $_FILES['attachments']['type'][$key];
                $filePath = $uploadDir . basename($fileName);

                // Move the file to the upload directory
                if (move_uploaded_file($tmp_name, $filePath)) {
                    // Insert file info into database
                    $stmt = $pdo->prepare("INSERT INTO announcement_attachments (announcement_id, file_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$announcement_id, $fileName, $filePath, $fileType, $fileSize]);
                }
            }
        }

        // Redirect or show success message
        header("Location: ".$_SERVER['HTTP_REFERER']."?success=1");
        exit();

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
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
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">



<!-- scripts for data_table -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="announcements.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
  :root {
    --primary:  #00192D;
    --primary-light: #818cf8;
    --dark: #1e293b;
    --light: #f8fafc;
    --gray: #94a3b8;
    --gray-light: #e2e8f0;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    /* color:#FFC107; */
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: #f1f5f9;
    color: var(--dark);
}

.notification-center {
    max-width: 1200px;
    margin: 2rem auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.header {
    padding: 1.5rem 2rem;
    background: var(--primary);
    color: #FFC107;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
    font-weight: 600;
    font-size: 1.5rem;
}

.notification-count {
    background: white;
    color: var(--primary);
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.875rem;
}

.notification-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.btn-primary {
    background: white;
    color: var(--primary);
}

.btn-primary:hover {
    background: rgba(255, 255, 255, 0.9);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
}

.notification-filters {
    padding: 1rem 2rem;
    border-bottom: 1px solid var(--gray-light);
    display: flex;
    gap: 1rem;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    background: none;
    border: none;
    color: var(--gray);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-btn.active, .filter-btn:hover {
    color: var(--primary);
    background: var(--gray-light);
}

.filter-btn i {
    margin-right: 0.5rem;
}

.notification-list {
    max-height: 600px;
    overflow-y: auto;
}

.notification-item {
    padding: 1.25rem 2rem;
    border-bottom: 1px solid var(--gray-light);
    display: flex;
    gap: 1rem;
    transition: all 0.2s;
}

.notification-item.unread {
    background: #f8fafc;
}

.notification-item:hover {
    background: #f1f5f9;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-icon.info {
    background: var(--info);
    color: white;
}

.notification-icon.success {
    background: var(--success);
    color: white;
}

.notification-icon.warning {
    background: var(--warning);
    color: white;
}

.notification-icon.danger {
    background: var(--danger);
    color: white;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    display: flex;
    justify-content: space-between;
}

.notification-time {
    color: var(--gray);
    font-size: 0.875rem;
    font-weight: 400;
}

.notification-message {
    color: var(--dark);
    line-height: 1.5;
    margin-bottom: 0.5rem;
}

.notification-actions {
    display: flex;
    gap: 0.5rem;
}
.notification-count {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.875rem;
    background: rgba(255, 193, 7, 0.1); /* subtle background for the yellow text */
}

.notification-count i {
    font-size: 0.8em;
}
.filter-btn {
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    background: none;
    border: none;
    color: #64748b; /* slate-500 */
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9375rem;
    position: relative;
    overflow: hidden;
}

.filter-btn i {
    font-size: 1.1em;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    color: #4f46e5; /* indigo-600 */
    background: #eef2ff; /* indigo-50 */
}

.filter-btn.active {
    color: #4f46e5; /* indigo-600 */
    background: #eef2ff; /* indigo-50 */
    font-weight: 600;
}

.filter-btn.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #4f46e5; /* indigo-600 */
    border-radius: 3px 3px 0 0;
}

/* Specific icon colors for each filter type */
.filter-btn .fa-info-circle { color: #3b82f6; } /* info - blue-500 */
.filter-btn .fa-check-circle { color: #10b981; } /* success - emerald-500 */
.filter-btn .fa-exclamation-triangle { color: #f59e0b; } /* warning - amber-500 */
.filter-btn .fa-exclamation-circle { color: #ef4444; } /* alert - red-500 */
.filter-btn .fa-archive { color: #8b5cf6; } /* archive - violet-500 */

.filter-btn.active .fa-inbox,
.filter-btn:hover .fa-inbox { color: #4f46e5; } /* indigo-600 */

/* Keep specific colors when active/hover */
.filter-btn.active .fa-info-circle,
.filter-btn:hover .fa-info-circle { color: #2563eb; } /* blue-600 */

.filter-btn.active .fa-check-circle,
.filter-btn:hover .fa-check-circle { color: #059669; } /* emerald-600 */

.filter-btn.active .fa-exclamation-triangle,
.filter-btn:hover .fa-exclamation-triangle { color: #d97706; } /* amber-600 */

.filter-btn.active .fa-exclamation-circle,
.filter-btn:hover .fa-exclamation-circle { color: #dc2626; } /* red-600 */

.filter-btn.active .fa-archive,
.filter-btn:hover .fa-archive { color: #7c3aed; } /* violet-600 */


@media (max-width: 768px) {
    .notification-filters {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 0.5rem;
        -webkit-overflow-scrolling: touch;
    }

    .filter-btn {
        padding: 0.6rem 1rem;
        font-size: 0.875rem;
    }

    .filter-btn i {
        font-size: 1em;
    }
}

.notification-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 0.5rem;
}

.action-btn {
    background: none;
    background-color: #00192D;
    border: none;
    color:#FFC107; /* blue-600 */
    cursor: pointer;
    font-size: 0.8125rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.action-btn i {
    font-size: 0.9em;
    transition: all 0.2s ease;
}

/* Hover effects */
.action-btn:hover {
  background:#FFC107; /* blue-100 */
  /* color:#FFC107; blue-600 */
}

.action-btn:hover i {
    transform: scale(1.1);
}

/* Specific styles for each action */
.action-btn:nth-child(1) { /* Mark as read */
    color:#FFC107; /* blue-500 */
}

.action-btn:nth-child(1):hover {
    background:#00192D; /* blue-100 */
    color:#FFC107; /* blue-600 */
    /* background-color: #00192D; */
}

.action-btn:nth-child(2) { /* Archive */
    color: #FFC107; /* violet-500 */
}

.action-btn:nth-child(2):hover {
    background: #ede9fe; /* violet-100 */
    color: #FFC107; /* violet-600 */
}

/* Active/click effect */
.action-btn:active {
    transform: translateY(1px);
}

/* Ripple effect */
.action-btn::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 1%, transparent 1%) center/15000%;
    opacity: 0;
    transition: opacity 0.5s, background-size 0.5s;
}

.action-btn:active::after {
    background-size: 100%;
    opacity: 1;
    transition: background-size 0s;
}

/* For notifications that are already read */
.notification-item.read .action-btn:nth-child(1) {
    color:#FFC107; /* slate-400 */
    cursor: default;
}

.notification-item.read .action-btn:nth-child(1):hover {
    background: transparent;
    color:#FFC107; /* slate-400 */
}
.notification-stats {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .stat-icon.info { background: #3b82f6; }
        .stat-icon.warning { background: #f59e0b; }
        .stat-icon.danger { background: #ef4444; }
        .stat-icon.success { background: #10b981; }

        .stat-info {
            flex: 1;
        }

        .stat-count {
            display: block;
            font-weight: 700;
            font-size: 1.2rem;
            color: #212529;
        }

        .stat-label {
            display: block;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border: none;
            border-radius: 8px;
            background: #f8f9fa;
            color: #212529;
            font-weight: 500;
            text-align: left;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            background: #e9ecef;
            color: #0d6efd;
            transform: translateX(5px);
        }

        .trend-item {
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .trend-item:hover {
            background: #f8f9fa;
        }

        .progress {
            border-radius: 3px;
            background-color: #e9ecef;
        }

        .bg-purple {
            background-color: #6f42c1;
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-md-4 {
                margin-top: 1.5rem;
            }
        }
        .announcement-card {
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  border: none;
  overflow: hidden;
}

.announcement-card .card-header {
  padding: 1rem 1.5rem;
  border-bottom: none;
}

.contact_section_header {
  font-weight: 600;
  font-size: 1.25rem;
  display: flex;
  align-items: center;
}

/* Stat Cards */
.stats-row {
  margin: -0.5rem;
}

.stat-item {
  padding: 0.5rem;
}

.stat-card {
  padding: 1.5rem 1rem;
  border-radius: 10px;
  height: 100%;
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  font-size: 1.75rem;
  margin-right: 1rem;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
}

.stat-content {
  flex: 1;
}

.stat-label {
  display: block;
  font-size: 0.85rem;
  opacity: 0.9;
  margin-bottom: 0.25rem;
}

.stat-label a {
  text-decoration: none;
}

.stat-value {
  display: block;
  font-size: 1.5rem;
  font-weight: 700;
  line-height: 1.2;
}

/* Gradient Backgrounds */
.bg-gradient-info {
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  color: white;
}

.bg-gradient-warning {
  background: linear-gradient(135deg, #f59e0b, #f97316);
  color: white;
}

.bg-gradient-success {
  background: linear-gradient(135deg, #10b981, #14b8a6);
  color: white;
}

/* Stylish Select Dropdown */
.select-wrapper {
  position: relative;
  width: 100%;
}

.stylish-select {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  padding: 0.75rem 1rem;
  border: none;
  border-radius: 8px;
  background-color: rgba(255, 255, 255, 0.2);
  color: white;
  font-weight: 500;
  width: 100%;
  cursor: pointer;
}

.stylish-select option {
  color: #333;
  background: white;
}

.select-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .stat-card {
    flex-direction: column;
    text-align: center;
    padding: 1.5rem 0.5rem;
  }

  .stat-icon {
    margin-right: 0;
    margin-bottom: 0.75rem;
  }

  .stat-value {
    font-size: 1.25rem;
  }
}
.close-btn {
  position: absolute;
  top: 15px;
  right: 15px;
  font-size: 24px;
  background: none;
  border: none;
  cursor: pointer;
  color: #666;
}

.popup-title {
  color: #00192D;
  margin-bottom: 20px;
  font-size: 1.5rem;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
  color: #333;
}

.form-select, .form-textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
  transition: border 0.3s;
}

.form-select:focus, .form-textarea:focus {
  border-color: #00192D;
  outline: none;
}

.form-textarea {
  min-height: 150px;
  resize: vertical;
}

.form-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 15px;
}

.btn-cancel {
  padding: 10px 20px;
  background: #f1f1f1;
  border: none;
  border-radius: 6px;
  color: #333;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-cancel:hover {
  background: #e0e0e0;
}

.btn-submit {
  padding: 10px 20px;
  background: #00192D;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-submit:hover {
  background: #003366;
}
</style>



  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!-- Top Notification Bell -->
<div id="top-alert" class="top-alert" style="position: fixed; top: 10px; right: 20px; z-index: 1000;">
  <button id="showNotifications" class="btn btn-warning">
    <i class="fas fa-bell"></i> <span id="top-count">0 New</span>
  </button>
</div>

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
                        src="../../dist/assets/img/user1-128x128.jpg"
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
                        src="../../dist/assets/img/user8-128x128.jpg"
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
                        src="../../dist/assets/img/user3-128x128.jpg"
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
                <span class="d-none d-md-inline">  <b>JENGO PAY</b>  </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="../../dist/assets/img/user2-160x160.jpg"
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
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <!-- <div id="sidebar"></div> This is where the sidebar is inserted -->
        <!-- <div id="sidebar"></div> -->
        <!-- <div id="sidebar"></div> -->
        <div > <?php include_once '../includes/sidebar1.php'; ?>  </div> <!-- This is where the sidebar is inserted -->
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
  <div class="col-sm-12">
    <div class="card announcement-card">
      <div class="card-header" style="color: #FFC107; background-color:#00192D;">
        <h3 class="mb-0 contact_section_header">
          <i class="fas fa-bullhorn me-2"></i> Announcements
        </h3>
      </div>
      <div class="card-body">
        <div class="row mt-2 announcement-summary">
          <div class="col-md-12">
            <div class="row stats-row">
              <!-- Summary Item 1 -->
              <div class="col-md-4 ">
                <div class="stat-card" style="color: #FFC107; background-color:#00192D;">
                  <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                  </div>
                  <div class="stat-content">
                    <label class="stat-label">
                      <a href="index.html" class="" style="color: #FFC107; background-color:#00192D;">Announcements Sent</a>
                    </label>
                    <span class="stat-value">60</span>
                  </div>
                </div>
              </div>

              <!-- Summary Item 2 -->
              <div class="col-md-4 ">
                <div class="stat-card" style="color: #FFC107; background-color:#00192D;">
                  <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                  </div>
                  <div class="stat-content">
                    <label class="stat-label">
                      <a href="#" class="" style="color: #FFC107; background-color:#00192D;">Drafts</a>
                    </label>
                    <span class="stat-value">2</span>
                  </div>
                </div>
              </div>

              <!-- Summary Item 3 -->
              <div class="col-md-4 ">
                <div class="stat-card" style="color: #FFC107; background-color:#00192D;">
                  <div class="select-wrapper">
                    <select id="property" name="property" class="form-select stylish-select">
                      <option value=""  style="color: #FFC107; background-color:#00192D;" disabled selected>Select Property</option>
                      <option value="High">Manucho</option>
                      <option value="Moderate">Ben 10</option>
                      <option value="Low">Alpha</option>
                    </select>
                    <div class="select-icon">
                      <i class="fas fa-chevron-down"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- </div> -->

              <!-- <div class="row"> -->



              <div class="col-sm-4">
                <!-- <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#" style="color: #00192D;">  <i class="bi bi-house"></i> Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol> -->
              </div>

            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row first mb-2 mt-2 ">

              <div class="col-md-12 d-flex justify-content-end">



                  <button id="add_provider_btn" class="btn" style="background-color: #00192D; color: white; font-size: small;">   <i class="fa fa-bullhorn"></i>
                  <b class="button_item" class="open-btn" onclick="opennotificationPopup()"> New_Announcement</b></button>

              </div>
              <!-- /.col -->
            </div>
        <!-- /.row -->




            <!--end::Row-->
            <!--begin::Row-->
            <div class="row">
              <!-- Start col -->
              <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                      <div class="notification-center">
                        <div class="header">
                            <h1>Announcements</h1>
                            <div class="notification-actions">
                            <span class="notification-count" style="color: #FFC107;">
                            <i class="fas fa-bell"></i>
                            <span>12 new</span>
                        </span>
                                <!-- <button class="btn btn-primary">
                                    <i class="fas fa-cog"></i> Settings
                                </button> -->
                                <button class="btn btn-secondary">
                                    <i class="fas fa-check-double"></i> Mark all as read
                                </button>
                            </div>
                        </div>

                        <div class="notification-filters">
                            <button class="filter-btn active">
                                <i class="fas fa-inbox"></i> All
                            </button>
                            <!-- <button class="filter-btn">
                                <i class="fas fa-info-circle"></i> Info
                            </button>
                            <button class="filter-btn">
                                <i class="fas fa-check-circle"></i> Success
                            </button>
                            <button class="filter-btn">
                                <i class="fas fa-exclamation-triangle"></i> Warning
                            </button> -->
                            <button class="filter-btn">
                            <i class="fas fa-check-circle"></i> Sent
                            </button>
                            <button class="filter-btn">
                                <i class="fas fa-exclamation-circle"></i> Drafts
                            </button>
                            <button class="filter-btn">
                                <i class="fas fa-archive"></i> Archived
                            </button>
                        </div>

                        <div class="notification-list" id="announcementList">
    <!-- Announcements will be loaded here -->
</div>
                      </div>
                    </div>
                </div>
              </div>
            <!-- Additional Info Column (col-md-4) -->
            <div class="col-md-4">
                <!-- Notification Stats Card -->
                <!-- Quick Actions Card -->
                 <div class="card mb-4">
                    <div class="card-header" style="color: #FFC107; background-color:#00192D;">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                    <!-- <button class="quick-action-btn">
            <i class="fas fa-money-bill-wave"></i> Record Rent Payment
        </button> -->
        <!-- <button class="quick-action-btn">
            <i class="fas fa-file-invoice-dollar"></i> Send Rent Reminder
        </button> -->
                         <button class="quick-action-btn">
                            <i class="fas fa-bell-slash"></i> Snooze Notifications
                        </button>
                        <!-- <button class="quick-action-btn">
            <i class="fas fa-tools"></i> Report Maintenance
        </button> -->

                     </div>
                </div>

                <!-- Notification Trends Card -->
                 <div class="card">
                    <div class="card-header" style="color: #FFC107; background-color:#00192D;">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Notification Trends</h5>
                    </div>
                    <div class="card-body">
                        <div class="trend-item mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Info Notifications</span>
                                <span class="text-success">+12% <i class="fas fa-arrow-up"></i></span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: 65%"></div>
                            </div>
                        </div>

                        <div class="trend-item mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Warning Alerts</span>
                                <span class="text-danger">-5% <i class="fas fa-arrow-down"></i></span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: 35%"></div>
                            </div>
                        </div>

                        <div class="trend-item mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Critical Alerts</span>
                                <span class="text-success">-18% <i class="fas fa-arrow-down"></i></span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: 22%"></div>
                            </div>
                        </div>

                        <div class="trend-item">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Success Notifications</span>
                                <span class="text-success">+24% <i class="fas fa-arrow-up"></i></span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 78%"></div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <h6 class="mb-0">Peak Time</h6>
                                <small class="text-muted">Most notifications received</small>
                            </div>
                            <span class="badge bg-purple">10:00 AM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                        <!-- Empty state example (hidden by default) -->
                        <!-- <div class="empty-state">
                            <i class="fas fa-bell-slash"></i>
                            <h3>No notifications</h3>
                            <p>You're all caught up! New notifications will appear here.</p>
                        </div> -->
                    </div>
                    </div>


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
          <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;"> JENGO PAY</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->




<!-- Overlay Cards -->







 <!-- Start View announcement -->
 <div class="view_announcement" id="view_announcement">
  <div class="card content">
   <div class="card-header view_announcement header">
       <h5 class="mb-0">Rent Payments</h5>
       <div id="close-overlay-btn" class="close-btn" > <b> &times;</b> </div>

   </div>
   <div class="card-body">
       <p class="card-text">
           All Rent Payments should be made before 24/01/17. Anyone who feels that they might not
       <p class="card-text announcement">
           All Rent Payments should be made before 24/01/17. Anyone who feels that they might not
           be able to complete their dues on 24/01/17 should communicate to management before the due date.
           Else, you face an eviction risk if you fail to pay your dues before the said date without a reasonable reason</p>

           <p class="timestamp" id="timestamp"></p> <!-- Timestamp -->


       <div class="Recipients border-top border-gray "> <p><strong>Recipients|</strong> All Employees</p>  </div>

   </div>
   <div class="card-footer view_announcement d-flex">
       <button class="btn btn-danger btn-sm">Delete</button>
       <button class="btn resend btn-sm">Resend</button>
   </div>
 </div>
 </div>

<!-- End view announcement -->

<!-- Start of new announcement card-->
<div class="container-fluid New_Announcement mt-5" id="New_Announcement">
  <div class="card New_Announcement">
      <div class="card-header text-white" style="background-color: #00192D; display: flex;">
          <h5 class="mb-0">New Announcement</h5>
         <div id="close-new_announcement-btn" class="close-btnOne" > <b> &times;</b> </div>
      </div>
      <div class="card-body">
          <!-- Title -->
          <div class="mb-3">
              <label for="announcementTitle" class="form-label">Title</label>
              <input type="text" class="form-control" id="announcementTitle" placeholder="Enter title">
          </div>



          <!-- Message -->
          <div class="mb-3">
              <label for="announcementMessage" class="form-label">Message</label>
              <textarea class="form-control" id="announcementMessage" rows="5" placeholder="Write your message here"></textarea>
          </div>

          <!-- Recipients -->

            <div class="mb-3 recipients-container" id="recipients-container">
              <label for="recipientsSelect" class="form-label">Recipients</label>  <i class="fas fa-chevron-down"></i>
              <div class="panel " >

              <select class="form-select bg-dark text-white" id="recipientsSelect" multiple>
                  <option value="all">All Employees</option>
                  <option value="managers">Managers</option>
                  <option value="tenants">Tenants</option>
                  <option value="staff">Staff</option>
              </select>

            </div>
          </div>


      </div>
      <div class="card-footer d-flex justify-content-end">
          <button type="button" class="btn btn-secondary me-2" id="saveDraftBtn">Add to Draft</button>
          <button type="button"  class="btn" style="background-color: #FFC107; color: white;" id="sendAnnouncementBtn">Send</button>
      </div>
  </div>
</div>
<!-- end of new announcement card-->


<!-- notification popup -->
<div class="notificationpopup-overlay" id="notificationPopup">
  <div class="card" style="margin-top: 20px;">
    <div class="card-header new-message-header">
      New Announcement
      <button class="close-btn" onclick="closenotificationPopup()">×</button>
    </div>
    <div class="card-body new-message-body">
      <form action="" method="POST" id="notificationForm">
        <div class="form-group">
          <label for="property">Recipients*</label>
          <select id="property" name="recipient" class="form-select" required>
            <option value="" disabled selected>Select Recipient</option>
            <option value="Manucho">Manucho</option>
            <option value="Ben 10">Ben 10</option>
            <option value="Alpha">Alpha</option>
            <option value="All Tenants">All Tenants</option>
          </select>
        </div>

        <div class="form-group">
          <label for="priority">Priority*</label>
          <select id="priority" name="priority" class="form-select" required>
            <option value="Normal">Normal</option>
            <option value="Urgent">Urgent</option>
            <option value="Reminder">Reminder</option>
          </select>
        </div>

        <div class="form-group">
          <label for="notes">Message*</label>
          <textarea id="notes" name="message" class="form-textarea" placeholder="Type your announcement here..." required></textarea>
        </div>

        <!-- Optional: add file upload support -->
        <!--
        <div class="form-group">
          <label for="fileInput">Attach Files</label>
          <input type="file" name="files[]" id="fileInput" multiple class="form-control">
        </div>
        -->

        <div class="actions d-flex justify-content-end">
          <button type="button" class="draft-btn text-danger btn" onclick="closenotificationPopup()">Cancel</button>
          <button type="submit" class="send-btn btn">Send Announcement</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="announcements.js"></script>

<!-- End Notification popup -->

<!-- end overlay card. -->

    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->



<!-- Overlay scripts -->
 <!-- View announcements script -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>




 <!-- sidebar dropdowns-->




<!-- create notification -->
<script>
  // Function to open the complaint popup
  function opennotificationPopup() {
    document.getElementById("notificationPopup").style.display = "flex";
  }

  // Function to close the complaint popup
  function closenotificationPopup() {
    document.getElementById("notificationPopup").style.display = "none";
  }
</script>

<script>
  function sendMessage() {
    const recipient = document.getElementById('property').value.trim();
    const priority = document.getElementById('priority').value.trim();
    const message = document.getElementById('notes').value.trim();

    if (!recipient || !priority || !message) {
      alert("Please fill all required fields.");
      return;
    }

    const formData = new FormData();
    formData.append('recipient', recipient);
    formData.append('priority', priority);
    formData.append('message', message);

    fetch('send_announcement.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      alert(data.message);
      if (data.status === 'success') {
        document.getElementById('notes').value = '';
        document.getElementById('property').value = '';
        document.getElementById('priority').value = 'Normal';
        closenotificationPopup();
      }
    })
    .catch(error => {
      console.error('Fetch error:', error);
      alert("Something went wrong. Please try again.");
    });
  }
</script>
<script>
function loadAnnouncements() {
  fetch('fetch_announcements.php')
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        const container = document.getElementById('announcementList');
        container.innerHTML = '';

        data.data.forEach(item => {
          const iconClass = getIconByPriority(item.priority); // Determine icon style

          const html = `
            <div class="notification-item unread">
              <div class="notification-icon ${iconClass}">
                <i class="fas ${getIconSymbol(item.priority)}"></i>
              </div>
              <div class="notification-content">
                <div class="notification-title">
                  <span>${item.priority} Announcement to ${item.recipient}</span>
                  <span class="notification-time">${formatTime(item.created_at)}</span>
                </div>
                <p class="notification-message">${item.message}</p>
                <div class="notification-actions">
                  <button class="action-btn">
                    <i class="fas fa-check"></i>Draft
                  </button>
                  <button class="action-btn">
                    <i class="fas fa-archive"></i> Archive
                  </button>
                </div>
              </div>
            </div>
          `;
          container.insertAdjacentHTML('beforeend', html);
        });
      }
    })
    .catch(error => console.error('Error loading announcements:', error));
}

function getIconByPriority(priority) {
  switch (priority.toLowerCase()) {
    case 'urgent': return 'danger';
    case 'reminder': return 'info';
    case 'normal': return 'success';
    default: return 'info';
  }
}

function getIconSymbol(priority) {
  switch (priority.toLowerCase()) {
    case 'urgent': return 'fa-exclamation-circle';
    case 'reminder': return 'fa-info-circle';
    case 'normal': return 'fa-check-circle';
    default: return 'fa-info-circle';
  }
}

function formatTime(datetime) {
  const d = new Date(datetime);
  return d.toLocaleString(); // or customize as needed
}

// Call it on page load
document.addEventListener('DOMContentLoaded', loadAnnouncements);
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

<!-- script for occordion -->
<script>

        // JavaScript to handle hover and hide functionality
        const filterContainer = document.getElementById("filter-container");
        const accordion = document.getElementById("filter");
        const panel = document.getElementById("panel");

        // Show panel when hovering over the accordion
        accordion.addEventListener("mouseenter", () => {
            panel.style.display = "block";
        });

        // Hide panel when moving out of both accordion and panel
         filterContainer.addEventListener("mouseleave", () => {
             panel.style.display = "none";
      });


</script>
<!-- End for Occordion script -->

    <!-- Begin script for datatable -->
    <script>
       $(document).ready(function() {
        $('#Texts').DataTable({
            "paging": true,
            "searching": true,
            "info": true,
            "lengthMenu": [5, 10, 25, 50],
            "language": {
                "search": "Filter records:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });

    </script>
    <!-- End script for data_table -->

<!--Begin sidebar script -->
<!-- <script>
  fetch('../bars/sidebar.html')  // Fetch the file
      .then(response => response.text()) // Convert it to text
      .then(data => {
          document.getElementById('sidebar').innerHTML = data; // Insert it
      })
      .catch(error => console.error('Error loading the file:', error)); // Handle errors
</script> -->
<!-- end sidebar script -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Notification stats chart
        const ctx = document.getElementById('notificationChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Info', 'Success', 'Warning', 'Alert'],
                datasets: [{
                    data: [24, 42, 8, 3],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    }
                }
            }
        });
    });
    </script>
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

  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".nav-item > .nav-link").forEach((navLink) => {
      navLink.addEventListener("click", function (e) {
        // Check if the link has a submenu (dropdown)
        let dropdown = this.nextElementSibling;
        if (dropdown && dropdown.classList.contains("nav-treeview")) {
          e.preventDefault(); // Prevents "#" from appearing in the URL
          dropdown.classList.toggle("show"); // Toggles dropdown visibility
        }
      });
    });
  });


     </script>

    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>
    <script>
      // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
      // IT'S ALL JUST JUNK FOR DEMO
      // ++++++++++++++++++++++++++++++++++++++++++

      /* apexcharts
       * -------
       * Here we will create a few charts using apexcharts
       */

      //-----------------------
      // - MONTHLY SALES CHART -
      //-----------------------

      const sales_chart_options = {
        series: [
          {
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 180,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#sales-chart'),
        sales_chart_options,
      );
      sales_chart.render();

      //---------------------------
      // - END MONTHLY SALES CHART -
      //---------------------------

      function createSparklineChart(selector, data) {
        const options = {
          series: [{ data }],
          chart: {
            type: 'line',
            width: 150,
            height: 30,
            sparkline: {
              enabled: true,
            },
          },
          colors: ['var(--bs-primary)'],
          stroke: {
            width: 2,
          },
          tooltip: {
            fixed: {
              enabled: false,
            },
            x: {
              show: false,
            },
            y: {
              title: {
                formatter: function (seriesName) {
                  return '';
                },
              },
            },
            marker: {
              show: false,
            },
          },
        };

        const chart = new ApexCharts(document.querySelector(selector), options);
        chart.render();
      }

      const table_sparkline_1_data = [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54];
      const table_sparkline_2_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 44];
      const table_sparkline_3_data = [15, 46, 21, 59, 33, 15, 34, 42, 56, 19, 64];
      const table_sparkline_4_data = [30, 56, 31, 69, 43, 35, 24, 32, 46, 29, 64];
      const table_sparkline_5_data = [20, 76, 51, 79, 53, 35, 54, 22, 36, 49, 64];
      const table_sparkline_6_data = [5, 36, 11, 69, 23, 15, 14, 42, 26, 19, 44];
      const table_sparkline_7_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 74];

      createSparklineChart('#table-sparkline-1', table_sparkline_1_data);
      createSparklineChart('#table-sparkline-2', table_sparkline_2_data);
      createSparklineChart('#table-sparkline-3', table_sparkline_3_data);
      createSparklineChart('#table-sparkline-4', table_sparkline_4_data);
      createSparklineChart('#table-sparkline-5', table_sparkline_5_data);
      createSparklineChart('#table-sparkline-6', table_sparkline_6_data);
      createSparklineChart('#table-sparkline-7', table_sparkline_7_data);

      //-------------
      // - PIE CHART -
      //-------------

      const pie_chart_options = {
        series: [700, 500, 400, 600, 300, 100],
        chart: {
          type: 'donut',
        },
        labels: ['Chrome', 'Edge', 'FireFox', 'Safari', 'Opera', 'IE'],
        dataLabels: {
          enabled: false,
        },
        colors: ['#0d6efd', '#20c997', '#ffc107', '#d63384', '#6f42c1', '#adb5bd'],
      };

      const pie_chart = new ApexCharts(document.querySelector('#pie-chart'), pie_chart_options);
      pie_chart.render();

      //-----------------
      // - END PIE CHART -
      //-----------------
    </script>
    <script>
// Enhance select dropdown
document.querySelectorAll('.stylish-select').forEach(select => {
  select.addEventListener('focus', function() {
    this.parentElement.style.boxShadow = '0 0 0 2px rgba(255,255,255,0.3)';
  });

  select.addEventListener('blur', function() {
    this.parentElement.style.boxShadow = 'none';
  });
});
</script>
<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    const notificationList = document.querySelector(".notification-list");
    const topCount = document.getElementById("top-count");
    const showNotifications = document.getElementById("showNotifications");

    // Fetch notifications on load
    fetchNotifications();

    // Fetch when bell clicked
    showNotifications.addEventListener("click", function () {
        fetchNotifications();
        scrollToNotificationSection();
    });

    function fetchNotifications() {
        fetch("fetch_notifications.php")
            .then(res => res.json())
            .then(data => {
                renderNotifications(data.notifications);
                topCount.textContent = `${data.new_count} New`;
            })
            .catch(err => {
                console.error("Failed to fetch notifications", err);
            });
    }

    function renderNotifications(notifications) {
        notificationList.innerHTML = ""; // clear previous
        notifications.forEach(notification => {
            const typeClass = {
                info: "info",
                success: "success",
                warning: "warning",
                alert: "danger"
            }[notification.type] || "info";

            const item = document.createElement("div");
            item.className = `notification-item ${notification.read ? "" : "unread"}`;

            item.innerHTML = `
                <div class="notification-icon ${typeClass}">
                    <i class="fas fa-${getIcon(notification.type)}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">
                        <span>${notification.title}</span>
                        <span class="notification-time">${notification.time}</span>
                    </div>
                    <p class="notification-message">${notification.message}</p>
                    <div class="notification-actions">
                        <button class="action-btn"><i class="fas fa-check"></i> Mark as read</button>
                        <button class="action-btn"><i class="fas fa-archive"></i> Archive</button>
                    </div>
                </div>
            `;
            notificationList.appendChild(item);
        });
    }

    function getIcon(type) {
        switch(type) {
            case "info": return "info-circle";
            case "success": return "check-circle";
            case "warning": return "exclamation-triangle";
            case "alert": return "exclamation-circle";
            default: return "info-circle";
        }
    }

    function scrollToNotificationSection() {
        const announcementSection = document.querySelector(".notification-center");
        if (announcementSection) {
            announcementSection.scrollIntoView({ behavior: "smooth" });
        }
    }
});
</script> -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('fetch_announcements.php')
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById('announcementList');
            list.innerHTML = '';

            if (data.status === 'success') {
                data.data.forEach((item, index) => {
                    const iconType = getIconType(item.priority);
                    const icon = getIcon(item.priority);

                    const html = `
                        <div class="notification-item unread" data-index="${index}">
                            <div class="notification-icon ${iconType}">
                                <i class="fas ${icon}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">
                                    <span>${item.priority} message to ${item.recipient}</span>
                                    <span class="notification-time">${formatTime(item.created_at)}</span>
                                </div>
                                <p class="notification-message">${item.message}</p>
                                <div class="notification-actions">
                                    <button class="action-btn mark-sent">
                                        <i class="fas fa-check"></i> Draft
                                    </button>
                                    <button class="action-btn">
                                        <i class="fas fa-archive"></i> Archive
                                    </button>
                                </div>
                            </div>
                        </div>`;

                    list.insertAdjacentHTML('beforeend', html);
                });

                // Attach event listeners to all "Draft" buttons to change to "Sent"
                document.querySelectorAll('.mark-sent').forEach(button => {
                    button.addEventListener('click', function () {
                        this.innerHTML = '<i class="fas fa-paper-plane"></i> Sent';
                        this.disabled = true; // Optionally disable the button after marking
                    });
                });

            } else {
                list.innerHTML = '<p class="text-danger">Failed to load announcements.</p>';
            }
        })
        .catch(err => {
            document.getElementById('announcementList').innerHTML = '<p class="text-danger">Error loading announcements.</p>';
            console.error(err);
        });
});

function getIconType(priority) {
    switch (priority.toLowerCase()) {
        case 'urgent': return 'danger';
        case 'normal': return 'success';
        case 'reminder': return 'info';
        default: return 'info';
    }
}

function getIcon(priority) {
    switch (priority.toLowerCase()) {
        case 'urgent': return 'fa-exclamation-circle';
        case 'normal': return 'fa-check-circle';
        case 'reminder': return 'fa-info-circle';
        default: return 'fa-bell';
    }
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleString(); // Customize formatting if needed
}
</script>

<script>
  // Submit form via AJAX (using Fetch API)
function submitAnnouncement(event) {
  event.preventDefault(); // Prevent page reload

  const form = event.target;
  const formData = new FormData(form);

  // Optional: Show loading state
  const submitBtn = form.querySelector('.btn-submit');
  submitBtn.disabled = true;
  submitBtn.textContent = 'Sending...';

  // Send data to backend
  fetch('../communications/actions/save_announcement.php?', {  // Replace with your backend endpoint
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Announcement sent successfully!');
      closenotificationPopup(); // Close popup
      form.reset(); // Clear form
    } else {
      alert('Error: ' + data.error);
    }
  })
  .catch(error => {
    alert('Network error: ' + error);
  })
  .finally(() => {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Send Announcement';
  });
}
</script>

    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
