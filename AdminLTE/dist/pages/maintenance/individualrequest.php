<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE | Dashboard v2</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE | Dashboard v2" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />

  <!-- LINKS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="expenses.css">
  <!-- scripts for data_table -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Pdf pluggin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f8fafc, #ffffff);
      color: #333;
    }

    .app-wrapper {
      background-color: rgba(128, 128, 128, 0.1);
    }

    .layout {
      display: flex;
      height: calc(100vh - 60px);
      width: 100%;
      overflow: hidden;
    }

    .sidebar-container {
      width: 250px;
      background-color: #00192D;
      color: #fff;
      overflow-y: auto;
    }

    .request-sidebar {
      background: #ffffff;
      display: flex;
      flex-direction: column;
      border-right: 1px solid #e0e0e0;
    }

    .request-sidebar h3 {
      background: #00192D;
      color: white;
      padding: 1.2rem;
      font-size: 1.2rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 0;
      /* border-top-right-radius: 10px; */
      border-top-left-radius: 10px;
    }

    .search-bar {
      padding: 1rem;
      background: #fff;
      border-bottom: 1px solid #e0e0e0;
    }

    .search-bar input {
      width: 100%;
      padding: 0.6rem 1rem;
      border-radius: 30px;
      border: 1px solid #ced4da;
      outline: none;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .search-bar input:focus {
      border-color: #FFC107;
      box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.25);
    }

    /* IMPROVED REQUEST LIST STYLES */
    .request-list {
      list-style: none;
      padding: 0;
      margin: 0;
      max-height: calc(100vh - 180px);
      overflow-y: auto;
    }

    .request-item {
      padding: 15px;
      border-bottom: 1px solid rgba(0, 25, 45, 0.1);
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 12px;
      background: white;
      margin: 8px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .request-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 25, 45, 0.1);
      background: #FFF7E0;
    }

    .request-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #00192D;
      color: #FFC107;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    .request-content {
      flex-grow: 1;
      min-width: 0;
    }

    .request-desc {
      font-weight: 500;
      color: #00192D;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 4px;
    }

    .request-meta {
      display: flex;
      gap: 10px;
      font-size: 0.85rem;
      color: #6c757d;
      flex-wrap: wrap;
    }

    .request-date {
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .request-status {
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .badge-new {
      background: #FFC107;
      color: #00192D;
      padding: 3px 8px;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-left: auto;
      flex-shrink: 0;
    }

    /* Status indicators */
    .status-pending {
      color: #FFC107;
    }

    .status-completed {
      color: #28a745;
    }

    .status-in-progress {
      color: #17a2b8;
    }

    .status-cancelled {
      color: #dc3545;
    }

    /* Priority indicators */
    .priority-high {
      color: #dc3545;
    }

    .priority-medium {
      color: #fd7e14;
    }

    .priority-low {
      color: #28a745;
    }

    .main-content {
      /* flex-grow: 1; */
      /* padding: 2rem; */
      /* overflow-y: auto; */
    }

    .detail-row {
      margin-bottom: 1.4rem;
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 1rem;
      color: #333;
    }

    .detail-label {
      font-weight: 600;
      min-width: 150px;
      color: #00192D;
    }

    .detail-icon {
      color: #FFC107;
      font-size: 1.2rem;
      width: 28px;
      text-align: center;
    }



    .photo-preview {
      border-radius: 10px;
      border: 1px solid #ccc;
      max-width: 100%;
      margin-top: 5px;
    }

    .no-selection {
      text-align: center;
      margin-top: 20%;
      color: #999;
      font-size: 1.3rem;
    }

    footer {
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
    }

    .row-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
      padding: 1.5rem;
      transition: all 0.3s ease;
      border-left: 5px solid transparent;
    }

    .row-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
      border-left: 5px solid #FFC107;
      background-color: #fdfaf3;
    }

    .detail-row {
      display: flex;
      align-items: center;
      gap: 8px;
      line-height: 1.3;
    }

    .detail-icon {
      width: 18px;
      text-align: center;
      color: #FFC107;
    }

    .detail-label {
      font-weight: 500;
      color: #FFC107;
      min-width: fit-content;
    }

    .photo-preview {
      max-height: 250px;
      object-fit: cover;
    }


    .active-request {
      background-color: #FFF7E0;
      border-left: 3px solid #FFC107;
    }

    /* Button Container */
    /* Button Container */
    .button-container {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      width: 100%;
      padding: 0.5rem;
    }

    /* Base Button Styles */
    .availability-btn,
    .unassigned-btn,
    .paid-btn {
      padding: 0.625rem 1rem;
      font-weight: 500;
      width: 100%;
      border: none;
      border-radius: 0.25rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Unassigned Button */
    .unassigned-btn {
      background-color: white;
      color: #00192D;
      border: 1px solid #00192D !important;
    }

    /* Paid Button */

    /* Button Icons */
    .availability-btn i,
    .unassigned-btn i,
    .paid-btn i {
      width: 1.25rem;
      text-align: center;
      margin-right: 0.5rem;
    }

    /* Hover States */
    .availability-btn:hover {
      background-color: #5a6268;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .unassigned-btn:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .paid-btn:hover {
      background-color: #e0a800;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Active States */
    .availability-btn:active,
    .unassigned-btn:active,
    .paid-btn:active {
      transform: translateY(0);
      box-shadow: none;
    }

    /* Focus States */
    .availability-btn:focus,
    .unassigned-btn:focus,
    .paid-btn:focus {
      outline: none;
      box-shadow: 0 0 0 0.25rem rgba(0, 25, 45, 0.25);
    }

    /* Secondary Buttons Container */
    .secondary-buttons {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      width: 100%;
      display: none;
      /* Hidden by default */
    }

    .btn:focus {
      box-shadow: none !important;
      outline: none;
    }

    .btn:hover {
      border: 1px solid #FFC107 !important;
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-dark" style="">
  <div class="app-wrapper">
    <!--begin::Header-->
    <?php include_once '../includes/header.php' ?>
    <!--end::Header-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
          <!--begin::Brand Text-->
          <span class="brand-text font-weight-light"><b class="p-2"
              style="background-color:#FFC107; border:2px solid #FFC107; border-top-left-radius:5px; font-weight:bold; color:#00192D;">BT</b><b
              class="p-2"
              style=" border-bottom-right-radius:5px; font-weight:bold; border:2px solid #FFC107; color: #FFC107;">JENGOPAY</b></span>
        </a>
        </span>
        <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
      </div>
      <!--end::Sidebar Brand-->
      <!--begin::Sidebar Wrapper-->
      <div> <?php include_once '../includes/sidebar.php'; ?> </div>
      <!--end::Sidebar Wrapper-->
    </aside>

    <!-- Main Layout -->
    <main class="app-main">
      <!--begin::App Content Header-->

      <div class="app-content">
        <p class="text-muted mt-2">Manage a maintanance Request</p>
        <div class="container-fluid rounded-2 mb-2">
          <div class="row p-1" style="background-color: #E6EAF0;">
            <div class="col-md-6 p-0">
              <a href="javascript:history.back()"
                class="btn"
                style="background-color: white; color: #00192D; font-weight: 500; width:100%; margin-right:2px;">
                <i class="bi bi-arrow-left"></i> Go Back
              </a>
            </div>
            <div class="col-md-6 p-0">
              <button id="availabilityBtn"
                class="btn"
                style="background-color: white; color: #00192D; font-weight: 500; width:100%; margin-left:2px;">
                <i class="bi bi-arrow-left"></i> Unavailable
              </button>
            </div>
          </div>
        </div>
        <div class="container-fluid rounded-2 p-1" style="background: #E6EAF0 !important; ">
          <div class="row">
            <div class="col-md-4" style="overflow: hidden; padding-right:2px;">
              <div class="request-sidebar rounded-2">
                <!-- <h3><i class="fa-solid fa-screwdriver-wrench"></i>Request NO 40</h3> -->
                <div class="d-flex flex-column gap-2 p-2">
                  <!-- Availability Button -->
                  <p>In Progress</p>
                  <!-- Secondary Buttons Container -->
                  <div id="secondaryButtons" class="secondary-buttons p-1 rounded-2" style="background-color: #E6EAF0;">
                    <button id="assign" class="btn unassigned-btn" onclick="showProposals()" id="showProposal" data-request-id="123">
                      <i class="fas fa-user-clock me-2"></i> UnAssigned
                    </button>
                    <button class="btn paid-btn">
                      <i class="fas fa-check-circle me-2"></i> Paid
                    </button>
                  </div>
                </div>

                <div class="search-bar rounded-2">
                  <div class="text-muted">Other Requests</div>
                  <input class="rounded-2" type="text" id="searchInput" placeholder="Search by unit, category, or property...">
                </div>
                <ul class="request-list" id="requestList"></ul>
              </div>
            </div>
            <div class="col-md-8" style="padding-right:10px; padding-left:0; padding-top:0 !;">
              <div class="main-content" id="detailsPanel">
                <!-- content displays here -->
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  <!-- Footer -->
  <footer class="app-footer">
    <!--begin::To the end-->
    <div class="float-end d-none d-sm-inline">Anything you want</div>
    <!--end::To the end-->
    <!--begin::Copyright-->
    <strong>
      Copyright &copy; 2014-2024&nbsp;
      <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;">JENGO PAY</a>.
    </strong>
    All rights reserved.
    <!--end::Copyright-->
  </footer>

  <!-- ASSign Modal -->
  <div class="modal fade" id="assignProviderModal" tabindex="-1" aria-labelledby="assignProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="assignProviderModalLabel">
            <i class="bi bi-person-check-fill me-2"></i>Assign Service Provider
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Form -->
        <form id="assignProviderForm">
          <div class="modal-body">

            <!-- Hidden Fields -->
            <input type="hidden" name="maintenance_request_id" id="maintenance_request_id">
            <input type="hidden" name="unit_id" id="unit_id">

            <!-- Service Provider Dropdown -->
            <div class="mb-3">
              <label for="service_provider_id" class="form-label">
                <i class="bi bi-tools me-1"></i>Service Provider
              </label>
              <select class="form-select" name="service_provider_id" id="service_provider_id" required>
                <option selected disabled value="">Select a provider</option>
                <!-- Populate dynamically -->
                <option value="1">John Doe - Plumbing</option>
                <option value="2">Jane Smith - Electrical</option>
              </select>
            </div>

            <!-- Scheduled Date -->
            <div class="mb-3">
              <label for="scheduled_date" class="form-label">
                <i class="bi bi-calendar-event me-1"></i>Scheduled Date
              </label>
              <input type="date" class="form-control" name="scheduled_date" id="scheduled_date">
            </div>

            <!-- Notes / Instructions -->
            <div class="mb-3">
              <label for="instructions" class="form-label">
                <i class="bi bi-pencil-square me-1"></i>Instructions
              </label>
              <textarea class="form-control" name="instructions" id="instructions" rows="3" placeholder="Any special notes..."></textarea>
            </div>

          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle me-1"></i>Cancel
            </button>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check2-circle me-1"></i>Assign
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
  </div>
  <div id="customBackdrop" class="modal-backdrop fade d-none"></div>
  <div id="proposalContainer" class="container proposalContainer py-2 fade d-none custom-modal bg-light" style="overflow: auto;">
    <div class="d-flex justify-content-between mb-2">
      <div class=" text-left" style="color:rgb(0 28 63 / 60%); font-weight:500;"><b> Provider Applications</b></div>

      <div class="text-center">
        <button class="bg-secondary text-white px-4 py-0 rounded hover:bg-blue-600">← Go Back</button>
      </div>

      <div class="text-right">
        <button class="text-gray-500 text-xl rounded hover:text-white hover:bg-red-500 transition">&times;</button>
      </div>
    </div>
    <div id="job_proposals_container">
      <div class="proposal-card mt-2">
        <div class="proposal-header d-flex align-items-start">
          <img src="https://i.pravatar.cc/70" alt="Profile Picture" class="profile-pic me-3">
          <div>
            <h5>Jane Doe <span class="badge bg-success">Top Rated</span></h5>
            <p>Full Stack Developer | React & Node.js</p>
          </div>
          <div class="ms-auto proposal-meta text-end">
            <h6>$25/hr</h6>
            <small>5 days delivery</small><br>
            <small class="text-success">✅ 42 jobs completed</small>
          </div>
        </div>

        <hr>

        <p><strong>Cover Letter:</strong> I'm excited to help build your job board! I have 3 years of experience with React and recently completed a similar project...</p>
        <p><strong>Location:</strong> Nairobi, Kenya (GMT+3)</p>

        <div class="d-flex justify-content-end mt-3">
          <button class="btn btn-outline-secondary btn-sm btn-action">Message</button>
          <button class="btn btn-outline-primary btn-sm btn-action">Shortlist</button>
          <button class="btn btn-outline-danger btn-sm">Decline</button>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const requestList = document.getElementById('requestList');
    const detailsPanel = document.getElementById('detailsPanel');
    const searchInput = document.getElementById('searchInput');
    let allRequests = [];
    let currentRequestId = null;

    // Get status icon mapping
    const statusIcons = {
      'Pending': 'fa-clock',
      'Completed': 'fa-check-circle',
      'In Progress': 'fa-spinner',
      'Cancelled': 'fa-times-circle'
    };

    // Get priority icon mapping
    const priorityIcons = {
      'High': 'fa-arrow-up',
      'Medium': 'fa-equals',
      'Low': 'fa-arrow-down'
    };

    fetch('get_requests.php')
      .then(res => res.json())
      .then(requests => {
        console.log(requests); // 👈 This prints the entire response data
        if (!requests.length || requests.error) {
          detailsPanel.innerHTML = `<div class="no-selection"><i class="fa-solid fa-triangle-exclamation"></i> No requests found.</div>`;
          return;
        }
        allRequests = requests;
        renderRequestList(allRequests);

        // Automatically show the first request
        if (allRequests.length > 0) {
          showRequestDetails(allRequests[0]);
          // Highlight the first item
          const firstItem = requestList.querySelector('.request-item');
          if (firstItem) {
            firstItem.classList.add('active-request');
          }
        }
      });

    function renderRequestList(requests) {
      requestList.innerHTML = '';

      if (requests.length === 0) {
        detailsPanel.innerHTML = `<div class="no-selection"><i class="fa-solid fa-triangle-exclamation"></i> No matching requests found.</div>`;
        return;
      }

      requests.forEach((req, index) => {
        const li = document.createElement('li');
        li.className = 'request-item';

        // Truncate description
        const truncatedDesc = req.description.length > 60 ?
          req.description.substring(0, 60) + '...' :
          req.description;

        // Format date
        const formattedDate = new Date(req.request_date).toLocaleDateString('en-US', {
          month: 'short',
          day: 'numeric',
          year: 'numeric'
        });

        li.innerHTML = `
          <div class="request-icon">
            <i class="fas ${statusIcons[req.status] || 'fa-tools'}"></i>
          </div>
          <div class="request-content">
            <div class="request-desc" title="${req.description}">
              ${truncatedDesc}
            </div>
            <div class="request-meta">
              <div class="request-date">
                <i class="far fa-calendar-alt"></i>
                ${formattedDate}
              </div>
              <div class="request-status">
                <i class="fas ${statusIcons[req.status] || 'fa-circle'} status-${req.status.toLowerCase().replace(' ', '-')}"></i>
                ${req.status}
              </div>
              ${req.priority ? `
              <div class="request-priority">
                <i class="fas ${priorityIcons[req.priority] || 'fa-circle'} priority-${req.priority.toLowerCase()}"></i>
                ${req.priority}
              </div>
              ` : ''}
            </div>
          </div>
          ${req.is_read == 0 ? '<span class="badge-new">NEW</span>' : ''}
        `;

        li.onclick = () => {
          // Remove active class from all items
          document.querySelectorAll('.request-item').forEach(item => {
            item.classList.remove('active-request');
          });

          // Add active class to clicked item
          li.classList.add('active-request');

          showRequestDetails(req);
          fetch('mark_as_read.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              id: req.id
            })
          }).then(() => {
            req.is_read = 1;
            li.querySelector('.badge-new')?.remove();
          });
        };

        requestList.appendChild(li);
      });
    }

    searchInput.addEventListener('input', function() {
      const query = this.value.toLowerCase();
      const filtered = allRequests.filter(req =>
        req.category.toLowerCase().includes(query) ||
        req.residence.toLowerCase().includes(query) ||
        req.unit.toLowerCase().includes(query) ||
        req.description.toLowerCase().includes(query)
      );
      renderRequestList(filtered);

      // Show first result if available
      if (filtered.length > 0) {
        showRequestDetails(filtered[0]);
        // Highlight the first item
        const firstItem = requestList.querySelector('.request-item');
        if (firstItem) {
          firstItem.classList.add('active-request');
        }
      }
    });

    function showRequestDetails(req) {
      currentRequestId = req.id;
      // Format date for details view
      const formattedDate = new Date(req.request_date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });

      // Update the availability button based on the request's status
      updateAvailabilityButton(req.availability);

      detailsPanel.innerHTML = `
        <div class="container-fluid px-1">
            <!-- Row 1: Property, Unit, Request ID -->
            <div class="row-card mb-1 p-3 rounded shadow-sm">
                <div class="row gx-3 gy-3 p-3 rounded border-0" style="background-color:; border: 1px solid #e0e0e0;">
                    <!-- Property -->
                    <div class="col-md-3">
                        <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                            <span style="background-color: #00192D; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <i class="fa-solid fa-building" style="color: #FFC107; font-size: 16px;"></i>
                            </span>
                            <span style="font-weight: 600;">Property</span>
                        </div>
                        <div style="margin-top: 6px; font-size: 15px; color: #333;">${req.residence}</div>
                    </div>

                    <!-- Unit -->
                    <div class="col-md-3">
                        <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                            <span style="background-color: #00192D; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <i class="fa-solid fa-door-closed" style="color: #FFC107; font-size: 16px;"></i>
                            </span>
                            <span style="font-weight: 600;">Unit</span>
                        </div>
                        <div style="margin-top: 6px; font-size: 15px; color: #333;">${req.unit}</div>
                    </div>

                    <!-- Request ID -->
                    <div class="col-md-3">
                        <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                            <span style="background-color: #00192D; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <i class="fa-solid fa-hashtag" style="color: #FFC107; font-size: 16px;"></i>
                            </span>
                            <span style="font-weight: 600;">Request ID</span>
                        </div>
                        <div style="margin-top: 6px; font-size: 15px; color: #333;">${req.id}</div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                            <span style="background-color: #00192D; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <i class="fa-solid fa-clipboard-check" style="color: #FFC107; font-size: 16px;"></i>
                            </span>
                            <span style="font-weight: 600;">Status</span>
                        </div>
                        <div style="margin-top: 6px; font-size: 15px; color: green;">${req.status}</div>
                    </div>
                </div>
            </div>
            <!-- Row 2: Category & Description -->
            <div class="row-card mb-1 p-3 rounded shadow-sm bg-white">
                <div class="row gx-3 gy-3 p-3 rounded border-0" style="border: 1px solid #e0e0e0;">
                    <div class="col-md-12 bg-white p-3">
                        <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                            <span style="background-color: #00192D; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                <i class="fa-solid fa-align-left" style="color: white; font-size: 16px;"></i>
                            </span>
                            <span style="font-weight: 600;">Description</span>
                        </div>
                        <div class="text-muted" style="margin-top: 6px; font-size: 15px; color: #333; line-height: 1.6;">${req.description}</div>
                    </div>
                </div>
            </div>
            <!-- Row 3: Photo -->
            <div class="row-card mb-1 p-3 rounded shadow-sm bg-white">
                <div class="detail-row mb-2">
                    <span class="detail-icon"><i class="fa-solid fa-image"></i></span>
                    <span class="detail-label">Image</span>
                </div>
                <img src="${req.photo || 'https://via.placeholder.com/400x250?text=No+Photo'}" alt="Photo" class="photo-preview w-100 rounded">
            </div>
            <!-- Row 4: Date, Status, Payment, Bid, Availability -->
            <div class="row-card p-3 rounded shadow-sm bg-white">
                <div class="row gx-2">
                    <div class="col-md-3 col-6 mb-2">
                        <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-calendar"></i></span><span class="detail-label">Date:</span> ${formattedDate}</div>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-money-bill"></i></span><span class="detail-label">Payment:</span> ${req.payment_status}</div>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-gavel"></i></span><span class="detail-label">Bid:</span> ${req.bid ?? 'N/A'}</div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    // New function to update button appearance
    function updateAvailabilityButton(availability) {
      const btn = document.getElementById('availabilityBtn');
      const secondaryButtons = document.getElementById('secondaryButtons');

      if (availability === 'available') {
        btn.innerHTML = '<i class="fas fa-check me-2"></i> Available';
        btn.style.backgroundColor = '#FFFFFF';
        secondaryButtons.style.display = 'flex';
      } else {
        btn.innerHTML = '<i class="fas fa-ban me-2"></i> Unavailable';
        btn.style.backgroundColor = '#E6EAF0';
        secondaryButtons.style.display = 'none';
      }
    }

    // Updated toggleAvailability function
    async function toggleAvailability() {
      if (!currentRequestId) return;

      const btn = document.getElementById('availabilityBtn');
      const secondaryButtons = document.getElementById('secondaryButtons');
      const isAvailable = btn.innerHTML.includes('Available');
      const newStatus = isAvailable ? 'unavailable' : 'available';

      try {
        const response = await fetch('update_availability.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id=${currentRequestId}&availability=${newStatus}`
        });

        const data = await response.json();

        if (data.success) {
          // Update the button appearance
          if (newStatus === 'available') {
            btn.innerHTML = '<i class="fas fa-check me-2"></i> Available';
            btn.style.backgroundColor = '#FFFFFF';
            secondaryButtons.style.display = 'flex';
          } else {
            btn.innerHTML = '<i class="fas fa-ban me-2"></i> Unavailable';
            btn.style.backgroundColor = '#E6EAF0';
            secondaryButtons.style.display = 'none';
          }

          // Update the local data
          const updatedRequest = allRequests.find(req => req.id === currentRequestId);
          if (updatedRequest) {
            updatedRequest.availability = newStatus;
          }
        } else {
          console.error('Update failed:', data.error);
        }
      } catch (error) {
        console.error('Error:', error);
      }
    }

    // Initialize the button click handler
    document.getElementById('availabilityBtn').addEventListener('click', toggleAvailability);
  </script>
  <script>
    function showProposals() {
      // const maintenanceRequestModalEl = document.getElementById('maintenanceRequestModal');
      // const maintenanceRequestModalInstance = bootstrap.Modal.getInstance(maintenanceRequestModalEl);
      // if (maintenanceRequestModalInstance) {
      //   maintenanceRequestModalInstance.hide(); // 👈 call it properly
      // }
      const viewProposal = document.getElementById("showProposal");
      //  const requestId = viewProposal.getAttribute("data-request-id");

      // fetch(`actions/get_request_proposals.php?request_id=${requestId}`)
      //   .then(response => response.json())
      //   .then(data => {
      //     console.log("Fetched Proposals:", data);
      //     // Do something with the data...y
      //     const container = document.getElementById("job_proposals_container");
      //     container.classList.remove("d-none"); // Make sure it's visible
      //     container.innerHTML = ""; // Clear previous content if needed
      //     data.forEach(p => {
      //       container.innerHTML += `
      //       <div class="proposal-card mt-2">
      //         <div class="proposal-header d-flex align-items-start">
      //           <img src="${p.profilePic || 'https://i.pravatar.cc/70'}" alt="Profile Picture" class="profile-pic me-3">
      //           <div>
      //             <h5>${p.name} <span class="badge bg-warning">5 <i class="fa fa-star " id="star-rating"></i>${p.ratings}</span></h5>
      //             <p>${p.category}</p>
      //           </div>
      //           <div class="ms-auto proposal-meta text-end">
      //             <h6>$${p.bid_amount}/hr</h6>
      //             <small>${p.estimated_time}</small><br>
      //             <small class="text-success">✅ ${p.jobs_completed} jobs completed</small>
      //           </div>
      //         </div>
      //         <hr>
      //         <p><strong>Cover Letter:</strong> ${p.cover_letter}</p>
      //         <p><strong>Location:</strong> ${p.location}</p>
      //         <div class="d-flex justify-content-end mt-3">
      //           <button class="btn btn-outline-secondary btn-sm btn-action">Message</button>
      //           <button class="btn btn-outline-primary btn-sm btn-action" style="margin-right:6px;">Shortlist</button>
      //           <button class="btn btn-outline-danger btn-sm">Decline</button>
      //         </div>
      //       </div>
      //     `;
      //     });
      //   })
      //   .catch(err => {
      //     console.error("Error fetching proposals:", err);
      //   })

      const modal = document.getElementById("proposalContainer");
      const backdrop = document.getElementById("customBackdrop");

      backdrop.classList.remove("d-none");
      modal.classList.remove("d-none");

      setTimeout(() => {
        backdrop.classList.add("show");
        modal.classList.add("show", "d-block");
      }, 10);

    }

    
    
  </script>
  <script src="../../../dist/js/adminlte.js"></script>
</body>

</html>