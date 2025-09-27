<?php
require_once '../../db/connect.php';

// Fetch all unpaid invoices with tenant names
$sql = "
  SELECT 
    ii.id, 
    ii.invoice_number, 
    ii.tenant, 
    ii.description, 
    ii.created_at, 
    ii.total,
    u.first_name,
    u.middle_name,
    t.unit,
    t.phone_number
  FROM invoice_items ii
  LEFT JOIN tenants t ON ii.tenant = t.id
  LEFT JOIN users u ON t.user_id = u.id
  ORDER BY u.first_name, u.middle_name, ii.created_at DESC
";
$stmt = $pdo->query($sql);
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group invoices by tenant
$tenants = [];
foreach ($invoices as $inv) {
    $tenantId = $inv['tenant'];
    $tenantName = trim($inv['first_name'] . ' ' . $inv['middle_name']);
    
    // If no name found from users table, use the tenant ID as fallback
    if (empty($tenantName) || $tenantName == ' ') {
        $tenantName = 'Tenant ' . $tenantId;
    }
    
    // Add unit information if available
    if (!empty($inv['unit'])) {
        $tenantName .= ' (' . $inv['unit'] . ')';
    }
    
    $tenants[$tenantId] = [
        'name' => $tenantName,
        'invoices' => [],
        'unit' => $inv['unit'],
        'phone_number' => $inv['phone_number']
    ];
}

// Add invoices to their respective tenants
foreach ($invoices as $inv) {
    $tenantId = $inv['tenant'];
    $tenants[$tenantId]['invoices'][] = $inv;
}

function daysOverdue($date) {
    $today = new DateTime();
    $invoiceDate = new DateTime($date);
    return $invoiceDate->diff($today)->days;
}

// Prepare buckets and totals for summary
$agedBuckets = [
    '0-30' => [],
    '31-60' => [],
    '61-90' => [],
    '90+' => []
];
$totals = [
    '0-30' => 0,
    '31-60' => 0,
    '61-90' => 0,
    '90+' => 0,
    'grand' => 0
];

