<?php
// 🔌 Include your PDO database connection
include '../db/connect.php';

// 📥 Fetch maintenance requests using PDO
try {
  $stmt = $pdo->prepare("SELECT * FROM maintenance_requests ORDER BY request_date DESC");
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
  <title>Job Seeker Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-image: url('building2.jpg');
      font-family: 'Segoe UI', sans-serif;
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      min-height: 100vh;

    }

    .app-wrapper {
      display: grid;
      grid-template-columns: auto 1fr;
      grid-template-rows: 60px 1fr;
      grid-template-areas:
        "header header"
        "main main"
        "footer footer";
      max-width: 100vw;
      min-height: 100vh;
    }

    .app-wrapper,
    .row,
    .col {
      overflow: visible !important;
      /* ensure no clipping */
    }

    :root {
      --header-height: 10vh;
    }

    .header {
      grid-area: header;
      background-color: #00192D;
      color: white;
      height: var(--header-height);
      padding: 20px;
      vertical-align: middle;
    }

    .main {
      grid-area: main;
      /* padding: 20px; */
      padding-top: var(--header-height);

    }

    .footer {
      grid-area: footer;
      background-color: #00192D;
      color: #fff;
      padding: 40px 20px 20px;
      font-size: 14px;
    }

    .footer-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .footer-section {
      flex: 1 1 250px;
      min-width: 200px;
    }

    .footer-section h2,
    .footer-section h3 {
      color: #FFC107;
      margin-bottom: 15px;
    }

    .footer-section ul {
      list-style: none;
      padding: 0;
    }

    .footer-section ul li {
      margin-bottom: 10px;
    }

    .footer-section ul li a {
      color: #fff;
      text-decoration: none;
    }

    .footer-section ul li a:hover {
      text-decoration: underline;
      color: #FFC107;
    }

    .footer-bottom {
      text-align: center;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      margin-top: 30px;
      padding-top: 15px;
      color: #ccc;
    }

    .header-bar {
      background-color: #00192D;
      color: #FFFFFF;
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-bar .nav-links a {
      color: #FFC107;
      /* margin-left: 20px; */
      text-decoration: none;
    }

    .header-bar .nav-links a:hover {
      color: #FF5722;
    }

    .nav-tabs {
      margin-bottom: 1.5rem;
    }

    .nav-tabs .nav-link {
      background-color: #00192D;
      color: #FFC107;
      border-radius: 8px;
      font-weight: 500;
      padding: 10px 20px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .nav-tabs .nav-link:hover {
      background-color: #FFC107;
      color: #00192D;
       z-index: 2;
    }

    .nav-tabs .nav-link.active {
      background-color: #FFC107 !important;
      color: #00192D !important;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    .nav-tabs .nav-link::before{
      content: "";
      position: absolute;
      top: 0;
      left: 100%;
      width: 100%;
      height: 100%;
      background-color: #FFC107;
      transition: all 0.4s ease;
      z-index: 0;
    }
  .nav-tabs .nav-link:hover::before {
      left: 0;
    }

    .nav-tabs .nav-link:hover {
      color: #00192D;
    }

    .nav-tabs .nav-link span {
      position: relative;
      z-index: 2;
    }
    .tab-content {
      background-color: #fff;
      border: 1px solid #dee2e6;
      border-top: none;
      border-radius: 0 0 8px 8px;
      padding: 24px;
    }

    .job-card {
      width: 100%;
      padding: 24px;
      margin-bottom: 24px;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 768px) {
      .row {
        gap: 2rem;
      }
    }



    .job-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: #0d6efd;
    }

    .badge-skill {
      background-color: #e9ecef;
      color: #333;
      margin-right: 5px;
    }

    .section-title {
      font-weight: 600;
      margin-bottom: 20px;
    }

    .read-toggle {
      color: #0d6efd;
      cursor: pointer;
      font-weight: 500;
    }

    /* Sidebar links hover effect */
    .text-dark:hover {
      color: #FF5722 !important;
      text-decoration: underline;
    }

    /* Optional: make scroll prettier */
    div[style*="overflow-y: auto"]::-webkit-scrollbar {
      width: 6px;
    }

    div[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
      background-color: #ccc;
      border-radius: 3px;
    }

    .apply-btn:hover {
      text-decoration: none !important;
    }
  </style>
</head>

<body>
  <div class="app-wrapper">
    <!-- Header -->
    <div class="header header-bar" style="position: fixed; width: 100%; display: flex; justify-content: space-between; align-items: center; background-color: #00192D; padding: 10px 20px; z-index: 1000; color: white;">
      <!-- Left side: Logo and Welcome -->
      <div style="display: flex; align-items: center; gap: 15px;">
        <div style="font-size: 22px; font-weight: bold; color: #FFC107; letter-spacing: 1px;">
          <h2>Jengo<span style="color: white;">Pay</span></h2>
        </div>
        <h1 style="margin: 0; font-size: 20px;">
          <i class="fas fa-users" style="color: #FFC107;"></i> Welcome Jackson
        </h1>
      </div>

      <!-- Right side: Nav links -->
      <div class="nav-links" style="display: flex; gap: 20px;">
        <a href="dashboard.php" style="color: white; text-decoration: none;"><i class="fas fa-home"></i> Dashboard</a>
        <a href="units_page.php" style="color: white; text-decoration: none;"><i class="fas fa-building"></i> Units</a>
        <a href="logout.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <div class="main">
      <!-- Search Bar -->
      <div class="container-fluid py-3">
        <div class="container">
          <form class="d-flex justify-content-center" role="search">
            <input class="form-control me-2 w-50" type="search" placeholder="Search Jobs" aria-label="Search" style="border-radius: 8px; border: none;">
            <button class="btn" type="submit" style="background-color: #FFC107; color: #00192D; font-weight: 600;">Search</button>
          </form>
        </div>
      </div>

      <!-- Main Content Row -->
      <div class="container-fluid py-5">
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
                  <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab"><span>Previous Jobs</span></button>
                </li>
              </ul>

              <div class="tab-content bg-light" id="jobTabsContent">
                <!-- FIND A JOB -->
                <div class="tab-pane fade show active" id="find" role="tabpanel">
                  <div class="section-title text-mute">Available Jobs</div>

                  <!-- Scrollable container for job cards -->
                  <div style=" padding-right: 10px;">

                    <?php if (!empty($requests)): ?>
                      <?php foreach ($requests as $row): ?>
                        <div class="job-card mb-3 p-3 bg-white shadow-sm rounded">
                          <div class="d-flex justify-content-between">
                            <div style="width: 100%;">
                              <div class="request" style="font-weight:bold; color: #00192D;">
                                <?= htmlspecialchars($row['request']) ?>
                              </div>
                              <div class="text-muted mb-2">
                                Posted: <?= date('M j, Y', strtotime($row['request_date'])) ?> •
                                Budget: <?= !empty($row['budget']) ? htmlspecialchars($row['budget']) : 'N/A' ?> •
                                <?= !empty($row['location']) ? htmlspecialchars($row['location']) : 'Remote' ?>
                              </div>
                              <span class="badge badge-skill">React</span>
                              <span class="badge badge-skill">Tailwind</span>
                              <span class="badge badge-skill">Git</span>
                              <p class="mt-2 job-description" style="font-style:italic;">
                                <?= nl2br(htmlspecialchars($row['description'])) ?>
                              </p>
                            </div>
                            <!-- Apply Button -->
                            <div class="text-end" style="white-space: nowrap;">
                              <button class="btn btn-outline-warning apply-btn text-dark" data-bs-toggle="modal" data-bs-target="#applyModal">Apply</button>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <p class="text-muted">No requests found.</p>
                    <?php endif; ?>

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
                        <form id="applyForm" class="px-2" method="POST" action="submit_application.php">

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
                            <input type="number" name="your_price" class="form-control rounded-pill" id="yourPrice" step="1" min="0" placeholder="4500">
                          </div>


                          <!-- Duration -->
                          <div class="mb-3" style="font-weight: bold; font-style:oblique;">
                            <label for="duration" class="form-label">
                              <i class="bi bi-clock me-1" style="color: #00192D;"></i> Select Duration
                            </label>
                            <select class="form-select" name="duration" id="duration" onchange="handleDurationChange(this)">
                              <option selected disabled>Select duration</option>
                              <option value="less than 24hrs">Less than 24hrs</option>
                              <option value="1 day">1 day</option>
                              <option value="2 days">2 days</option>
                              <option value="3 days">3 days</option>
                              <option value="other">Other</option>
                            </select>
                          </div>

                          <!-- Custom Duration -->
                          <div class="mb-3 d-none" id="customDurationDiv" style="font-weight: bold; font-style:oblique;">
                            <label for="customDuration" class="form-label">
                              <i class="bi bi-calendar-plus me-1" style="color: #00192D;"></i> Specify Duration
                            </label>
                            <input type="text" name="custom_duration" class="form-control rounded-pill" id="customDuration" placeholder="e.g. 5 days">
                          </div>

                          <!-- Cover Letter -->
                          <div class="mb-3" style="font-weight: bold; font-style:oblique;">
                            <label for="coverLetter" class="form-label">
                              <i class="bi bi-envelope-paper me-1" style="color: #00192D;"></i> Cover Letter
                            </label>
                            <textarea class="form-control rounded-4" name="cover_letter" id="coverLetter" rows="4"
                              placeholder="Explain why you are the best fit for this job..."></textarea>
                          </div>

                        </form>
                      </div>

                      <!-- Modal Footer -->
                      <div class="modal-footer justify-content-center" style="background-color: #f8f9fa;">
                        <button type="submit" class="btn rounded-pill px-4 py-2" form="applyForm"
                          style="background-color: #00192D; color: #FFC107;">
                          <i class="bi bi-check2-circle me-1"></i> Submit Application
                        </button>
                      </div>

                    </div>
                  </div>
                </div>

                <!-- APPLICATIONS -->
                <div class="tab-pane fade" id="applications" role="tabpanel">
                  <div class="section-title">Jobs You've Applied To</div>
                  <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      Backend Developer for Payment Gateway
                      <span class="badge bg-info">Under Review</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      Mobile App React Native
                      <span class="badge bg-warning text-dark">Interview Scheduled</span>
                    </li>
                  </ul>
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




  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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

    // Show/hide custom duration field
    function handleDurationChange(select) {
      const customDiv = document.getElementById("customDurationDiv");
      if (select.value === "other") {
        customDiv.classList.remove("d-none");
      } else {
        customDiv.classList.add("d-none");
      }
    }
    window.handleDurationChange = handleDurationChange; // Make function global

    // Submit form using AJAX
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("applyForm");

      form.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("submit_application.php", {
            method: "POST",
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert("Application submitted successfully!");
              const modal = bootstrap.Modal.getInstance(document.getElementById("applyModal"));
              modal.hide();
              form.reset();
              document.getElementById("customDurationDiv").classList.add("d-none");
            } else {
              alert("Failed to submit application: " + (data.error || "Unknown error"));
              console.error("Server error:", data.error);
            }
          })
          .catch(error => {
            console.error("Error submitting form:", error);
            alert("Something went wrong while submitting the form.");
          });
      });
    });
  </script>



</body>

</html>