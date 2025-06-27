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
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=1");
    exit();
  } catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
  }
}
?>

<?php
include '../db/connect.php';

try {
  // Get today's date in Y-m-d format
  $today = date('Y-m-d');

  // Fetch only today's sent announcements
  $query = "SELECT * FROM announcements
              WHERE status = 'Sent'
              AND DATE(created_at) = :today
              ORDER BY created_at DESC";

  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':today', $today);
  $stmt->execute();
  $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Database error: " . $e->getMessage());
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
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta
    name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
  <!--end::Primary Meta Tags-->
  <!--begin::Fonts-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous" />
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
  <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
  <!-- <link rel="stylesheet" href="text.css" /> -->
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">



  <!-- scripts for data_table -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="announcements.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <style>
    :root {
      --primary: #00192D;
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

    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.6;
      }

      100% {
        opacity: 1;
      }
    }

    .announcement-item:hover {
      background-color: rgba(0, 25, 45, 0.08) !important;
      transition: background-color 0.2s ease;
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

    .filter-btn.active,
    .filter-btn:hover {
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
      background: rgba(255, 193, 7, 0.1);
      /* subtle background for the yellow text */
    }

    .notification-count i {
      font-size: 0.8em;
    }

    .filter-btn {
      padding: 0.75rem 1.25rem;
      border-radius: 8px;
      background: none;
      border: none;
      color: #64748b;
      /* slate-500 */
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
      color: #4f46e5;
      /* indigo-600 */
      background: #eef2ff;
      /* indigo-50 */
    }

    .filter-btn.active {
      color: #4f46e5;
      /* indigo-600 */
      background: #eef2ff;
      /* indigo-50 */
      font-weight: 600;
    }

    .filter-btn.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: #4f46e5;
      /* indigo-600 */
      border-radius: 3px 3px 0 0;
    }

    /* Specific icon colors for each filter type */
    .filter-btn .fa-info-circle {
      color: #3b82f6;
    }

    /* info - blue-500 */
    .filter-btn .fa-check-circle {
      color: #10b981;
    }

    /* success - emerald-500 */
    .filter-btn .fa-exclamation-triangle {
      color: #f59e0b;
    }

    /* warning - amber-500 */
    .filter-btn .fa-exclamation-circle {
      color: #ef4444;
    }

    /* alert - red-500 */
    .filter-btn .fa-archive {
      color: #8b5cf6;
    }

    /* archive - violet-500 */

    .filter-btn.active .fa-inbox,
    .filter-btn:hover .fa-inbox {
      color: #4f46e5;
    }

    /* indigo-600 */

    /* Keep specific colors when active/hover */
    .filter-btn.active .fa-info-circle,
    .filter-btn:hover .fa-info-circle {
      color: #2563eb;
    }

    /* blue-600 */

    .filter-btn.active .fa-check-circle,
    .filter-btn:hover .fa-check-circle {
      color: #059669;
    }

    /* emerald-600 */

    .filter-btn.active .fa-exclamation-triangle,
    .filter-btn:hover .fa-exclamation-triangle {
      color: #d97706;
    }

    /* amber-600 */

    .filter-btn.active .fa-exclamation-circle,
    .filter-btn:hover .fa-exclamation-circle {
      color: #dc2626;
    }

    /* red-600 */

    .filter-btn.active .fa-archive,
    .filter-btn:hover .fa-archive {
      color: #7c3aed;
    }

    /* violet-600 */


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
      color: #FFC107;
      /* blue-600 */
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
      background: #FFC107;
      /* blue-100 */
      /* color:#FFC107; blue-600 */
    }

    .action-btn:hover i {
      transform: scale(1.1);
    }

    /* Specific styles for each action */
    .action-btn:nth-child(1) {
      /* Mark as read */
      color: #FFC107;
      /* blue-500 */
    }

    .action-btn:nth-child(1):hover {
      background: #00192D;
      /* blue-100 */
      color: #FFC107;
      /* blue-600 */
      /* background-color: #00192D; */
    }

    .action-btn:nth-child(2) {
      /* Archive */
      color: #FFC107;
      /* violet-500 */
    }

    .action-btn:nth-child(2):hover {
      background: #ede9fe;
      /* violet-100 */
      color: #FFC107;
      /* violet-600 */
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
      background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 1%, transparent 1%) center/15000%;
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
      color: #FFC107;
      /* slate-400 */
      cursor: default;
    }

    .notification-item.read .action-btn:nth-child(1):hover {
      background: transparent;
      color: #FFC107;
      /* slate-400 */
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

    .stat-icon.info {
      background: #3b82f6;
    }

    .stat-icon.warning {
      background: #f59e0b;
    }

    .stat-icon.danger {
      background: #ef4444;
    }

    .stat-icon.success {
      background: #10b981;
    }

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

    .form-select,
    .form-textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 16px;
      transition: border 0.3s;
    }

    .form-select:focus,
    .form-textarea:focus {
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

    .notification-card {
      width: 100%;
      max-width: 500px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 25, 45, 0.3);
      overflow: hidden;
      border: none;
      transform: translateY(-20px);
      transition: transform 0.3s ease;
      background-color: white;
    }

    .notificationpopup-overlay.active .notification-card {
      transform: translateY(0);
    }

    .new-message-header {
      background-color: #00192D;
      color: #FFC107;
      padding: 16px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: none;
      font-family: 'Segoe UI', Arial, sans-serif;
    }

    .notification-title {
      font-size: 18px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .close-btn {
      background: none;
      border: none;
      color: #FFC107;
      font-size: 24px;
      cursor: pointer;
      line-height: 1;
      padding: 0;
      margin: 0;
      transition: transform 0.2s;
    }

    .close-btn:hover {
      transform: scale(1.2);
      color: white;
    }

    .new-message-body {
      padding: 25px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #00192D;
      font-size: 14px;
    }

    .form-select,
    .form-textarea {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #e0e0e0;
      border-radius: 6px;
      font-size: 14px;
      transition: all 0.3s;
      background-color: #f9f9f9;
    }

    .form-select {
      appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2300192D' stroke='%2300192D' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 16px;
    }

    .form-select:focus,
    .form-textarea:focus {
      border-color: #FFC107;
      box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
      outline: none;
      background-color: white;
    }

    .form-textarea {
      min-height: 120px;
      resize: vertical;
    }

    .actions {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 25px;
    }

    .draft-btn,
    .send-btn {
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      border: none;
      font-size: 14px;
    }

    .draft-btn {
      background-color: #f5f5f5;
      color: #d32f2f;
      border: 1px solid #e0e0e0;
    }

    .draft-btn:hover {
      background-color: #ffebee;
    }

    .send-btn {
      background-color: #FFC107;
      color: #00192D;
      font-weight: 600;
    }

    .send-btn:hover {
      background-color: #ffd54f;
      box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
      transform: translateY(-1px);
    }

    /* Priority color indicators */
    #priority option[value="Normal"] {
      color: #388e3c;
    }

    #priority option[value="Urgent"] {
      color: #d32f2f;
      font-weight: bold;
    }

    #priority option[value="Reminder"] {
      color: #ffa000;
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
      .notificationpopup-overlay {
        padding: 20px;
        align-items: center;
      }

      .notification-card {
        max-width: 100%;
      }

      .actions {
        flex-direction: column;
        gap: 10px;
      }

      .draft-btn,
      .send-btn {
        width: 100%;
      }
    }

    .draft-status {
      padding: 10px;
      margin: 10px 0;
      border-radius: 4px;
      background-color: #fff8e1;
      color: #ff8f00;
      font-size: 14px;
      display: none;
    }

    .draft-status.show {
      display: block;
      animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .scrollable-container {
      scroll-behavior: smooth;
      position: relative;
    }

    .scroll-btn {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      cursor: pointer;
      z-index: 10;
      display: none;
    }

    .scroll-up {
      top: 10px;
    }

    .scroll-down {
      bottom: 10px;
    }

    #announcementList {
      position: relative;
      transition: all 0.3s ease;
    }

    .alert-box {
      padding: 15px 20px;
      background-color: #FFC107;
      /* amber background */
      color: #00192D;
      /* deep navy text */
      border: 1px solid #00192D;
      /* matching border */
      border-radius: 8px;
      font-weight: bold;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: opacity 0.3s ease, transform 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      margin: 10px auto;
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 20px;
      line-height: 20px;
      cursor: pointer;
      color: inherit;
      margin-left: 15px;
    }

    .timeline {
      position: relative;
      padding-left: 1rem;
    }

    .timeline-date {
      padding: 0.5rem 1rem;
      font-weight: bold;
      border-radius: 4px;
      margin: 1rem 0;
      display: inline-block;
    }

    .timeline-item {
      display: flex;
      padding-bottom: 1rem;
      position: relative;
    }

    .timeline-marker {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      position: absolute;
      left: 0;
      top: 4px;
    }

    .timeline-content {
      margin-left: 1.5rem;
      padding: 0.5rem 1rem;
      background-color: rgba(0, 25, 45, 0.03);
      border-radius: 4px;
      flex-grow: 1;
    }

    .timeline-item:not(:last-child):before {
      content: '';
      position: absolute;
      left: 5px;
      top: 16px;
      height: 100%;
      width: 2px;
      background: rgba(0, 0, 0, 0.1);
    }

    .notification-list {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .notification-item {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-bottom: 15px;
      padding: 15px;
      display: flex;
      transition: all 0.3s ease;
      border-left: 4px solid #ddd;
    }

    .notification-item.sent {
      border-left-color: #4CAF50;
    }

    .notification-item.archived {
      border-left-color: #9E9E9E;
      opacity: 0.8;
    }

    .notification-item.deleting {
      transform: scale(0.9);
      opacity: 0;
    }

    .notification-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
      font-size: 18px;
    }

    .notification-icon.danger {
      background-color: #FFEBEE;
      color: #F44336;
    }

    .notification-icon.info {
      background-color: #E3F2FD;
      color: #2196F3;
    }

    .notification-icon.success {
      background-color: #E8F5E9;
      color: #4CAF50;
    }

    .notification-content {
      flex-grow: 1;
    }

    .notification-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
    }

    .notification-priority {
      font-weight: bold;
    }

    .notification-time {
      color: #757575;
      font-size: 0.9em;
    }

    .notification-recipient {
      color: #616161;
      font-size: 0.9em;
      margin-bottom: 10px;
    }

    .notification-message {
      color: #424242;
      margin: 10px 0;
      white-space: pre-wrap;
    }

    .notification-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 10px;
      font-size: 0.9em;
    }

    .notification-status {
      color: #757575;
    }

    .notification-actions {
      display: flex;
      gap: 8px;
    }

    .action-btn {
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.85em;
      transition: background 0.2s;
    }

    .archive-btn {
      background: #E0E0E0;
      color: #424242;
    }

    .archive-btn:hover {
      background: #BDBDBD;
    }

    .delete-btn {
      background: #FFEBEE;
      color: #F44336;
    }

    .delete-btn:hover {
      background: #FFCDD2;
    }

    .no-messages,
    .error-message {
      text-align: center;
      padding: 30px;
      color: #757575;
    }

    .alert {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 12px 20px;
      border-radius: 4px;
      color: white;
      z-index: 1000;
      animation: fadeIn 0.3s;
    }

    .alert-success {
      background-color: #4CAF50;
    }

    .alert-error {
      background-color: #F44336;
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
                      class="img-size-50 rounded-circle me-3" />
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="dropdown-item-title">
                      Brad Diesel
                      <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
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
                      class="img-size-50 rounded-circle me-3" />
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
                      class="img-size-50 rounded-circle me-3" />
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
                alt="User Image" />
              <span class="d-none d-md-inline"> <b>JENGO PAY</b> </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <!--begin::User Image-->
              <li class="user-header text-bg-primary">
                <img
                  src="../../dist/assets/img/user2-160x160.jpg"
                  class="rounded-circle shadow"
                  alt="User Image" />
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
            class="brand-image opacity-75 shadow" />
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
      <div> <?php include_once '../includes/sidebar1.php'; ?> </div> <!-- This is where the sidebar is inserted -->
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
                            <!-- Announcements Sent -->
                            <div class="stat-content">
                              <label class="stat-label">
                                <a href="index.html" style="color: #FFC107; background-color:#00192D;">Announcements Sent</a>
                              </label>
                              <span class="stat-value" id="sentCount">0</span>
                            </div>
                          </div>
                        </div>

                        <!-- Summary Item 2 -->
                        <div class="col-md-4 ">
                          <div class="stat-card" style="color: #FFC107; background-color:#00192D;">
                            <div class="stat-icon">
                              <i class="fas fa-clock"></i>
                            </div>
                            <!-- Drafts -->
                            <div class="stat-content">
                              <label class="stat-label">
                                <a href="#" style="color: #FFC107; background-color:#00192D;">Drafts</a>
                              </label>
                              <span class="stat-value" id="draftCount">0</span>
                            </div>
                          </div>
                        </div>

                        <!-- Summary Item 3 -->
                        <div class="col-md-4 ">
                          <div class="stat-card" style="color: #FFC107; background-color:#00192D;">
                            <div class="select-wrapper">
                              <select id="property" name="property" class="form-select stylish-select">
                                <option value="" style="color: #FFC107; background-color:#00192D;" disabled selected>Select Property</option>
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

            <div id="notificationBox" class="alert-box" style="display: none;">
              <span id="notificationMessage">🔔 This is a notification.</span>
              <button onclick="dismissNotification()" class="close-btn">&times;</button>
            </div>

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
              <button id="add_provider_btn" class="btn" style="background-color: #00192D; color: white; font-size: small;"> <i class="fa fa-bullhorn"></i>
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
                      <button class="filter-btn active" onclick="loadAnnouncements()">
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
                      <button class="filter-btn" onclick="showSentMessages()">
                        <i class="fas fa-check-circle"></i> Sent
                      </button>

                      <button class="filter-btn" onclick="showDrafts()">
                        <i class="fas fa-exclamation-circle"></i> Drafts
                      </button>

                      <button class="filter-btn" onclick="showArchivedMessages()">
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
                <div class="card-header d-flex justify-content-between align-items-center"
                  style="color: #FFC107; background-color:#00192D;">
                  <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Today's Announcements</h5>
                  <span class="badge bg-warning"><?= count($announcements) ?> Today</span>
                </div>

                <div class="card-body p-0">
                  <?php if (!empty($announcements)): ?>
                    <div class="list-group list-group-flush">
                      <?php foreach ($announcements as $item): ?>
                        <div class="list-group-item">
                          <div class="d-flex justify-content-between align-items-start">
                            <div>
                              <h6 class="mb-1 fw-bold">
                                <?= $item['priority'] == 'Urgent' ? '🚨 ' : ($item['priority'] == 'Reminder' ? '⏰ ' : '📢 ') ?>
                                <?= htmlspecialchars($item['recipient'] ?: 'All Users') ?>
                              </h6>
                              <p class="mb-1"><?= htmlspecialchars($item['message']) ?></p>
                            </div>
                            <small class="text-muted">
                              <?= date('h:i A', strtotime($item['created_at'])) ?>
                            </small>
                          </div>
                          <div class="mt-2">
                            <span class="badge
                              <?= $item['priority'] == 'Urgent' ? 'bg-warning text-dark' : ($item['priority'] == 'Reminder' ? 'bg-success' : 'bg-info') ?>">
                              <?= htmlspecialchars($item['priority']) ?>
                            </span>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <div class="text-center p-4">
                      <i class="far fa-bell-slash fa-2x mb-2" style="color: #6c757d;"></i>
                      <p class="text-muted">No announcements for today</p>
                    </div>
                  <?php endif; ?>
                  <!-- </div> -->
                  <!-- </div> -->
                  <!-- </div> -->
                  <!-- </div> -->
                  <!-- </div> -->

                  <!-- </div> -->
                  <!-- <button class="quick-action-btn">
            <i class="fas fa-tools"></i> Report Maintenance
        </button> -->

                </div>
              </div>

              <!-- Notification Trends Card -->
              <!-- <div class="card">
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
    </div> -->

              <div class="card mb-4">
                <div class="card-header" style="color: #FFC107; background-color:#00192D;">
                  <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <!-- Pin/Unpin -->
                    <a href="#" class="btn btn-outline-warning w-100 py-2 mb-2">
                      <i class="fas fa-thumbtack me-2"></i> Pin Announcement
                    </a>

                    <!-- Schedule -->
                    <a href="#" class="btn btn-outline-warning w-100 py-2 mb-2">
                      <i class="far fa-clock me-2"></i> Schedule Post
                    </a>

                    <!-- Edit Drafts -->
                    <a href="#" class="btn btn-outline-warning w-100 py-2 mb-2">
                      <i class="fas fa-edit me-2"></i> Edit Drafts
                    </a>
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
        <div id="close-overlay-btn" class="close-btn"> <b> &times;</b> </div>

      </div>
      <div class="card-body">
        <p class="card-text">
          All Rent Payments should be made before 24/01/17. Anyone who feels that they might not
        <p class="card-text announcement">
          All Rent Payments should be made before 24/01/17. Anyone who feels that they might not
          be able to complete their dues on 24/01/17 should communicate to management before the due date.
          Else, you face an eviction risk if you fail to pay your dues before the said date without a reasonable reason</p>

        <p class="timestamp" id="timestamp"></p> <!-- Timestamp -->


        <div class="Recipients border-top border-gray ">
          <p><strong>Recipients|</strong> All Employees</p>
        </div>

      </div>
      <div class="card-footer view_announcement d-flex">
        <button class="btn btn-danger btn-sm">Delete</button>
        <button class="btn resend btn-sm">Resend</button>
      </div>
    </div>
  </div>

  <!-- End view announcement -->


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
            <label for="property">Select Recipient*</label>
            <select id="property" name="recipient" class="form-select" required>
              <option value="" disabled selected>Select Recipient*</option>
              <option value="Manucho">Manucho</option>
              <option value="Ben 10">Ben 10</option>
              <option value="Alpha">Alpha</option>
              <option value="All Tenants">All Tenants</option>
            </select>
          </div>

          <div class="form-group">
            <label for="priority">Priority*</label>
            <select id="priority" name="priority" class="form-select" required>
              <option value="" disabled selected>Select Priority*</option>
              <option value="Normal">Normal</option>
              <option value="Urgent">Urgent</option>
              <option value="Reminder">Reminder</option>
            </select>
          </div>

          <div class="form-group">
            <label for="notes">Message*</label>
            <textarea id="notes" name="message" class="form-textarea" placeholder="Type your announcement here..." required></textarea>
          </div>

          <div class="draft-status" id="draftStatus">
            <!-- Draft saved message will appear here -->
          </div>

          <!-- Optional: add file upload support -->
          <!--
        <div class="form-group">
          <label for="fileInput">Attach Files</label>
          <input type="file" name="files[]" id="fileInput" multiple class="form-control">
        </div>
        -->

          <div class="actions d-flex justify-content-end">
            <button type="button" class="draft-btn" id="saveDraftBtn">Save Draft</button>
            <button type="button" class="draft-btn text-danger btn" onclick="closenotificationPopup()">Cancel</button>
            <!-- Send Announcement Button -->
<button type="button" class="send-btn btn" onclick="sendAnnouncement()">Send Announcement</button>
          </div>
        </form>
      </div>
    </div>
  </div>














  <!-- <script>
function saveAsDraft() {
  const recipient = document.getElementById('property').value;
  const priority = document.getElementById('priority').value;
  const message = document.getElementById('notes').value;

  if (!recipient && !priority && !message) {
    return; // Don't save empty drafts
  }

  fetch('save_draft.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      recipient: recipient,
      priority: priority,
      message: message
    })
  })
  .then(response => response.json())
  .then(data => {
    document.getElementById('draftStatus').innerText = 'Draft saved!';
  })
  .catch(error => {
    console.error('Error saving draft:', error);
  });
}
</script> -->

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('saveDraftBtn').addEventListener('click', function() {
        const recipient = document.getElementById('property').value;
        const priority = document.getElementById('priority').value;
        const message = document.getElementById('notes').value;

        console.log("Recipient:", recipient);
console.log("Priority:", priority);
console.log("Message:", message);

        // Don't save empty form
        if (!recipient && !priority && !message) {
          alert('Cannot save empty draft.');
          return;
        }

        fetch('save_draft.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              recipient: recipient,
              priority: priority,
              message: message
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Draft saved successfully!');
            } else {
              alert('Failed to save draft.');
            }
          })
          .catch(error => {
            console.error('Error saving draft:', error);
            alert('An error occurred while saving the draft.');
          });
      });
    });
  </script>

  <!-- <script>
