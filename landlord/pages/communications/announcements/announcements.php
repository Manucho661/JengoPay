<?php session_start() ?>
<?php
include '../../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $recipient = $_POST['recipient'] ?? '';
  $priority = $_POST['priority'] ?? 'Normal';
  $message = $_POST['message'] ?? '';
  $created_at = date('Y-m-d H:i:s');
  $updated_at = date('Y-m-d H:i:s');

  // Validate required fields
  if (empty($recipient) || empty($message)) {
    echo '<div class="alert alert-danger">Recipient and message are required fields.</div>';
    exit();
  }

  try {
    // Insert announcement into database
    $stmt = $pdo->prepare("INSERT INTO announcements (recipient, priority, message, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$recipient, $priority, $message, $created_at, $updated_at]);
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

        // Validate file type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($fileType, $allowedTypes)) {
          continue; // Skip invalid file types
        }

        if ($fileSize > $maxSize) {
          continue; // Skip files that are too large
        }

        // Move the file to the upload directory
        if (move_uploaded_file($tmp_name, $filePath)) {
          // Insert file info into database
          $stmt = $pdo->prepare("INSERT INTO announcement_attachments (announcement_id, file_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
          $stmt->execute([$announcement_id, $fileName, $filePath, $fileType, $fileSize]);
        }
      }
    }

    // Show success notification
    echo '<div class="announcement-notification" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background: #4CAF50; color: white; padding: 15px 20px; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 1000; display: flex; align-items: center; max-width: 80%;">
        <span style="font-size: 24px; margin-right: 10px;">✓</span>
        <p style="margin: 0;">Announcement sent successfully!</p>
      </div>
      <script>
      setTimeout(() => {
          document.querySelector(".announcement-notification").style.opacity = "0";
          document.querySelector(".announcement-notification").style.transition = "opacity 0.5s ease";
          setTimeout(() => {
              document.querySelector(".announcement-notification").remove();
              window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";
          }, 500);
      }, 3000);
      </script>';

    // Optional: Send email notification
    // sendAnnouncementNotification($recipient, $priority, $message);

    exit();
  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo '<div class="alert alert-danger">Error saving announcement. Please try again.</div>';
    exit();
  }
}

// Optional email notification function
/*
function sendAnnouncementNotification($recipient, $priority, $message) {
    $to = 'admin@example.com';
    $subject = "New $priority Announcement for $recipient";
    $headers = "From: announcements@yourdomain.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $email_body = "New announcement:\n\n";
    $email_body .= "Building: $recipient\n";
    $email_body .= "Priority: $priority\n";
    $email_body .= "Message:\n$message\n";

    mail($to, $subject, $email_body, $headers);
}
*/
?>

<?php
include '../../db/connect.php';

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

<?php
// Example: Fetch buildings from database (if not already done)
require '../../db/connect.php'; // Adjust path as needed

