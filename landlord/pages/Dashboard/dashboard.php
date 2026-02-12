<?php
session_start();
require_once "../../../auth/auth_check.php";   // Protect this page
?>

<?php
// get summry
require_once "./actions/dashboardSummary.php";
// revenue
require_once $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/financials/balancesheet/actions/getEquity.php';
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

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
    crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


  <!-- Main css file -->
  <link rel="stylesheet" href="../../../landlord/assets/main.css" />


  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <!-- scripts for data_table -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


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

    a {
      text-decoration: none;
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
                    <b><?= $buildingCount ?></b>
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
                    <b><?= $tenantCount ?></b>
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
                    <p class="mb-0" style="font-weight: bold;">KSH</p>
                    <?php
                    $value = (float)$retainedEarnings;
                    $isNegative = $value < 0;

                    // convert to K format
                    $formatted = number_format(abs($value) / 1000, 2) . 'K';

                    // add brackets if negative
                    if ($isNegative) {
                      $formatted = "($formatted)";
                    }
                    ?>

                    <b style=" color: <?= $isNegative ? 'red' : 'var(--main-color)' ?>;">
                      KSH <?= $formatted ?>
                    </b>
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
                    <p class="mb-0" style="font-weight: bold;">Submitted Requests</p>
                    <b><?= $requestCount ?></b>
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
                    <?php if (!empty($lastBuildings)): ?>

                      <?php foreach ($lastBuildings as $building): ?>
                        <div class="property-item">
                          <div class="d-flex justify-content-between align-items-center">
                            <div>
                              <h6 class="mb-1"><?= htmlspecialchars($building['building_name']) ?></h6>
                              <small class="text-muted">Recently added property</small>
                            </div>
                            <span class="badge badge-occupied">Active</span>
                          </div>
                        </div>
                      <?php endforeach; ?>

                    <?php else: ?>

                      <div class="text-center py-5">
                        <i class="fas fa-building fa-2x mb-3 text-muted"></i>
                        <h6 class="mb-2">No properties found</h6>
                        <p class="text-muted small mb-3">
                          Get started by adding your first property.
                        </p>
                        <a href="add_property.php" class="btn btn-primary btn-sm">
                          Add Property
                        </a>
                      </div>

                    <?php endif; ?>
                  </div>

                  <?php if (!empty($lastBuildings)): ?>
                    <a href="properties.php" class="actionBtn">
                      View All Properties
                    </a>
                  <?php else: ?>
                    <a href="add_property.php" class="actionBtn">
                      Add Property
                    </a>
                  <?php endif; ?>

                </div>
              </div>

              <!-- Maintenance Requests -->
              <div class="col-lg-6">
                <div class="content-card fixed-height-card">
                  <h5><i class="fas fa-tools me-2"></i>Recent Maintenance Requests</h5>

                  <div class="scrollable-content">

                    <?php if (!empty($lastMaintenanceRequests)): ?>

                      <?php foreach ($lastMaintenanceRequests as $request): ?>
                        <div class="maintenance-item">
                          <div class="d-flex justify-content-between">
                            <div>
                              <h6 class="mb-1"><?= htmlspecialchars($request['title'] ?? 'Maintenance Request') ?></h6>
                              <small class="text-muted">
                                <?= htmlspecialchars($request['building_name'] ?? 'Building') ?>
                                • Unit <?= htmlspecialchars($request['unit_number'] ?? '-') ?>
                              </small>
                              <p class="mb-0 mt-2">
                                Status: <?= htmlspecialchars($request['status'] ?? 'Pending') ?>
                              </p>
                            </div>
                            <span class="badge bg-secondary">
                              <?= htmlspecialchars($request['status'] ?? 'Pending') ?>
                            </span>
                          </div>
                          <small class="text-muted">
                            <?= htmlspecialchars($request['created_at']) ?>
                          </small>
                        </div>
                      <?php endforeach; ?>

                    <?php else: ?>

                      <?php if (!empty($lastBuildings)): ?>
                        <!-- Buildings exist but no requests -->
                        <div class="text-center py-5">
                          <i class="fas fa-tools fa-2x mb-3 text-muted"></i>
                          <h6 class="mb-2">No maintenance requests yet</h6>
                          <p class="text-muted small mb-3">
                            Maintenance requests will appear here once created.
                          </p>
                        </div>
                      <?php else: ?>
                        <!-- No buildings at all -->
                        <div class="text-center py-5">
                          <i class="fas fa-building fa-2x mb-3 text-muted"></i>
                          <h6 class="mb-2">No buildings created</h6>
                          <p class="text-muted small mb-3">
                            Maintenance requests can only be submitted after creating buildings.
                          </p>
                          <a href="add_property.php" class="btn btn-primary btn-sm">
                            Add Property
                          </a>
                        </div>
                      <?php endif; ?>

                    <?php endif; ?>

                  </div>

                  <!-- Bottom Button Logic -->
                  <?php if (!empty($lastMaintenanceRequests)): ?>
                    <a href="maintenance_requests.php" class="btn btn-outline-primary btn-sm mt-3 w-100">
                      View All Requests
                    </a>

                  <?php elseif (!empty($lastBuildings)): ?>
                    <a href="create_request.php" class="btn btn-outline-primary btn-sm mt-3 w-100">
                      Create Maintenance Request
                    </a>

                  <?php else: ?>
                    <a href="add_property.php" class="btn btn-primary btn-sm mt-3 w-100">
                      Add Property First
                    </a>
                  <?php endif; ?>

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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

  <script src="/jengopay/landlord/assets/main.js"></script>
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