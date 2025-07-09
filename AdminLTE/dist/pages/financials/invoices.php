<?php
include '../db/connect.php';

$stmt = $pdo->prepare("SELECT account_code, account_name FROM chart_of_accounts ORDER BY account_name ASC");
$stmt->execute();
$accountItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
include '../db/connect.php';

// Get the latest invoice number
$stmt = $pdo->query("SELECT invoice_number FROM invoice ORDER BY id DESC LIMIT 1");
$lastInvoice = $stmt->fetch(PDO::FETCH_ASSOC);

// Extract and increment the numeric part
if ($lastInvoice && preg_match('/INV(\d+)/', $lastInvoice['invoice_number'], $matches)) {
    $nextNumber = intval($matches[1]) + 1;
} else {
    $nextNumber = 1;
}

// Format the new invoice number (e.g., INV00001)
$invoiceNumber = 'INV' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

$invoiceId = $pdo->lastInsertId();
?>


<?php
// At the top of your PHP file (before HTML)
require_once '../db/connect.php';

try {
    $stmt = $pdo->prepare("SELECT building_id FROM buildings ORDER BY building_id");
    $stmt->execute();
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $buildings = [];
    // You might want to log this error in production
    error_log("Error fetching buildings: " . $e->getMessage());
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
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
    <link rel="stylesheet" href="invoices.css">
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

    <style>
      a{
          text-decoration: none;
      }

      body{
        font-size: 16px;
          background-color: rgba(128, 128, 128, 0.1);
      }
      .summary-table {
        float: right;
        width: 20px;
        margin-top: 20px;
      }

      .summary-table th {
        width: 50%;
        text-align: left;
      }

      .summary-table td input {
        width: 70%;
        font-size: 0.8rem; /* Adjusting the font size */
      }

  </style>
  <script>
function addRow() {
  const table = document.querySelector(".items-table tbody");
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
    <td>
      <select name="account_item[]" required>
        <option value="" disabled selected>Select Option</option>
        <option value="rent">Rent</option>
        <option value="water">Water Bill</option>
        <option value="garbage">Garbage</option>
      </select>
    </td>
    <td><textarea name="description[]" placeholder="Description" rows="1" required></textarea></td>
    <td><input type="number" name="unit_price[]" class="form-control unit-price" placeholder="123" required></td>
    <td><input type="number" name="quantity[]" class="form-control quantity" placeholder="1" required></td>
    <td><input type="number" class="form-control subtotal" placeholder="0" readonly></td>
    <td>
      <select name="taxes[]" class="form-select vat-option" required>
        <option value="" disabled selected>Select Option</option>
        <option value="inclusive">VAT 16% Inclusive</option>
        <option value="exclusive">VAT 16% Exclusive</option>
        <option value="zero">Zero Rated</option>
        <option value="exempt">Exempted</option>
      </select>
    </td>
    <td><input type="number" class="form-control vat-amount" placeholder="0" readonly></td>
    <td><input type="number" name="total[]" class="form-control total" placeholder="0" readonly></td>
    <td><button type="button" class="delete-btn btn btn-danger btn-sm" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button></td>
  `;

  table.appendChild(newRow);
  attachEvents(newRow); // Attach calculation events to new row
}

function deleteRow(btn) {
  btn.closest("tr").remove();
  updateTotals();
}

function attachEvents(row) {
  const unitPriceInput = row.querySelector(".unit-price");
  const quantityInput = row.querySelector(".quantity");
  const vatSelect = row.querySelector(".vat-option");

  unitPriceInput.addEventListener("input", () => calculateRow(row));
  quantityInput.addEventListener("input", () => calculateRow(row));
  vatSelect.addEventListener("change", () => calculateRow(row));
}

function calculateRow(row) {
  const unitPrice = parseFloat(row.querySelector(".unit-price").value) || 0;
  const quantity = parseFloat(row.querySelector(".quantity").value) || 0;
  const vatOption = row.querySelector(".vat-option").value;

  const subtotal = unitPrice * quantity;
  let vatAmount = 0;

  if (vatOption === "inclusive") {
    vatAmount = subtotal - (subtotal / 1.16);
  } else if (vatOption === "exclusive") {
    vatAmount = subtotal * 0.16;
  } else if (vatOption === "zero" || vatOption === "exempt") {
    vatAmount = 0;
  }

  const total = (vatOption === "inclusive") ? subtotal : subtotal + vatAmount;

  row.querySelector(".subtotal").value = subtotal.toFixed(2);
  row.querySelector(".vat-amount").value = vatAmount.toFixed(2);
  row.querySelector(".total").value = total.toFixed(2);

  updateTotals();
}

function updateTotals() {
  // Optional: implement total summary across all rows here if needed
}

function printInvoice() {
  window.print();
}

function downloadPDF() {
  const element = document.querySelector(".invoice-container");
  html2pdf().from(element).save("invoice.pdf");
}

// Attach events to initial row(s) after DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".items-table tbody tr").forEach(row => {
    attachEvents(row);
  });
});
</script>

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
            <!-- <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li> -->
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
                        src="../../dist/assets/img/user1-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-end fs-7 text-danger"
                          ><i class="bi bi-star-fill"></i
                        ></span>
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
                        src="../../dist/assets/img/user8-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
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
                        src="../../dist/assets/img/user3-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
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
                  src="../../dist/assets/img/user2-160x160.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">Alexander Pierce</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="../../dist/assets/img/user2-160x160.jpg"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
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
            <!--begin::Brand Image-->
            <!-- <img
              src="../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            /> -->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light"></span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div><?php include_once '../includes/sidebar1.php'; ?></div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <!-- <li class="nav-item">
                    <a href="./index.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard</p>
                    </a>
                  </li> -->
                  <li class="nav-item">
                    <a href="./index2.html" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard </p>
                    </a>
                  </li>
                  <!-- <li class="nav-item">
                    <a href="./index3.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Dashboard v3</p>
                    </a>
                  </li> -->
                </ul>
              </li>
              <li class="nav-item">
                <a href="./property.html" class="nav-link">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>Tenants</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./tenantscreening.html" class="nav-link">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>Tenant Screening</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./leasy.html" class="nav-link">
                  <i class="nav-icon bi bi-file-earmark-text"></i>
                  <p>Lease Management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>
                   Financial Documents
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./invoices.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Invoices</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./paymentreceipts.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Payment Receipts</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./rentdeposit.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p> Rent Deposit Reports</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./profitandloss.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Profit&Loss Statement</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./balancesheet.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Balance Sheet</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./cashflow.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Cashflow</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="./taxreports.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Tax Reports</p>
                    </a>
                  </li>

                </ul>
              </li>
              <li class="nav-item">
                <a href="./showCommunication.html" class="nav-link">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>Maintenance Requests</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="./serviceproviders.html" class="nav-link">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>Service Providers</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="./rating&reviews.html" class="nav-link">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>Reviews And Rating</p>
                </a>
              </li>

               <!-- Start Communications part -->
               <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-pencil-square"></i>
                  <p>
                    Communications
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="communications/texts.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Texts</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="communications/announcements.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Announcements</p>
                    </a>

                  </li>
                </ul>
              </li>

              <!-- End communications part -->

              <!-- <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>
                    Layout Options
                    <span class="nav-badge badge text-bg-secondary me-3">6</span>
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./layout/unfixed-sidebar.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Default Sidebar</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./layout/fixed-sidebar.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Fixed Sidebar</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./layout/layout-custom-area.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Layout <small>+ Custom Area </small></p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./layout/sidebar-mini.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Sidebar Mini</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./layout/collapsed-sidebar.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Sidebar Mini <small>+ Collapsed</small></p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./layout/logo-switch.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Sidebar Mini <small>+ Logo Switch</small></p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./layout/layout-rtl.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Layout RTL</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-tree-fill"></i>
                  <p>
                    UI Elements
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./UI/general.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>General</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./UI/icons.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Icons</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./UI/timeline.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Timeline</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-pencil-square"></i>
                  <p>
                    Forms
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./forms/general.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>General Elements</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-table"></i>
                  <p>
                    Tables
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./tables/simple.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Simple Tables</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-header">EXAMPLES</li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-box-arrow-in-right"></i>
                  <p>
                    Auth
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-box-arrow-in-right"></i>
                      <p>
                        Version 1
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="./examples/login.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Login</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="./examples/register.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Register</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-box-arrow-in-right"></i>
                      <p>
                        Version 2
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="./examples/login-v2.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Login</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="./examples/register-v2.html" class="nav-link">
                          <i class="nav-icon bi bi-circle"></i>
                          <p>Register</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="./examples/lockscreen.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Lockscreen</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-header">DOCUMENTATIONS</li>
              <li class="nav-item">
                <a href="./docs/introduction.html" class="nav-link">
                  <i class="nav-icon bi bi-download"></i>
                  <p>Installation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./docs/layout.html" class="nav-link">
                  <i class="nav-icon bi bi-grip-horizontal"></i>
                  <p>Layout</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./docs/color-mode.html" class="nav-link">
                  <i class="nav-icon bi bi-star-half"></i>
                  <p>Color Mode</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-ui-checks-grid"></i>
                  <p>
                    Components
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./docs/components/main-header.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Main Header</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./docs/components/main-sidebar.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Main Sidebar</p>
                    </a>
                  </li>
                </ul>
              </li> -->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-filetype-js"></i>
                  <p>
                   Help
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./docs/javascript/treeview.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Treeview</p>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- <li class="nav-item">
                <a href="./docs/browser-support.html" class="nav-link">
                  <i class="nav-icon bi bi-browser-edge"></i>
                  <p>Browser Support</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./docs/how-to-contribute.html" class="nav-link">
                  <i class="nav-icon bi bi-hand-thumbs-up-fill"></i>
                  <p>How To Contribute</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="./docs/faq.html" class="nav-link">
                  <i class="nav-icon bi bi-question-circle-fill"></i>
                  <p>FAQ</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="./docs/license.html" class="nav-link">
                  <i class="nav-icon bi bi-patch-check-fill"></i>
                  <p>License</p>
                </a>
              </li>
              <li class="nav-header">MULTI LEVEL EXAMPLE</li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle-fill"></i>
                  <p>Level 1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle-fill"></i>
                  <p>
                    Level 1
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Level 2</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>
                        Level 2
                        <i class="nav-arrow bi bi-chevron-right"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      <li class="nav-item">
                        <a href="#" class="nav-link">
                          <i class="nav-icon bi bi-record-circle-fill"></i>
                          <p>Level 3</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#" class="nav-link">
                          <i class="nav-icon bi bi-record-circle-fill"></i>
                          <p>Level 3</p>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="#" class="nav-link">
                          <i class="nav-icon bi bi-record-circle-fill"></i>
                          <p>Level 3</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Level 2</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle-fill"></i>
                  <p>Level 1</p>
                </a>
              </li>
              <li class="nav-header">LABELS</li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle text-danger"></i>
                  <p class="text">Important</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle text-warning"></i>
                  <p>Warning</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-circle text-info"></i>
                  <p>Informational</p>
                </a>
              </li>
            </ul> -->
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">

              <!-- Filter Section -->
    <!-- <div class="filter-container">
      <h3>Balance Sheet</h3>
     <label for="date-range">
       <i class="fas fa-filter"></i>
       Filters:</label>
     <select id="date-range">AS of :Month
         <option value="Q1">Month</option>
         <option value="Q1">January</option>
         <option value="Q2">February</option>
         <option value="Q3">March</option>
         <option value="Q4">April</option>
         <option value="Q1">May</option>
         <option value="Q1">June</option>
         <option value="Q2">July</option>
         <option value="Q3">August</option>
         <option value="Q4">September</option>
         <option value="Q1">October</option>
         <option value="Q1">November</option>
         <option value="Q2">December</option>
     </select> -->

     <!-- <label for="category">Basis:</label>
     <select id="category">
         <option value="cash">Cash</option>
         <option value="accrual">Accrual</option>
     </select> -->

     <!-- <button onclick="applyFilter()">RUN REPORTS</button>
 </div>
            <div class="statement-container">
              <button id="exportButton" style="border-radius: 10px; margin-left: 90%;">
                <i class="fas fa-share-square"></i> Export
               </button> -->
                <!-- <h2>Balance Sheet|CROWN Z TOWERS(MONTHLY)</h2> -->

                 <!-- /.col -->
        <!-- <div class="col-md-12">
            <div class="card" > -->
              <!-- <div class="card-header" style="background-color: #00192D;"> -->
                <!-- <h3 class="card-title"  style="color: #FFC107; ">BALANCE SHEET MONTHLY|CROWN Z TOWERS(Monthly)</h3> -->

                <!-- <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                  </button>
                </div> -->
                 <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body text-center">

                    <!-- <table>
                        <tr>
                        <th style="text-align: left; color: #FFC107;">Assets</th>
                        <th style="text-align: right; color: #FFC107;">Amount (KSH)</th>
                    </tr>
                        <tr class="category" style=" color: #FFC107;"><td>Current Assets</td><td></td></tr>
                        <tr><td>Rent Deposits</td><td>KSH5,000</td></tr>
                        <tr><td>Accounts Receivable</td><td>KSH2,000</td></tr>
                        <tr><td>Prepaid Expenses</td><td>KSH300</td></tr>
                        <tr><td>Maintenance Inventory</td><td>KSH700</td></tr>

                        <tr class="category" style=" color: #FFC107;"><td>Fixed Assets</td><td></td></tr>
                        <tr><td>Office Equipment</td><td>KSH3,000</td></tr>
                        <tr><td>Vehicles</td><td>KSH15,000</td></tr>
                        <tr><td>Property Assets</td><td>KSH50,000</td></tr>

                        <tr><td>Investments</td><td>KSH10,000</td></tr>
                        <tr class="#"  style="background-color: #FFC107;"><td>Total Assets</td><td>KSH85,000</td></tr>

                        <tr>
                            <th style="text-align: left; color: #FFC107;">Liabilities</th>
                            <th style="text-align: right; color: #FFC107;">Amount (KSH)</th>
                        </tr>
                        <tr class="category"><td>Current Liabilities</td><td></td></tr>
                        <tr><td>Accounts Payable</td><td>KSH1,200</td></tr>
                        <tr><td>Maintenance Costs Owed</td><td>KSH1,500</td></tr>
                        <tr><td>Pending Refunds</td><td>KSH300</td></tr>
                        <tr><td>Salaries Payable</td><td>KSH1,000</td></tr>

                        <tr class="category"><td>Long-term Liabilities</td><td></td></tr>
                        <tr><td>Loans Payable</td><td>KSH20,000</td></tr>
                        <tr><td>Deferred Tax Liabilities</td><td>KSH5,000</td></tr>

                        <tr class="#"  style="background-color: #FFC107;"><td>Total Liabilities</td><td>KSH29,000</td></tr>
                        </table> -->
            <!-- <hr> -->
<!-- </div>
</div>
</div>
</div>
</div> -->

<!-- <hr> -->
             <!-- /.col -->
        <div class="col-md-12">
            <div class="card card-success">
              <div class="card-header" style="background-color: #00192D;">
                <h3 class="card-title"  style="color: #FFC107;">Add Tenant Invoice</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                  </button>
                </div>
                 <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <!-- <div class="row g-3"> -->
                  <!-- <div class="row g-3"> -->
                  <!-- <div class="form-section col-md-6"> -->
                <div class="card-body text-center">
                    <div class="form-section">
                      <b><h2 style="text-align: left; font-weight: 600;">Invoice Details</h2></b>
                      <form method="POST" action="submit_invoice.php">
  <div class="form-row">
    <input type="text" value="<?= $invoiceNumber ?>" disabled>
    <input type="hidden" name="invoice_number" value="<?= $invoiceNumber ?>"> <!-- for actual submission -->
    <input type="date" name="invoice_date" placeholder="Invoice Date" required>
  </div>

  <div class="row g-3">
    <div class="form-section col-md-6">
      <b><h2 style="text-align: left;font-weight: 600;">Building</h2></b>
      <select id="buildingSelect" class="form-control">
            <option value="" selected>Select Property</option>
            <?php
            require_once '../db/connect.php';
            try {
                $stmt = $pdo->query("SELECT building_id, building_name FROM buildings ORDER BY building_name");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="'.htmlspecialchars($row['building_id']).'">'
                        .htmlspecialchars($row['building_name'])
                        .'</option>';
                }
            } catch (PDOException $e) {
                echo '<option value="">Error loading buildings</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-section col-md-6">
        <b><h2 style="text-align: left;font-weight: 600;">Tenant Information</h2></b>
        <select id="tenantSelect" class="form-control" disabled>
            <option value="" selected>Select a building first</option>
        </select>
    </div>
</div>


<div id="tenantDetails" class="mt-3" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tenant Details</h5>
                    <p><strong>Unit:</strong> <span id="tenantUnit"></span></p>
                    <p><strong>Rent Amount:</strong> <span id="tenantRent"></span></p>
                    <p><strong>Phone:</strong> <span id="tenantPhone"></span></p>
                    <p><strong>ID No:</strong> <span id="tenantIdNo"></span></p>
                </div>

  <!-- Item Table -->
  <div class="form-section">
    <hr>
    <table class="items-table">
      <thead>
        <tr>
          <th>Item (Service)</th>
          <th>Description</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Taxes</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <select name="account_item[]" required>
              <option value="" disabled selected>Select Account Item</option>
              <?php foreach ($accountItems as $item): ?>
                <option value="<?= htmlspecialchars($item['account_code']) ?>">
                  <?= htmlspecialchars($item['account_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><textarea name="description[]" placeholder="Description" rows="1" required></textarea></td>
          <td><input type="number" name="quantity[]" class="form-control quantity" placeholder="1" required></td>
          <td><input type="number" name="unit_price[]" class="form-control unit-price" placeholder="123" required></td>
          <td>
            <select name="taxes[]" class="form-select vat-option" required>
              <option value="" disabled selected>Select Option</option>
              <option value="inclusive">VAT 16% Inclusive</option>
              <option value="exclusive">VAT 16% Exclusive</option>
              <option value="zero">Zero Rated</option>
              <option value="exempted">Exempted</option>
            </select>
          </td>
          <td>
            <input type="number" name="total[]" class="form-control total" placeholder="0" readonly>
            <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
              <i class="fa fa-trash" style="font-size: 12px;"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <button type="button" class="add-btn" onclick="addRow()">
      <i class="fa fa-plus"></i> ADD MORE
    </button>
  </div>

  <!-- Submit -->
  <div class="form-btns">
    <button type="submit">Submit</button>
  </div>
</form>


                </div>
            </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <hr>

            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12">

                    <!-- /.card-header -->
                    <div class="card-body">
                      <!--begin::Row-->
                      <div class="table-responsive">
                          <!-- Table -->
                          <table id="invoice" class="table table-striped" style="width: 100%; padding:10px; height: fit-content;">
                              <thead>
                                  <tr>
                                      <th>Invoice Number</th>
                                      <th>Property Name</th>
                                      <th>Tenant</th>
                                      <th>Payment Status</th>
                                      <th>Invoice Date</th>
                                      <th>Due Date</th>
                                      <th>Sub-Totals</th>
                                      <th>Taxes</th>
                                      <th>Totals</th>
                                      <th>ACTIONS</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <td>INV NO 00001100</td>
                                      <td>Huduma Apartments</td>
                                      <td>John Doe</td>
                                      <td><button style="background-color: #00192D; color: #FFC107; border-radius: 10px;">
                                          !PENDING</button></td>
                                      <td>10-11-2025</td>
                                      <td>11-12-2025</td>
                                      <td>Ksh 15,000</td>
                                      <td>Ksh 2100</td>
                                      <td>Ksh 17,100</td>
                                      <td>
                                      </button>
                                      <a href="../financials/viewinvoices.html"> <button class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="Get Full Report about this Repair Work"><i class="fa fa-file"></i></button></a>
                                      <button   class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Assign this Task to a Plumbing Service Providersingle_units.php">    <i class="fa fa-trash" style="font-size: 12px; color: red;"></i>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>INV NO 00001101</td>
                                      <td>Sunrise Apartments</td>
                                      <td>Jane Smith</td>
                                      <td><button style="background-color: green; color: white; border-radius: 10px;"><span>&#10004;</span> <!-- ✔ -->
                                          PAID</button></td>
                                      <td>15-10-2025</td>
                                      <td>15-11-2025</td>
                                      <td>Ksh 12,500</td>
                                      <td>Ksh 1800</td>
                                      <td>Ksh 14,300</td>
                                      <td>
                                          </button>
                                          <a href="../financials/viewinvoices.html"> <button class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="Get Full Report about this Repair Work"><i class="fa fa-file"></i></button></a>
                                          <button   class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Assign this Task to a Plumbing Service Providersingle_units.php">    <i class="fa fa-trash" style="font-size: 12px; color: red;"></i>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>

            <script>
                 // Function to view the invoice in the modal
    function viewInvoice() {
      document.getElementById('viewInvoiceNumber').textContent = document.querySelector('[name="invoice_number"]').value;
      document.getElementById('viewInvoiceDate').textContent = document.querySelector('[name="invoice_date"]').value;
      document.getElementById('viewCustomerName').textContent = document.querySelector('[name="tenant_name"]').value;
      document.getElementById('viewCustomerAddress').textContent = document.querySelector('[name="customer_address"]').value;
      document.getElementById('viewCustomerEmail').textContent = document.querySelector('[name="customer_email"]').value;
      document.getElementById('viewPaymentMethod').textContent = document.querySelector('[name="payment_method"]').value;
      document.getElementById('viewShippingOption').textContent = document.querySelector('[name="shipping_option"]').value;
    }
                // Function to delete a row
                function deleteRow(button) {
                  // Find the row to delete
                  var row = button.parentElement.parentElement;
                  row.remove();
                  updateTotalAmount();
                }

                // Function to update the total amount of an item when quantity or price is changed
                function updateTotal(input) {
                  var row = input.parentElement.parentElement;
                  var quantity = row.querySelector('[name="item_quantity[]"]').value;
                  var price = row.querySelector('[name="item_price[]"]').value;
                  var totalCell = row.querySelector('[name="item_total[]"]');
                  totalCell.value = (quantity * price).toFixed(2);

                  updateTotalAmount();
                }

                // Function to calculate the total invoice amount
                function updateTotalAmount() {
                  var totalAmount = 0;
                  var rows = document.querySelectorAll('.items-table tbody tr');
                  rows.forEach(function(row) {
                    var total = parseFloat(row.querySelector('[name="item_total[]"]').value) || 0;
                    totalAmount += total;
                  });
                  document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
                }
              </script>

    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="../../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>

<script>
  $(document).ready(function() {
    // Fetch buildings when page loads
    $.ajax({
        url: 'fetch_building_rent.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var $dropdown = $('#buildingSelect');
            $dropdown.empty().append('<option value="" disabled selected>Select Property</option>');

            if (response && response.length > 0) {
                $.each(response, function(index, building) {
                    $dropdown.append(
                        $('<option>', {
                            value: building.building_name,
                            text: building.building_name,
                            'data-id': building.building_id
                        })
                    );
                });
            } else {
                $dropdown.append('<option value="" disabled>No buildings found</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching buildings:", error);
            $('#buildingSelect').append('<option value="" disabled>Error loading buildings</option>');
        }
    });
});
</script>

<!-- Required Scripts -->
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

<script>
  document.addEventListener("DOMContentLoaded", function () {
    function formatNumber(num) {
      return num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function calculateRow(row) {
      const unitInput = row.querySelector(".unit-price");
      const quantityInput = row.querySelector(".quantity");
      const vatSelect = row.querySelector(".vat-option");
      const totalInput = row.querySelector(".total");

      const unitPrice = parseFloat(unitInput?.value) || 0;
      const quantity = parseFloat(quantityInput?.value) || 0;
      let subtotal = unitPrice * quantity;

      let vatAmount = 0;
      let total = subtotal;
      const vatType = vatSelect?.value;

      if (vatType === "inclusive") {
        subtotal = subtotal / 1.16;
        vatAmount = total - subtotal;
      } else if (vatType === "exclusive") {
        vatAmount = subtotal * 0.16;
        total += vatAmount;
      } else if (vatType === "zero") {
        vatAmount = 0; // VAT 0% for Zero Rated
        total = subtotal; // No tax added for Zero Rated
      } else if (vatType === "exempted") {
        vatAmount = 0; // No VAT for Exempted
        total = subtotal; // No tax added for Exempted
      }

      totalInput.value = formatNumber(total);
      return { subtotal, vatAmount, total, vatType };
    }

    function updateTotalAmount() {
      let subtotalSum = 0, taxSum = 0, grandTotal = 0, exemptedSum = 0, zeroVatSum = 0;
      let vat16Used = false, vat0Used = false, exemptedUsed = false;

      document.querySelectorAll(".items-table tbody tr").forEach(row => {
        if (row.querySelector(".unit-price")) {
          const { subtotal, vatAmount, total, vatType } = calculateRow(row);
          subtotalSum += subtotal;
          taxSum += vatAmount;
          grandTotal += total;

          if (vatType === "inclusive" || vatType === "exclusive") {
            vat16Used = true;
          } else if (vatType === "zero") {
            zeroVatSum += 0; // Zero Rated has zero VAT
            vat0Used = true;
          } else if (vatType === "exempted") {
            exemptedSum += 0; // Exempted has zero VAT
            exemptedUsed = true;
          }
        }
      });

      createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, zeroVatSum, exemptedSum, vat16Used, vat0Used, exemptedUsed });
    }

    function createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, zeroVatSum, exemptedSum, vat16Used, vat0Used, exemptedUsed }) {
      let summaryTable = document.querySelector(".summary-table");

      if (!summaryTable) {
        summaryTable = document.createElement("table");
        summaryTable.className = "summary-table table table-bordered";
        summaryTable.style = "width: 20%; float: right; font-size: 0.8rem; margin-top: 10px;";
        summaryTable.innerHTML = `<tbody></tbody>`;
        document.querySelector(".items-table").after(summaryTable);
      }

      const tbody = summaryTable.querySelector("tbody");
      tbody.innerHTML = `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">Sub-total</th>
          <td><input type="text" class="form-control" value="${formatNumber(subtotalSum)}" readonly style="padding: 5px;"></td>
        </tr>
        ${vat16Used ? `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">VAT 16%</th>
          <td><input type="text" class="form-control" value="${formatNumber(taxSum)}" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        ${vat0Used ? `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">VAT 0%</th>
          <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        ${exemptedUsed ? `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">Exempted</th>
          <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">Total</th>
          <td><input type="text" class="form-control" value="${formatNumber(grandTotal)}" readonly style="padding: 5px;"></td>
        </tr>
      `;
    }

    function attachEvents(row) {
      const inputs = [".unit-price", ".quantity", ".vat-option"];
      inputs.forEach(sel => {
        const el = row.querySelector(sel);
        if (el) {
          el.addEventListener("input", updateTotalAmount);
          el.addEventListener("change", updateTotalAmount);
        }
      });
    }

    window.addRow = function () {
      const table = document.querySelector(".items-table tbody");
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>
          <select name="payment_method" required>
            <option value="" disabled selected>Select Option</option>
            <option value="credit_card">Rent</option>
            <option value="paypal">Water Bill</option>
            <option value="bank_transfer">Garbage</option>
          </select>
        </td>
        <td><textarea name="Description" placeholder="Description" rows="1" required></textarea></td>
        <td><input type="number" class="form-control quantity" placeholder="1"></td>
        <td><input type="number" class="form-control unit-price" placeholder="123"></td>
        <td>
          <select class="form-select vat-option">
            <option value="" disabled selected>Select Option</option>
            <option value="inclusive">VAT 16% Inclusive</option>
            <option value="exclusive">VAT 16% Exclusive</option>
            <option value="zero">Zero Rated</option>
            <option value="exempted">Exempted</option>
          </select>
        </td>
        <td>
          <input type="text" class="form-control total" placeholder="0" readonly>
          <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
            <i class="fa fa-trash" style="font-size: 12px;"></i>
          </button>
        </td>
      `;
      table.appendChild(newRow);
      attachEvents(newRow);
    };

    window.deleteRow = function (btn) {
      btn.closest("tr").remove();
      updateTotalAmount();
    };

    document.querySelectorAll(".items-table tbody tr").forEach(attachEvents);
    updateTotalAmount();
  });
</script>

<!-- <script>
  $(document).ready(function() {
    // Load buildings on page load
    $.ajax({
        url: 'fetch_building_rent.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var $dropdown = $('#buildingSelect');
            $dropdown.empty().append('<option value="" disabled selected>Select Property</option>');

            if (response && response.length > 0) {
                $.each(response, function(index, building) {
                    $dropdown.append(
                        $('<option>', {
                            value: building.id,
                            text: building.name,
                            'data-rent': building.default_rent
                        })
                    );
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching buildings:", error);
        }
    });

    // When building is selected
    $('#buildingSelect').change(function() {
        var buildingId = $(this).val();
        if (buildingId) {
            fetchTenantsByBuilding(buildingId);
            // Set default rent for building
            var defaultRent = $(this).find(':selected').data('rent');
            if (defaultRent) {
                $('input[name="unit_price[]"]').first().val(defaultRent);
            }
        } else {
            $('#tenantSelect').empty().append('<option value="" disabled selected>Select Tenant</option>');
            resetRentAmount();
        }
    });

    // When tenant is selected
    $('#tenantSelect').change(function() {
        var rentAmount = $(this).find(':selected').data('rent');
        if (rentAmount) {
            updateRentAmount(rentAmount);
        } else {
            resetRentAmount();
        }
    });

    function fetchTenantsByBuilding(buildingId) {
        $.ajax({
            url: 'fetch_tenants_by_building.php',
            type: 'POST',
            dataType: 'json',
            data: { building_id: buildingId },
            success: function(response) {
                var $dropdown = $('#tenantSelect');
                $dropdown.empty().append('<option value="" disabled selected>Select Tenant</option>');

                if (response && response.length > 0) {
                    $.each(response, function(index, tenant) {
                        $dropdown.append(
                            $('<option>', {
                                value: tenant.tenant_id,
                                text: tenant.full_name + ' - ' + tenant.unit_number,
                                'data-unit': tenant.unit_id,
                                'data-rent': tenant.rent_amount
                            })
                        );
                    });
                } else {
                    $dropdown.append('<option value="" disabled>No active tenants in this building</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching tenants:", error);
                $('#tenantSelect').append('<option value="" disabled>Error loading tenants</option>');
            }
        });
    }

    function updateRentAmount(amount) {
        $('input[name="unit_price[]"]').first().val(amount);
        calculateRowTotal($('input[name="quantity[]"]').first());
    }

    function resetRentAmount() {
        $('input[name="unit_price[]"]').first().val('');
        $('input[name="total[]"]').first().val('');
    }

    // Calculate row total when quantity or price changes
    $(document).on('input', '.quantity, .unit-price', function() {
        calculateRowTotal($(this));
    });

    // Calculate row total when tax option changes
    $(document).on('change', '.vat-option', function() {
        calculateRowTotal($(this));
    });

    function calculateRowTotal(element) {
        var row = element.closest('tr');
        var quantity = parseFloat(row.find('.quantity').val()) || 0;
        var unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        var taxOption = row.find('.vat-option').val();
        var subtotal = quantity * unitPrice;
        var total = subtotal;

        // Apply tax calculations
        if (taxOption === 'exclusive') {
            total = subtotal * 1.16; // Add 16% VAT
        } else if (taxOption === 'inclusive') {
            // Price already includes VAT
            total = subtotal;
        }
        // Zero rated and exempted remain as subtotal

        row.find('.total').val(total.toFixed(2));
    }

    // Add new row function
    window.addRow = function() {
        var newRow = $('.items-table tbody tr').first().clone();
        newRow.find('input').val('');
        newRow.find('textarea').val('');
        newRow.find('select').prop('selectedIndex', 0);
        $('.items-table tbody').append(newRow);
    };

    // Delete row function
    window.deleteRow = function(btn) {
        if ($('.items-table tbody tr').length > 1) {
            $(btn).closest('tr').remove();
        } else {
            alert("You need at least one item row.");
        }
    };
});
</script> -->


    <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>
    <script>
      // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
      // IT'S ALL JUST JUNK FOR DEMO
      // ++++++++++++++++++++++++++++++++++++++++++

      /* apexcharts
       * -------
       * Here we will create a few charts using apexcharts
       */

      //-----------------------
      // - MONTHLY SALES CHART -
      //-----------------------

      const sales_chart_options = {
        series: [
          {
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 180,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#sales-chart'),
        sales_chart_options,
      );
      sales_chart.render();

      //---------------------------
      // - END MONTHLY SALES CHART -
      //---------------------------

      function createSparklineChart(selector, data) {
        const options = {
          series: [{ data }],
          chart: {
            type: 'line',
            width: 150,
            height: 30,
            sparkline: {
              enabled: true,
            },
          },
          colors: ['var(--bs-primary)'],
          stroke: {
            width: 2,
          },
          tooltip: {
            fixed: {
              enabled: false,
            },
            x: {
              show: false,
            },
            y: {
              title: {
                formatter: function (seriesName) {
                  return '';
                },
              },
            },
            marker: {
              show: false,
            },
          },
        };

        const chart = new ApexCharts(document.querySelector(selector), options);
        chart.render();
      }

      const table_sparkline_1_data = [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54];
      const table_sparkline_2_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 44];
      const table_sparkline_3_data = [15, 46, 21, 59, 33, 15, 34, 42, 56, 19, 64];
      const table_sparkline_4_data = [30, 56, 31, 69, 43, 35, 24, 32, 46, 29, 64];
      const table_sparkline_5_data = [20, 76, 51, 79, 53, 35, 54, 22, 36, 49, 64];
      const table_sparkline_6_data = [5, 36, 11, 69, 23, 15, 14, 42, 26, 19, 44];
      const table_sparkline_7_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 74];

      createSparklineChart('#table-sparkline-1', table_sparkline_1_data);
      createSparklineChart('#table-sparkline-2', table_sparkline_2_data);
      createSparklineChart('#table-sparkline-3', table_sparkline_3_data);
      createSparklineChart('#table-sparkline-4', table_sparkline_4_data);
      createSparklineChart('#table-sparkline-5', table_sparkline_5_data);
      createSparklineChart('#table-sparkline-6', table_sparkline_6_data);
      createSparklineChart('#table-sparkline-7', table_sparkline_7_data);

      //-------------
      // - PIE CHART -
      //-------------

      const pie_chart_options = {
        series: [700, 500, 400, 600, 300, 100],
        chart: {
          type: 'donut',
        },
        labels: ['Chrome', 'Edge', 'FireFox', 'Safari', 'Opera', 'IE'],
        dataLabels: {
          enabled: false,
        },
        colors: ['#0d6efd', '#20c997', '#ffc107', '#d63384', '#6f42c1', '#adb5bd'],
      };

      const pie_chart = new ApexCharts(document.querySelector('#pie-chart'), pie_chart_options);
      pie_chart.render();

      //-----------------
      // - END PIE CHART -
      //-----------------
    </script>

<script>
  document.getElementById("exportButton").addEventListener("click", function() {
    // Example data (can be your table data or an array of objects)
    const data = [
      { Name: "John", Age: 30, City: "New York" },
      { Name: "Jane", Age: 25, City: "London" },
      { Name: "Mark", Age: 35, City: "Paris" }
    ];

    // Convert data to a worksheet
    const ws = XLSX.utils.json_to_sheet(data);

    // Create a new workbook and append the worksheet
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

    // Export the workbook as an Excel file
    XLSX.writeFile(wb, "ExportedData.xlsx");
  });
</script>

<!-- <script> -->
<script>
document.getElementById('buildingSelect').addEventListener('change', function() {
    const buildingId = this.value;
    const tenantSelect = document.getElementById('tenantSelect');

    if (!buildingId) {
        tenantSelect.innerHTML = '<option value="" selected>Select a building first</option>';
        tenantSelect.disabled = true;
        return;
    }

    tenantSelect.disabled = false;
    tenantSelect.innerHTML = '<option value="">Loading tenants...</option>';

    fetch(`get_tenants.php?building_id=${buildingId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.error) throw new Error(data.error);

            if (data.length > 0) {
                let options = '<option value="" selected>Select Tenant</option>';
                data.forEach(tenant => {
                    // Exact field names from your database schema
                    const firstName = tenant.first_name || '';
                    const middleName = tenant.middle_name || '';

                    // Clean name formatting
                    const displayName = `${firstName} ${middleName}`.trim() || 'Unknown Tenant';
                    const unitInfo = tenant.unit_id ? ` - Unit ${tenant.unit_id}` : '';

                    options += `
                        <option value="${tenant.user_id}"
                                data-user-id="${tenant.user_id}"
                                data-unit-id="${tenant.unit_id || ''}">
                            ${displayName}${unitInfo}
                        </option>`;
                });
                tenantSelect.innerHTML = options;
            } else {
                tenantSelect.innerHTML = '<option value="" selected>No active tenants found</option>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tenantSelect.innerHTML = `
                <option value="" selected>
                    Error: ${error.message.substring(0, 30)}${error.message.length > 30 ? '...' : ''}
                </option>`;
            tenantSelect.disabled = true;
        });
});
</script>

<!-- </script> -->

<!-- <script>
  // Function to fetch buildings with tenants
async function fetchBuildingsWithTenants() {
  try {
    const response = await fetch('fetch_data.php', {
      method: 'POST', // or 'GET' depending on your PHP script
      headers: {
        'Content-Type': 'application/json',
      },
      // body: JSON.stringify({ building_name: 'Pink House' }), // Only if you need to filter by building
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const buildings = await response.json();

    // Process the data
    displayBuildings(buildings);

    return buildings;
  } catch (error) {
    console.error('Error fetching buildings:', error);
    // Display error to user
    document.getElementById('error-message').textContent = 'Failed to load buildings. Please try again.';
    return [];
  }
}

// Function to display buildings and tenants
function displayBuildings(buildings) {
  const container = document.getElementById('buildings-container');
  container.innerHTML = ''; // Clear previous content

  buildings.forEach(building => {
    const buildingElement = document.createElement('div');
    buildingElement.className = 'building';

    const buildingHeader = document.createElement('h2');
    buildingHeader.textContent = building.building_name;
    buildingElement.appendChild(buildingHeader);

    if (building.tenants && building.tenants.length > 0) {
      const tenantsList = document.createElement('ul');
      building.tenants.forEach(tenant => {
        const tenantItem = document.createElement('li');
        tenantItem.innerHTML = `
          <strong>${tenant.full_name}</strong>
          <div>Unit: ${tenant.unit_id}</div>
          <div>Rent: ${tenant.rent_amount || 'Not specified'}</div>
          <div>Phone: ${tenant.phone_number}</div>
        `;
        tenantsList.appendChild(tenantItem);
      });
      buildingElement.appendChild(tenantsList);
    } else {
      const noTenants = document.createElement('p');
      noTenants.textContent = 'No active tenants in this building';
      buildingElement.appendChild(noTenants);
    }

    container.appendChild(buildingElement);
  });
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', fetchBuildingsWithTenants);
</script> -->

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.getElementById('buildingSelect');
    const tenantSelect = document.getElementById('tenantSelect');
    const tenantDetails = document.getElementById('tenantDetails');
    const tenantUnit = document.getElementById('tenantUnit');
    const tenantRent = document.getElementById('tenantRent');
    const tenantPhone = document.getElementById('tenantPhone');
    const tenantIdNo = document.getElementById('tenantIdNo');

    let allBuildingsData = []; // Store all buildings and tenants data

    // Fetch all buildings with their tenants
    fetch('fetch_data.php') // Replace with your actual endpoint
        .then(response => response.json())
        .then(data => {
            allBuildingsData = data;

            // Populate building dropdown
            data.forEach(building => {
                const option = document.createElement('option');
                option.value = building.building_name;
                option.textContent = building.building_name;
                buildingSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching buildings:', error);
            // alert('Failed to load buildings. Please try again.');
        });

    // Update tenants when building is selected
    buildingSelect.addEventListener('change', function() {
        const buildingName = this.value;

        if (!buildingName) {
            tenantSelect.disabled = true;
            tenantSelect.innerHTML = '<option value="" disabled selected>Select Tenant</option>';
            tenantDetails.style.display = 'none';
            return;
        }

        // Find the selected building in our data
        const selectedBuilding = allBuildingsData.find(b => b.building_name === buildingName);

        if (selectedBuilding && selectedBuilding.tenants) {
            // Populate tenant dropdown
            tenantSelect.innerHTML = '<option value="" disabled selected>Select Tenant</option>';
            selectedBuilding.tenants.forEach(tenant => {
                const option = document.createElement('option');
                option.value = tenant.user_id;
                option.textContent = tenant.full_name;
                option.dataset.unit = tenant.unit_id;
                option.dataset.rent = tenant.rent_amount;
                option.dataset.phone = tenant.phone_number;
                option.dataset.idNo = tenant.id_no;
                tenantSelect.appendChild(option);
            });

            tenantSelect.disabled = false;
            tenantDetails.style.display = 'none';
        } else {
            tenantSelect.innerHTML = '<option value="" disabled selected>No tenants found</option>';
            tenantSelect.disabled = false;
        }
    });

    // Show tenant details when tenant is selected
    tenantSelect.addEventListener('change', function() {
        if (!this.value) {
            tenantDetails.style.display = 'none';
            return;
        }

        const selectedOption = this.options[this.selectedIndex];
        tenantUnit.textContent = selectedOption.dataset.unit || 'N/A';
        tenantRent.textContent = selectedOption.dataset.rent ?
            'KSh ' + parseFloat(selectedOption.dataset.rent).toLocaleString() : 'N/A';
        tenantPhone.textContent = selectedOption.dataset.phone || 'N/A';
        tenantIdNo.textContent = selectedOption.dataset.idNo || 'N/A';

        tenantDetails.style.display = 'block';
    });
});
</script> -->


<!-- <script>
  $(document).ready(function() {
    // When building is selected
    $('select[name="building"]').change(function() {
        var buildingName = $(this).val();
        if (buildingName) {
            fetchTenantsByBuilding(buildingName);
            fetchRentAmount(buildingName);
        } else {
            $('select[name="tenant"]').empty().append('<option value="" disabled selected>Select Tenant</option>');
            $('input[name="unit_price[]"]').val('');
        }
    });

    // When tenant is selected
    $('select[name="tenant"]').change(function() {
        var tenantId = $(this).val();
        if (tenantId) {
            fetchTenantDetails(tenantId);
        }
    });

    function fetchTenantsByBuilding(buildingName) {
        $.ajax({
            url: 'fetch_tenants.php',
            type: 'POST',
            dataType: 'json',
            data: { building_name: buildingName },
            success: function(response) {
                populateTenantDropdown(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching tenants:", error);
                $('select[name="tenant"]').empty().append(
                    '<option value="" disabled selected>Error loading tenants</option>'
                );
            }
        });
    }

    function populateTenantDropdown(tenants) {
        var $dropdown = $('select[name="tenant"]');
        $dropdown.empty().append('<option value="" disabled selected>Select Tenant</option>');

        if (tenants && tenants.length > 0) {
            $.each(tenants, function(index, tenant) {
                $dropdown.append(
                    $('<option>', {
                        value: tenant.tenant_id,
                        text: tenant.full_name,
                        'data-unit': tenant.unit_id,
                        'data-rent': tenant.rent_amount
                    })
                );
            });
        } else {
            $dropdown.append('<option value="" disabled selected>No tenants found</option>');
        }
    }

    function fetchTenantDetails(tenantId) {
        $.ajax({
            url: 'fetch_tenant_details.php',
            type: 'POST',
            dataType: 'json',
            data: { tenant_id: tenantId },
            success: function(response) {
                if (response && response.rent_amount) {
                    $('input[name="unit_price[]"]').first().val(response.rent_amount);
                    calculateRowTotal($('input[name="quantity[]"]').first());
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching tenant details:", error);
            }
        });
    }

    function fetchRentAmount(buildingName) {
        $.ajax({
            url: 'fetch_building_rent.php',
            type: 'POST',
            dataType: 'json',
            data: { building_name: buildingName },
            success: function(response) {
                if (response && response.default_rent) {
                    $('input[name="unit_price[]"]').first().val(response.default_rent);
                    calculateRowTotal($('input[name="quantity[]"]').first());
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching rent amount:", error);
            }
        });
    }

    // Calculate row total when quantity or price changes
    $(document).on('input', '.quantity, .unit-price', function() {
        calculateRowTotal($(this));
    });

    // Calculate row total when tax option changes
    $(document).on('change', '.vat-option', function() {
        calculateRowTotal($(this));
    });

    function calculateRowTotal(element) {
        var row = element.closest('tr');
        var quantity = parseFloat(row.find('.quantity').val()) || 0;
        var unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        var taxOption = row.find('.vat-option').val();
        var subtotal = quantity * unitPrice;
        var total = subtotal;

        // Apply tax calculations
        if (taxOption === 'exclusive') {
            total = subtotal * 1.16; // Add 16% VAT
        } else if (taxOption === 'inclusive') {
            // Price already includes VAT
            total = subtotal;
        }
        // Zero rated and exempted remain as subtotal

        row.find('.total').val(total.toFixed(2));
    }

    // Add new row function
    window.addRow = function() {
        var newRow = $('.items-table tbody tr').first().clone();
        newRow.find('input').val('');
        newRow.find('textarea').val('');
        newRow.find('select').prop('selectedIndex', 0);
        $('.items-table tbody').append(newRow);
    };

    // Delete row function
    window.deleteRow = function(btn) {
        if ($('.items-table tbody tr').length > 1) {
            $(btn).closest('tr').remove();
        } else {
            alert("You need at least one item row.");
        }
    };
});
</script> -->


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


<script>
$(document).ready(function() {
  $('#repaireExpenses').DataTable({
      "lengthChange": false,
      "dom": 'Bfrtip',
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  });
});
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
      const checkboxes = document.querySelectorAll("#columnFilter input[type='checkbox']");
      const menuButton = document.getElementById("menuButton");
      const columnFilter = document.getElementById("columnFilter");

      // Toggle menu visibility when clicking the three dots
      menuButton.addEventListener("click", function(event) {
          columnFilter.classList.toggle("hidden");
          columnFilter.style.display = columnFilter.classList.contains("hidden") ? "none" : "block";

          // Prevent closing immediately when clicking inside
          event.stopPropagation();
      });

      // Hide menu when clicking outside
      document.addEventListener("click", function(event) {
          if (!menuButton.contains(event.target) && !columnFilter.contains(event.target)) {
              columnFilter.classList.add("hidden");
              columnFilter.style.display = "none";
          }
      });

      // Column filtering logic
      checkboxes.forEach(checkbox => {
          checkbox.addEventListener("change", function() {
              let columnClass = `.col-${this.dataset.column}`;
              let elements = document.querySelectorAll(columnClass);

              elements.forEach(el => {
                  el.style.display = this.checked ? "" : "none";
              });
          });
      });
  });

      </script>
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
