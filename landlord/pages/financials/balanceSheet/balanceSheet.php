<?php
session_start();
require_once '../../db/connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

// require_once 'actions/getLiabilities.php';
// require_once 'actions/getRetainedEarnings.php';

// actions
require_once './actions/getNonCurrentAssets.php';
require_once './actions/getCurrentAssets.php';
require_once './actions/getCurrentLiabilities.php';
require_once './actions/getNonCurrentLiabilities.php';
require_once  './actions/getEquity.php';
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


  <!--Css files-->
  <link rel="stylesheet" href="/jengopay/landlord/assets/main.css" />
  <link rel="stylesheet" href="balancesheet.css">



  <!-- scripts for data_table -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


  <!-- Include XLSX and FileSaver.js for Excel export -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

</head>

<style>
  body {
    font-size: 16px;
  }

  .app-wrapper {
    background-color: rgba(128, 128, 128, 0.1);
  }


  td {
    font-size: 14px;
  }

  .total-row {
    font-weight: bold;
    background-color: #f2f2f2;
    /* light gray */
    font-size: 1.1em;
  }

  .total-row td {
    /* border-bottom: 2px solid #000; */
    /* top border to separate total */
  }

  /* liabilities */
  .totalLiabilities-row td {
    border-bottom: 2px solid #000;
    font-weight: bold;
    font-size: 16px;
  }

  /* equity */
  .totalEquityCell {
    font-weight: bold;
    font-size: 14px;
  }

  .totalAssets-row td {
    border-bottom: 2px solid #000;
    font-weight: bold;
    font-size: 16px;
    /* color:green; */
  }

  .main-section-header {
    font-size: 16px;
    font-weight: bold;
  }

  .section-header {
    color: green !important;
    font-size: 14px;
  }

  .main-row td:first-child {
    font-weight: 600;
    font-size: 14px;
    color: #002B5B;
    /* navy */
    margin-left: 10px;
  }

  .main-row[aria-expanded="true"] {
    font-weight: bold;
  }

  .asset-name-cell:focus {
    background-color: #00192D !important;
  }

  .equityAndLiabilities {
    font-weight: bold;
    font-size: 16px;
    border-bottom: 2px solid #000;
  }

  .form-label {
    white-space: nowrap;
    /* Prevent the label text from wrapping */
  }

  .amount-cell {
    text-align: start;
    /* keep the div at the start of the cell */
    vertical-align: middle;
  }

  .amount-text {
    display: inline-block;
    /* allows fixed width and internal alignment */
    width: 100px;
    /* width of the div controlling text alignment */
    text-align: right;
    /* aligns text inside the div to the right */
  }

  /* more button */
  .more:hover {
    color: #00192D !important;
  }

  .stat-card {
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
</style>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

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
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-8">
              <div class="col-sm-8 d-flex">
                <span class="info-box-icon p-2 rounded" style="background-color:#FFC107; color:#fff;">
                  <i class="bi bi-currency-exchange" style="color:#00192D;"></i> </span>
                <h3 class="mb-0 mx-2">The Balance Sheet</h3>
              </div>
            </div>
            <div class="col-sm-4">
            </div>
          </div>
          <!--begin::Row-->
          <div class="row first mb-2 mt-2 rounded-circle">
            <p class="text-muted">Manage your Balance Sheet </p>
            <!-- /.col -->
          </div>
          <!--end::Row-->
          <!--begin::Row-->
          <div class="row align-items-center p-2 rounded mb-3" style="background-color: #ffffff;">
            <div class="col-md-9">
              <div class="d-flex gap-3 align-items-center w-100">
                <!-- Category Filter -->
                <div class="form-group w-100">
                  <label for="selectFilter" class="form-label p-2">Choose a Building</label>
                  <select id="selectFilter" class="form-select w-100" aria-label="Select filter">
                    <option selected>Select a building</option>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                  </select>
                </div>

                <!-- Date Filter -->
                <div class="form-group w-100">
                  <label for="filterDate" class="form-label p-2">Select Date</label>
                  <input type="date" id="filterDate" class="form-control w-100 border-1 shadow-sm" />
                </div>
              </div>
            </div>

            <!-- Export Buttons -->
            <div class="col-md-3 d-flex justify-content-md-end mt-3 mt-md-0">
              <div class="d-flex gap-2">
                <button class="btn rounded-circle shadow-sm" id="downloadBtn" style="background-color: #FFC107; border: none;">
                  <i class="fas fa-file-pdf" style="font-size: 24px; color: #00192D;"></i>
                </button>
                <button class="btn rounded-circle shadow-sm" id="exportToExcel" style="background-color: #FFC107; border: none;">
                  <i class="fas fa-file-excel" style="font-size: 24px; color: #00192D;"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- /end row -->
          <div class="row">
            <!-- Start col -->
            <div class="container balancesheet">
              <div>
                <div class="table-responsive">
                  <table class="table table-striped" id="myTable">
                    <thead class="thead">
                      <tr>
                        <th>Description</th>
                        <th>Amount (KSH)</th>
                      </tr>
                    </thead>
                    <tbody class="mb-3">
                      <tr>
                        <th colspan="2">Assets</th>
                      </tr>
                      <tr>
                        <th colspan="2" class="section-header fs-6 fw-normal">Non-current Assets</th>
                      </tr>

                      <?php foreach ($nonCurrentAssets as $asset): ?>
                        <tr
                          class="main-row clickable-row"
                          data-href="/jengopay/financials/generalledger/general_ledger.php?account_code=<?= urlencode($asset['amount']) ?>"
                          style="cursor: pointer;">
                          <td><?= htmlspecialchars($asset['name']) ?></td>
                          <td class="amount-text text-success">
                            <?php
                            $amount = (float)$asset['amount'];
                            echo $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                            if ($amount < 0) echo '<span style="color:red;">';
                            ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>

                      <tr class="total-row">
                        <td>Total</td>
                        <td class="amount-text">
                          <?php
                          $totalNonCurrentAssets = (float)$totalNonCurrentAssets;
                          echo $totalNonCurrentAssets < 0 ? '(' . number_format(abs($totalNonCurrentAssets), 2) . ')' : number_format($totalNonCurrentAssets, 2);
                          if ($totalNonCurrentAssets < 0) echo '<span style="color:red;">';
                          ?>
                        </td>
                      </tr>
                    </tbody>
                    <tbody>
                      <tr>
                        <th colspan="2" class="section-header fs-6 fw-normal">Current Assets</th>
                      </tr>

                      <?php foreach ($CurrentAssets as $asset): ?>
                        <tr
                          class="main-row clickable-row"
                          data-href="/jengopay/financials/generalledger/general_ledger.php?account_code=<?= urlencode($asset['amount']) ?>"
                          style="cursor: pointer;">
                          <td><?= htmlspecialchars($asset['name']) ?></td>
                          <td class="amount-text text-success">
                            <?php
                            $amount = (float)$asset['amount'];
                            echo $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                            if ($amount < 0) echo '<span style="color:red;">';
                            ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>

                      <tr class="total-row">
                        <td>Total</td>
                        <td class="amount-text">
                          <?php
                          $totalCurrentAssets = (float)$totalCurrentAssets;
                          echo $totalCurrentAssets < 0 ? '(' . number_format(abs($totalCurrentAssets), 2) . ')' : number_format($totalCurrentAssets, 2);
                          if ($totalCurrentAssets < 0) echo '<span style="color:red;">';
                          ?>
                        </td>
                      </tr>
                      <tr class="total-row">
                        <td class="fs-6">Total Assets</td>
                        <td class="amount-text">
                          <?php
                          $totalAssets = (float)($totalCurrentAssets + $totalNonCurrentAssets);
                          echo $totalAssets < 0 ? '(' . number_format(abs($totalAssets), 2) . ')' : number_format($totalAssets, 2);
                          if ($totalAssets < 0) echo '<span style="color:red;">';
                          ?>
                        </td>
                      </tr>
                    </tbody>
                    <tbody>
                      <tr>
                        <th colspan="2">Liabilities</th>
                      </tr>
                      <tr>
                        <th colspan="2" class="section-header fs-6 fw-normal">Current Liabilities</th>
                      </tr>

                      <?php foreach ($currentLiabilities as $currentLiability): ?>
                        <tr
                          class="main-row clickable-row"
                          data-href="/jengopay/financials/generalledger/general_ledger.php?account_code=<?= urlencode($currentLiability['amount']) ?>"
                          style="cursor: pointer;">
                          <td><?= htmlspecialchars($currentLiability['account_name']) ?></td>
                          <td class="amount-text text-success">
                            <?php
                            $amount = (float)$currentLiability['amount'];
                            echo $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                            if ($amount < 0) echo '<span style="color:red;">';
                            ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <tr class="total-row">
                        <td>Total</td>
                        <td class="amount-text">
                          <?php
                          $totalCurrentLiabilities = (float)$totalCurrentLiabilities;
                          echo $totalCurrentLiabilities < 0 ? '(' . number_format(abs($totalCurrentLiabilities), 2) . ')' : number_format($totalCurrentLiabilities, 2);
                          if ($totalCurrentLiabilities < 0) echo '<span style="color:red;">';
                          ?>
                        </td>
                      </tr>
                    </tbody>
                    <tbody>
                      <tr>
                        <th colspan="2" class="section-header fs-6 fw-normal">Non-Current Liabilities</th>
                      </tr>
                      <?php foreach ($nonCurrentLiabilities as $nonCurrentliability): ?>
                        <tr
                          class="main-row clickable-row"
                          data-href="/jengopay/financials/generalledger/general_ledger.php?account_code=<?= urlencode($nonCurrentliability['amount']) ?>"
                          style="cursor: pointer;">
                          <td><?= htmlspecialchars($nonCurrentliability['account_name']) ?></td>
                          <td class="amount-text text-success">
                            <?php
                            $amount = (float)$nonCurrentliability['amount'];
                            echo $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                            if ($amount < 0) echo '<span style="color:red;">';
                            ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <tr class="total-row">
                        <td>Total</td>
                        <td class="amount-text">
                          <?php
                          $totalNonCurrentLiabilities = (float)$totalNonCurrentLiabilities;
                          echo $totalNonCurrentLiabilities < 0 ? '(' . number_format(abs($totalNonCurrentLiabilities), 2) . ')' : number_format($totalNonCurrentLiabilities, 2);
                          if ($totalNonCurrentLiabilities < 0) echo '<span style="color:red;">';
                          ?>
                        </td>
                      </tr>
                      <tr class="total-row">
                        <td class="total-row">Total Liabilities</td>
                        <td class="amount-text">
                          <?php
                          $totalLiabilities = (float)($totalNonCurrentLiabilities + $totalCurrentLiabilities);
                          echo $totalLiabilities < 0 ? '(' . number_format(abs($totalLiabilities), 2) . ')' : number_format($totalLiabilities, 2);
                          if ($totalLiabilities < 0) echo '<span style="color:red;">';
                          ?>
                        </td>
                      </tr>
                    </tbody>
                    <tbody>
                      <tr>
                        <th colspan="2">Equity</th>
                      </tr>
                      <tr>
                        <td class="main-row clickable-row">Owner's Capital</td>
                        <td class="amount-text">
                          <?php
                          $totalEquity = (float)$totalEquity;
                          echo $totalEquity < 0 ? '(' . number_format(abs($totalEquity), 2) . ')' : number_format($totalEquity, 2);
                          if ($totalEquity < 0) echo '<span style="color:red;">';
                          ?>
                        </td>
                      </tr>
                      <tr class="main-row clickable-row">
                        <td>Retained Earnings</td>
                        <td class="amount-text">
                          <?php
                          $retainedEarnings = (float)$retainedEarnings;
                          echo $retainedEarnings < 0 ? '(' . number_format(abs($retainedEarnings), 2) . ')' : number_format($retainedEarnings, 2);
                          if ($retainedEarnings < 0) echo '<span style="color:red;">';
                          ?>
                        </td>
                      </tr>
                    </tbody>
                    <tr>
                      <td class="total-row">Total liabilities and Equity</td>
                      <td class="amount-text">
                        <?php
                        $totalEquityAndLiabilities = (float)($totalEquity + $totalLiabilities);
                        echo $totalEquityAndLiabilities < 0 ? '(' . number_format(abs($totalEquityAndLiabilities), 2) . ')' : number_format($totalEquityAndLiabilities, 2);
                        if ($totalEquityAndLiabilities < 0) echo '<span style="color:red;">';
                        ?>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>

              <!-- /.col -->
            </div>
            <!--end::Row-->
          </div>
        </div>
        <!--end::Container-->
      </div>
    </main>
    <!--end::App Main-->

    <!--begin::Footer-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!--end::Footer-->
  </div>
  <!--end::App Wrapper-->

  <!-- Overlay scripts -->
  <!-- View announcements script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Function to toggle the visibility of the overlay
    function toggleOverlay() {
      var overlay = document.getElementById('overlay');
      // If overlay is hidden, show it
      if (overlay.style.display === "none" || overlay.style.display === "") {
        overlay.style.display = "flex";
      } else {
        overlay.style.display = "none";
      }
    }
  </script>


  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <!-- Js files -->
  <script src="../../../assets/main.js"></script>
  <script type="module" src="./js/main.js"></script>

  <!-- handle link to gl -->
  <script>
    document.addEventListener("click", function(e) {
      const row = e.target.closest(".clickable-row");
      if (row && row.dataset.href) {
        window.location.href = row.dataset.href;
      }
    });
  </script>

  <script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"></script>

  <!--end::Script-->
</body>
<!--end::Body-->

</html>