try {
  $stmt = $pdo->query("SELECT id, building_name FROM buildings ORDER BY building_name ASC");
  $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error fetching buildings: " . $e->getMessage();
  $buildings = [];
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
  <link rel="stylesheet" href="/jengoPay/landlord/assets/main.css" />

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
  <link rel="stylesheet" href="/Jengopay/landlord/pages/communications/announcements/css/announcements.css">


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
      max-width: 100%;
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
      max-width: 100%;
      margin: 0 auto;
      padding: 20px;
    }

    .notification-item {
      padding: 1.25rem 2rem;
      border-bottom: 1px solid var(--gray-light);
      display: flex;
      gap: 1rem;
      transition: all 0.2s;
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-bottom: 15px;
      padding: 15px;
      border-left: 4px solid #FFC107;
    }

    .notification-item.unread {
      background: #f8fafc;
    }

    .notification-item:hover {
      background: #f1f5f9;
    }

    .notification-item.sent {
      border-left-color: #FFC107;
    }

    .notification-item.archived {
      border-left-color: #FFC107;
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
      flex-shrink: 0;
      margin-right: 15px;
      font-size: 18px;
    }

    .notification-icon.info {
      background: var(--info);
      color: white;
      background-color: #E3F2FD;
      color: #2196F3;
    }

    .notification-icon.success {
      background: var(--success);
      color: white;
      background-color: #E8F5E9;
      color: #4CAF50;
    }

    .notification-icon.warning {
      background: var(--warning);
      color: white;
    }

    .notification-icon.danger {
      background: var(--danger);
      color: white;
      background-color: #FFEBEE;
      color: #F44336;
    }

    .notification-content {
      flex: 1;
      flex-grow: 1;
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
      color: #757575;
      font-size: 0.9em;
    }

    .notification-message {
      color: var(--dark);
      line-height: 1.5;
      margin-bottom: 0.5rem;
      color: #424242;
      margin: 10px 0;
      white-space: pre-wrap;
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

    @media (max-width: 100%) {
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
    @media (max-width: 100%) {
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

    /* New CSS for announcement functionality */
    .announcement-container {
      max-width: 1000px;
      margin: 2rem auto;
      padding: 1rem;
    }

    .announcement-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--gray-light);
    }

    .announcement-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary);
    }

    .announcement-list {
      display: grid;
      gap: 1rem;
    }

    .announcement-item {
      background: white;
      border-radius: 8px;
      padding: 1.5rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      transition: all 0.2s ease;
      border-left: 4px solid var(--gray-light);
    }

    .announcement-item.unread {
      border-left-color: var(--primary);
      background-color: rgba(0, 25, 45, 0.03);
    }

    .announcement-item.archived {
      border-left-color: var(--gray);
      opacity: 0.8;
    }

    .announcement-item.deleting {
      transform: scale(0.98);
      opacity: 0;
      transition: all 0.3s ease;
    }

    .announcement-priority {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      margin-bottom: 0.5rem;
    }

    .priority-normal {
      background-color: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .priority-urgent {
      background-color: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .priority-reminder {
      background-color: rgba(59, 130, 246, 0.1);
      color: var(--info);
    }

    .announcement-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
      color: var(--gray);
    }

    .announcement-recipient {
      font-weight: 500;
    }

    .announcement-time {
      color: var(--gray);
    }

    .announcement-message {
      margin: 1rem 0;
      line-height: 1.6;
      color: var(--dark);
    }

    .announcement-actions {
      display: flex;
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .announcement-btn {
      padding: 0.5rem 1rem;
      border-radius: 6px;
      font-size: 0.875rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      transition: all 0.2s ease;
      border: none;
    }

    .announcement-btn i {
      font-size: 0.9em;
    }

    .btn-archive {
      background-color: rgba(139, 92, 246, 0.1);
      color: #8b5cf6;
    }

    .btn-archive:hover {
      background-color: rgba(139, 92, 246, 0.2);
    }

    .btn-delete {
      background-color: rgba(239, 68, 68, 0.1);
      color: #ef4444;
    }

    .btn-delete:hover {
      background-color: rgba(239, 68, 68, 0.2);
    }

    .no-announcements {
      text-align: center;
      padding: 3rem;
      color: var(--gray);
      font-size: 1.1rem;
    }

    .alert {
      position: fixed;
      top: 1rem;
      right: 1rem;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      gap: 1rem;
      z-index: 1000;
      animation: slideIn 0.3s ease-out;
      max-width: 400px;
    }

    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .alert-success {
      background-color: var(--success);
      color: white;
    }

    .alert-error {
      background-color: var(--danger);
      color: white;
    }

    .alert-info {
      background-color: var(--info);
      color: white;
    }

    .alert-close {
      background: none;
      border: none;
      color: inherit;
      cursor: pointer;
      font-size: 1.25rem;
      margin-left: 0.5rem;
    }

    @media (max-width: 768px) {
      .announcement-container {
        padding: 0.5rem;
      }

      .announcement-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }

      .announcement-meta {
        flex-direction: column;
        gap: 0.5rem;
      }

      .announcement-actions {
        flex-wrap: wrap;
      }
    }

    /* Notification header styles */
    .notification-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
    }

    .notification-priority {
      font-weight: bold;
    }

    .notification-recipient {
      color: #616161;
      font-size: 0.9em;
      margin-bottom: 10px;
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

    .archive-btn {
      background: #E0E0E0;
      color: #424242;
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

    .archive-btn:hover {
      background: #BDBDBD;
    }

    .delete-btn {
      background: #FFEBEE;
      color: #F44336;
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

    .delete-btn:hover {
      background: #FFCDD2;
    }

    .no-messages,
    .error-message {
      text-align: center;
      padding: 30px;
      color: #757575;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .list-group-flush::-webkit-scrollbar {
      width: 6px;
    }

    .list-group-flush::-webkit-scrollbar-thumb {
      background-color: #00192D;
      border-radius: 4px;
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-dark" style="">
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
              <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Latest Announcements</h5>
              <span class="badge bg-warning"><?= count($announcements) ?> Today</span>
            </div>

            <div class="card-body p-0">
              <?php if (!empty($announcements)): ?>
                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
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

          <div class="card mb-4 shadow-sm">
            <div class="card-header text-warning fw-bold" style="background-color: #00192D;">
              <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <ul class="list-group mt-3">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Total Announcements
                    <span class="badge bg-dark rounded-pill" id="totalCount">--</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Drafts
                    <span class="badge bg-warning text-dark rounded-pill" id="draftCount">--</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Sent
                    <span class="badge bg-success rounded-pill" id="sentCount">--</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    Archived
                    <span class="badge bg-secondary rounded-pill" id="archivedCount">--</span>
                  </li>
                </ul>


              </div>
            </div>
          </div>
        </div>
      </div>


  </div>
  <!-- /.col -->
  </div>
  <!--end::Row-->
  </div>
  <!--end::Container-->
  </div>
  </main>
  <!--end::App Main-->

  <!--begin::Footer-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
  <!-- end footer -->
  </div>
  <!--end::App Wrapper-->

  <!-- plugin for pdf -->

  <script>
    // Dynamic rows for expenses table
    let rowCount = 0;

    function addRow() {
      rowCount++;
      const tableBody = document.querySelector('#expensesTable tbody');
      const newRow = document.createElement('tr');
      newRow.id = 'row-' + rowCount;
      newRow.innerHTML = `
        <td>
            <select name="bill_name[]" class="form-control form-control-sm bill-select" required>
                <option value="" selected hidden>Select Bill</option>
                <option value="Water">Water</option>
                <option value="Garbage">Garbage</option>
                <option value="Electricity">Electricity</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Internet">Internet</option>
                <option value="Security">Security</option>
                <option value="Parking">Parking</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" name="bill_name_other[]" class="form-control form-control-sm mt-1 d-none" placeholder="Specify other bill">
        </td>
        <td><input type="number" name="quantity[]" class="form-control form-control-sm qty-input" min="1" value="1" required></td>
        <td><input type="number" name="unit_price[]" class="form-control form-control-sm price-input" min="0" step="0.01" value="0" required></td>
        <td class="subtotal-cell">0.00</td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowCount})">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;
      tableBody.appendChild(newRow);

      // Add event listeners for calculations
      const qtyInput = newRow.querySelector('.qty-input');
      const priceInput = newRow.querySelector('.price-input');
      const billSelect = newRow.querySelector('.bill-select');

      qtyInput.addEventListener('input', calculateTotals);
      priceInput.addEventListener('input', calculateTotals);
      billSelect.addEventListener('change', function() {
        const otherInput = this.closest('td').querySelector('[name="bill_name_other[]"]');
        if (this.value === 'Other') {
          otherInput.classList.remove('d-none');
          otherInput.required = true;
        } else {
          otherInput.classList.add('d-none');
          otherInput.required = false;
        }
      });

      calculateTotals();
    }

    function removeRow(rowId) {
      const row = document.getElementById('row-' + rowId);
      if (row) {
        row.remove();
        calculateTotals();
      }
    }

    function calculateTotals() {
      let totalQty = 0;
      let totalUnitPrice = 0;
      let totalSubtotal = 0;

      const rows = document.querySelectorAll('#expensesTable tbody tr');
      rows.forEach(row => {
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const subtotalCell = row.querySelector('.subtotal-cell');

        if (qtyInput && priceInput && subtotalCell) {
          const qty = parseFloat(qtyInput.value) || 0;
          const price = parseFloat(priceInput.value) || 0;
          const subtotal = qty * price;

          subtotalCell.textContent = subtotal.toFixed(2);

          totalQty += qty;
          totalUnitPrice += price;
          totalSubtotal += subtotal;
        }
      });

      // Update footer totals
      document.getElementById('totalQty').textContent = totalQty;
      document.getElementById('totalUnitPrice').textContent = totalUnitPrice.toFixed(2);
      document.getElementById('totalSubtotal').textContent = totalSubtotal.toFixed(2);
    }

    // Initialize with one row when page loads
    document.addEventListener('DOMContentLoaded', function() {
      addRow();

      // Form validation before submission
      const form = document.querySelector('form');
      form.addEventListener('submit', function(e) {
        // Validate that at least one bill row exists
        const billRows = document.querySelectorAll('#expensesTable tbody tr');
        if (billRows.length === 0) {
          e.preventDefault();
          alert('Please add at least one recurring bill or remove the entire recurring bills section.');
          return false;
        }

        // Validate each bill row
        let isValid = true;
        billRows.forEach(row => {
          const billSelect = row.querySelector('.bill-select');
          const qtyInput = row.querySelector('.qty-input');
          const priceInput = row.querySelector('.price-input');

          if (!billSelect.value || !qtyInput.value || !priceInput.value) {
            isValid = false;
          }

          // Check for "Other" bill with empty specification
          if (billSelect.value === 'Other') {
            const otherInput = row.querySelector('[name="bill_name_other[]"]');
            if (!otherInput.value.trim()) {
              isValid = false;
              otherInput.classList.add('is-invalid');
            }
          }
        });

        if (!isValid) {
          e.preventDefault();
          alert('Please fill in all required fields for recurring bills.');
          return false;
        }

        return true;
      });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Validate monthly rent input
      const monthlyRentInput = document.getElementById('monthly_rent');
      if (monthlyRentInput) {
        monthlyRentInput.addEventListener('blur', function() {
          const value = parseFloat(this.value);
          if (value < 0) {
            alert('Monthly rent cannot be negative');
            this.value = '';
            this.focus();
          }
        });

        // Format the input to 2 decimal places
        monthlyRentInput.addEventListener('change', function() {
          if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
          }
        });
      }

      // Form submission validation
      const form = document.querySelector('form');
      form.addEventListener('submit', function(e) {
        const monthlyRent = parseFloat(monthlyRentInput.value);
        if (!monthlyRent || monthlyRent <= 0) {
          e.preventDefault();
          alert('Please enter a valid monthly rent amount');
          monthlyRentInput.focus();
          return false;
        }

        // Add chart of accounts reference to form data
        const coaInput = document.createElement('input');
        coaInput.type = 'hidden';
        coaInput.name = 'chart_of_accounts_ref';
        coaInput.value = '<?= $rental_account_code ?>';
        this.appendChild(coaInput);

        return true;
      });
    });
  </script>

  <!-- Main Js File -->
  <script src="/jengoPay/landlord/assets/main.js"></script>
  <script src="js/main.js"></script>
  <!-- html2pdf depends on html2canvas and jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script type="module" src="./js/main.js"></script>
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
  <!-- pdf download plugin -->


  <!-- Scripts -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!--end::Body-->

</html>