<?php // Start session
session_start();
// 🔌 Include your PDO database connection
include '../db/connect.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/service_provider_auth_check.php';

// 📥 Check if the user is logged in and their role is 'provider'
if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] === 'provider') {
  // Get the full name from the session and capitalize the first name
  $fullName = $_SESSION['user']['name']; // Assuming first_name is like 'john wangui'
  $serviceProvider = ucwords(strtok($fullName, ' ')); // Get the first word (John)
} else {
  $serviceProvider = ''; // Default if user is not logged in or not a provider
}

// actions
include_once './actions/getApplications.php';
include_once './actions/withdrawApplication.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Applications - Service Provider Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="requestOrders.css">
  <style>
    :root {
      --primary-color: #00192D;
      --accent-color: #FFC107;
      --light-bg: #f8f9fa;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      /* background-color: var(--light-bg); */
      background-color: #f4f6f9;

    }

    /* Header Styles */

    /* Navigation */
    .navigation {
      background: white;
      padding: 1rem 0;
      margin-bottom: 2rem;
    }

    .nav-links {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .nav-links a {
      color: var(--primary-color);
      text-decoration: none;
      padding: 0.5rem 1.2rem;
      border-radius: 5px;
      transition: all 0.3s;
      font-weight: 500;
    }

    .nav-links a:hover,
    .nav-links a.active {
      background: var(--accent-color);
      color: var(--primary-color);
    }

    /* Page Title Section */
    .page-title-section {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      margin-bottom: 2rem;
    }

    .page-title {
      color: var(--primary-color);
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .page-subtitle {
      color: #6c757d;
      font-size: 1rem;
    }

    /* Stats Cards */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 10px;
      text-align: center;
      transition: transform 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-icon {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }

    .stat-card.pending .stat-icon {
      color: #FFC107;
    }

    .stat-card.accepted .stat-icon {
      color: #28a745;
    }

    .stat-card.rejected .stat-icon {
      color: #dc3545;
    }

    .stat-card.total .stat-icon {
      color: var(--primary-color);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.3rem;
    }

    .stat-label {
      color: #6c757d;
      font-size: 0.9rem;
      font-weight: 600;
    }

    /* Filter Section */
    .filter-section {
      background: white;
      padding: 1.5rem;
      border-radius: 10px;
      margin-bottom: 2rem;
    }

    .filter-tabs {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      margin-bottom: 1.5rem;
      border-bottom: 2px solid #e9ecef;
      padding-bottom: 1rem;
    }

    .filter-tab {
      padding: 0.5rem 1.5rem;
      border: none;
      background: transparent;
      color: #6c757d;
      font-weight: 600;
      cursor: pointer;
      position: relative;
      transition: all 0.3s;
    }

    .filter-tab:hover {
      color: var(--primary-color);
    }

    .filter-tab.active {
      color: var(--primary-color);
    }

    .filter-tab.active::after {
      content: '';
      position: absolute;
      bottom: -1.1rem;
      left: 0;
      width: 100%;
      height: 3px;
      background: var(--accent-color);
    }

    /* Application Card */
    .application-card {
      background: white;
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      transition: all 0.3s;
      border-left: 4px solid transparent;
    }

    .application-card:hover {
      transform: translateX(5px);
    }

    .application-card.pending {
      border-left-color: #FFC107;
    }

    .application-card.accepted {
      border-left-color: #28a745;
    }

    .application-card.rejected {
      border-left-color: #dc3545;
    }

    .application-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 1rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .application-title {
      color: var(--primary-color);
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .status-badge {
      padding: 0.4rem 1rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-badge.pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-badge.accepted {
      background: #d4edda;
      color: #155724;
    }

    .status-badge.rejected {
      background: #f8d7da;
      color: #721c24;
    }

    .application-meta {
      display: flex;
      gap: 2rem;
      flex-wrap: wrap;
      margin-bottom: 1rem;
      color: #6c757d;
      font-size: 0.9rem;
    }

    .meta-item {
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    .meta-item i {
      color: var(--accent-color);
    }

    .application-details {
      background: var(--light-bg);
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      padding: 0.5rem 0;
      border-bottom: 1px solid #dee2e6;
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-label {
      font-weight: 600;
      color: var(--primary-color);
    }

    .detail-value {
      color: #495057;
    }

    .application-message {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 8px;
      border-left: 3px solid var(--accent-color);
      margin-bottom: 1rem;
      font-size: 0.95rem;
      color: #495057;
      line-height: 1.6;
    }

    .application-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .applied-date {
      color: #6c757d;
      font-size: 0.85rem;
    }

    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }

    .btn-action {
      padding: 0.5rem 1.2rem;
      border-radius: 5px;
      border: none;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 0.9rem;
    }

    .btn-view {
      background: var(--primary-color);
      color: white;
    }

    .btn-view:hover {
      background: var(--accent-color);
      color: var(--primary-color);
      transform: translateY(-2px);
    }

    .btn-withdraw {
      background: #dc3545;
      color: white;
    }

    .btn-withdraw:hover {
      background: #c82333;
      transform: translateY(-2px);
    }

    .btn-message {
      background: #17a2b8;
      color: white;
    }

    .btn-message:hover {
      background: #138496;
      transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
      background: white;
      border-radius: 10px;
      padding: 4rem 2rem;
      text-align: center;
    }

    .empty-state-icon {
      font-size: 5rem;
      color: #dee2e6;
      margin-bottom: 1.5rem;
    }

    .empty-state h3 {
      color: var(--primary-color);
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    .empty-state p {
      color: #6c757d;
      margin-bottom: 2rem;
    }

    /* Pagination */
    .pagination {
      margin: 2rem 0;
    }

    .pagination .page-link {
      color: var(--primary-color);
      border: 1px solid #dee2e6;
      padding: 0.5rem 0.75rem;
      margin: 0 0.2rem;
      border-radius: 5px;
      transition: all 0.3s;
    }

    .pagination .page-link:hover {
      background: var(--accent-color);
      color: var(--primary-color);
      border-color: var(--accent-color);
    }

    .pagination .page-item.active .page-link {
      background: var(--primary-color);
      border-color: var(--primary-color);
      color: white;
    }

    /* Footer */
    .footer {
      background: var(--primary-color);
      color: white;
      padding: 2rem 0;
      margin-top: 3rem;
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }

    .footer-section h4 {
      color: var(--accent-color);
      margin-bottom: 1rem;
    }

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin-bottom: 0.5rem;
    }

    .footer-links a {
      color: white;
      text-decoration: none;
      transition: color 0.3s;
    }

    .footer-links a:hover {
      color: var(--accent-color);
    }

    .footer-bottom {
      text-align: center;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Offcanvas Custom Styles */
    .offcanvas-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #003d5c 100%);
      color: white;
      padding: 1.5rem;
    }

    .offcanvas-title {
      font-size: 1.3rem;
      font-weight: 700;
    }

    .offcanvas-body {
      padding: 0;
    }

    .detail-section {
      padding: 1.5rem;
      border-bottom: 1px solid #e9ecef;
    }

    .detail-section:last-child {
      border-bottom: none;
    }

    .detail-section-title {
      color: var(--primary-color);
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .detail-section-title i {
      color: var(--accent-color);
    }

    .info-grid {
      display: grid;
      gap: 1rem;
    }

    .info-item {
      display: flex;
      justify-content: space-between;
      padding: 0.75rem;
      background: var(--light-bg);
      border-radius: 8px;
    }

    .info-label {
      font-weight: 600;
      color: #6c757d;
    }

    .info-value {
      font-weight: 600;
      color: var(--primary-color);
    }

    .message-box {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 8px;
      border-left: 3px solid var(--accent-color);
      line-height: 1.6;
      color: #495057;
    }

    .status-indicator {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-weight: 700;
      font-size: 0.9rem;
    }

    .status-indicator.pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-indicator.accepted {
      background: #d4edda;
      color: #155724;
    }

    .status-indicator.rejected {
      background: #f8d7da;
      color: #721c24;
    }

    .timeline {
      position: relative;
      padding-left: 2rem;
    }

    .timeline-item {
      position: relative;
      padding-bottom: 1.5rem;
    }

    .timeline-item::before {
      content: '';
      position: absolute;
      left: -1.5rem;
      top: 0.5rem;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: var(--accent-color);
      border: 3px solid white;
    }

    .timeline-item::after {
      content: '';
      position: absolute;
      left: -1.1rem;
      top: 1.2rem;
      width: 2px;
      height: calc(100% - 0.5rem);
      background: #dee2e6;
    }

    .timeline-item:last-child::after {
      display: none;
    }

    .timeline-date {
      font-size: 0.85rem;
      color: #6c757d;
      font-weight: 600;
    }

    .timeline-content {
      color: #495057;
      margin-top: 0.3rem;
    }

    /* Image Slider in Offcanvas */
    .offcanvas-image-slider {
      position: relative;
      width: 100%;
      height: 250px;
      background: #e9ecef;
      border-radius: 10px;
      overflow: hidden;
    }

    .offcanvas-slider-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
    }

    .offcanvas-slider-image.active {
      display: block;
    }

    .offcanvas-slider-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 25, 45, 0.8);
      color: white;
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s;
      z-index: 10;
    }

    .offcanvas-slider-btn:hover {
      background: var(--accent-color);
      color: var(--primary-color);
      transform: translateY(-50%) scale(1.1);
    }

    .offcanvas-slider-btn.prev {
      left: 10px;
    }

    .offcanvas-slider-btn.next {
      right: 10px;
    }

    .offcanvas-image-counter {
      position: absolute;
      top: 15px;
      right: 15px;
      background: rgba(0, 25, 45, 0.8);
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      z-index: 10;
    }

    .offcanvas-slider-indicators {
      position: absolute;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 8px;
      z-index: 10;
    }

    .offcanvas-indicator-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      transition: all 0.3s;
    }

    .offcanvas-indicator-dot.active {
      background: var(--accent-color);
      width: 20px;
      border-radius: 4px;
    }

    .action-btn-offcanvas {
      width: 100%;
      padding: 0.75rem;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      margin-bottom: 0.5rem;
    }

    .btn-message-client {
      background: #17a2b8;
      color: white;
    }

    .btn-message-client:hover {
      background: #138496;
      transform: translateY(-2px);
    }

    .btn-withdraw-app,
    .btn-decline-ass {
      background: #dc3545;
      color: white;
    }

    .btn-withdraw-app:hover,
    .btn-decline-ass:hover {
      background: #c82333;
      transform: translateY(-2px);
    }

    @media (max-width: 768px) {
      .application-header {
        flex-direction: column;
      }

      .application-footer {
        flex-direction: column;
        align-items: stretch;
      }

      .action-buttons {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">

    <?php if (!empty($error)): ?>
      <div id="flashToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3500">
        <div class="d-flex">
          <div class="toast-body small">
            <?= htmlspecialchars($error) ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div id="flashToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="polite" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
          <div class="toast-body small">
            <?= htmlspecialchars($success) ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>

  </div>
  <div class="app-wrapper">

    <!-- Header -->
    <!--Start Header -->
    <?php
    include "./includes/header.php"
    ?>
    <!-- end header -->

    <div class="main">
      <!-- Navigation -->
      <nav class="navigation">
        <div class="container">
          <div class="nav-links">
            <a href="requestOrders.php"><i class="fas fa-search"></i> Find a Job</a>
            <a href="applications.php" class="active"><i class="fas fa-file-alt"></i> Your Applications</a>
            <a href="#"><i class="fas fa-briefcase"></i> Assigned Jobs</a>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <div class="container">
        <!-- Page Title -->
        <div class="page-title-section">
          <h1 class="page-title">Your Applications</h1>
          <p class="page-subtitle">Track and manage all your job applications in one place</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">

          <!-- Total Applications -->
          <div class="stat-card d-flex align-items-center rounded-2 p-3">
            <div>
              <i class="fas fa-file-alt fs-1 me-3 text-warning"></i>
            </div>
            <div>
              <p class="mb-0" style="font-weight: bold;">Total Applications</p>
              <b><?= $totalApplications ?></b>
            </div>
          </div>

          <!-- Pending Review -->
          <div class="stat-card d-flex align-items-center rounded-2 p-3">
            <div>
              <i class="fas fa-clock fs-1 me-3 text-warning"></i>
            </div>
            <div>
              <p class="mb-0" style="font-weight: bold;">Pending Review</p>
              <b><?= $pending ?></b>
            </div>
          </div>

          <!-- Accepted -->
          <div class="stat-card d-flex align-items-center rounded-2 p-3">
            <div>
              <i class="fas fa-check-circle fs-1 me-3 text-warning"></i>
            </div>
            <div>
              <p class="mb-0" style="font-weight: bold;">Accepted</p>
              <b><?= $accepted ?></b>
            </div>
          </div>

          <!-- Declined -->
          <div class="stat-card d-flex align-items-center rounded-2 p-3">
            <div>
              <i class="fas fa-check-circle fs-1 me-3 text-warning"></i>
            </div>
            <div>
              <p class="mb-0" style="font-weight: bold;">Assigned</p>
              <b><?= $declined ?></b>
            </div>
          </div>

        </div>


        <!-- Filter Section -->
        <div class="filter-section">
          <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterApplications('all')">
              All Applications <span style="background: #e9ecef; padding: 0.2rem 0.6rem; border-radius: 10px; margin-left: 0.3rem;"><?= $totalApplications ?></span>
            </button>
            <button class="filter-tab" onclick="filterApplications('pending')">
              Pending <span style="background: #fff3cd; padding: 0.2rem 0.6rem; border-radius: 10px; margin-left: 0.3rem;"><?= $pending ?></span>
            </button>
            <button class="filter-tab" onclick="filterApplications('accepted')">
              Accepted <span style="background: #d4edda; padding: 0.2rem 0.6rem; border-radius: 10px; margin-left: 0.3rem;"><?= $accepted ?></span>
            </button>
            <button class="filter-tab" onclick="filterApplications('rejected')">
              Assigned <span style="background: #f8d7da; padding: 0.2rem 0.6rem; border-radius: 10px; margin-left: 0.3rem;"><?= $declined ?></span>
            </button>
          </div>

          <div class="row">
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Search by job title or property...">
            </div>
            <div class="col-md-3">
              <select class="form-select">
                <option>Sort by: Most Recent</option>
                <option>Sort by: Oldest</option>
                <option>Sort by: Status</option>
              </select>
            </div>
            <div class="col-md-3">
              <button class="btn w-100" style="background: var(--primary-color); color: white;">
                <i class="fas fa-filter"></i> Apply Filters
              </button>
            </div>
          </div>
        </div>

        <!-- Applications List -->
        <?php
        // Sample data


        foreach ($applications as $application): ?>
          <div class="application-card <?php echo $application['status']; ?>">
            <div class="application-header">
              <div>
                <h3 class="application-title"><?php echo $application['title']; ?></h3>
                <div class="application-meta">
                  <div class="meta-item">
                    <i class="fas fa-building"></i>
                    <span><?php echo $application['building_name']; ?> - Unit <?php echo $application['unit_number']; ?></span>
                  </div>
                  <div class="meta-item">
                    <i class="fas fa-tag"></i>
                    <span><?php echo $application['category']; ?></span>
                  </div>
                </div>
              </div>
              <span class="status-badge <?php echo $application['status']; ?>">
                <?php echo ucfirst($application['status']); ?>
              </span>
            </div>

            <div class="application-details">
              <div class="detail-row">
                <span class="detail-label">Client's Budget:</span>
                <span class="detail-value"><?php echo $application['budget']; ?></span>
              </div>
              <div class="detail-row">
                <span class="detail-label">Your Proposed Budget:</span>
                <span class="detail-value" style="font-weight: 600; color: var(--primary-color);"><?php echo $application['proposed_budget']; ?></span>
              </div>
              <div class="detail-row">
                <span class="detail-label">Your Estimated Duration:</span>
                <span class="detail-value"><?php echo $application['proposed_duration']; ?></span>
              </div>
            </div>


            <div class="application-footer">
              <div class="applied-date">
                <i class="fas fa-calendar"></i> Applied on <?php echo date('M d, Y', strtotime($application['created_at'])); ?>
              </div>
              <div class="action-buttons">
                <button class="btn-action btn-view" data-bs-toggle="offcanvas"
                  data-bs-target="#applicationDetailsOffcanvas"
                  data-proposal-id="<?php echo htmlspecialchars($application['proposal_id']); ?>"
                  data-title="<?php echo htmlspecialchars($application['title']); ?>"
                  data-property="<?php echo htmlspecialchars($application['building_name']); ?>"
                  data-unit="<?php echo htmlspecialchars($application['unit_number']); ?>"
                  data-category="<?php echo htmlspecialchars($application['category']); ?>"
                  data-status="<?php echo $application['status']; ?>"
                  data-job-budget="<?php echo ($application['budget'] === null || $application['budget'] === '') ? 'Not set' : htmlspecialchars($request['budget']); ?>"

                  data-your-budget="<?php echo htmlspecialchars($application['proposed_budget']); ?>"
                  data-duration="<?php echo htmlspecialchars($application['proposed_duration']); ?>"
                  data-applied-date="<?php echo date('M d, Y', strtotime($application['created_at'])); ?>">

                  <i class="fas fa-eye"></i> View Details
                </button>
                <?php if ($application['status'] === 'Accepted'): ?>
                  <button class="btn-action btn-message">
                    <i class="fas fa-comments"></i> Message Client
                  </button>
                <?php elseif ($application['status'] === 'Pending'): ?>
                  <form method="POST" action="">
                    <input type="hidden" name="proposal_id" value="<?php echo htmlspecialchars($application['proposal_id']); ?>">
                    <button class="btn-action btn-withdraw" type="submit" name="withdraw_application">
                      <i class="fas fa-trash"></i> Withdraw
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <nav aria-label="Applications pagination">
          <ul class="pagination justify-content-center">
            <li class="page-item disabled">
              <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-section">
            <h4>About Us</h4>
            <p>Connecting skilled service providers with property owners for quality maintenance and repair services.</p>
          </div>
          <div class="footer-section">
            <h4>Quick Links</h4>
            <ul class="footer-links">
              <li><a href="#">Find Jobs</a></li>
              <li><a href="#">How It Works</a></li>
              <li><a href="#">Pricing</a></li>
              <li><a href="#">FAQ</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h4>Support</h4>
            <ul class="footer-links">
              <li><a href="#">Help Center</a></li>
              <li><a href="#">Contact Us</a></li>
              <li><a href="#">Terms of Service</a></li>
              <li><a href="#">Privacy Policy</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h4>Contact</h4>
            <p><i class="fas fa-envelope"></i> support@example.com</p>
            <p><i class="fas fa-phone"></i> +254 700 000 000</p>
            <div style="margin-top: 1rem;">
              <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-facebook fa-lg"></i></a>
              <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-twitter fa-lg"></i></a>
              <a href="#" style="color: white;"><i class="fab fa-linkedin fa-lg"></i></a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2026 Service Provider Portal. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>

  <!-- Modals and offcanvas -->
  <!-- Offcanvas for Application Details -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="applicationDetailsOffcanvas" aria-labelledby="applicationDetailsLabel" style="width: 650px; max-width: 90vw;">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="applicationDetailsLabel">
        <i class="fas fa-file-alt"></i> Application Details
      </h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <!-- Job Information Section -->
      <div class="detail-section">
        <h3 class="detail-section-title">
          <i class="fas fa-briefcase"></i> Job Information
        </h3>
        <h4 id="offcanvasJobTitle" style="color: var(--primary-color); font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem;"></h4>

        <div class="info-grid">
          <div class="info-item">
            <span class="info-label">Property:</span>
            <span class="info-value" id="offcanvasProperty"></span>
          </div>
          <div class="info-item">
            <span class="info-label">Category:</span>
            <span class="info-value" id="offcanvasCategory"></span>
          </div>
        </div>
      </div>

      <!-- Status Section -->
      <div class="detail-section">
        <h3 class="detail-section-title">
          <i class="fas fa-info-circle"></i> Application Status
        </h3>
        <div id="offcanvasStatus"></div>
      </div>

      <!-- Budget & Duration Section -->
      <div class="detail-section">
        <h3 class="detail-section-title">
          <i class="fas fa-money-bill-wave"></i> Budget & Duration
        </h3>
        <div class="info-grid">
          <div class="info-item">
            <span class="info-label">Client's Budget:</span>
            <span class="info-value" id="offcanvasClientBudget"></span>
          </div>
          <div class="info-item">
            <span class="info-label">Your Proposed Budget:</span>
            <span class="info-value" style="color: #28a745;" id="offcanvasYourBudget"></span>
          </div>
          <div class="info-item">
            <span class="info-label">Your Duration:</span>
            <span class="info-value" id="offcanvasDuration"></span>
          </div>
        </div>
      </div>

      <!-- Job Images Section -->
      <div class="detail-section">
        <h3 class="detail-section-title">
          <i class="fas fa-images"></i> Job Images
        </h3>
        <div class="offcanvas-image-slider" id="offcanvasImageSlider">
          <!-- Images will be populated dynamically -->
          <div class="offcanvas-image-counter">
            <span class="current-image-number">1</span> / <span class="total-images">0</span>
          </div>

          <!-- Navigation Buttons (only shown if multiple images) -->
          <button class="offcanvas-slider-btn prev" onclick="changeOffcanvasSlide(-1)" style="display: none;">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="offcanvas-slider-btn next" onclick="changeOffcanvasSlide(1)" style="display: none;">
            <i class="fas fa-chevron-right"></i>
          </button>

          <!-- Indicators (only shown if multiple images) -->
          <div class="offcanvas-slider-indicators" id="offcanvasIndicators" style="display: none;"></div>
        </div>
      </div>

      <!-- Timeline Section -->
      <div class="detail-section">
        <h3 class="detail-section-title">
          <i class="fas fa-history"></i> Application Timeline
        </h3>
        <div class="timeline">
          <div class="timeline-item">
            <div class="timeline-date" id="offcanvasAppliedDate"></div>
            <div class="timeline-content">You submitted your application</div>
          </div>
          <div class="timeline-item" id="timelineStatus" style="display: none;">
            <div class="timeline-date" id="offcanvasStatusDate"></div>
            <div class="timeline-content" id="offcanvasStatusText"></div>
          </div>
        </div>
      </div>

      <!-- Actions Section -->
      <div class="detail-section" id="actionsSection">
        <h3 class="detail-section-title">
          <i class="fas fa-tasks"></i> Actions
        </h3>
        <button class="action-btn-offcanvas btn-message-client" id="btnAcceptOffcanvas" style="display: none;">
          <i class="fas fa-comments"></i> Accept the assignement
        </button>

        <form method="POST" action="" id="declineAssignmentForm" style="display: none;">
          <input type="hidden" name="assigned_request_id" id="assigned_request_id" value="">
          <button class="action-btn-offcanvas btn-decline-ass" type="submit" id="btnDeclineAssignment">
            <i class="fas fa-trash"></i> Decline the assignement
          </button>
        </form>
        <form method="POST" action="" id="acceptProposalForm">
          <input type="hidden" name="proposal_id" id="proposal_id" value="">
          <button class="action-btn-offcanvas btn-withdraw-app" type="submit" id="btnWithApplication" style="display: none;">
            <i class="fas fa-trash"></i> Withdraw the Application
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Filter applications by status
    function filterApplications(status) {
      // Remove active class from all tabs
      document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
      });

      // Add active class to clicked tab
      event.target.classList.add('active');

      // Filter logic would go here
      // In a real application, this would filter the displayed applications
      console.log('Filtering by:', status);
    }

    // Action buttons functionality
  </script>

  <!-- Toast message -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toastEl = document.getElementById("flashToast");
      if (toastEl && window.bootstrap) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
      }
    });
  </script>

  <script>
    let currentSlideIndex = 0;
    let totalSlides = 0;

    // Image slider functions for offcanvas
    function changeOffcanvasSlide(direction) {
      const slider = document.getElementById('offcanvasImageSlider');
      const slides = slider.querySelectorAll('.offcanvas-slider-image');
      const indicators = slider.querySelectorAll('.offcanvas-indicator-dot');
      const counter = slider.querySelector('.current-image-number');

      currentSlideIndex += direction;

      // Loop around
      if (currentSlideIndex >= totalSlides) currentSlideIndex = 0;
      if (currentSlideIndex < 0) currentSlideIndex = totalSlides - 1;

      // Update slides
      slides.forEach((slide, index) => {
        slide.classList.toggle('active', index === currentSlideIndex);
      });

      // Update indicators
      indicators.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlideIndex);
      });

      // Update counter
      counter.textContent = currentSlideIndex + 1;
    }

    function goToOffcanvasSlide(index) {
      currentSlideIndex = index;
      const slider = document.getElementById('offcanvasImageSlider');
      const slides = slider.querySelectorAll('.offcanvas-slider-image');
      const indicators = slider.querySelectorAll('.offcanvas-indicator-dot');
      const counter = slider.querySelector('.current-image-number');

      // Update slides
      slides.forEach((slide, idx) => {
        slide.classList.toggle('active', idx === index);
      });

      // Update indicators
      indicators.forEach((dot, idx) => {
        dot.classList.toggle('active', idx === index);
      });

      // Update counter
      counter.textContent = index + 1;
    }

    // Populate offcanvas with application details
    const offcanvasElement = document.getElementById('applicationDetailsOffcanvas');
    offcanvasElement.addEventListener('show.bs.offcanvas', function(event) {
      const button = event.relatedTarget;

      // Get all data from button
      const title = button.getAttribute('data-title');
      const property = button.getAttribute('data-property');
      const unit = button.getAttribute('data-unit');
      const category = button.getAttribute('data-category');
      const status = button.getAttribute('data-status');
      const clientBudget = button.getAttribute('data-client-budget');
      const yourBudget = button.getAttribute('data-your-budget');
      const duration = button.getAttribute('data-duration');
      const message = button.getAttribute('data-message');
      const appliedDate = button.getAttribute('data-applied-date');
      const imagesJSON = button.getAttribute('data-images');
      const proposalId = button.getAttribute('data-proposal-id');
      const images = JSON.parse(imagesJSON);

      console.log(status);

      // Setup image slider
      const imageSlider = document.getElementById('offcanvasImageSlider');
      const indicatorsContainer = document.getElementById('offcanvasIndicators');

      // Clear previous images
      const existingImages = imageSlider.querySelectorAll('.offcanvas-slider-image');
      existingImages.forEach(img => img.remove());
      indicatorsContainer.innerHTML = '';

      if (images && images.length > 0) {
        totalSlides = images.length;
        currentSlideIndex = 0;

        // Update counter
        imageSlider.querySelector('.total-images').textContent = totalSlides;
        imageSlider.querySelector('.current-image-number').textContent = '1';

        // Add images
        images.forEach((image, index) => {
          const img = document.createElement('img');
          img.src = 'https://via.placeholder.com/650x250/00192D/FFC107?text=Job+Image+' + (index + 1);
          img.alt = 'Job image ' + (index + 1);
          img.className = 'offcanvas-slider-image' + (index === 0 ? ' active' : '');
          imageSlider.insertBefore(img, imageSlider.firstChild);
        });

        // Show/hide navigation based on image count
        if (totalSlides > 1) {
          imageSlider.querySelector('.prev').style.display = 'flex';
          imageSlider.querySelector('.next').style.display = 'flex';
          indicatorsContainer.style.display = 'flex';

          // Add indicators
          for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('span');
            dot.className = 'offcanvas-indicator-dot' + (i === 0 ? ' active' : '');
            dot.onclick = () => goToOffcanvasSlide(i);
            indicatorsContainer.appendChild(dot);
          }
        } else {
          imageSlider.querySelector('.prev').style.display = 'none';
          imageSlider.querySelector('.next').style.display = 'none';
          indicatorsContainer.style.display = 'none';
        }
      } else {
        // No images - show placeholder
        totalSlides = 1;
        const img = document.createElement('img');
        img.src = 'https://via.placeholder.com/650x250/e9ecef/6c757d?text=No+Images+Available';
        img.alt = 'No images';
        img.className = 'offcanvas-slider-image active';
        imageSlider.insertBefore(img, imageSlider.firstChild);

        imageSlider.querySelector('.total-images').textContent = '0';
        imageSlider.querySelector('.current-image-number').textContent = '0';
        imageSlider.querySelector('.prev').style.display = 'none';
        imageSlider.querySelector('.next').style.display = 'none';
        indicatorsContainer.style.display = 'none';
      }

      // Populate offcanvas
      document.getElementById('offcanvasJobTitle').textContent = title;
      document.getElementById('offcanvasProperty').textContent = property + ' - Unit ' + unit;
      document.getElementById('offcanvasCategory').textContent = category;
      document.getElementById('offcanvasClientBudget').textContent = clientBudget;
      document.getElementById('offcanvasYourBudget').textContent = yourBudget;
      document.getElementById('offcanvasDuration').textContent = duration;
      document.getElementById('offcanvasAppliedDate').textContent = 'Applied on ' + appliedDate;
      document.getElementById('proposal_id').value = proposalId;
      const btnWithApplication = document.getElementById('btnWithApplication').name = 'withdraw_application';
      const btnDeclineAssigment = document.getElementById('btnDeclineAssignment');
      btnDeclineAssigment.name = 'decline_assignment';


      // Set status with proper styling
      let statusHTML = '';
      let statusIcon = '';
      let statusText = '';

      if (status === 'Pending') {
        statusIcon = '<i class="fas fa-clock"></i>';
        statusText = 'Pending Review';
        statusHTML = '<div class="status-indicator pending">' + statusIcon + ' ' + statusText + '</div>';
      } else if (status === 'Accepted') {
        statusIcon = '<i class="fas fa-check-circle"></i>';
        statusText = 'Accepted';
        statusHTML = '<div class="status-indicator accepted">' + statusIcon + ' ' + statusText + '</div>';

        // Show status in timeline
        document.getElementById('timelineStatus').style.display = 'block';
        document.getElementById('offcanvasStatusDate').textContent = 'Status updated';
        document.getElementById('offcanvasStatusText').textContent = 'Your application was accepted by the client';
      } else if (status === 'rejected') {
        statusIcon = '<i class="fas fa-times-circle"></i>';
        statusText = 'Declined';
        statusHTML = '<div class="status-indicator rejected">' + statusIcon + ' ' + statusText + '</div>';

        // Show status in timeline
        document.getElementById('timelineStatus').style.display = 'block';
        document.getElementById('offcanvasStatusDate').textContent = 'Status updated';
        document.getElementById('offcanvasStatusText').textContent = 'Your application was declined by the client';
      }

      document.getElementById('offcanvasStatus').innerHTML = statusHTML;

      // Show/hide action buttons based on status
      const btnAccept = document.getElementById('btnAcceptOffcanvas');
      const declineAssignmentForm = document.getElementById('declineAssignmentForm')
      const btnWithdraw = document.getElementById('btnWithApplication');

      if (status === 'Accepted') {
        btnAccept.style.display = 'block';
        declineAssignmentForm.style.display = 'block';
        btnDeclineAssigment.style.display = 'block';
        btnWithdraw.style.display = 'none';
      } else if (status === 'Pending') {
        btnAccept.style.display = 'none';
        btnWithdraw.style.display = 'block';
      } else {
        btnAccept.style.display = 'none';
        btnWithdraw.style.display = 'none';
      }
    });

    // Message client button in offcanvas
    document.getElementById('btnMessageOffcanvas').addEventListener('click', function() {
      alert('Open messaging interface');
    });

    // Withdraw button in offcanvas
    // document.getElementById('btnWithdrawOffcanvas').addEventListener('click', function() {
    //   if (confirm('Are you sure you want to withdraw this application?')) {
    //     alert('Application withdrawn successfully');
    //     // Close offcanvas
    //     const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
    //     offcanvas.hide();
    //   }
    // });

    // Filter applications by status
    function filterApplications(status) {
      // Remove active class from all tabs
      document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
      });

      // Add active class to clicked tab
      event.target.classList.add('active');

      // Filter logic would go here
      // In a real application, this would filter the displayed applications
      console.log('Filtering by:', status);
    }

    // Action buttons functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Message buttons in cards
      document.querySelectorAll('.btn-message').forEach(button => {
        button.addEventListener('click', function() {
          alert('Open messaging interface');
        });
      });

      // Withdraw buttons in cards
      document.querySelectorAll('.btn-withdraw').forEach(button => {
        button.addEventListener('click', function() {
          if (confirm('Are you sure you want to withdraw this application?')) {
            alert('Application withdrawn successfully');
          }
        });
      });
    });
  </script>
</body>

</html>