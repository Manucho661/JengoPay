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

// 📥 Fetch maintenance requests using PDO
try {
    $stmt = $pdo->prepare("SELECT * FROM maintenance_requests WHERE availability = 'unavailable' ORDER BY created_at DESC");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC); // 🎯 Fetch as associative array
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
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

<body>
  <div class="app-wrapper">
    <!--Start Header -->
    <?php include "./includes/header.php" ?>
    <!-- end header -->

    <div class="main py-4">
      <!-- Search Bar -->
      <div class="container-fluid">
        <div class="container">
          <form class="d-flex justify-content-center" role="search">
            <input class="form-control me-2 w-50" type="search" placeholder="Search Jobs" aria-label="Search" style="border-radius: 8px; border: none;">
            <button class="btn" type="submit" style="background-color: #FFC107; color: #00192D; font-weight: 600;">Search</button>
          </form>
        </div>
      </div>

      <!-- Main Content Row -->
      <div class="container-fluid py-3">
        <div class="container-fluid">
          <div class="row">

            <!-- LEFT: Job Tabs and Listings -->
            <div class="col-lg-9">
              <ul class="nav nav-tabs mb-3 gap-3" id="jobTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active " id="find-tab" data-bs-toggle="tab" data-bs-target="#find" type="button" role="tab"> <span>Find a Job</span> </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="apps-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button" role="tab"><span>Your Applications</span> </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="apps-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab"><span>Assigned Jobs</span> </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab"><span>Previous Jobs</span></button>
                </li>
              </ul>

              <div class="tab-content rounded bg-light" id="jobTabsContent">
                <!-- FIND A JOB -->
                <div class="tab-pane fade show active" id="find" role="tabpanel">
                  <div class="section-title text-mute">Available Jobs</div>

                  <!-- Scrollable container for job cards -->
                  <div id="requests-list-container" style=" padding-right: 10px;">



                  </div> <!-- End scrollable -->
                </div>
                
                <!-- Apply Modal -->
                <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg">

                      <!-- Modal Header -->
                      <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
                        <h5 class="modal-title d-flex align-items-center" id="applyModalLabel">
                          <i class="bi bi-briefcase-fill me-2"></i> Apply for Job
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <!-- Modal Body -->
                      <div class="modal-body">
                        <form id="applyForm" class="px-2">

                          <input type="hidden" id="requestId" name="requestId" value="">

                          <!-- Client Price (Plain Text) -->
                          <div class="mb-3" style="font-weight: bold; font-style: oblique;">
                            <label class="form-label">
                              <i class="bi bi-tags me-1" style="color: #00192D;"></i> Client Price
                            </label>
                            <input type="hidden" name="client_price" value="5000">
                            <div class="form-control-plaintext ps-3" style="border: 1px solid #ced4da; border-radius: 50px; background-color: #f8f9fa;">
                              5000
                            </div>
                          </div>

                          <!-- Your Price -->
                          <div class="mb-3" style="font-weight: bold; font-style: oblique;">
                            <label for="yourPrice" class="form-label">
                              <i class="bi bi-currency-dollar me-1" style="color: #00192D;"></i> Your Price
                            </label>
                            <input type="number" name="your_price" class="form-control rounded-pill" id="yourPrice" step="1" min="0" placeholder="4500" required>
                          </div>

                          <!-- Duration -->
                          <div class="mb-3" style="font-weight: bold; font-style: oblique;">
                            <label for="duration" class="form-label">
                              <i class="bi bi-clock me-1" style="color: #00192D;"></i> Select Duration
                            </label>
                            <select class="form-select" name="duration" id="duration" onchange="handleDurationChange(this)" required>
                              <option selected disabled value="">Select duration</option>
                              <option value="less than 24hrs">Less than 24hrs</option>
                              <option value="1 day">1 day</option>
                              <option value="2 days">2 days</option>
                              <option value="3 days">3 days</option>
                              <option value="other">Other</option>
                            </select>
                          </div>

                          <!-- Custom Duration -->
                          <div class="mb-3 d-none" id="customDurationDiv" style="font-weight: bold; font-style: oblique;">
                            <label for="customDuration" class="form-label">
                              <i class="bi bi-calendar-plus me-1" style="color: #00192D;"></i> Specify Duration
                            </label>
                            <input type="text" name="custom_duration" class="form-control rounded-pill" id="customDuration" placeholder="e.g. 5 days">
                          </div>

                          <!-- Modal Footer -->
                          <div class="modal-footer justify-content-center" style="background-color: #f8f9fa;">
                            <button type="submit" class="btn rounded-pill px-4 py-2" style="background-color: #00192D; color: #FFC107;">
                              <i class="bi bi-check2-circle me-1"></i> Submit Application
                            </button>
                          </div>

                        </form>
                      </div>
                    </div>
                  </div>
                </div>


                <!-- APPLICATIONS -->
                <div class="tab-pane fade" id="applications" role="tabpanel">
                </div>

                <!-- Assignments-->
                <div class="tab-pane fade" id="assignments" role="tabpanel">
                </div>
                <!-- HISTORY -->
                <div class="tab-pane fade" id="history" role="tabpanel">
                  <div class="section-title">Your Completed Jobs</div>
                  <div class="job-card">
                    <div class="job-title">E-commerce Admin Dashboard</div>
                    <div class="text-muted mb-1">Completed: May 2025</div>
                    <p>Built a full admin dashboard using React and Bootstrap. Client rated: ⭐ 5.0</p>
                  </div>
                  <div class="job-card">
                    <div class="job-title">Company Portfolio Website</div>
                    <div class="text-muted mb-1">Completed: March 2025</div>
                    <p>Delivered a responsive portfolio site in 1 week. Client feedback: "Very professional and fast!"</p>
                  </div>
                </div>
              </div>
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