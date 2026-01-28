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
            <a href="#"><i class="fas fa-history"></i> Previous Jobs</a>
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
          <div class="stat-card total">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-number">12</div>
            <div class="stat-label">Total Applications</div>
          </div>
          <div class="stat-card pending">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-number">7</div>
            <div class="stat-label">Pending Review</div>
          </div>
          <div class="stat-card accepted">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">4</div>
            <div class="stat-label">Accepted</div>
          </div>
          <div class="stat-card rejected">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-number">1</div>
            <div class="stat-label">Declined</div>
          </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
          <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterApplications('all')">
              All Applications <span style="background: #e9ecef; padding: 0.2rem 0.6rem; border-radius: 10px; margin-left: 0.3rem;">12</span>
            </button>
            <button class="filter-tab" onclick="filterApplications('pending')">
              Pending <span style="background: #fff3cd; padding: 0.2rem 0.6rem; border-radius: 10px; margin-left: 0.3rem;">7</span>
            </button>
            <button class="filter-tab" onclick="filterApplications('accepted')">
              Accepted <span style="background: #d4edda; padding: 0.2rem 0.6rem; border-radius: 10px; margin-left: 0.3rem;">4</span>
            </button>
            <button class="filter-tab" onclick="filterApplications('rejected')">
              Declined <span style="background: #f8d7da; padding: 0.2rem 0.6rem; border-radius: 10px; margin-left: 0.3rem;">1</span>
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
        $applications = [
          [
            'title' => 'Electrical Outlet Installation',
            'property' => 'Silver House',
            'unit' => 'C9',
            'status' => 'pending',
            'applied_date' => '2026-01-20',
            'client_budget' => 'KES 5,000',
            'your_budget' => 'KES 4,500',
            'your_duration' => '2-3 hours',
            'message' => 'I have over 5 years of experience in residential electrical work. I\'m certified and have all necessary tools. Can start immediately.',
            'category' => 'Electrical'
          ],
          [
            'title' => 'Kitchen Sink Leak Repair',
            'property' => 'Golden Heights',
            'unit' => 'A12',
            'status' => 'accepted',
            'applied_date' => '2026-01-18',
            'client_budget' => 'KES 3,500',
            'your_budget' => 'KES 3,200',
            'your_duration' => '1-2 hours',
            'message' => 'I specialize in plumbing repairs and have fixed similar issues many times. Quick and professional service guaranteed.',
            'category' => 'Plumbing'
          ],
          [
            'title' => 'Ceiling Fan Installation',
            'property' => 'Palm Residences',
            'unit' => 'B5',
            'status' => 'pending',
            'applied_date' => '2026-01-19',
            'client_budget' => 'KES 7,000',
            'your_budget' => 'KES 6,500',
            'your_duration' => '3-4 hours',
            'message' => 'Licensed electrician with experience in ceiling fan installations. I ensure proper wiring and secure mounting.',
            'category' => 'Electrical'
          ],
          [
            'title' => 'Door Lock Replacement',
            'property' => 'Sunset Apartments',
            'unit' => 'D3',
            'status' => 'rejected',
            'applied_date' => '2026-01-17',
            'client_budget' => 'KES 2,500',
            'your_budget' => 'KES 2,000',
            'your_duration' => '1 hour',
            'message' => 'Quick and efficient lock replacement service. I carry various lock types.',
            'category' => 'Carpentry'
          ],
          [
            'title' => 'Interior Wall Painting',
            'property' => 'Riverside Complex',
            'unit' => 'H9',
            'status' => 'accepted',
            'applied_date' => '2026-01-15',
            'client_budget' => 'KES 12,000',
            'your_budget' => 'KES 11,000',
            'your_duration' => '2 days',
            'message' => 'Professional painter with 8 years experience. Clean work, attention to detail, and excellent finishing.',
            'category' => 'Painting'
          ]
        ];

        foreach ($applications as $application): ?>
          <div class="application-card <?php echo $application['status']; ?>">
            <div class="application-header">
              <div>
                <h3 class="application-title"><?php echo $application['title']; ?></h3>
                <div class="application-meta">
                  <div class="meta-item">
                    <i class="fas fa-building"></i>
                    <span><?php echo $application['property']; ?> - Unit <?php echo $application['unit']; ?></span>
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
                <span class="detail-value"><?php echo $application['client_budget']; ?></span>
              </div>
              <div class="detail-row">
                <span class="detail-label">Your Proposed Budget:</span>
                <span class="detail-value" style="font-weight: 600; color: var(--primary-color);"><?php echo $application['your_budget']; ?></span>
              </div>
              <div class="detail-row">
                <span class="detail-label">Your Estimated Duration:</span>
                <span class="detail-value"><?php echo $application['your_duration']; ?></span>
              </div>
            </div>

            <div class="application-message">
              <strong style="color: var(--primary-color); display: block; margin-bottom: 0.5rem;">Your Message:</strong>
              <?php echo $application['message']; ?>
            </div>

            <div class="application-footer">
              <div class="applied-date">
                <i class="fas fa-calendar"></i> Applied on <?php echo date('M d, Y', strtotime($application['applied_date'])); ?>
              </div>
              <div class="action-buttons">
                <button class="btn-action btn-view">
                  <i class="fas fa-eye"></i> View Details
                </button>
                <?php if ($application['status'] === 'accepted'): ?>
                  <button class="btn-action btn-message">
                    <i class="fas fa-comments"></i> Message Client
                  </button>
                <?php elseif ($application['status'] === 'pending'): ?>
                  <button class="btn-action btn-withdraw">
                    <i class="fas fa-trash"></i> Withdraw
                  </button>
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
    document.addEventListener('DOMContentLoaded', function() {
      // View Details buttons
      document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function() {
          alert('View detailed application information');
        });
      });

      // Withdraw buttons
      document.querySelectorAll('.btn-withdraw').forEach(button => {
        button.addEventListener('click', function() {
          if (confirm('Are you sure you want to withdraw this application?')) {
            alert('Application withdrawn successfully');
          }
        });
      });

      // Message buttons
      document.querySelectorAll('.btn-message').forEach(button => {
        button.addEventListener('click', function() {
          alert('Open messaging interface');
        });
      });
    });
  </script>
</body>

</html>