<?php
// Start session
session_start();

// 🔌 Include your PDO database connection
include '../db/connect.php';


// 📥 Check if the user is logged in and their role is 'provider'
if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] === 'provider') {
  // Get the full name from the session and capitalize the first name
  $fullName = $_SESSION['user']['name']; // Assuming first_name is like 'john wangui'
  $serviceProvider = ucwords(strtok($fullName, ' ')); // Get the first word (John)
} else {
  $serviceProvider = ''; // Default if user is not logged in or not a provider
}

// dedicated file for fetching requests
include './actions/getRequests1.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="requestOrders.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<style>
  :root {
    --primary-color: #00192D;
    --accent-color: #FFC107;
    --light-bg: #f8f9fa;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--light-bg);
  }

  
  /* Navigation */
  .navigation {
    background: white;
    padding: 1rem 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
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

  /* Filter Section */
  .filter-section {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  }

  .filter-title {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
  }

  /* Request Card */
  .request-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .request-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
  }

  .request-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
  }

  .request-title {
    color: var(--primary-color);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .category-badge {
    background: var(--accent-color);
    color: var(--primary-color);
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .property-info {
    color: #6c757d;
    margin-bottom: 0.8rem;
  }

  .property-info i {
    color: var(--accent-color);
    margin-right: 0.3rem;
  }

  .request-meta {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
  }

  .meta-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    color: #6c757d;
    font-size: 0.9rem;
  }

  .meta-item i {
    color: var(--primary-color);
  }

  .request-description {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 1rem;
    position: relative;
  }

  .description-short {
    display: block;
  }

  .description-full {
    display: none;
  }

  .description-short.hidden {
    display: none;
  }

  .description-full.visible {
    display: block;
  }

  .read-more-btn {
    color: var(--accent-color);
    background: none;
    border: none;
    padding: 0;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.9rem;
    transition: color 0.3s;
    margin-top: 0.3rem;
    display: inline-block;
  }

  .read-more-btn:hover {
    color: var(--primary-color);
    text-decoration: underline;
  }

  .read-more-btn i {
    margin-left: 0.3rem;
  }

  /* Empty State */
  .empty-state {
    background: white;
    border-radius: 10px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
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
    font-size: 1rem;
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
  }

  .empty-state-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
  }

  .empty-state-btn {
    padding: 0.7rem 1.5rem;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-block;
  }

  .empty-state-btn.primary {
    background: var(--primary-color);
    color: white;
  }

  .empty-state-btn.primary:hover {
    background: var(--accent-color);
    color: var(--primary-color);
    transform: translateY(-2px);
  }

  .empty-state-btn.secondary {
    background: var(--light-bg);
    color: var(--primary-color);
    border: 1px solid #dee2e6;
  }

  .empty-state-btn.secondary:hover {
    background: white;
    border-color: var(--primary-color);
  }

  .request-images {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
  }

  .request-images img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    cursor: pointer;
    transition: transform 0.2s;
  }

  .request-images img:hover {
    transform: scale(1.05);
  }

  .request-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
  }

  .budget-info {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-color);
  }

  .apply-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 0.6rem 1.8rem;
    border-radius: 5px;
    font-weight: 600;
    transition: all 0.3s;
  }

  .apply-btn:hover {
    background: var(--accent-color);
    color: var(--primary-color);
    transform: translateY(-2px);
  }

  /* Sidebar Sections */
  .sidebar-section {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  }

  .sidebar-title {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.1rem;
  }

  .category-list,
  .location-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .filter-btn {
    background: var(--light-bg);
    border: 1px solid #dee2e6;
    color: var(--primary-color);
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-align: left;
    transition: all 0.3s;
    cursor: pointer;
  }

  .filter-btn:hover {
    background: var(--accent-color);
    border-color: var(--accent-color);
  }

  .subscribe-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, #003d5c 100%);
    color: white;
    padding: 2rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
  }

  .subscribe-section h3 {
    margin-bottom: 1rem;
  }

  .subscribe-input {
    display: flex;
    gap: 0.5rem;
  }

  .subscribe-input input {
    flex: 1;
    padding: 0.6rem;
    border: none;
    border-radius: 5px;
  }

  .subscribe-btn {
    background: var(--accent-color);
    color: var(--primary-color);
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 5px;
    font-weight: 600;
    white-space: nowrap;
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

  .pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background: #fff;
  }

  .pagination-info {
    color: #6c757d;
    font-size: 0.9rem;
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
    .request-header {
      flex-direction: column;
    }

    .request-footer {
      flex-direction: column;
      gap: 1rem;
      align-items: stretch;
    }

    .subscribe-input {
      flex-direction: column;
    }
  }
