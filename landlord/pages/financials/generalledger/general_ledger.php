<?php
session_start();
require_once '../../db/connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

// landlord
$userId = (int)($_SESSION['user']['id'] ?? 0);
if ($userId <= 0) {
  throw new Exception("Not authenticated.");
}

// Fetch landlord ID linked to the logged-in user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
$stmt->execute([$userId]);
$landlord = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$landlord) {
  throw new Exception("Landlord account not found for this user.");
}

$landlord_id = (int)$landlord['id'];

// Capture filters
$from_date    = trim((string)($_GET['from_date'] ?? ''));   // YYYY-MM-DD
$to_date      = trim((string)($_GET['to_date'] ?? ''));     // YYYY-MM-DD
$account_code = trim((string)($_GET['account_code'] ?? '')); // from dropdown usually

// Build WHERE + params safely
$where  = ["jl.landlord_id = :landlord_id"];
$params = [":landlord_id" => $landlord_id];

// Date filters (optional)
if ($from_date !== '') {
  $where[] = "DATE(je.created_at) >= :from_date";
  $params[":from_date"] = $from_date;
}

if ($to_date !== '') {
  $where[] = "DATE(je.created_at) <= :to_date";
  $params[":to_date"] = $to_date;
}

// Account filter (optional) - use ONE field (account_code)
if ($account_code !== '') {
  $where[] = "a.account_code = :account_code";
  $params[":account_code"] = $account_code;
}

$whereSql = "WHERE " . implode(" AND ", $where);

// General Ledger query
$sql = "
    SELECT 
        je.created_at,
        je.reference,
        je.description,
        a.account_code,
        a.account_name,
        jl.debit,
        jl.credit
    FROM journal_entries je
    INNER JOIN journal_lines jl ON je.id = jl.journal_entry_id
    INNER JOIN chart_of_accounts a ON jl.account_id = a.account_code
    $whereSql
    ORDER BY je.created_at DESC, je.id DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ledgerRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Accounts for dropdown filter
