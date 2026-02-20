<?php
session_start();
require_once '../../db/connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

// actions
require_once './actions/getProfitAndLoss.php';
// include buildings
require_once 'actions/getBuildings.php';

?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Profit$Loss</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE | Dashboard v2" />
  <meta name="author" content="ColorlibHQ" />
  <meta
    name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <!--begin::Fonts-->

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

  <!-- Include jsPDF library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <!-- Include jsPDF autoTable plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
  <style>
    body {
      font-size: 16px;
    }

    .main-row td:first-child {
      font-weight: 600;
      font-size: 14px;
      color: #002B5B;
      /* navy */
      margin-left: 10px;
    }

    .amount-box {
      width: 50px;
      /* pick a width that fits your biggest number */
      white-space: nowrap;
      font-variant-numeric: tabular-nums;
      font-feature-settings: "tnum";
    }

    a {
      text-decoration: none !important;
    }

    /* table */
    .table tbody td {
      padding: 15px 10px;
      vertical-align: middle;
      color: var(--main-color);
      font-size: 14px;
      cursor: pointer;
    }

    .major-header th {
      font-size: 15px;
      letter-spacing: 0.2px;
      text-transform: uppercase;

    }

    /* Sub-section headers: Current Assets, etc */
    .section-header {
      background: rgba(0, 25, 45, 0.04);
      border-left: 4px solid rgba(0, 25, 45, 0.25);
      padding-left: 12px !important;
      font-weight: 600 !important;
    }

    /* Child rows (accounts) indentation */
    .child-row td:first-child {
      padding-left: 34px;
      position: relative;
    }

    /* Small marker to show it’s nested */
    .child-row td:first-child::before {
      content: "•";
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      opacity: .55;
    }

    /* Dotted leader line between description and amount */
    .desc-cell {
      position: relative;
    }

    .desc-cell .desc-text {
      position: relative;
      /* background: #fff; */
      /* helps the text sit over the dotted line */
      padding-right: 10px;
      z-index: 2;
    }

    .desc-cell::after {
      content: "";
      position: absolute;
      left: 34px;
      /* align with indentation */
      right: 5px;
      top: 50%;
      transform: translateY(-50%);
      border-bottom: 1px dotted rgba(0, 0, 0, 0.25);
      z-index: 1;
    }

    /* Hover for clickable rows */
    .clickable-row:hover {
      background: rgba(0, 25, 45, 0.06) !important;
    }

    /* Total rows pop */
    .total-row td,
    .total-row th {
      background: rgba(0, 0, 0, 0.03) !important;
      font-weight: 700;
      border-top: 1px solid rgba(0, 0, 0, 0.12);
    }

    /* Grand totals (Total Assets / Total Liabilities & Equity) */
    .total-row td.fs-6,
    .total-row td:contains("Total Assets") {
      font-weight: 800;
    }

    /* Make amount column look tighter and aligned */
    .amount-box {
      min-width: 160px;
      justify-content: flex-end;
      font-variant-numeric: tabular-nums;
      /* nicer digit alignment */
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">

    <!--begin::Header-->
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php';
    ?>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <?php
    include_once '../../includes/sidebar.php';
    ?>
    <!--end::Sidebar-->

    <!--begin::App Main-->
    <main class="main">
      <!--begin::Container-->
      <div class="container-fluid">

        <nav aria-label="breadcrumb">
          <ol class="breadcrumb" style="">
            <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/dashboard.php" style="text-decoration: none;">Dashboard</a></li>
            <li class="breadcrumb-item active">Profit $ Loss</li>
          </ol>
        </nav>

        <!--First Row-->
        <div class="row align-items-center mb-3">
          <div class="col-12 d-flex align-items-center">
            <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
            <h3 class="mb-0 ms-3">Profit $ Loss</h3>
          </div>
        </div>

        <!-- Filters -->
        <div class="row g-3 mb-4">
          <!-- Filter by Building -->
          <div class="col-md-12 col-sm-12">
            <div class="card border-0 mb-4">
              <div class="card-body ">

                <form method="GET">
                  <!-- always reset to page 1 when applying filters -->
                  <input type="hidden" name="page" value="1">

                  <div class="filters-scroll">
                    <div class="row g-3 mb-3 filters-row align-items-end">

                      <div class="col-auto filter-col">
                        <label class="form-label text-muted small">Buildings</label>

                        <select class="form-select shadow-sm" name="building_id">
                          <option value="">All Buildings</option>
                          <?php foreach ($buildings as $building): ?>
                            <?php $bid = (string)(int)$building['id']; ?>
                            <option value="<?= $bid ?>" <?= (($buildingId ?? '') === $bid) ? 'selected' : '' ?>>
                              <?= htmlspecialchars($building['building_name']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>

                      </div>

                      <div class="col-auto filter-col">
                        <label class="form-label text-muted small">Date From</label>
                        <input
                          type="date"
                          name="date_from"
                          class="form-control"
                          value="<?= htmlspecialchars($dateFrom ?? '') ?>">
                      </div>

                      <div class="col-auto filter-col">
                        <label class="form-label text-muted small">Date To</label>
                        <input
                          type="date"
                          name="date_to"
                          class="form-control"
                          value="<?= htmlspecialchars($dateTo ?? '') ?>">
                      </div>

                      <div class="col-auto filter-col d-flex gap-2">
                        <button type="submit" class="actionBtn">
                          <i class="fas fa-search"></i> Apply
                        </button>

                        <a href="profit&loss.php" class="btn btn-secondary">
                          <i class="fas fa-redo"></i> Reset
                        </a>
                      </div>

                    </div>

                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
        <!-- Third row, main content -->
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card border-0 min-vh-100">
              <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color:#00192D; color:#fff;">

                <b>
                  The Profit and Loss
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
                <table class="table table-striped rounded" style="width: 100%;">
                  <thead style="background-color: rgba(128, 128, 128, 0.2); color: black;">
                    <tr>
                      <th style="font-size: 16px;">Description</th>
                      <th class="p-0" style="font-size: 16px;">Amount (KSH) </th>
                    </tr>
                  </thead>
                  <tbody id="accordionFinance">
                    <!-- Income header -->
                    <tr>
                      <th colspan="2" class="section-header" style="color:green;background:rgba(39, 174, 96, 0.15);       padding: 15px 10px;">Income</th>
                    </tr>
                    <!-- Main Row -->
                    <tr>
                      <?php foreach ($incomeRows  as $item): ?>
                    <tr class="main-row" data-href="/jengopay/landlord/pages/financials/generalledger/general_ledger.php?account_code=<?= urlencode($item['account_code']) ?> style=" cursor:pointer;">
                      <td><?= $item['account_name'] ?></td>
                      <td class="p-0">
                        <div class="d-flex justify-content-start">
                          <div class="d-flex justify-content-end amount-box">
                            <?= $item['account_code'] ?>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach ?>

                  <!-- Total Income -->
                  <tr class="category">
                    <td><b>Total Income</b></td>
                    <td class="p-0">
                      <div class="d-flex justify-content-start">
                        <div class="d-flex justify-content-end amount-box">
                          <?= $totalIncome ?>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <!-- Expenses header -->
                  <tr>
                    <th colspan="2" class="section-header" style="color:red;background-color: #fff8e6; padding: 15px 10px;">Expenses</th>
                  </tr>


                  <?php foreach ($expenseRows as $item): ?>
                    <tr class="main-row child-row clickable-row" data-href="/jengopay/landlord/pages/financials/generalledger/general_ledger.php?account_code=<?= urlencode($item['account_code']) ?>">
                      <td class=" desc-cell"> <span class="desc-text"><?= $item['account_name'] ?></span> </td>
                      <td class="p-0">
                        <div class="d-flex justify-content-start">
                          <div class="d-flex justify-content-end amount-box">
                            <?= $item['amount'] ?>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach ?>

                  <tr class="category">
                    <td><b>Total Expenses</b></td>
                    <td class="p-0">
                      <div class="d-flex justify-content-start">
                        <div class="d-flex justify-content-end amount-box">
                          <b> <?= $totalExpenses ?></b>
                        </div>
                      </div>
                    </td>
                  </tr>

                  <tr class="category">
                    <td><b>NET PROFIT</b></td>
                    <td class="p-0">
                      <div class="d-flex justify-content-start">
                        <div class="d-flex justify-content-end amount-box">

                          <?php
                          $isNegative = $netProfit < 0;
                          $displayValue = number_format(abs($netProfit), 2);
                          ?>

                          <b class="<?= $isNegative ? 'text-danger' : '' ?>">
                            <?= $isNegative ? "($displayValue)" : $displayValue ?>
                          </b>

                        </div>
                      </div>
                    </td>

                  </tr>
                  </tbody>
                </table>
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
  <!--end::App Wrapper-->

  <script src="/jengopay/landlord/assets/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll("tr.clickable-row[data-href]").forEach(row => {
        row.addEventListener("click", (e) => {
          // If user clicks a link/button inside the row, let that work normally
          if (e.target.closest("a, button, input, select, textarea, label")) return;

          const href = row.getAttribute("data-href");
          if (href) window.location.href = href;
        });
      });
    });
  </script>
  <!--end::Script-->
</body>
<!--end::Body-->

</html>