</style>

<body>
  <div class="app-wrapper">
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
            <a href="yourApplications.php"><i class="fas fa-file-alt"></i> Your Applications</a>
            <a href="" class="active"><i class="fas fa-briefcase"></i> Assigned Jobs</a>
            <a href="previousRequests.php"><i class="fas fa-history"></i> Previous Jobs</a>
          </div>
        </div>
      </nav>

      <!-- Main Content Row -->
      <div class="container-fluid py-3">
        <div class="container-fluid">
          <div class="row">

            <!-- LEFT: Job Tabs and Listings -->
            <div class="col-lg-9">
              <!-- Filter Section -->
              <div class="filter-section">
                <h3 class="filter-title"><i class="fas fa-filter"></i> Filter & Search</h3>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" placeholder="Search requests...">
                  </div>
                  <div class="col-md-3 mb-3">
                    <select class="form-select">
                      <option>All Categories</option>
                      <option>Electrical</option>
                      <option>Plumbing</option>
                      <option>Carpentry</option>
                      <option>Painting</option>
                    </select>
                  </div>
                  <div class="col-md-3 mb-3">
                    <button class="btn btn-primary w-100" style="background: var(--primary-color); border: none;">
                      <i class="fas fa-search"></i> Search
                    </button>
                  </div>
                </div>
              </div>

              <!-- Request Cards -->
              <?php
              // Sample data - in real application, this would come from database
            

              // Pagination logic
              $itemsPerPage = 5;
              $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
              $totalItems = count($requests);
              $totalPages = ceil($totalItems / $itemsPerPage);
              $offset = ($currentPage - 1) * $itemsPerPage;
              $currentRequests = array_slice($requests, $offset, $itemsPerPage);


              // Check if there are any requests
              if (empty($currentRequests)): ?>
                <!-- Empty State -->
                <div class="empty-state">
                  <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                  </div>
                  <h3>No Requests Available</h3>
                  <p>
                    There are currently no job requests matching your criteria.
                    Try adjusting your filters or check back later for new opportunities.
                  </p>
                  <div class="empty-state-actions">
                    <a href="?" class="empty-state-btn primary">
                      <i class="fas fa-sync-alt"></i> Refresh Page
                    </a>
                    <a href="#" class="empty-state-btn secondary" onclick="clearFilters(); return false;">
                      <i class="fas fa-filter"></i> Clear Filters
                    </a>
                  </div>
                </div>
                <?php else:
                foreach ($currentRequests as $request): ?>
                  <div class="request-card">
                    <div class="request-header">
                      <div>
                        <h3 class="request-title"><?php echo $request['title']; ?></h3>
                        <div class="property-info">
                          <i class="fas fa-building"></i> <strong><?php echo $request['building_name']; ?></strong> - Unit <?php echo $request['unit']; ?>
                        </div>
                      </div>
                      <span class="category-badge"><?php echo $request['category']; ?></span>
                    </div>

                    <div class="request-meta">
                      <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span><?php echo date('M d, Y', strtotime($request['date'])); ?></span>
                      </div>
                      <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span><?php echo $request['duration']; ?></span>
                      </div>
                    </div>

                    <?php
                    $description = $request['description'];
                    $shortDescription = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                    $needsToggle = strlen($description) > 100;
                    ?>

                    <div class="request-description">
                      <span class="description-short"><?php echo $shortDescription; ?></span>
                      <?php if ($needsToggle): ?>
                        <span class="description-full"><?php echo $description; ?></span>
                        <button class="read-more-btn" onclick="toggleDescription(this)">
                          <span class="more-text">Read More <i class="fas fa-chevron-down"></i></span>
                          <span class="less-text" style="display: none;">Read Less <i class="fas fa-chevron-up"></i></span>
                        </button>
                      <?php endif; ?>
                    </div>

                    <?php if (!empty($request['images'])): ?>
                      <div class="request-images">
                        <?php foreach ($request['images'] as $image): ?>
                          <img src="https://via.placeholder.com/80" alt="Request image">
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>

                    <div class="request-footer">
                      <div class="budget-info">
                        <i class="fas fa-money-bill-wave"></i> <?php echo $request['budget']; ?>
                      </div>
                      <button class="apply-btn">
                        <i class="fas fa-paper-plane"></i> Apply Now
                      </button>
                    </div>
                  </div>
              <?php endforeach;
              endif; ?>
              <!-- Pagination -->
              <?php if ($totalPages > 1): ?>
                <nav aria-label="Request pagination">
                  <ul class="pagination justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                      <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                      <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                      </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                      <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>

                <!-- Pagination Info -->
                <div class="pagination-info text-center mb-4">
                  <p class="text-muted">
                    Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $itemsPerPage, $totalItems); ?>
                    of <?php echo $totalItems; ?> requests
                  </p>
                </div>
              <?php endif; ?>
            </div>

            <!-- RIGHT: Sidebar -->
            <div class="col-lg-3">
              <div class="position-sticky" style="top: 80px; max-height: calc(100vh - 80px); overflow-y: auto;">
                <!-- Subscribe -->
                <div class="p-4 mb-4 shadow-sm bg-white rounded border">
                  <h5 class="fw-bold">📬 Subscribe to Job Alert</h5>
                  <p class="small text-muted">Join thousands getting job updates weekly</p>
                  <input type="email" class="form-control mb-2" placeholder="Enter your email here!">
                  <button class="btn btn-dark w-100" style="background-color:#00192D;">Subscribe</button>
                </div>

                <!-- Category -->
                <div class="p-3 mb-4 shadow-sm bg-white rounded border">
                  <h6 class="fw-bold">🗂 Jobs by Category</h6>
                  <div class="d-flex flex-column gap-2 small">
                    <a href="#" class="text-decoration-none text-dark">Plumber</a>
                    <a href="#" class="text-decoration-none text-dark">Electrician</a>
                    <a href="#" class="text-decoration-none text-dark">HVAC Technician/Heating and Cooling Specialist</a>
                    <a href="#" class="text-decoration-none text-dark">Carpenter, Glazier or Locksmith </a>
                    <a href="#" class="text-decoration-none text-dark">Building Maintenance Technician/Handyman</a>
                    <a href="#" class="text-decoration-none text-dark">Sanitation Worker</a>
                    <a href="#" class="text-decoration-none text-dark">Security Officer</a>
                    <a href="#" class="text-decoration-none text-dark">Janitor</a>
                  </div>
                </div>

                <!-- Location -->
                <div class="p-3 mb-4 shadow-sm bg-white rounded border">
                  <h6 class="fw-bold">📍 Jobs by Location</h6>
                  <div class="d-flex flex-wrap gap-2 small">
                    <a href="#" class="text-decoration-none text-dark">Nairobi</a>
                    <a href="#" class="text-decoration-none text-dark">Mombasa</a>
                    <a href="#" class="text-decoration-none text-dark">Kisumu</a>
                    <a href="#" class="text-decoration-none text-dark">Eldoret</a>
                    <a href="#" class="text-decoration-none text-dark">Nakuru</a>
                    <a href="#" class="text-decoration-none text-dark">Thika</a>
                    <a href="#" class="text-decoration-none text-dark">Kitale</a>
                    <a href="#" class="text-decoration-none text-dark">Machakos</a>
                  </div>
                  <button class="btn btn-warning btn-sm mt-2">View All Locations</button>
                </div>
                <div class="p-4 shadow-sm bg-white border border-warning rounded">
                  <h6 class="fw-bold text-warning mb-2">🔧 Featured Service: Jemo Fixers</h6>
                  <p class="text-dark small mb-1">
                    Need quick repairs? <strong>Jemo Fixers</strong> handles plumbing, electrical, and handyman jobs within hours.
                  </p>
                  <div class="mb-2">
                    <span class="badge bg-warning text-dark me-1">Fast Response</span>
                    <span class="badge bg-light text-dark border">Trusted</span>
                  </div>
                  <a href="#" class="btn btn-sm btn-outline-warning w-100">Hire Jemo Fixers</a>
                </div>
              </div>
            </div>
          </div>

        </div> <!-- end .row -->
      </div> <!-- end .container -->
    </div> <!-- end .container-fluid -->
  </div>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-section about">
        <h2>Jengo Pay</h2>
        <p>Jengo Pay is your trusted platform for managing properties, tenants, and service providers efficiently. Empowering real estate with smart tech.</p>
      </div>

      <div class="footer-section links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#">Dashboard</a></li>
          <li><a href="#">Units</a></li>
          <li><a href="#">Tenants</a></li>
          <li><a href="#">Reports</a></li>
        </ul>
      </div>

      <div class="footer-section contact">
        <h3>Contact Us</h3>
        <p><i class="fas fa-phone-alt"></i> +254 712 345 678</p>
        <p><i class="fas fa-envelope"></i> support@jengopay.co.ke</p>
        <p><i class="fas fa-map-marker-alt"></i> Nairobi, Kenya</p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2025 Jengo Pay. All Rights Reserved.</p>
    </div>
  </footer>

  </div>

  <!-- CHAT AREA -->
  <!-- Floating Mini Chat Panel -->
  <div class="chat-panel" id="chatPanel">
    <div class="chat-panel-header">
      Messages
      <i class="bi bi-x-lg" id="closeChatPanel" style="cursor:pointer;"></i>
    </div>
    <div class="chat-list">
      <div class="chat-item" data-client="Manucho Apartments">
        <strong>Manucho Apartments</strong><br>
        <small>Can we review the last design updates?</small>
      </div>
      <div class="chat-item" data-client="Silver Spoon Apartments">
        <strong>Silver Spoon Apartments</strong><br>
        <small>Got the latest quote?</small>
      </div>
      <div class="chat-item" data-client="White House">
        <strong>White House</strong><br>
        <small>Thanks for sending the document!</small>
      </div>
    </div>
  </div>

  <!-- Right-side Chat Modal -->
  <div class="modal right fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title mb-0" id="chatModalLabel">Chat with Manucho Apartments</h5>
            <small class="text-muted" id="projectSubtitle">Ongoing Project</small>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="chat-body" id="chatBody">
          <div class="message client">
            <div class="bubble">Hello!</div>
          </div>
          <div class="message me">
            <div class="bubble">Hi there 👋</div>
          </div>
        </div>

        <div class="chat-footer">
          <form id="chatForm" class="input-group">
            <input
              type="text"
              id="chatInput"
              class="form-control"
              placeholder="Type a message..."
              required>
            <button class="btn btn-primary" id="chatSendBtn" type="submit">
              <i class="bi bi-send"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script type="module" src="js/main.js"></script>
  <script>
    // Read more / less toggle
    document.querySelectorAll('.job-description').forEach(function(desc) {
      const fullText = desc.textContent.trim();
      const maxLength = 200;

      if (fullText.length > maxLength) {
        const shortText = fullText.slice(0, maxLength).trim();
        desc.setAttribute('data-full', fullText);
        desc.setAttribute('data-short', shortText);

        desc.innerHTML = `
        ${shortText}... <span class="read-toggle" style="color: blue; cursor: pointer;">Read more</span>
      `;

        desc.querySelector('.read-toggle').addEventListener('click', function toggleHandler() {
          const isShort = desc.textContent.trim().endsWith('Read more');
          desc.innerHTML = isShort ?
            `${fullText} <span class="read-toggle" style="color: blue; cursor: pointer;">Read less</span>` :
            `${shortText}... <span class="read-toggle" style="color: blue; cursor: pointer;">Read more</span>`;
          desc.querySelector('.read-toggle').addEventListener('click', toggleHandler);
        });
      }
    });

    // Show/hide custom duration
    function handleDurationChange(select) {
      const customDiv = document.getElementById("customDurationDiv");
      if (select.value === "other") {
        customDiv.classList.remove("d-none");
      } else {
        customDiv.classList.add("d-none");
      }
    }
    window.handleDurationChange = handleDurationChange;

    // Form submission with validation
  </script>




</body>

</html>