document.addEventListener('DOMContentLoaded', function () {
  document.body.addEventListener('click', function (e) {
    if (e.target.closest('.archive-btn')) {
      const btn = e.target.closest('.archive-btn');
      const id = btn.getAttribute('data-id');

      if (confirm('Are you sure you want to archive this announcement?')) {
        archiveAnnouncement(id);
      }
    }
  });
});

function archiveAnnouncement(id) {
  fetch('archive_announcement.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: id })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Announcement archived.');
      // Optionally reload list (you can detect which view is active)
      loadAnnouncements(); // or showDrafts(), showSentMessages(), etc.
    } else {
      alert('Failed to archive.');
    }
  })
  .catch(err => {
    console.error('Error archiving:', err);
    alert('An error occurred.');
  });
}
</script> -->

  <script>
    function showArchivedMessages() {
      fetch('get_archived_messages.php')
        .then(response => response.json())
        .then(data => {
          const container = document.getElementById('announcementList');
          container.innerHTML = '';

          if (!data || data.length === 0) {
            container.innerHTML = '<p>No archived announcements found.</p>';
            return;
          }

          data.forEach(item => {
            const iconClass = getIconByPriority(item.priority);
            const html = `
          <div class="notification-item unread" id="announcement-${item.id}">
            <div class="notification-icon ${iconClass}">
              <i class="fas ${getIconSymbol(item.priority)}"></i>
            </div>
            <div class="notification-content">
              <div class="notification-title">
                <span>${item.priority} Archived to ${item.recipient}</span>
                <span class="notification-time">${formatTime(item.created_at)}</span>
              </div>
              <p class="notification-message">${item.message}</p>
              <div class="notification-actions">
                <span class="badge bg-secondary text-light" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-archive"></i> Archived
                </span>
                <button class="action-btn restore-btn" data-id="${item.id}">
                  <i class="fas fa-undo"></i> Restore
                </button>
                <button class="action-btn delete-btn" data-id="${item.id}">
                  <i class="fas fa-trash-alt"></i> Delete
                </button>
              </div>
            </div>
          </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
          });
        })
        .catch(error => {
          console.error('Error fetching archived messages:', error);
        });
    }

    // Add event listeners to Restore and Delete buttons (for archived view)
    document.addEventListener('click', function(e) {
      const restoreBtn = e.target.closest('.restore-btn');
      const deleteBtn = e.target.closest('.delete-btn');

      if (restoreBtn) {
        const id = restoreBtn.dataset.id;
        if (confirm('Restore this archived announcement?')) {
          restoreAnnouncement(id);
        }
      }

      if (deleteBtn) {
        const id = deleteBtn.dataset.id;
        if (confirm('Permanently delete this archived announcement?')) {
          deleteAnnouncement(id);
        }
      }
    });

    function restoreAnnouncement(id) {
      fetch('restore_message.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showAlert('Announcement restored successfully', 'success');
            showArchivedMessages(); // Reload the archived list
          } else {
            showAlert('Failed to restore: ' + (data.error || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Restore error:', error);
          showAlert('Error restoring announcement', 'error');
        });
    }

    function deleteAnnouncement(id) {
      fetch('delete_message.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showAlert('Announcement deleted successfully', 'success');
            showArchivedMessages(); // Refresh archived list
          }
          else {
            showAlert('Failed to delete: ' + (data.error || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Delete error:', error);
          showAlert('Error deleting announcement', 'error');
        });
    }

    function showAlert(message, type = 'info') {
      alert(message); // Replace with toast/snackbar if needed
    }

    function getIconByPriority(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'danger';
        case 'reminder':
          return 'info';
        case 'normal':
          return 'success';
        default:
          return 'info';
      }
    }

    function getIconSymbol(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'fa-exclamation-circle';
        case 'reminder':
          return 'fa-info-circle';
        case 'normal':
          return 'fa-check-circle';
        default:
          return 'fa-info-circle';
      }
    }

    function formatTime(datetime) {
      const d = new Date(datetime);
      return d.toLocaleString();
    }
  </script>


  <script>
    function getIconByPriority(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'danger';
        case 'reminder':
          return 'info';
        case 'normal':
          return 'success';
        default:
          return 'info';
      }
    }

    function getIconSymbol(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'fa-exclamation-circle';
        case 'reminder':
          return 'fa-info-circle';
        case 'normal':
          return 'fa-check-circle';
        default:
          return 'fa-info-circle';
      }
    }

    function formatTime(datetime) {
      const d = new Date(datetime);
      return d.toLocaleString();
    }
  </script>


  <script>
    function showDrafts() {
      fetch('get_drafts.php')
        .then(response => response.json())
        .then(data => {
          const container = document.getElementById('announcementList');
          container.innerHTML = '';

          if (data.length === 0) {
            container.innerHTML = '<p>No drafts found.</p>';
            return;
          }

          data.forEach(item => {
            const iconClass = getIconByPriority(item.priority);
            const html = `
          <div class="notification-item unread" id="announcement-${item.id}">
            <div class="notification-icon ${iconClass}">
              <i class="fas ${getIconSymbol(item.priority)}"></i>
            </div>
            <div class="notification-content">
              <div class="notification-title">
                <span>${item.priority} Draft to ${item.recipient}</span>
                <span class="notification-time">${formatTime(item.created_at)}</span>
              </div>
              <p class="notification-message">${item.message}</p>
              <div class="notification-actions">
                <button class="action-btn edit-btn" data-id="${item.id}">
                  <i class="fas fa-pencil-alt"></i> Edit
                </button>
                <button class="action-btn delete-btn" data-id="${item.id}">
                  <i class="fas fa-trash-alt"></i> Delete
                </button>
              </div>
            </div>
          </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
          });

          // Add event listeners for drafts
          document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
              const messageId = this.getAttribute('data-id');
              editSentMessage(messageId);
            });
          });

          document.querySelectorAll('.archive-btn').forEach(button => {
            button.addEventListener('click', function() {
              archiveSentMessage(this.getAttribute('data-id'));
            });
          });

          document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
              deleteDraft(this.getAttribute('data-id'));
            });
          });
        })
        .catch(error => console.error('Error loading drafts:', error));
    }

    function deleteDraft(draftId) {
      if (!confirm('Are you sure you want to permanently delete this draft? This action cannot be undone.')) {
        return;
      }

      fetch('delete_draft.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${draftId}`
      })
    }
    //   .then(response => response.json())
    //   .then(data => {
    //     if (data.success) {
    //       document.getElementById(`announcement-${draftId}`)?.remove();
    //       showAlert('Draft deleted successfully', 'success');
    //     } else {
    //       showAlert('Failed to delete draft: ' + (data.error || data.message), 'error');
    //     }
    //   })
    //   .catch(error => {
    //     console.error('Error deleting draft:', error);
    //     showAlert('An error occurred while deleting the draft', 'error');
    //   });
    // }
  </script>


  <script>
    document.addEventListener('click', function(e) {
      const editBtn = e.target.closest('.edit-btn');
      if (editBtn) {
        const id = editBtn.dataset.id;
        editDraft(id);
      }
    });
  </script>

  <script>
    function editDraft(id) {
      console.log('Editing draft with ID:', id); // for testing
      window.location.href = `edit_draft.php?id=${id}`;
    }
  </script>

  <script>
    function showNotification(message, type = 'success') {
      const box = document.getElementById('notificationBox');
      const messageSpan = document.getElementById('notificationMessage');

      messageSpan.textContent = message;

      // Set styles based on type
      if (type === 'success') {
        box.style.backgroundColor = '#d4edda';
        box.style.color = '#155724';
        box.style.borderColor = '#c3e6cb';
      } else {
        box.style.backgroundColor = '#f8d7da';
        box.style.color = '#721c24';
        box.style.borderColor = '#f5c6cb';
      }

      box.style.display = 'flex';

      // Auto-dismiss after 3 seconds
      setTimeout(() => {
        if (box.style.display !== 'none') {
          dismissNotification();
        }
      }, 3000);
    }

    function dismissNotification() {
      const box = document.getElementById('notificationBox');
      box.style.display = 'none';
    }
  </script>

  <script>
    // Drafts function
    // function showDrafts() {
    //   fetch('get_drafts.php')
    //     .then(response => response.json())
    //     .then(data => {
    //       const container = document.getElementById('announcementList');
    //       container.innerHTML = '';

    //       if (data.length === 0) {
    //         container.innerHTML = '<p>No draft announcements found.</p>';
    //         return;
    //       }

    //       data.forEach(item => {
    //         const iconClass = getIconByPriority(item.priority);
    //         const html = `
    //           <div class="notification-item unread" id="announcement-${item.id}">
    //             <div class="notification-icon ${iconClass}">
    //               <i class="fas ${getIconSymbol(item.priority)}"></i>
    //             </div>
    //             <div class="notification-content">
    //               <div class="notification-title">
    //                 <span>${item.priority} Draft to ${item.recipient}</span>
    //                 <span class="notification-time">${formatTime(item.created_at)}</span>
    //               </div>
    //               <p class="notification-message">${item.message}</p>
    //               <div class="notification-actions">
    //                 <button class="action-btn edit-btn" data-id="${item.id}">
    //                   <i class="fas fa-pencil-alt"></i> Edit
    //                 </button>
    //                 <button class="action-btn archive-btn" data-id="${item.id}">
    //                   <i class="fas fa-archive"></i> Archive
    //                 </button>
    //                 <button class="action-btn delete-btn" data-id="${item.id}">
    //                   <i class="fas fa-trash-alt"></i> Delete
    //                 </button>
    //               </div>
    //             </div>
    //           </div>
    //         `;
    //         container.insertAdjacentHTML('beforeend', html);
    //       });

    //       // Enable scrolling if content exceeds container height
    //       enableScrolling();
    //     })
    //     .catch(error => console.error('Error loading drafts:', error));
    // }

    // Shared utility functions
    function getIconByPriority(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'danger';
        case 'reminder':
          return 'info';
        case 'normal':
          return 'success';
        default:
          return 'info';
      }
    }

    function getIconSymbol(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'fa-exclamation-circle';
        case 'reminder':
          return 'fa-info-circle';
        case 'normal':
          return 'fa-check-circle';
        default:
          return 'fa-info-circle';
      }
    }

    function formatTime(datetime) {
      const d = new Date(datetime);
      return d.toLocaleString();
    }

    // function enableScrolling() {
    //     const container = document.getElementById('announcementList');
    //     const maxHeight = 500;

    //     if (container.scrollHeight > maxHeight) {
    //         container.style.maxHeight = `${maxHeight}px`;
    //         container.style.overflowY = 'auto';
    //         container.classList.add('scrollable-container');
    //         addScrollButtons(container);
    //     }
    // }

    // function addScrollButtons(container) {
    //     const scrollUp = document.createElement('button');
    //     scrollUp.className = 'scroll-btn scroll-up';
    //     scrollUp.innerHTML = '<i class="fas fa-chevron-up"></i>';
    //     scrollUp.onclick = () => container.scrollBy({ top: -100, behavior: 'smooth' });

    //     const scrollDown = document.createElement('button');
    //     scrollDown.className = 'scroll-btn scroll-down';
    //     scrollDown.innerHTML = '<i class="fas fa-chevron-down"></i>';
    //     scrollDown.onclick = () => container.scrollBy({ top: 100, behavior: 'smooth' });

    //     container.parentNode.insertBefore(scrollUp, container);
    //     container.parentNode.appendChild(scrollDown);

    //     container.addEventListener('scroll', () => {
    //         scrollUp.style.display = container.scrollTop > 0 ? 'block' : 'none';
    //         scrollDown.style.display = container.scrollTop < container.scrollHeight - container.clientHeight ? 'block' : 'none';
    //     });
    // }


    // Event listeners
    // document.addEventListener('click', function(e) {
    //     const archiveBtn = e.target.closest('.archive-btn');
    //     const deleteBtn = e.target.closest('.delete-btn');
    //     const editBtn = e.target.closest('.edit-btn');

    //     if (archiveBtn) {
    //         const id = archiveBtn.dataset.id;
    //         if (confirm('Archive this announcement?')) archiveAnnouncement(id);
    //     }

    //     // if (deleteBtn) {
    //     //     const id = deleteBtn.dataset.id;
    //     //     if (confirm('Delete this announcement?')) deleteAnnouncement(id);
    //     // }

    //     if (editBtn) {
    //         const id = editBtn.dataset.id;
    //         editDraft(id);
    //     }
    // });

    // function archiveAnnouncement(id) {
    //     console.log('Archiving announcement:', id);
    //     // Implement actual archive functionality
    // }

    // function deleteAnnouncement(id) {
    //     console.log('Deleting announcement:', id);
    //     // Implement actual delete functionality
    // }

    // function editDraft(id) {
    //     console.log('Editing draft:', id);
    //     // Implement actual edit functionality
    // }

    // // Initialize with drafts view by default
    // document.addEventListener('DOMContentLoaded', showDrafts);
    // 
  </script>



  <script>
    function loadDraft(draftId) {
      fetch(`get_draft_by_id.php?id=${draftId}`)
        .then(response => response.json())
        .then(data => {
          document.getElementById('property').value = data.recipient;
          document.getElementById('priority').value = data.priority;
          document.getElementById('notes').value = data.message;

          // Show the popup if it's hidden
          document.getElementById("notificationPopup").style.display = "block";
        })
        .catch(error => {
          console.error('Error loading draft:', error);
        });
    }
  </script>



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
      saveAsDraft(); // Save before closing
      document.getElementById("notificationPopup").style.display = "none";
    }
  </script>

  <script>
    const form = document.getElementById('notificationForm');
    const draftKey = 'notification_draft';

    // Load draft if exists
    document.addEventListener('DOMContentLoaded', () => {
      const draft = JSON.parse(localStorage.getItem(draftKey));
      if (draft) {
        if (draft.recipient) form.recipient.value = draft.recipient;
        if (draft.priority) form.priority.value = draft.priority;
        if (draft.message) form.message.value = draft.message;
        document.getElementById('draftStatus').textContent = 'Draft loaded';
      }
    });

    // Auto-save to localStorage on input change
    form.addEventListener('input', () => {
      const data = {
        recipient: form.recipient.value,
        priority: form.priority.value,
        message: form.message.value,
      };
      localStorage.setItem(draftKey, JSON.stringify(data));
      document.getElementById('draftStatus').textContent = 'Draft saved';
    });

    // Save draft manually (on "Save Draft" button)
    function saveAsDraft() {
      const data = {
        recipient: form.recipient.value,
        priority: form.priority.value,
        message: form.message.value,
      };
      localStorage.setItem(draftKey, JSON.stringify(data));
      document.getElementById('draftStatus').textContent = 'Draft manually saved';
    }

    // Optional: clear draft after successful submission
    form.addEventListener('submit', () => {
      localStorage.removeItem(draftKey);
    });

    // Optional: save on popup close
    function closenotificationPopup() {
      saveAsDraft(); // Automatically saves before closing
      document.getElementById('notificationPopup').style.display = 'none';
    }

    // Make closenotificationPopup global
    window.closenotificationPopup = closenotificationPopup;
    window.saveAsDraft = saveAsDraft;
  </script>
  <script>
    function showSentMessages() {
      fetch('get_sent_messages.php')
        .then(response => {
          if (!response.ok) throw new Error('Network response was not ok');
          return response.json();
        })
        .then(data => {
          const container = document.getElementById('announcementList');
          container.innerHTML = '';

          if (data.length === 0) {
            container.innerHTML = '<div class="no-messages">No announcements found</div>';
            return;
          }

          data.forEach(item => {
            const announcement = createAnnouncementElement(item);
            container.appendChild(announcement);
          });

          addEventListeners();
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('announcementList').innerHTML = `
        <div class="error-message">
          Failed to load announcements. Please try again.
        </div>
      `;
        });
    }

    function createAnnouncementElement(item) {
      const announcement = document.createElement('div');
      announcement.className = `notification-item ${item.status.toLowerCase()}`;
      announcement.id = `announcement-${item.id}`;

      const iconClass = getIconByPriority(item.priority);
      const iconSymbol = getIconSymbol(item.priority);

      announcement.innerHTML = `
    <div class="notification-icon ${iconClass}">
      <i class="fas ${iconSymbol}"></i>
    </div>
    <div class="notification-content">
      <div class="notification-header">
        <span class="notification-priority">${capitalize(item.priority)} Priority</span>
        <span class="notification-time">${formatTime(item.created_at)}</span>
      </div>
      <div class="notification-recipient">To: ${item.recipient || 'All Tenants'}</div>
      <p class="notification-message">${item.message}</p>
      <div class="notification-footer">
        <span class="notification-status">Status: ${item.status}</span>
        <div class="notification-actions">
          ${item.status !== 'Archived' ? `
          <button class="action-btn archive-btn" data-id="${item.id}">
            <i class="fas fa-archive"></i> Archive
          </button>
          ` : ''}
          <button class="action-btn delete-btn" data-id="${item.id}">
            <i class="fas fa-trash-alt"></i> Delete
          </button>
        </div>
      </div>
    </div>
  `;

      return announcement;
    }

    function addEventListeners() {
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', handleDelete);
      });

      document.querySelectorAll('.archive-btn').forEach(btn => {
        btn.addEventListener('click', handleArchive);
      });
    }

    function handleDelete(e) {
      const id = e.currentTarget.getAttribute('data-id');
      if (confirm('Are you sure you want to delete this announcement?')) {
        deleteAnnouncement(id);
      }
    }

    function handleArchive(e) {
      const id = e.currentTarget.getAttribute('data-id');
      if (confirm('Are you sure you want to archive this announcement?')) {
        archiveAnnouncement(id);
      }
    }

    function deleteAnnouncement(id) {
      fetch('delete_sent_message.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id=${id}`
        })
        .then(response => {
          if (!response.ok) throw new Error('Delete failed');
          return response.json();
        })
        .then(data => {
          if (data.success) {
            const element = document.getElementById(`announcement-${id}`);
            if (element) {
              element.classList.add('deleting');
              setTimeout(() => element.remove(), 300);
            }
            showAlert('Announcement deleted successfully', 'success');
          } else {
            throw new Error(data.error || 'Unknown error');
          }
        })
        .catch(error => {
          console.error('Delete error:', error);
          showAlert(`Delete failed: ${error.message}`, 'error');
        });
    }

    function archiveAnnouncement(id) {
      fetch('archive_sent_message.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id=${id}`
        })
        .then(response => {
          if (!response.ok) throw new Error('Archive failed');
          return response.json();
        })
        .then(data => {
          if (data.success) {
            showAlert('Announcement archived successfully', 'success');
            showSentMessages(); // Refresh the list
          } else {
            throw new Error(data.error || 'Unknown error');
          }
        })
        .catch(error => {
          console.error('Archive error:', error);
          showAlert(`Archive failed: ${error.message}`, 'error');
        });
    }

    // Utility functions
    function getIconByPriority(priority) {
      const map = {
        'urgent': 'danger',
        'reminder': 'info',
        'normal': 'success'
      };
      return map[priority.toLowerCase()] || 'info';
    }

    function getIconSymbol(priority) {
      const map = {
        'urgent': 'fa-exclamation-circle',
        'reminder': 'fa-info-circle',
        'normal': 'fa-check-circle'
      };
      return map[priority.toLowerCase()] || 'fa-info-circle';
    }

    function formatTime(datetime) {
      if (!datetime) return '';
      const date = new Date(datetime);
      return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    }

    function capitalize(str) {
      if (!str) return '';
      return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

    function showAlert(message, type) {
      const alert = document.createElement('div');
      alert.className = `alert alert-${type}`;
      alert.textContent = message;
      document.body.appendChild(alert);

      setTimeout(() => {
        alert.remove();
      }, 3000);
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', showSentMessages);

    // Optional: Add refresh button functionality
    document.getElementById('refreshBtn')?.addEventListener('click', showSentMessages);
  </script>


  <!-- <script>
  document.addEventListener('DOMContentLoaded', function () {
  document.body.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.delete-btn');
    if (deleteBtn) {
      const id = deleteBtn.getAttribute('data-id');
      if (confirm('Are you sure you want to delete this announcement?')) {
        deleteAnnouncement(id);
      }
    }
  });
});

