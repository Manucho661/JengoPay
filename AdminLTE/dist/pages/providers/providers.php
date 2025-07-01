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
  <style>
    body {
      background-image: url('building2.jpg');
      font-family: 'Segoe UI', sans-serif;
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      height: 100vh;
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
    }

    .nav-tabs .nav-link:hover {
      background-color: #FFC107;
      color: #00192D;
    }

    .nav-tabs .nav-link.active {
      background-color: #FFC107 !important;
      color: #00192D !important;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
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
  </style>
</head>

<body>

  <!-- Header -->
  <div class="header-bar">
    <h1><i class="fas fa-users" style="color: #FFC107;"></i> Welcome Jackson</h1>
    <div class="nav-links">
      <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
      <a href="units_page.php"><i class="fas fa-building"></i> Units</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>

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
              <button class="nav-link active" id="find-tab" data-bs-toggle="tab" data-bs-target="#find" type="button" role="tab">Find a Job</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="apps-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button" role="tab">Your Applications</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Previous Jobs</button>
            </li>
          </ul>

          <div class="tab-content bg-light" id="jobTabsContent">
            <!-- FIND A JOB -->
            <div class="tab-pane fade show active" id="find" role="tabpanel">
              <div class="section-title text-mute">Available Jobs</div>

              <!-- Scrollable container for job cards -->
              <div style="max-height: 500px; overflow-y: auto; padding-right: 10px;">

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
                        <div class="text-end" style="white-space: nowrap;">
                          <button class="btn btn-outline-primary">Apply</button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p class="text-muted">No requests found.</p>
                <?php endif; ?>

              </div> <!-- End scrollable -->
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
              <a href="#" class="text-decoration-none text-dark">IT & Software</a>
              <a href="#" class="text-decoration-none text-dark">Accounting & Finance</a>
              <a href="#" class="text-decoration-none text-dark">Sales & Marketing</a>
              <a href="#" class="text-decoration-none text-dark">Education & Training</a>
              <a href="#" class="text-decoration-none text-dark">Engineering</a>
              <a href="#" class="text-decoration-none text-dark">Healthcare</a>
              <a href="#" class="text-decoration-none text-dark">Customer Service</a>
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
        </div>

      </div> <!-- end .row -->
    </div> <!-- end .container -->
  </div> <!-- end .container-fluid -->



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Read More / Less Script -->
  <script>
    document.querySelectorAll('.job-description').forEach(function(desc) {
      const fullText = desc.textContent.trim();
      const maxLength = 200;

      if (fullText.length > maxLength) {
        const shortText = fullText.slice(0, maxLength).trim();

        desc.setAttribute('data-full', fullText);
        desc.setAttribute('data-short', shortText);

        desc.innerHTML = `
          ${shortText}... <span class="read-toggle">Read more</span>
        `;

        desc.querySelector('.read-toggle').addEventListener('click', function toggleHandler() {
          const isShort = desc.textContent.trim().endsWith('Read more');
          desc.innerHTML = isShort ?
            `${fullText} <span class="read-toggle">Read less</span>` :
            `${shortText}... <span class="read-toggle">Read more</span>`;

          desc.querySelector('.read-toggle').addEventListener('click', toggleHandler);
        });
      }
    });
  </script>
</body>

</html>