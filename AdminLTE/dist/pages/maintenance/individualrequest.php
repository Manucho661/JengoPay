<!DOCTYPE html>
<html lang="en">

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
  <!-- LINKS -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
  <!-- <link rel="stylesheet" href="text.css" /> -->
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="expenses.css">
  <!-- scripts for data_table -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
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
      <div> <?php include_once '../includes/sidebar1.php'; ?> </div> <!-- This is where the sidebar is inserted -->
      <!--end::Sidebar Wrapper-->
    </aside>

    <!-- Main Layout -->
    <div class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
      </div>
      <div class="app-content">
        <div class="layout">
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
  </div>

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