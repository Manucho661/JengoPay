<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Maintenance Request Viewer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
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
      width: 320px;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      border-right: 1px solid #e0e0e0;
    }

    .request-sidebar h3 {
      background: #FFC107;
      color: #00192D;
      padding: 1.2rem;
      font-size: 1.2rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-bar {
      padding: 1rem;
      background: #fff;
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

    .request-list {
      flex-grow: 1;
      overflow-y: auto;
    }

    .request-item {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #f0f0f0;
      cursor: pointer;
      transition: background 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 0.95rem;
    }

    .request-item:hover {
      background: #FFF7E0;
    }

    .badge-new {
      background: #00192D;
      color: #FFC107;
      padding: 4px 10px;
      font-size: 12px;
      border-radius: 20px;
      margin-left: 8px;
      font-weight: 600;
    }

    .main-content {
      flex-grow: 1;
      padding: 2rem;
      overflow-y: auto;
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

    .availability-btn {
      padding: 0.45rem 1.2rem;
      background: #FFC107;
      color: #000;
      border: none;
      border-radius: 30px;
      font-weight: bold;
      transition: 0.3s ease;
    }

    .availability-btn:hover {
      background-color: #e0a800;
    }

    .availability-btn.active {
      background-color: #28a745;
      color: white;
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

/* ✨ Hover effect */
.row-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
  border-left: 5px solid #FFC107;
  background-color: #fdfaf3;
}

    .navbar {
      background-color: #00192D !important;
    }

    .navbar .nav-link, .navbar .navbar-brand, .navbar .dropdown-item, .navbar span {
      color: #fff !important;
    }

    .navbar .badge {
      background-color: #FFC107;
      color: #000;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
    <div class="container-fluid">
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
      <!-- <div id="sidebar"></div> -->
      <!-- This is where the sidebar is inserted -->

      <!--end::Sidebar Wrapper-->
    </aside>
      
      <ul class="navbar-nav ms-auto d-flex align-items-center">
        <li class="nav-item dropdown">
          <a class="nav-link" data-bs-toggle="dropdown" href="#">
            <i class="bi bi-bell-fill"></i>
            <span class="badge bg-warning">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-end shadow">
            <span class="dropdown-header">15 Notifications</span>
            <a href="#" class="dropdown-item"><i class="bi bi-envelope me-2"></i> 4 new messages</a>
            <a href="#" class="dropdown-item"><i class="bi bi-people-fill me-2"></i> 8 friend requests</a>
            <a href="#" class="dropdown-item"><i class="bi bi-file-earmark-fill me-2"></i> 3 new reports</a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <span class="d-none d-md-inline"><b>Alexander Pierce</b></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li class="user-header bg-primary text-white text-center p-3">
              <img src="../../../dist/assets/img/user2-160x160.jpg" class="rounded-circle mb-2" alt="User Image" style="width: 80px;">
              <p>Alexander Pierce<br><small>Member since Nov. 2023</small></p>
            </li>
            <li class="user-body text-center py-2">
              <a href="#" class="btn btn-sm btn-outline-dark mx-1">Profile</a>
              <a href="#" class="btn btn-sm btn-outline-danger mx-1">Sign out</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Layout -->
  <div class="layout">
    <div class="sidebar-container">
      <?php include_once '../includes/sidebar1.php'; ?>
    </div>
    <div class="request-sidebar">
      <h3><i class="fa-solid fa-screwdriver-wrench"></i> Maintenance Requests</h3>
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search by unit, category, or property...">
      </div>
      <ul class="request-list" id="requestList"></ul>
    </div>
    <div class="main-content" id="detailsPanel">
      <div class="no-selection"><i class="fa-solid fa-circle-info"></i> Select a request to view details</div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-light text-center py-3">
    <div class="container">
      <strong>
        &copy; 2014-2024 <a href="https://adminlte.io" class="text-decoration-none text-dark">JENGO PAY</a>. All rights reserved.
      </strong>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const requestList = document.getElementById('requestList');
    const detailsPanel = document.getElementById('detailsPanel');
    const searchInput = document.getElementById('searchInput');
    let allRequests = [];

    fetch('get_requests.php')
      .then(res => res.json())
      .then(requests => {
        if (!requests.length || requests.error) {
          detailsPanel.innerHTML = `<div class="no-selection"><i class="fa-solid fa-triangle-exclamation"></i> No requests found.</div>`;
          return;
        }
        allRequests = requests;
        renderRequestList(allRequests);
      });

    function renderRequestList(requests) {
      requestList.innerHTML = '';
      requests.forEach(req => {
        const li = document.createElement('li');
        li.className = 'request-item';
        li.innerHTML = `
          <span><i class="fa-solid fa-wrench"></i> ${req.category} ${req.is_read == 0 ? '<span class="badge-new">New</span>' : ''}</span>
          <i class="fa-solid fa-chevron-right"></i>`;

        li.onclick = () => {
          showRequestDetails(req);
          fetch('mark_as_read.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: req.id })
          }).then(() => {
            req.is_read = 1;
            li.querySelector('.badge-new')?.remove();
          });
        };

        requestList.appendChild(li);
      });
    }

    searchInput.addEventListener('input', function () {
      const query = this.value.toLowerCase();
      const filtered = allRequests.filter(req =>
        req.category.toLowerCase().includes(query) ||
        req.residence.toLowerCase().includes(query) ||
        req.unit.toLowerCase().includes(query)
      );
      renderRequestList(filtered);
    });

 function showRequestDetails(req) {
  detailsPanel.innerHTML = `
    <div class="container">
      <!-- Row 1: Property, Unit, Request ID -->
      <div class="row-card mb-4 p-3 rounded shadow-sm bg-white">
        <div class="row">
          <div class="col-md-4">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-building"></i></span><span class="detail-label">Property:</span> ${req.residence}</div>
          </div>
          <div class="col-md-4">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-door-closed"></i></span><span class="detail-label">Unit:</span> ${req.unit}</div>
          </div>
          <div class="col-md-4">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-hashtag"></i></span><span class="detail-label">Request ID:</span> ${req.id}</div>
          </div>
        </div>
      </div>

      <!-- Row 2: Category & Description -->
      <div class="row-card mb-4 p-3 rounded shadow-sm bg-white">
        <div class="row">
          <div class="col-md-6">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-layer-group"></i></span><span class="detail-label">Category:</span> ${req.category}</div>
          </div>
          <div class="col-md-6">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-align-left"></i></span><span class="detail-label">Description:</span> ${req.description}</div>
          </div>
        </div>
      </div>

      <!-- Row 3: Photo -->
      <div class="row-card mb-4 p-3 rounded shadow-sm bg-white">
        <div class="detail-row">
          <span class="detail-icon"><i class="fa-solid fa-image"></i></span>
          <span class="detail-label">Photo:</span><br>
        </div>
        <img src="${req.photo || 'https://via.placeholder.com/400x250?text=No+Photo'}" alt="Photo" class="photo-preview mt-2">
      </div>

      <!-- Row 4: Date, Status, Payment, Bid, Availability -->
      <div class="row-card mb-4 p-3 rounded shadow-sm bg-white">
        <div class="row">
          <div class="col-md-3 mb-3">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-calendar"></i></span><span class="detail-label">Date:</span> ${req.request_date}</div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-tasks"></i></span><span class="detail-label">Status:</span> ${req.status}</div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-money-bill"></i></span><span class="detail-label">Payment:</span> ${req.payment_status}</div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="detail-row"><span class="detail-icon"><i class="fa-solid fa-gavel"></i></span><span class="detail-label">Bid:</span> ${req.bid ?? 'N/A'}</div>
          </div>
          <div class="col-12">
            <div class="detail-row">
              <span class="detail-icon"><i class="fa-solid fa-toggle-on"></i></span>
              <span class="detail-label">Availability:</span>
              <button class="availability-btn ${req.availability === 'Available' ? 'active' : ''}" onclick="toggleAvailability(this)">
                ${req.availability}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
}



    window.toggleAvailability = (btn) => {
      const current = btn.textContent.trim();
      btn.textContent = current === "Available" ? "Unavailable" : "Available";
      btn.classList.toggle('active');
    };
  </script>
</body>
</html>