foreach ($invoices as $inv) {
    $days = daysOverdue($inv['created_at']);
    if ($days <= 30) {
        $agedBuckets['0-30'][] = $inv;
        $totals['0-30'] += $inv['total'];
    } elseif ($days <= 60) {
        $agedBuckets['31-60'][] = $inv;
        $totals['31-60'] += $inv['total'];
    } elseif ($days <= 90) {
        $agedBuckets['61-90'][] = $inv;
        $totals['61-90'] += $inv['total'];
    } else {
        $agedBuckets['90+'][] = $inv;
        $totals['90+'] += $inv['total'];
    }
    $totals['grand'] += $inv['total'];
}
?>
<!doctype html>
<html lang="en">
<!--begin::Head-->

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
  <link rel="stylesheet" href="../../../../landlord/css/adminlte.css" />
  <!-- <link rel="stylesheet" href="text.css" /> -->
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="profit&loss.css">

  <!-- scripts for data_table -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

  <!-- Include XLSX and FileSaver.js for Excel export -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

  <!-- Include jsPDF library (latest version) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <!-- Include jsPDF autoTable plugin (latest compatible version with jsPDF 2.5.1) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

  <!-- Include jsPDF library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <!-- Include jsPDF autoTable plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
  <style>
    body {
      font-size: 16px;
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
          <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
          <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
          <!--begin::Navbar Search-->
          <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <i class="bi bi-search"></i>
            </a>
          </li>
          <!--end::Navbar Search-->
          <!--begin::Messages Dropdown Menu-->
          <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
              <i class="bi bi-chat-text"></i>
              <span class="navbar-badge badge text-bg-danger">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <a href="#" class="dropdown-item">
                <!--begin::Message-->
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img
                      src="../../../dist/assets/img/user1-128x128.jpg"
                      alt="User Avatar"
                      class="img-size-50 rounded-circle me-3" />
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="dropdown-item-title">
                      Brad Diesel
                      <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                    </h3>
                    <p class="fs-7">Call me whenever you can...</p>
                    <p class="fs-7 text-secondary">
                      <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
                <!--end::Message-->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <!--begin::Message-->
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img
                      src="../../../dist/assets/img/user8-128x128.jpg"
                      alt="User Avatar"
                      class="img-size-50 rounded-circle me-3" />
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="dropdown-item-title">
                      John Pierce
                      <span class="float-end fs-7 text-secondary">
                        <i class="bi bi-star-fill"></i>
                      </span>
                    </h3>
                    <p class="fs-7">I got your message bro</p>
                    <p class="fs-7 text-secondary">
                      <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
                <!--end::Message-->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <!--begin::Message-->
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img
                      src="../../../dist/assets/img/user3-128x128.jpg"
                      alt="User Avatar"
                      class="img-size-50 rounded-circle me-3" />
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="dropdown-item-title">
                      Nora Silvester
                      <span class="float-end fs-7 text-warning">
                        <i class="bi bi-star-fill"></i>
                      </span>
                    </h3>
                    <p class="fs-7">The subject goes here</p>
                    <p class="fs-7 text-secondary">
                      <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
                <!--end::Message-->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
          </li>
          <!--end::Messages Dropdown Menu-->
          <!--begin::Notifications Dropdown Menu-->
          <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
              <i class="bi bi-bell-fill"></i>
              <span class="navbar-badge badge text-bg-warning">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <span class="dropdown-item dropdown-header">15 Notifications</span>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="bi bi-envelope me-2"></i> 4 new messages
                <span class="float-end text-secondary fs-7">3 mins</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="bi bi-people-fill me-2"></i> 8 friend requests
                <span class="float-end text-secondary fs-7">12 hours</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                <span class="float-end text-secondary fs-7">2 days</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
            </div>
          </li>
          <!--end::Notifications Dropdown Menu-->
          <!--begin::Fullscreen Toggle-->
          <li class="nav-item">
            <a class="nav-link" href="#" data-lte-toggle="fullscreen">
              <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
              <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
            </a>
          </li>
          <!--end::Fullscreen Toggle-->
          <!--begin::User Menu Dropdown-->
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img
                src="17.jpg"
                class="user-image rounded-circle shadow"
                alt="User Image" />
              <span class="d-none d-md-inline"> <b>JENGO PAY</b> </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <!--begin::User Image-->
              <li class="user-header text-bg-primary">
                <img
                  src="../../dist/assets/img/user2-160x160.jpg"
                  class="rounded-circle shadow"
                  alt="User Image" />
                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2023</small>
                </p>
              </li>
              <!--end::User Image-->
              <!--begin::Menu Body-->
              <li class="user-body">
                <!--begin::Row-->
                <div class="row">
                  <div class="col-4 text-center"><a href="#">Followers</a></div>
                  <div class="col-4 text-center"><a href="#">Sales</a></div>
                  <div class="col-4 text-center"><a href="#">Friends</a></div>
                </div>
                <!--end::Row-->
              </li>
              <!--end::Menu Body-->
              <!--begin::Menu Footer-->
              <li class="user-footer">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
                <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
              </li>
              <!--end::Menu Footer-->
            </ul>
          </li>
          <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
      </div>
      <!--end::Container-->
    </nav>
    <!--end::Header-->
    <!--begin::Sidebar-->
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
      <div > <?php include_once '../../includes/sidebar.php'; ?>  </div> <!-- This is where the sidebar is inserted -->
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header py-3">
    <!--begin::Container-->
    <div class="container-fluid px-4">
      <h1 class="mb-4">Aged Receivables</h1>

      <!-- Summary Panel -->
      <div class="row g-3 mb-4">
        <?php
        $bucketLabels = ['0–30', '31–60', '61–90', '90+'];
        $keys = ['0-30','31-60','61-90','90+'];
        foreach ($keys as $i => $key): ?>
          <div class="col-lg-2 col-md-3 col-sm-6">
            <div class="card aged-bucket h-100 shadow-sm">
              <div class="card-body text-center">
                <h6 class="card-title mb-2"><?= $bucketLabels[$i] ?> days</h6>
                <p class="card-text fs-4 fw-bold"><?= number_format($totals[$key], 2) ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="col-lg-3 col-md-6">
          <div class="card aged-bucket h-100 shadow-sm" style="background-color:#FFC107; color:#00192D;">
            <div class="card-body text-center">
              <h6 class="card-title mb-2">Total</h6>
              <p class="card-text fs-4 fw-bold"><?= number_format($totals['grand'], 2) ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs for buckets -->
      <ul class="nav nav-tabs bucket-tab mb-3" id="agedTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="tab-all" data-bs-toggle="tab" data-bs-target="#pane-all">
            All Tenants
          </button>
        </li>
        <?php foreach ($keys as $i => $key): ?>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-<?= $key ?>" data-bs-toggle="tab" data-bs-target="#pane-<?= $key ?>">
              <?= $bucketLabels[$i] ?> days
            </button>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="tab-content">
        <!-- All Tenants Tab -->
        <div class="tab-pane fade show active" id="pane-all">
          <div class="table-responsive">
            <table id="table-all" class="table table-bordered table-striped align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Tenant Name</th>
                  <th class="text-end">0-30 Days</th>
                  <th class="text-end">31-60 Days</th>
                  <th class="text-end">61-90 Days</th>
                  <th class="text-end">90+ Days</th>
                  <th class="text-end">Total Due</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($tenants as $tenantId => $tenantData): 
                  $tenantTotals = ['0-30'=>0,'31-60'=>0,'61-90'=>0,'90+'=>0,'grand'=>0];
                  foreach ($tenantData['invoices'] as $inv) {
                    $days = daysOverdue($inv['created_at']);
                    if ($days <= 30) $tenantTotals['0-30'] += $inv['total'];
                    elseif ($days <= 60) $tenantTotals['31-60'] += $inv['total'];
                    elseif ($days <= 90) $tenantTotals['61-90'] += $inv['total'];
                    else $tenantTotals['90+'] += $inv['total'];
                    $tenantTotals['grand'] += $inv['total'];
                  }
                ?>
                <tr class="tenant-row" data-tenant="<?= $tenantId ?>">
                  <td class="fw-semibold"><?= htmlspecialchars($tenantData['name']) ?></td>
                  <td class="text-end"><?= number_format($tenantTotals['0-30'], 2) ?></td>
                  <td class="text-end"><?= number_format($tenantTotals['31-60'], 2) ?></td>
                  <td class="text-end"><?= number_format($tenantTotals['61-90'], 2) ?></td>
                  <td class="text-end"><?= number_format($tenantTotals['90+'], 2) ?></td>
                  <td class="text-end fw-bold"><?= number_format($tenantTotals['grand'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Individual bucket tabs -->
        <?php foreach ($keys as $i => $key): ?>
          <div class="tab-pane fade" id="pane-<?= $key ?>">
            <div class="table-responsive">
              <table id="table-<?= $key ?>" class="table table-bordered table-striped align-middle">
                <thead class="">
                  <tr>
                    <th>Tenant Name</th>
                    <th>Unit</th>
                    <th>Invoice #</th>
                    <th>Description</th>
                    <th>Invoice Date</th>
                    <th class="text-end">Amount</th>
                    <th class="text-end">Days Overdue</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($agedBuckets[$key] as $row): 
                    $tenantName = trim($row['first_name'].' '.$row['middle_name']);
                    if (empty($tenantName)) $tenantName = 'Tenant '.$row['tenant'];
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($tenantName) ?></td>
                    <td><?= htmlspecialchars($row['unit'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                    <td class="text-end"><?= number_format($row['total'], 2) ?></td>
                    <td class="text-end"><?= daysOverdue($row['created_at']) ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
    <!--end::Container-->
  </div>
  <!--end::App Content Header-->
</main>
<!--end::App Main-->


<!-- Tenant Invoices Modal -->
<div class="modal fade" id="tenantModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Invoices for <span id="modal-tenant-name"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="tenant-invoices-content">
          <!-- Content will be loaded via AJAX -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS libs -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
  // Initialize all tables
  $('.table').each(function() {
    if (!$(this).hasClass('dataTable')) {
      $(this).DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf', 'print'],
        pageLength: 10,
        language: { search: "Search:" }
      });
    }
  });

  // View tenant invoices
  $('.view-tenant').on('click', function(e) {
    e.stopPropagation();
    var tenantId = $(this).data('tenant');
    showTenantInvoices(tenantId);
  });

  // Also allow clicking the entire row
  $('.tenant-row').on('click', function() {
    var tenantId = $(this).data('tenant');
    showTenantInvoices(tenantId);
  });

  function showTenantInvoices(tenantId) {
    $('#modal-tenant-name').text('Tenant ID: ' + tenantId);
    $('#tenant-invoices-content').html('<div class="text-center py-4"><div class="spinner-border" role="status"></div><br>Loading invoices...</div>');
    $('#tenantModal').modal('show');

    // Load tenant invoices via AJAX
    $.get('get_tenant_invoices.php', { tenant: tenantId }, function(data) {
      $('#tenant-invoices-content').html(data);
    }).fail(function() {
      $('#tenant-invoices-content').html('<div class="alert alert-danger">Error loading invoices</div>');
    });
  }
});
</script>

</body>
<!--end::Body-->
</html>
