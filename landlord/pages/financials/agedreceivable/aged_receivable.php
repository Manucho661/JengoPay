<?php
require_once '../../db/connect.php';

// Fetch unpaid invoices with tenant details
$sql = "
  SELECT 
    i.id, 
    i.invoice_number, 
    i.tenant, 
    i.description, 
    i.invoice_date, 
    i.due_date, 
    i.total,
    t.first_name,
    t.middle_name,
    t.last_name,
    t.main_contact AS phone_number,
    t.building
  FROM invoice i
  LEFT JOIN tenants t ON i.tenant = t.id
  WHERE i.payment_status = 'unpaid'
  ORDER BY t.first_name, t.middle_name, i.invoice_date DESC
";
$stmt = $pdo->query($sql);
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group invoices by tenant
$tenants = [];
foreach ($invoices as $inv) {
    $tenantId = $inv['tenant'];
    $tenantName = trim($inv['first_name'] . ' ' . $inv['middle_name'] . ' ' . $inv['last_name']);

    if (empty(trim($tenantName))) $tenantName = "Tenant #" . $tenantId;
    if (!empty($inv['building'])) $tenantName .= " (Building: " . $inv['building'] . ")";

    if (!isset($tenants[$tenantId])) {
        $tenants[$tenantId] = [
            'name' => $tenantName,
            'phone' => $inv['phone_number'],
            'building' => $inv['building'],
            'invoices' => []
        ];
    }
    $tenants[$tenantId]['invoices'][] = $inv;
}

// Calculate days overdue
function daysOverdue($invoiceDate, $dueDate = null) {
    $today = new DateTime();
    $date = $dueDate ? new DateTime($dueDate) : new DateTime($invoiceDate);
    return $date->diff($today)->days;
}

// Prepare summary totals
$totals = [
    '0-30' => 0,
    '31-60' => 0,
    '61-90' => 0,
    '90+' => 0,
    'grand' => 0
];

foreach ($invoices as $inv) {
    $days = daysOverdue($inv['invoice_date'], $inv['due_date']);
    if ($days <= 30) $totals['0-30'] += $inv['total'];
    elseif ($days <= 60) $totals['31-60'] += $inv['total'];
    elseif ($days <= 90) $totals['61-90'] += $inv['total'];
    else $totals['90+'] += $inv['total'];
    $totals['grand'] += $inv['total'];
}
?>

<!doctype html>
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
  <meta charset="utf-8" />
  <title>Aged Receivables | JengoPay</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../../../landlord/css/adminlte.css" />

  <style>
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .summary-card { background-color: #00192D; color: #FFC107; border-radius: 10px; }
    .summary-card h5 { margin: 0; }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper" style="background-color:rgba(128,128,128,0.1);">
  <?php include_once '../../includes/header.php'; ?>
  <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand text-center p-2">
      <span class="brand-text fw-bold text-warning">BT JENGOPAY</span>
    </div>
    <div><?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?></div>
  </aside>

  <main class="app-main">
    <div class="app-content-header p-3">
      <h3 class="fw-bold text-dark">📋 Aged Receivables Report</h3>
      <p class="text-muted">Outstanding tenant invoices by aging category.</p>
    </div>

    <div class="app-content">
      <div class="container-fluid bg-white rounded-2 shadow-sm p-3">
        <div class="row text-center mb-4">
          <?php foreach (['0-30','31-60','61-90','90+'] as $range): ?>
          <div class="col-md-3">
            <div class="summary-card p-3">
              <h5><?= $range ?> Days</h5>
              <h4><?= number_format($totals[$range], 2) ?></h4>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <table id="agedTable" class="table table-striped table-hover align-middle">
          <thead style="background-color:#00192D; color:#FFC107;">
            <tr>
              <th>Tenant</th>
              <th>0-30 Days</th>
              <th>31-60 Days</th>
              <th>61-90 Days</th>
              <th>90+ Days</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tenants as $tenantId => $tenant):
              $buckets = ['0-30'=>0,'31-60'=>0,'61-90'=>0,'90+'=>0];
              foreach ($tenant['invoices'] as $inv) {
                $days = daysOverdue($inv['invoice_date'], $inv['due_date']);
                if ($days <= 30) $buckets['0-30'] += $inv['total'];
                elseif ($days <= 60) $buckets['31-60'] += $inv['total'];
                elseif ($days <= 90) $buckets['61-90'] += $inv['total'];
                else $buckets['90+'] += $inv['total'];
              }
              $total = array_sum($buckets);
            ?>
            <tr class="tenant-row" data-tenant="<?= $tenantId ?>" style="cursor:pointer;">
              <td><?= htmlspecialchars($tenant['name']) ?></td>
              <td><?= number_format($buckets['0-30'], 2) ?></td>
              <td><?= number_format($buckets['31-60'], 2) ?></td>
              <td><?= number_format($buckets['61-90'], 2) ?></td>
              <td><?= number_format($buckets['90+'], 2) ?></td>
              <td><?= number_format($total, 2) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="fw-bold bg-light">
              <td>Total</td>
              <td><?= number_format($totals['0-30'], 2) ?></td>
              <td><?= number_format($totals['31-60'], 2) ?></td>
              <td><?= number_format($totals['61-90'], 2) ?></td>
              <td><?= number_format($totals['90+'], 2) ?></td>
              <td><?= number_format($totals['grand'], 2) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </main>

  <footer class="app-footer text-center py-3">
    <strong>© 2025 JENGO PAY</strong> — All rights reserved.
  </footer>
</div>

<!-- Invoice Modal -->
<div class="modal fade" id="tenantModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#00192D; color:#FFC107;">
        <h5 class="modal-title">Tenant Invoice Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="tenantDetails">
        <p class="text-center text-muted">Loading...</p>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(function(){
  $('#agedTable').DataTable({
    paging:true, searching:true, info:false,
    language: { searchPlaceholder:"Search tenant..." }
  });

  // Click row to open tenant invoice details
  $('.tenant-row').on('click', function(){
    const tenantId = $(this).data('tenant');
    $('#tenantDetails').html('<p class="text-center text-muted">Loading...</p>');
    $('#tenantModal').modal('show');
    $.get('get_tenant_invoices.php', { tenant: tenantId }, function(data){
      $('#tenantDetails').html(data);
    });
  });
});
</script>
</body>
</html>