function deleteAnnouncement(id) {
  fetch('delete_announcement.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById(`announcement-${id}`).remove();
        alert('Announcement deleted.');
      } else {
        alert('Failed to delete announcement.');
      }
    })
    .catch(error => {
      console.error('Delete error:', error);
      alert('An error occurred while deleting.');
    });
}

</script> -->

  <script>
    function getIconByPriority(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'danger';
        case 'reminder':
          return 'info';
        case 'normal':
          return 'success';
        default:
          return 'info';
      }
    }

    function getIconSymbol(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'fa-exclamation-circle';
        case 'reminder':
          return 'fa-info-circle';
        case 'normal':
          return 'fa-check-circle';
        default:
          return 'fa-info-circle';
      }
    }

    function formatTime(datetime) {
      const d = new Date(datetime);
      return d.toLocaleString();
    }
  </script>

<script>
// Change the function name from sendMessage to sendAnnouncement
function sendAnnouncement() {
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
      alert("✅ Announcement sent successfully!");

      document.getElementById('notes').value = '';
      document.getElementById('property').value = '';
      document.getElementById('priority').value = 'Normal';

      if (typeof closenotificationPopup === 'function') {
        closenotificationPopup();
      }
    }
  })
  .catch(error => {
    console.error('Fetch error:', error);
    alert("❌ Something went wrong. Please try again.");
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
              const iconClass = getIconByPriority(item.priority);
              let actions = '';

              if (item.status === 'Sent') {
                actions = `
              <button class="action-btn sent-label" disabled>
                <i class="fas fa-paper-plane"></i> Sent
              </button>
              <button class="action-btn archive-btn" data-id="${item.id}">
                <i class="fas fa-archive"></i> Archive
              </button>
            `;
              } else if (item.status === 'Draft') {
                actions = `
              <span class="badge bg-warning text-dark" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-pencil-alt"></i> Draft
              </span>
              <button class="action-btn edit-btn" data-id="${item.id}">
                <i class="fas fa-edit"></i> Edit
              </button>
            `;
              } else if (item.status === 'Archived') {
                actions = `
              <span class="badge bg-secondary" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-archive"></i> Archived
              </span>
              <button class="action-btn restore-btn" data-id="${item.id}">
                <i class="fas fa-undo"></i> Restore
              </button>
            `;
              }

              const html = `
            <div class="notification-item unread" id="announcement-${item.id}">
              <div class="notification-icon ${iconClass}">
                <i class="fas ${getIconSymbol(item.priority)}"></i>
              </div>
              <div class="notification-content">
                <div class="notification-title">
                  <span>${item.priority} ${item.status} to ${item.recipient}</span>
                  <span class="notification-time">${formatTime(item.created_at)}</span>
                </div>
                <p class="notification-message">${item.message}</p>
                <div class="notification-actions">
                  ${actions}
                  <button class="action-btn delete-btn" data-id="${item.id}">
                    <i class="fas fa-trash-alt"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          `;

              container.insertAdjacentHTML('beforeend', html);
            });

            enableScrolling();
          }
        })
        .catch(error => console.error('Error loading announcements:', error));
    }

    function getIconByPriority(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'danger';
        case 'reminder':
          return 'info';
        case 'normal':
          return 'success';
        default:
          return 'info';
      }
    }

    function getIconSymbol(priority) {
      switch (priority.toLowerCase()) {
        case 'urgent':
          return 'fa-exclamation-circle';
        case 'reminder':
          return 'fa-info-circle';
        case 'normal':
          return 'fa-check-circle';
        default:
          return 'fa-info-circle';
      }
    }

    function formatTime(datetime) {
      const d = new Date(datetime);
      return d.toLocaleString();
    }

    function showAlert(message, type = 'info') {
      alert(message); // You can customize with toast/snackbar if needed
    }

    // document.addEventListener('DOMContentLoaded', loadAnnouncements);

    // document.addEventListener('click', function (e) {
    //   const archiveBtn = e.target.closest('.archive-btn');
    //   const deleteBtn = e.target.closest('.delete-btn');
    //   const restoreBtn = e.target.closest('.restore-btn');
    //   const editBtn = e.target.closest('.edit-btn');

    // if (archiveBtn) {
    //   const id = archiveBtn.dataset.id;
    //   if (confirm('Archive this announcement?')) archiveAnnouncement(id);
    // }

    // if (deleteBtn) {
    //   const id = deleteBtn.dataset.id;
    //   if (confirm('Delete this announcement?')) deleteAnnouncement(id);
    // }

    // if (restoreBtn) {
    //   const id = restoreBtn.dataset.id;
    //   if (confirm('Restore this announcement?')) restoreAnnouncement(id);
    // }

    // if (editBtn) {
    //   const id = editBtn.dataset.id;
    //   editDraft(id);
    // }


    function archiveAnnouncement(id) {
      fetch('archive_message.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showAlert('Announcement archived successfully', 'success');
            loadAnnouncements();
          } else {
            showAlert('Failed to archive: ' + (data.error || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Archive error:', error);
          showAlert('Error archiving announcement', 'error');
        });
    }

    function deleteAnnouncement(id) {
      fetch('delete_message.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showAlert('Announcement deleted successfully', 'success');
            loadAnnouncements();
          } else {
            showAlert('Failed to delete: ' + (data.error || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Delete error:', error);
          showAlert('Error deleting announcement', 'error');
        });
    }

    function restoreAnnouncement(id) {
      fetch('restore_message.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showAlert('Announcement restored successfully', 'success');
            loadAnnouncements();
          } else {
            showAlert('Failed to restore: ' + (data.error || 'Unknown error'), 'error');
          }
        })
        .catch(error => {
          console.error('Restore error:', error);
          showAlert('Error restoring announcement', 'error');
        });
    }

    function editDraft(id) {
      // Redirect to edit draft page
      window.location.href = `edit_draft.php?id=${id}`;
    }
  </script>

  <script>
    document.addEventListener('click', function(e) {
      const archiveBtn = e.target.closest('.archive-btn');
      const deleteBtn = e.target.closest('.delete-btn');

      // if (archiveBtn) {
      //   const id = archiveBtn.dataset.id;
      //   if (confirm('Archive this announcement?')) archiveAnnouncement(id);
      // }

      // if (deleteBtn) {
      //   const id = deleteBtn.dataset.id;
      //   if (confirm('Delete this announcement?')) deleteAnnouncement(id);
      // }
    });
  </script>

  <script>
    function enableScrolling() {
      const container = document.getElementById('announcementList');
      const maxHeight = 500; // Set your desired max height

      if (container.scrollHeight > maxHeight) {
        container.style.maxHeight = `${maxHeight}px`;
        container.style.overflowY = 'auto';
        container.classList.add('scrollable-container');
        addScrollButtons(container);
      }
    }

    function addScrollButtons(container) {
      // Create scroll up button
      const scrollUp = document.createElement('button');
      scrollUp.className = 'scroll-btn scroll-up';
      scrollUp.innerHTML = '<i class="fas fa-chevron-up"></i>';
      scrollUp.onclick = () => container.scrollBy({
        top: -100,
        behavior: 'smooth'
      });

      // Create scroll down button
      const scrollDown = document.createElement('button');
      scrollDown.className = 'scroll-btn scroll-down';
      scrollDown.innerHTML = '<i class="fas fa-chevron-down"></i>';
      scrollDown.onclick = () => container.scrollBy({
        top: 100,
        behavior: 'smooth'
      });

      // Add buttons to container
      container.parentNode.insertBefore(scrollUp, container);
      container.parentNode.appendChild(scrollDown);

      // Show/hide buttons based on scroll position
      container.addEventListener('scroll', () => {
        scrollUp.style.display = container.scrollTop > 0 ? 'block' : 'none';
        scrollDown.style.display = container.scrollTop < container.scrollHeight - container.clientHeight ? 'block' : 'none';
      });
    }
  </script>

  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
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
    crossorigin="anonymous"></script>
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
    document.addEventListener('DOMContentLoaded', function() {
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
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".nav-item > .nav-link").forEach((navLink) => {
        navLink.addEventListener("click", function(e) {
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
    crossorigin="anonymous"></script>
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
      series: [{
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
        series: [{
          data
        }],
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
              formatter: function(seriesName) {
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

  <script>
function fetchAnnouncementCounts() {
  fetch('get_announcement_counts.php')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('sentCount').textContent = data.sent;
        document.getElementById('draftCount').textContent = data.drafts;
      } else {
        console.error('Failed to load counts:', data.error);
      }
    })
    .catch(error => {
      console.error('Error fetching counts:', error);
    });
}

document.addEventListener('DOMContentLoaded', () => {
  fetchAnnouncementCounts(); // initial call
  setInterval(fetchAnnouncementCounts, 2000); // refresh every 2 seconds
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

  <!-- <script>
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
                                        <i class="fas fa-archive" data-id="${item.id}"></i> Archive
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
</script> -->

  <!--end::Script-->
</body>
<!--end::Body-->

</html>