$accounts = $pdo->query("
    SELECT account_code, account_name 
    FROM chart_of_accounts 
    ORDER BY account_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

$runningBalance = 0.0;
?>


<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>JengoPay | General Ledger</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="JengoPay | General Ledger" />
  <meta name="author" content="JengoPay" />
  <meta
    name="description"
    content="JengoPay General Ledger - Track and manage all financial transactions." />
  <meta
    name="keywords"
    content="jengopay, finance, accounting, general ledger, transactions" />
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
  <link rel="stylesheet" href="/jengopay/landlord/assets/main.css" />

  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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

  <style>
    :root {
      --primary-color: #00192D;
      --accent-color: #FFC107;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    body {
      font-size: 16px;
      background-color: var(--light-bg);
    }

    .app-wrapper {
      background-color: var(--light-bg);
    }
    

    a {
      text-decoration: none !important;
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">

    <!--begin::Header-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <?php include_once '../../includes/sidebar.php'; ?>
    <!--end::Sidebar-->

    <!--begin::App Main-->
    <main class="main">
      <div class="container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb" style="">
            <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/dashboard.php" style="text-decoration: none;">Dashboard</a></li>
            <li class="breadcrumb-item active">General ledger</li>
          </ol>
        </nav>

        <!--First Row-->
        <div class="row align-items-center mb-3">
          <div class="col-12 d-flex align-items-center">
            <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
            <h3 class="mb-0 ms-3">General Ledger</h3>
          </div>
        </div>

        <!-- Fifth Row: filter -->
        <div class="row g-3 mb-4">
          <!-- Filter by Building -->
          <div class="col-md-12 col-sm-12">
            <div class="card border-0 mb-4">
              <div class="card-body ">
                <h5 class="card-title mb-3"><i class="fas fa-filter"></i> Filters</h5>
                <form method="GET">
                  <!-- always reset to page 1 when applying filters -->
                  <input type="hidden" name="page" value="1">

                  <div class="filters-scroll">
                    <div class="row g-3 mb-3 filters-row">

                      <div class="col-auto filter-col">
                        <label for="account_id" class="form-label">Account</label>
                        <select id="account_id" name="account_id" class="form-select">
                          <option value="">-- All Accounts --</option>
                          <?php foreach ($accounts as $acc): ?>
                            <option value="<?= $acc['account_code'] ?>" <?= $account_id == $acc['account_code'] ? 'selected' : '' ?>>
                              <?= htmlspecialchars($acc['account_name']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="col-auto filter-col">
                        <label class="form-label text-muted small">Date From</label>
                        <input type="date" id="from_date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" class="form-control">
                      </div>

                      <div class="col-auto filter-col">
                        <label class="form-label text-muted small">Date To</label>
                        <input type="date" id="to_date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" class="form-control">
                      </div>

                    </div>
                  </div>

                  <div class="d-flex gap-2 justify-content-end">

                    <button type="button" class="btn text-white bg-secondary" onclick="resetFilters()">
                      <i class="fas fa-redo me-2"></i> Reset
                    </button>

                    <button type="submit" class="actionBtn">
                      <i class="fas fa-search"></i> Apply Filters
                    </button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <!-- Ledger Table -->
            <div class="card border-0 ">
             
                <div class="card-header d-flex justify-content-between align-items-center"
                  style="background-color:#00192D; color:#fff;">

                  <b>
                    The General Ledger
                    (<span class="text-warning"><?= date('d-M Y') ?></span>)
                  </b>

                  <!-- Export buttons -->
                  <div class="export-icons">
                    <button class="btn btn-sm me-2 pdfBtn2 text-dark bg-warning" title="Export PDF">
                      <i class="bi bi-download"></i>
                    </button>

                    <button class="btn btn-sm me-2 pdfBtn2 text-dark bg-warning" title="Export Excel">
                      <i class="bi bi-printer"></i>
                    </button>
                  </div>

                </div>
              
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-hover mb-0">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Account</th>
                        <th class="text-end">Debit (KSH)</th>
                        <th class="text-end">Credit (KSH)</th>
                        <th class="text-end">Running Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $runningBalance = 0;
                      foreach ($ledgerRows as $row):
                        $runningBalance += $row['debit'] - $row['credit'];
                        $balanceClass = $runningBalance >= 0 ? 'running-balance-positive' : 'running-balance-negative';
                      ?>
                        <tr>
                          <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))) ?></td>
                          <td>
                            <span class="badge-account"><?= htmlspecialchars($row['reference']) ?></span>
                          </td>
                          <td><?= htmlspecialchars($row['description']) ?></td>
                          <td>
                            <div ><?= htmlspecialchars($row['account_name']) ?>(<span class="text-sucess"> <?= htmlspecialchars($row['account_code']) ?></span>)</div>
                           
                          </td>
                          <td class="text-end"><?= number_format($row['debit'], 2) ?></td>
                          <td class="text-end"><?= number_format($row['credit'], 2) ?></td>
                          <td class="text-end <?= $balanceClass ?>"><?= number_format($runningBalance, 2) ?></td>
                        </tr>
                      <?php endforeach; ?>

                      <?php if (empty($ledgerRows)): ?>
                        <tr>
                          <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No transactions found for the selected filters</p>
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->

    <!--start footer -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!-- end footer -->
  </div>
  <!--end::App Wrapper-->

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <script src="/jengopay/landlord/assets/main.js"></script>

  <script>
    // Initialize DataTable
    $(document).ready(function() {
      $('table').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
          search: "Search transactions:",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
        }
      });
    });

    function exportToExcel() {
      const table = document.querySelector('table');
      const wb = XLSX.utils.table_to_book(table, {
        sheet: "General Ledger"
      });
      XLSX.writeFile(wb, 'General_Ledger_' + new Date().toISOString().split('T')[0] + '.xlsx');
    }

    function exportToPDF() {
      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF();

      // Add title
      doc.setFontSize(16);
      doc.text('General Ledger Report', 14, 15);

      // Add date range if available
      const fromDate = document.getElementById('from_date').value;
      const toDate = document.getElementById('to_date').value;
      if (fromDate && toDate) {
        doc.setFontSize(10);
        doc.text(`Date Range: ${fromDate} to ${toDate}`, 14, 22);
      }

      // Add table
      doc.autoTable({
        html: 'table',
        startY: 30,
        theme: 'grid',
        headStyles: {
          fillColor: [0, 25, 45]
        },
        styles: {
          fontSize: 8,
          cellPadding: 2
        }
      });

      doc.save('General_Ledger_' + new Date().toISOString().split('T')[0] + '.pdf');
    }

    // Reset filters function
    function resetFilters() {
      // Clear form inputs
      document.getElementById('from_date').value = '';
      document.getElementById('to_date').value = '';
      document.getElementById('account_id').value = '';

      // Submit the form to reload the page without filters
      window.location.href = window.location.pathname;
    }
  </script>
</body>
<!--end::Body-->

</html>