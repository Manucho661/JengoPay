<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Job Seeker Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
     background-image: url('building2.jpg');  
      /* background-color: url(); */
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
      margin-left: 20px;
      text-decoration: none;
    }
    .header-bar .nav-links a:hover {
      color: #FF5722;
    }

    .nav-tabs .nav-link.active {
      background-color: #fff;
      border-color: #dee2e6 #dee2e6 #fff;
      font-weight: bold;
    }

    .tab-content {
      background-color: #fff;
      border: 1px solid #dee2e6;
      border-top: none;
      border-radius: 0 0 8px 8px;
      padding: 24px;
    }

    .job-card {
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
      background-color: #fff;
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

    .action-buttons {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .section-title {
      font-weight: 600;
      margin-bottom: 20px;
    }


  </style>
</head>
<body>
  <div class="header-bar">
    <h1><i class="fas fa-users" style="color: #FFC107;"></i> Welcome Jackson</h1>
    <div class="nav-links">
      <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
      <a href="units_page.php"><i class="fas fa-building"></i> Units</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
  <div class="container py-5">
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" id="jobTabs" role="tablist">
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
    <!-- Tab Content -->
    <div class="tab-content" id="jobTabsContent">
      <!-- FIND A JOB -->
      <div class="tab-pane fade show active" id="find" role="tabpanel">
        <div class="section-title">Available Jobs</div>
        <!-- Job Card 1 -->
        <div class="job-card">
          <div class="d-flex justify-content-between">
            <div>
              <div class="job-title">React Developer for Startup Landing Page</div>
              <div class="text-muted mb-2">Posted: 2 days ago • Budget: $500 • Remote</div>
              <span class="badge badge-skill">React</span>
              <span class="badge badge-skill">Tailwind</span>
              <span class="badge badge-skill">Git</span>
              <p class="mt-2">Looking for a front-end dev to create a sleek landing page for our SaaS tool...</p>
            </div>
            <div class="text-end">
              <button class="btn btn-outline-primary">Apply</button>
            </div>
          </div>
        </div>
        <!-- Job Card 2 -->
        <div class="job-card">
          <div class="d-flex justify-content-between">
            <div>
              <div class="job-title">Full Stack Developer (React + Node.js)</div>
              <div class="text-muted mb-2">Posted: 4 days ago • Hourly: $30/hr • Nairobi Preferred</div>
              <span class="badge badge-skill">Node.js</span>
              <span class="badge badge-skill">Firebase</span>
              <span class="badge badge-skill">REST API</span>
              <p class="mt-2">We need someone to build an internal dashboard to manage user analytics and feedback...</p>
            </div>
            <div class="text-end">
              <button class="btn btn-outline-primary">Apply</button>
            </div>
          </div>
        </div>
      </div>
      <!-- YOUR APPLICATIONS -->
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
      <!-- PREVIOUS JOBS -->
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
