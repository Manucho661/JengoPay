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
require_once 'actions/getBuildings.php';
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
    /* color: green !important; */
    /* font-size: 14px; */
  }

  .main-row td:first-child {
    font-weight: 600;
    font-size: 14px;
    color: #002B5B;
    /* navy */
    /* margin-left: 10px; */
  }

  .amount-box {
    width: 30px;
    /* pick a width that fits your biggest number */
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
    font-feature-settings: "tnum";
  }



  .main-row[aria-expanded="true"] {
    font-weight: bold;
  }


  .form-label {
    white-space: nowrap;
    /* Prevent the label text from wrapping */
  }



  /* more button */
  .more:hover {
    color: #00192D !important;
  }

  .stat-card {
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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
  }

  .table th {
    padding: 15px 10px;

  }

  /* Child items (accounts) */
  .child-row td:first-child {
    padding-left: 28px;
    /* indentation */
    position: relative;
    font-weight: 500;
  }

  /* small marker to show it's under a section */
  .child-row td:first-child::before {
    content: "•";
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    opacity: .6;
  }

  /* hover effect for clickable child rows */
  .child-row.clickable-row:hover {
    background: rgba(0, 25, 45, 0.06);
  }

  .section-header {
    background: rgba(0, 0, 0, 0.03);
    border-left: 4px solid rgba(0, 25, 45, 0.25);
    padding-left: 12px !important;
    color: #00192D;
    font-size: 14px;
    font-weight: 600;

  }

  /* ===== Balance Sheet table polish ===== */
  .table-striped tbody tr td,
  .table-striped tbody tr th {
    vertical-align: middle;
  }

  /* Major headers: Assets / Liabilities / Equity */
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
    background: #fff;
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
                      <div class="row g-3 mb-3 filters-row align-items-end">

                        <div class="col-auto filter-col">
                          <label class="form-label text-muted small">Buildings</label>

                          <select class="form-select shadow-sm" name="building_id">
                            <option value="">All Buildings</option>
                            <?php foreach ($buildings as $building): ?>
                              <?php $bid = (string)(int)$building['id']; ?>
                              <option value="<?= $bid ?>" <?= (($building_id ?? '') === $bid) ? 'selected' : '' ?>>
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
                            value="<?= htmlspecialchars($date_from ?? '') ?>">
                        </div>

                        <div class="col-auto filter-col">
                          <label class="form-label text-muted small">Date To</label>
                          <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="<?= htmlspecialchars($date_to ?? '') ?>">
                        </div>

                        <div class="col-auto filter-col d-flex gap-2">
                          <button type="submit" class="actionBtn">
                            <i class="fas fa-search"></i> Apply
                          </button>

                          <a href="balanceSheet.php" class="btn btn-secondary">
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

          <!-- /end row -->
          <div class="row">
            <!-- Start col -->
            <div class="col-md-12">
              <div class="card border-0 ">
                <div class="card-header d-flex justify-content-between align-items-center"
                  style="background-color:#00192D; color:#fff;">

                  <b>
                    The Balance Sheet
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
                    <table class="table" id="">
                      <thead>
                        <tr>
                          <th>Description</th>
                          <th>Amount (KSH)</th>
                        </tr>
                      </thead>

                      <!-- ================= ASSETS ================= -->
                      <tbody>
                        <tr class="major-header">
                          <th colspan="2" style="color:green;background:rgba(39, 174, 96, 0.15);">Assets</th>
                        </tr>


                        <tr>
                          <th colspan="2" class="section-header">Non-current Assets</th>
                        </tr>

                        <?php foreach ($nonCurrentAssets as $asset): ?>
                          <tr class="main-row child-row clickable-row"
                            data-href="/jengopay/landlord/pages/financials/generalledger/general_ledger.php?account_code=<?= urlencode($asset['account_id']) ?>"
                            style="cursor:pointer;">
                            <td><?= htmlspecialchars($asset['name']) ?></td>
                            <td class="d-flex justify-content-start">
                              <div class="">
                                <?php
                                $amount = (float)$asset['amount'];
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>

                        <tr class="total-row">
                          <td>Total</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)$totalNonCurrentAssets;
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>

                        <tr>
                          <th colspan="2" class="section-header">Current Assets</th>
                        </tr>

                        <?php foreach ($CurrentAssets as $asset): ?>
                          <tr class="main-row child-row clickable-row" data-href="/jengopay/landlord/pages/financials/generalledger/general_ledger.php?account_code=<?= urlencode($asset['account_id']) ?>" style="cursor:pointer;">

                            <td class="desc-cell">
                              <span class="desc-text"><?= htmlspecialchars($asset['name']) ?></span>
                            </td>
                            <td class="p-0">
                              <div class="d-flex justify-content-start">
                                <div class="d-flex justify-content-end amount-box">
                                  <?php
                                  $amount = (float)$asset['amount'];
                                  $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                  echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                  ?>
                                </div>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>

                        <tr class="total-row">
                          <td>Total</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)$totalCurrentAssets;
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>

                        <tr class="total-row">
                          <td class="fs-6">Total Assets</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)($totalCurrentAssets + $totalNonCurrentAssets);
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>


                        <!-- ================= LIABILITIES ================= -->

                        <tr class="major-header">
                          <th colspan="2" style="background:#fff8e6 !important;">Liabilities</th>
                        </tr>

                        <tr>
                          <th colspan="2" class="section-header">Current Liabilities</th>
                        </tr>

                        <?php foreach ($currentLiabilities as $liability): ?>
                          <tr class="main-row child-row clickable-row" style="cursor:pointer;" data-href="/jengopay/landlord/pages/financials/generalledger/general_ledger.php?account_code=<?= urlencode($liability['account_id']) ?>">

                            <td class="desc-cell">
                              <span class="desc-text"><?= htmlspecialchars($liability['account_name']) ?></span>
                            </td>

                            <td class="p-0">
                              <div class="d-flex justify-content-start">
                                <div class="d-flex justify-content-end amount-box">
                                  <?php
                                  $amount = (float)$liability['amount'];
                                  $formatted = $amount < 0
                                    ? '(' . number_format(abs($amount), 2) . ')'
                                    : number_format($amount, 2);

                                  echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                  ?>
                                </div>
                              </div>
                            </td>

                          </tr>

                        <?php endforeach; ?>

                        <tr class="total-row">
                          <td>Total</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)$totalCurrentLiabilities;
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>

                        <tr>
                          <th colspan="2" class="section-header">Non-Current Liabilities</th>
                        </tr>

                        <?php foreach ($nonCurrentLiabilities as $liability): ?>
                          <tr class="main-row clickable-row" data-href="/jengopay/financials/generalledger/general_ledger.php?account_code=<?= urlencode($asset['amount']) ?>" style="cursor:pointer;">

                            <td class="desc-cell">
                              <span class="desc-text"><?= htmlspecialchars($liability['account_name']) ?></span>
                            </td>
                            <td class="p-0">
                              <div class="d-flex justify-content-start">
                                <div class="d-flex justify-content-end amount-box">
                                  <?php
                                  $amount = (float)$liability['amount'];
                                  $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                  echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                  ?>
                                </div>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>

                        <tr class="total-row">
                          <td>Total</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)$totalNonCurrentLiabilities;
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>

                        <tr class="total-row">
                          <td>Total Liabilities</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $totalLiabilitiesAmount = (float)($totalCurrentLiabilities + $totalNonCurrentLiabilities);
                                $formatted = $totalLiabilitiesAmount < 0 ? '(' . number_format(abs($totalLiabilitiesAmount), 2) . ')' : number_format($totalLiabilitiesAmount, 2);
                                echo '<span class="' . ($totalLiabilitiesAmount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>


                        <!-- ================= EQUITY ================= -->

                        <tr>
                          <th colspan="2" style="background:#fff8e6 !important;">Equity</th>
                        </tr>

                        <tr class="main-row child-row clickable-row">
                          <td class="desc-cell">Owner's Capital</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)$owners_capital;
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>

                        <tr class="main-row child-row clickable-row">
                          <td class="desc-cell">Retained Earnings</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)$retainedEarnings;
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="total-row">
                          <td>Total Equity</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)($owners_capital + $retainedEarnings);
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr class="total-row">
                          <td>Total Liabilities and Equity</td>
                          <td class="p-0">
                            <div class="d-flex justify-content-start">
                              <div class="d-flex justify-content-end amount-box">
                                <?php
                                $amount = (float)($totalEquity + $totalLiabilitiesAmount);
                                $formatted = $amount < 0 ? '(' . number_format(abs($amount), 2) . ')' : number_format($amount, 2);
                                echo '<span class="' . ($amount < 0 ? 'text-danger' : 'text-success') . '">' . $formatted . '</span>';
                                ?>
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
  <!-- <script type="module" src="./js/main.js"></script> -->

  <script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"></script>

  <!--end::Script-->

  <!-- script to help you route to chat of accounts -->
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

</body>
<!--end::Body-->

</html>