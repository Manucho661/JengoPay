<?php
require_once 'actions/getLiabilities.php';
require_once 'actions/getEquity.php';
require_once 'actions/getRetainedEarnings.php';

// formating negative numbers
function formatAccounting($amount)
{
  return $amount < 0
    ? '(' . number_format(abs($amount), 2) . ')'
    : number_format($amount, 2);
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

  <link rel="stylesheet" href="balancesheet.css">

  <link rel="stylesheet" href="">

  <!-- scripts for data_table -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

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

  .sub-category {
    padding-left: 35px !important;
    font-size: 16px;
  }

  .sub-category.total {
    font-weight: 600;
  }

  .sub-current-assets.total {
    font-weight: 600;
    font-size: 20px;
  }

  .sub-current-assets {
    font-size: 20px;
  }

  .current-assets {
    font-size: 14px;
    font-weight: bold;
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
    border-bottom: 2px solid #000;
    /* top border to separate total */
  }

  .main-section-header {
    font-size: 16px;
    font-weight: bold;
  }

  .section-header {
    color: green !important;
    font-size: 14px;
    font-weight: bold;
  }
.main-row td:first-child {
  font-weight: 600;
  font-size: 14px;
  color: #002B5B; /* navy */
  margin-left: 10px;
}

  .main-row[aria-expanded="true"] {
    font-weight: bold;
  }
  .asset-name-cell:focus{
    background-color: #00192D !important;
  }
</style>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <?php include_once "actions/getAssets.php" ?>
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
      <div> <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?> </div> <!-- This is where the sidebar is inserted -->
      <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-8">
              <h3 class="mb-0 contact_section_header"> 💰 &nbsp; Balance Sheet</h3>
            </div>
            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#" style="color: #00192D;"> <i class="bi bi-house"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
          <!-- /end row -->
        </div>
        <!--end::Container-->
      </div>
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!-- Info boxes -->
          <!-- /.row -->
          <!--begin::Row-->
          <div class="row first mb-2 mt-2 rounded-circle">
            <p class="text-muted">Manage your Balance Sheet </p>
            <!-- /.col -->
          </div>
          <!--end::Row-->
          <!--begin::Row-->
          <div class="row align-items-center py-3 px-4 rounded shadow-sm mb-3" style="background-color: #ffffff;">
            <div class="col-md-6">
              <div class="d-flex gap-3 align-items-center">
                <!-- Category Filter -->
                <select id="categoryFilter" class="form-select border-1 shadow-sm" style="background-color: #ffffff; color: #00192D; border-color: #00192D;">
                  <option value="">Select</option>
                  <option value="technology">All</option>
                  <option value="health">Manucho</option>
                  <option value="business">Ebenezer</option>
                  <option value="education">Crown Z</option>
                </select>

                <!-- Date Filter -->
                <input type="date" id="filterDate" class="form-control border-1 shadow-sm" style="background-color: #ffffff; color: #00192D; border-color: #00192D;" />
              </div>
            </div>

            <!-- Export Buttons -->
            <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
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

          <!--end::Row-->

          <!--begin::Row-->
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
                    <tbody>
                      <!-- Assets Section -->
                      <tr>
                        <td class="current-assets" style="color:green;">Current Assets:</td>
                        <td></td>
                      </tr>
                      <?php foreach ($currentAssets as $item): ?>
                        <?php if (in_array($item['name'], $mustDisplayedCurrentAssets) || $item['amount'] > 0): ?>
                          <tr class="sub-current-assets">
                            <td class="sub-category"><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= formatAccounting($item['amount']) ?></td>
                          </tr>
                        <?php endif; ?>
                      <?php endforeach; ?>
                      <tr class="sub-current-assets total fw-bold">
                        <td class="sub-category">Total Current Assets</td>
                        <td><?= formatAccounting($totalCurrent) ?></td>
                      </tr>

                      <?php
                      echo '<tr class="fw-bold">';
                      echo '<td> <div style="font-size:16px;">Total Assets </div> </td>';
                      echo '<td>' . formatAccounting($totalAssets) . '</td>';
                      echo '</tr>';

                      ?>
                      <!-- Liabilities Section -->
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>


                      <tr class="fw-bold">
                        <td class="Liabilities main-category">Liabilities</td>
                        <td></td>
                      </tr>
                      <?php
                      echo '<tr><td class="current-assets" style="color:green;">Current Liabilities:</td><td></td></tr>';
                      foreach ($currentLiabilities as $item) {
                        echo '<tr class="sub-current-assets">';
                        echo '<td class="sub-category">' . htmlspecialchars($item['liability_name']) . '</td>';
                        echo '<td>' . formatAccounting($item['amount']) . '</td>';
                        echo '</tr>';
                      }
                      echo '<tr class="sub-current-assets total fw-bold">';
                      echo '<td class="sub-category">Total Current Liabilities</td>';
                      echo '<td>' . formatAccounting($totalCurrentLiabilities) . '</td>';
                      echo '</tr>';
                      ?>
                      <?php
                      echo '<tr><td class="current-assets" style="color:green;">Non-Current Liabilities:</td><td></td></tr>';
                      foreach ($nonCurrentLiabilities as $item) {
                        echo '<tr class="sub-current-assets">';
                        echo '<td class="sub-category">' . htmlspecialchars($item['liability_name']) . '</td>';
                        echo '<td>' . formatAccounting($item['amount']) . '</td>';
                        echo '</tr>';
                      }
                      echo '<tr class="sub-current-assets total fw-bold">';
                      echo '<td class="sub-category">Total Non-Current Liabilities</td>';
                      echo '<td>' . formatAccounting($totalNonCurrentLiabilities) . '</td>';
                      echo '</tr>';
                      ?>
                      <?php
                      echo '<tr class="fw-bold">';
                      echo '<td> <div style="font-size:16px;">Total Liabilities </div> </td>';
                      echo '<td>' . formatAccounting($totalLiabilities) . '</td>';
                      echo '</tr>';

                      ?>
                      <!-- Equity Section -->
                      <tr class="fw-bold">
                        <td></td>
                        <td></td>
                      </tr>

                      <tr class="equity" class="fw-bold">
                        <td class="main-category"><b>Equity</b> </td>
                        <td></td>
                      </tr>
                      <?php
                      foreach ($owners_equities as $item) {
                        echo '<tr class="sub-current-assets">';
                        echo '<td class="sub-category">' . htmlspecialchars($item['name']) . '</td>';
                        echo '<td>' . formatAccounting($item['amount']) . '</td>';
                        echo '</tr>';
                      }

                      // ✅ Add $retainedEarnings under Equity section
                      echo '<tr class="sub-current-assets">';
                      echo '<td class="sub-category">Retained Earnings</td>';
                      echo '<td>' . formatAccounting($retainedEarnings) . '</td>';
                      echo '</tr>';

                      // ✅ Total Equity (including retainedEarnings)
                      $grandTotalEquity = $totalEquity + $retainedEarnings;

                      echo '<tr class="sub-current-assets total fw-bold">';
                      echo '<td class="sub-category">Total Equity</td>';
                      echo '<td>' . formatAccounting($grandTotalEquity) . '</td>';
                      echo '</tr>';
                      ?>
                      <!-- Total Liabilities & Equity -->
                      <?php
                      echo '<tr class="fw-bold">';
                      echo '<td> <div style="font-size:16px;">Total Liabilities & Equity </div> </td>';
                      echo '<td>' . number_format($totalLiabilities + $grandTotalEquity, 2) . '</td>';
                      echo '</tr>';
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->



    <footer class="app-footer">
      <!--begin::To the end-->
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <!--end::To the end-->
      <!--begin::Copyright-->
      <strong>
        Copyright &copy; 2014-2024&nbsp;
        <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;"> JENGO PAY</a>.
      </strong>
      All rights reserved.
      <!--end::Copyright-->
    </footer>
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
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->

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
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>



  <!--
  Add expense scripts.

-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="../../../../landlord/js/adminlte.js"></script>
  <script type="module" src="./js/main.js"></script>

  <script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"></script>

  <!--end::Script-->
</body>
<!--end::Body-->

</html>