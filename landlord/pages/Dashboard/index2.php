<?php
session_start();
require_once "../../../auth/auth_check.php";   // Protect this page
?>


<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <?php if (isset($successMessage)) echo "<div class='alert alert-success'>$successMessage</div>"; ?>
  <?php if (isset($errorMessage)) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE | Dashboard v2</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE | Dashboard v2" />
  
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
  <link rel="stylesheet" href="../../../landlord/assets/main.css" />
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

  <style>
    :root {
      --main-color: #00192D;
      --accent-color: #FFC107;
      --text-light: #ffffff;
    }

    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      background-color: var(--main-color);
      /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
    }

    .navbar-brand {
      color: var(--text-light) !important;
      font-weight: 700;
      font-size: 1.5rem;
    }

    .navbar-brand i {
      color: var(--accent-color);
    }

    .sidebar {
      background-color: var(--main-color);
      min-height: calc(100vh - 56px);
      padding: 20px 0;
    }

    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.8);
      padding: 12px 20px;
      margin: 5px 15px;
      border-radius: 8px;
      transition: all 0.3s;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: var(--accent-color);
      color: var(--main-color);
    }

    .sidebar .nav-link i {
      margin-right: 10px;
      width: 20px;
    }



    .stat-card:hover {
      transform: translateY(-5px);
      /* box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12); */
    }

    .stat-card .icon {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .stat-card.properties .icon {
      background-color: rgba(0, 25, 45, 0.1);
      color: var(--main-color);
    }

    .stat-card.tenants .icon {
      background-color: rgba(255, 193, 7, 0.2);
      color: #d39e00;
    }

    .stat-card.revenue .icon {
      background-color: rgba(40, 167, 69, 0.1);
      color: #28a745;
    }

    .stat-card.maintenance .icon {
      background-color: rgba(220, 53, 69, 0.1);
      color: #dc3545;
    }

    .stat-card h3 {
      color: var(--main-color);
      font-size: 1.8rem;
      font-weight: 700;
      margin: 10px 0 5px 0;
    }

    .stat-card p {
      color: #6c757d;
      margin: 0;
      font-size: 0.9rem;
    }

    .content-card {
      background: white;
      border-radius: 12px;
      padding: 25px;
      /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); */
      margin-bottom: 20px;
    }

    .content-card h5 {
      color: var(--main-color);
      font-weight: 600;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--accent-color);
    }

    .property-item {
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 10px;
      background-color: #f8f9fa;
      transition: all 0.2s;
    }

    .property-item:hover {
      background-color: #e9ecef;
    }

    .badge-occupied {
      background-color: #28a745;
    }

    .badge-vacant {
      background-color: #dc3545;
    }

    .maintenance-item {
      padding: 15px;
      border-left: 4px solid;
      margin-bottom: 15px;
      background-color: #f8f9fa;
      border-radius: 4px;
    }

    .maintenance-item.urgent {
      border-color: #dc3545;
    }

    .maintenance-item.normal {
      border-color: var(--accent-color);
    }

    .maintenance-item.low {
      border-color: #28a745;
    }

    .btn-primary {
      background-color: var(--main-color);
      border-color: var(--main-color);
    }

    .btn-primary:hover {
      background-color: #001020;
      border-color: #001020;
    }

    .btn-warning {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
      color: var(--main-color);
    }

    .user-menu {
      background-color: var(--accent-color);
      color: var(--main-color);
      padding: 8px 15px;
      border-radius: 25px;
      font-weight: 600;
    }

    .chart-container {
      position: relative;
      height: 300px;
    }

    #revenueChart {
      max-height: 300px;
    }

    .scrollable-content {
      max-height: 400px;
      overflow-y: auto;
    }

    .scrollable-content::-webkit-scrollbar {
      width: 6px;
    }

    .scrollable-content::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .scrollable-content::-webkit-scrollbar-thumb {
      background: var(--accent-color);
      border-radius: 10px;
    }

    .scrollable-content::-webkit-scrollbar-thumb:hover {
      background: #d39e00;
    }

    .fixed-height-card {
      height: 550px;
      display: flex;
      flex-direction: column;
    }

    .fixed-height-card .scrollable-content {
      flex: 1;
      overflow-y: auto;
    }

    .stat-card {
      background: #fff;
      padding: 18px;
      /* border-radius: 12px; */
      /* box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); */
      margin-bottom: 20px;
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-dark" style="">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">

    <!--begin::Header-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
    <!--end::Sidebar-->

    <!--begin::App Main-->
    <main class="main">
      <div class="container-fluid">
        <div class="row">
          <!-- Main Content -->
          <div class="col-md-12 p-4">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">Dashboard Overview</h2>

            <!-- Statistics Cards -->
            <!-- Bootstrap Icons CDN -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

            <!-- Bootstrap Icons CDN -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

            <div class="row g-3">

              <!-- Properties -->
              <div class="col-lg-3 col-md-6 d-flex">
                <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                  <div>
                    <i class="bi bi-building fs-1 me-3 text-warning"></i>
                  </div>
                  <div>
                    <p class="mb-0" style="font-weight: bold;">Total Properties</p>
                    <b>24</b>
                  </div>
                </div>
              </div>

              <!-- Tenants -->
              <div class="col-lg-3 col-md-6 d-flex">
                <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                  <div>
                    <i class="bi bi-people-fill fs-1 me-3 text-warning"></i>
                  </div>
                  <div>
                    <p class="mb-0" style="font-weight: bold;">Active Tenants</p>
                    <b>300</b>
                  </div>
                </div>
              </div>

              <!-- Revenue -->
              <div class="col-lg-3 col-md-6 d-flex">
                <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                  <div>
                    <i class="bi bi-cash-stack fs-1 me-3 text-warning"></i>
                  </div>
                  <div>
                    <p class="mb-0" style="font-weight: bold;">Monthly Revenue</p>
                    <b>KSH 48,250</b>
                  </div>
                </div>
              </div>

              <!-- Maintenance -->
              <div class="col-lg-3 col-md-6 d-flex">
                <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                  <div>
                    <i class="bi bi-exclamation-triangle-fill fs-1 me-3 text-warning"></i>
                  </div>
                  <div>
                    <p class="mb-0" style="font-weight: bold;">Pending Requests</p>
                    <b>8</b>
                  </div>
                </div>
              </div>

            </div>




            <!-- Charts and Properties -->
            <div class="row mt-4">
              <!-- Revenue Chart -->
              <div class="col-lg-8">
                <div class="content-card fixed-height-card">
                  <h5><i class="fas fa-chart-line me-2"></i>Revenue Overview</h5>
                  <div style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                  </div>
                </div>
              </div>

              <!-- Quick Actions -->
              <div class="col-lg-4">
                <div class="content-card" style="height: 300px;">
                  <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                  <div class="d-grid gap-2">
                    <button class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Property</button>
                    <button class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Add Tenant</button>
                    <button class="btn btn-warning"><i class="fas fa-file-invoice-dollar me-2"></i>Create Invoice</button>
                    <button class="btn btn-primary"><i class="fas fa-wrench me-2"></i>Log Maintenance</button>
                  </div>
                </div>

                <div class="content-card mt-3" style="height: 233px; display: flex; flex-direction: column;">
                  <h5><i class="fas fa-calendar-alt me-2"></i>Upcoming</h5>
                  <div class="scrollable-content" style="max-height: none; flex: 1;">
                    <div class="mb-3">
                      <small class="text-muted">Tomorrow</small>
                      <p class="mb-1"><strong>Property Inspection</strong></p>
                      <p class="mb-0 text-muted">Maple Street Apartments</p>
                    </div>
                    <div class="mb-3">
                      <small class="text-muted">Dec 1</small>
                      <p class="mb-1"><strong>Rent Due</strong></p>
                      <p class="mb-0 text-muted">23 properties</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Properties and Maintenance -->
            <div class="row mt-4">
              <!-- Recent Properties -->
              <div class="col-lg-6">
                <div class="content-card fixed-height-card">
                  <h5><i class="fas fa-building me-2"></i>Recent Properties</h5>

                  <div class="scrollable-content">
                    <div class="property-item">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-1">Sunset Apartments #12</h6>
                          <small class="text-muted">2 Bed, 1 Bath • $1,200/mo</small>
                        </div>
                        <span class="badge badge-occupied">Occupied</span>
                      </div>
                    </div>

                    <div class="property-item">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-1">Oak Street House</h6>
                          <small class="text-muted">3 Bed, 2 Bath • $1,850/mo</small>
                        </div>
                        <span class="badge badge-vacant">Vacant</span>
                      </div>
                    </div>

                    <div class="property-item">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-1">Riverside Loft #5</h6>
                          <small class="text-muted">1 Bed, 1 Bath • $950/mo</small>
                        </div>
                        <span class="badge badge-occupied">Occupied</span>
                      </div>
                    </div>

                    <div class="property-item">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-1">Downtown Studio #203</h6>
                          <small class="text-muted">Studio • $800/mo</small>
                        </div>
                        <span class="badge badge-occupied">Occupied</span>
                      </div>
                    </div>
                  </div>

                  <button class="btn btn-outline-primary btn-sm mt-3 w-100">View All Properties</button>
                </div>
              </div>

              <!-- Maintenance Requests -->
              <div class="col-lg-6">
                <div class="content-card fixed-height-card">
                  <h5><i class="fas fa-tools me-2"></i>Recent Maintenance Requests</h5>

                  <div class="scrollable-content">
                    <div class="maintenance-item urgent">
                      <div class="d-flex justify-content-between">
                        <div>
                          <h6 class="mb-1">Water Leak - Urgent</h6>
                          <small class="text-muted">Maple Street #8 • Sarah Johnson</small>
                          <p class="mb-0 mt-2">Bathroom ceiling leaking water</p>
                        </div>
                        <span class="badge bg-danger">Urgent</span>
                      </div>
                      <small class="text-muted">2 hours ago</small>
                    </div>

                    <div class="maintenance-item normal">
                      <div class="d-flex justify-content-between">
                        <div>
                          <h6 class="mb-1">HVAC Not Heating</h6>
                          <small class="text-muted">Oak Street House • Mike Davis</small>
                          <p class="mb-0 mt-2">Heater not producing warm air</p>
                        </div>
                        <span class="badge bg-warning text-dark">Normal</span>
                      </div>
                      <small class="text-muted">5 hours ago</small>
                    </div>

                    <div class="maintenance-item low">
                      <div class="d-flex justify-content-between">
                        <div>
                          <h6 class="mb-1">Lightbulb Replacement</h6>
                          <small class="text-muted">Sunset Apt #3 • Emma Wilson</small>
                          <p class="mb-0 mt-2">Kitchen light bulb needs replacement</p>
                        </div>
                        <span class="badge bg-success">Low</span>
                      </div>
                      <small class="text-muted">1 day ago</small>
                    </div>
                  </div>

                  <button class="btn btn-outline-primary btn-sm mt-3 w-100">View All Requests</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!--end::Footer-->

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

  <script type="module" src="../../../landlord/assets/main.js"></script>
  <script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Revenue',
          data: [38000, 39500, 41000, 42500, 43000, 44500, 45000, 46500, 47000, 47500, 48250, 48250],
          borderColor: '#00192D',
          backgroundColor: 'rgba(0, 25, 45, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '$' + value.toLocaleString();
              }
            }
          }
        }
      }
    });
  </script>

</body>
<!--end::Body-->

</html>