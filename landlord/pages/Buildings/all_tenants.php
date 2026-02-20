<?php
session_start();
require_once '../../db/connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';
//  include_once 'includes/lower_right_popup_form.php';
?>
<!-- actions -->
<?php
require_once './actions/getAllTenants.php'
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
  <meta name="author" content="ColorlibHQ" />
  <meta
    name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta
    name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
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
  <link rel="stylesheet" href="../../assets/main.css" />
  <!-- <link rel="stylesheet" href="text.css" /> -->
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->

  <!-- jquery link-->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <!--Tailwind CSS  -->
  <style>
    .app-wrapper {
      background-color: rgba(128, 128, 128, 0.1);
    }

    .modal-backdrop.show {
      opacity: 0.4 !important;
      /* Adjust the value as needed */
    }

    .diagonal-paid-label {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
      /* Centered and rotated */
      background-color: rgba(0, 128, 0, 0.2);
      /* Light green with transparency */
      color: green;
      font-weight: bold;
      font-size: 24px;
      padding: 15px 40px;
      border: 2px solid green;
      border-radius: 8px;
      text-transform: uppercase;
      pointer-events: none;
      z-index: 10;
      white-space: nowrap;
    }

    .diagonal-unpaid-label {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
      /* Centered and rotated */
      background-color: rgba(255, 0, 0, 0.2);
      /* Red with transparency for "UNPAID" */
      color: #ff4d4d;
      /* Softer red text color */
      font-weight: bold;
      font-size: 24px;
      padding: 15px 40px;
      border: 2px solid red;
      border-radius: 8px;
      text-transform: uppercase;
      pointer-events: none;
      z-index: 10;
      white-space: nowrap;
    }

    .diagonal-partially-paid-label {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
      /* Centered and rotated */
      background-color: rgba(255, 165, 0, 0.2);
      /* Amber background with opacity */
      color: #ff9900;
      /* Amber or gold text */
      font-weight: bold;
      font-size: 24px;
      padding: 15px 40px;
      border: 2px solid #ff9900;
      /* Amber border */
      border-radius: 8px;
      text-transform: uppercase;
      pointer-events: none;
      z-index: 10;
      white-space: nowrap;
    }

    /* Sliding chat panel */
    .chat-panel {
      position: fixed;
      top: 0;
      right: -400px;
      width: 400px;
      height: 100%;
      background: white;
      border-left: 2px solid #ccc;
      box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
      transition: right 0.3s ease;
      z-index: 9999;
      display: flex;
      flex-direction: column;
    }

    .chat-panel.open {
      right: 0;
    }

    .chat-header {
      background: #00192D;
      color: #fff;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 18px;
    }

    .chat-body {
      padding: 15px;
      flex: 1;
      overflow-y: auto;
      background: #f8f9fa;
    }

    .chat-footer {
      padding: 15px;
      border-top: 1px solid #ddd;
      background: #fff;
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
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb" style="">
            <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/dashboard.php" style="text-decoration: none;">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Buildings/buildings.php" style="text-decoration: none;">Buildings</a></li>
            <li class="breadcrumb-item active">Tenants</li>
          </ol>
        </nav>
        <!--First Row-->
        <div class="row align-items-center mb-3">
          <div class="col-12 d-flex align-items-center">
            <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
            <h3 class="mb-0 ms-3">Tenants</h3>
          </div>
        </div>

        <!-- Third Row: stats -->
        <div class="row g-3">

          <!-- Total Tenants -->
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
              <div>
                <i class="bi bi-people-fill fs-1 me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">Total Tenants</p>
                <b><?= $tenantCount ?></b>
              </div>
            </div>
          </div>

          <!-- Single Units -->
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
              <div>
                <i class="bi bi-house-door-fill fs-1 me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">In Single Units</p>
                <b><?= $singleUnitTenants ?></b>
              </div>
            </div>
          </div>

          <!-- Bed Sitters -->
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
              <div>
                <i class="bi bi-door-closed-fill fs-1 me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">In Bed Sitters</p>
                <b><?= $bedSitterTenants ?></b>
              </div>
            </div>
          </div>

          <!-- Multi Rooms -->
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
              <div>
                <i class="bi bi-building-fill fs-1 me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">In Multi Rooms</p>
                <b><?= $multiRoomTenants ?></b>
              </div>
            </div>
          </div>

        </div>


        <div class="row mb-3 mt-3">
          <div class="col-md-12">
            <div class="card border-0 mb-4">
              <div class="card-body">
                <h6 class="mb-3" style="color: var(--main-color); font-weight: 600;">
                  Tenants per unit category
                </h6>

                <button type="button" class="action-link allUnits-link" style="text-decoration: none;">
                  <i class="fas fa-th"></i> All Tenants
                </button>

                <button type="button" class="action-link" style="text-decoration: none;">
                  <i class="fas fa-door-open"></i> Single Units
                </button>

                <button type="button" class="action-link" style="text-decoration: none;">
                  <i class="fas fa-bed"></i> Bedsitter Units
                </button>

                <button type="button" class="action-link" style="text-decoration: none;">
                  <i class="fas fa-door-closed"></i> Multi-Room Units
                </button>
              </div>
            </div>
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
                        <label class="form-label text-muted small">Search</label>
                        <input
                          type="text"
                          name="search"
                          class="form-control"
                          placeholder="Supplier or expense no..."
                          value="<?= htmlspecialchars($search ?? '') ?>">
                      </div>

                      <div class="col-auto filter-col">
                        <label class="form-label text-muted small">Buildings</label>
                        <select class="form-select shadow-sm" name="building_id">
                          <option value="">All Buildings</option>

                        </select>
                      </div>

                      <div class="col-auto filter-col">
                        <label class="form-label text-muted small">Status</label>
                        <select name="status" class="form-select">
                          <option value="" <?= ($status ?? '') === '' ? 'selected' : '' ?>>All Statuses</option>

                          <!-- Use values that match your DB exactly -->
                          <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                          <option value="unpaid" <?= ($status ?? '') === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                          <option value="overpaid" <?= ($status ?? '') === 'overpaid' ? 'selected' : '' ?>>Overpaid</option>
                          <option value="partially paid" <?= ($status ?? '') === 'partially paid' ? 'selected' : '' ?>>Partially Paid</option>
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

                    </div>
                  </div>

                  <div class="d-flex gap-2 justify-content-end">
                    <!-- Replace with your real page name -->
                    <a href="expenses.php" class="btn btn-secondary">
                      <i class="fas fa-redo"></i> Reset
                    </a>

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
          <div class="col-12">
            <div class="card border-0 ">
              <div class="card-header" style="background-color: #00192D; color:#fff;">
                <b>All tenants (<span class="text-warning"><?= $tenantCount ?></span>)</b>
              </div>
              <div class="card-body">
                <!-- Display Results in HTML -->
                <div class="table-responsive mt-3">
                  <table class="table table-striped" id="dataTable">
                    <thead style="background-color:#00192D; color:#fff;">
                      <tr>
                        <th>Name</th>
                        <th>Building</th>
                        <th>Contacts</th>
                        <th>Identification</th>
                        <th>Move In</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php include_once 'processes/encrypt_decrypt_function.php'; ?>
                      <?php if (count($allTenants) > 0): ?>
                        <?php $i = 1;
                        foreach ($allTenants as $tenant): ?>
                          <tr>
                            <td><?= htmlspecialchars($tenant['first_name']) . ' ' . htmlspecialchars($tenant['middle_name']);; ?></td>
                            <td><?= htmlspecialchars($tenant['building_name']); ?> (<span class="text-success"><?= htmlspecialchars($tenant['unit_number']); ?></span>)</td>
                            <td>
                              <?= htmlspecialchars($tenant['phone']); ?>
                            </td>
                            <td>
                              <?= htmlspecialchars($tenant['national_id']); ?>
                            </td>
                            <td><?= htmlspecialchars($tenant['move_in_date']); ?></td>

                            <td>
                              <?php if ($tenant['status'] == 'Active'): ?>
                                <button class="btn btn-xs shadow" style="background-color:#24953E; color:#fff"><i class="bi bi-person-check"></i> <?= $tenant['status']; ?></button>
                              <?php else: ?>
                                <button class="btn btn-xs shadow" style="background-color: #cc0001; color:#fff"><i class="bi bi-person-exclamation"></i> <?= $tenant['status']; ?></button>
                              <?php endif; ?>
                            </td>
                            <td>
                              <div class="btn-group shadow">
                                <button type="button" class="btn btn-default btn-xs" style="border:1px solid rgb(0, 25, 45 ,.3);">Action</button>
                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-xs" data-toggle="dropdown" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu shadow" role="menu" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                  <?php
                                  if ($tenant['status'] == 'Active') {
                                  ?>
                                    <a class="dropdown-item" href="<?= 'https://wa.me/' . $tenant['phone'] . '/?text=Hello,' . " " . $tenant['first_name'] . '' ?>" target="_blank"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                                    <a class="dropdown-item open-chat"
                                      data-tenant="<?= $tenant['first_name']; ?>"
                                      href="#">
                                      <i class="bi bi-envelope"></i> Message
                                    </a>
                                    <a class="dropdown-item" href="tenant_profile.php?profile=<?= encryptor('encrypt', $tenant['id']); ?>"><i class="fas fa-newspaper"></i> Profile</a>
                                    <a class="dropdown-item" href="\Jengopay\landlord\pages\Buildings/ten_invoice.php?invoice=<?= encryptor('encrypt', $tenant['id']); ?>&tenant_type=single_unit">
                                      <i class="bi bi-newspaper"></i> Invoice
                                    </a> <a class="dropdown-item" href="all_individual_tenant_invoices.php?invoice=<?= encryptor('encrypt', $tenant['id']); ?>"><i class="bi bi-receipt"></i> All Invoices</a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#vacateTenantModal<?= htmlspecialchars($tenant['id']); ?>" href="#"><i class="bi bi-arrow-right"></i> Vacate</a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#shiftTenantModal<?= htmlspecialchars($tenant['id']); ?>" href="#"><i class="fa fa-refresh"></i> Shift</a>
                                    <a class="dropdown-item" href="email_tenant.php?email=<?= encryptor('encrypt', $tenant['id']); ?>"><i class="bi bi-envelope-open"></i> Email</a>
                                    <a class="dropdown-item" href="tenant_payment.php?payment=<?= encryptor('encrypt', $tenant['id']); ?>"><i class="fas fa-money"></i> Payment</a>
                                    <!--<div class="dropdown-divider"></div>-->
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addInfoModal<?= htmlspecialchars($tenant['id']); ?>"><i class="bi bi-plus-square"></i> Add Info</a>
                                  <?php
                                  } else {
                                  ?>
                                    <a class="dropdown-item" href="tenant_profile.php?profile=<?= encryptor('encrypt', $row['id']); ?>"><i class="fas fa-newspaper"></i> Profile</a>
                                    <a class="dropdown-item" href="all_individual_tenant_invoices.php?invoice=<?= encryptor('encrypt', $row['id']); ?>"><i class="bi bi-receipt"></i> All Invoices</a>
                                  <?php
                                  }
                                  ?>
                                </div>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="10" class="text-center text-muted">No tenant records found</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
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
    <!-- end footer -->
  </div>
  <!--end::App Wrapper-->

  <!-- plugin for pdf -->


  <!-- RIGHT SIDE CHAT SLIDE PANEL -->
  <div id="chatPanel" class="chat-panel">
    <div class="chat-header">
      <strong id="chatTenantName"></strong>
      <button class="close-chat btn btn-sm btn-danger">X</button>
    </div>

    <div class="chat-body" id="chatBody">
      <!-- Messages will load here -->
      <p class="text-muted"></p>
    </div>

    <div class="chat-footer">
      <textarea id="chatMessage" class="form-control" placeholder="Type message..."></textarea>
      <button id="sendChatBtn" class="btn btn-primary mt-2">Send</button>
    </div>
  </div>


  <script>
    // ==================== Deposits ====================
    function addRow() {
      const tbody = document.querySelector("#paymentTable tbody");
      const row = document.createElement("tr");
      row.innerHTML = `
    <td><input type="text" class="form-control depositFor" name="deposit_for[]"></td>
    <td><input type="number" class="form-control requiredPay" name="required_pay[]" value="0" min="0"></td>
    <td><input type="number" class="form-control amountPaid" name="amount_paid[]" value="0" min="0"></td>
    <td class="balance"><input type="hidden" name="balance">0</td>
    <td class="subTotal"><input type="hidden" name="subtotal"0</td>
    <td><button type="button" class="btn btn-sm removeRow" style="background-color:#cc0001; color:#fff;"><i class="bi bi-trash"></i> Remove</button></td>
  `;
      tbody.appendChild(row);

      // Input listeners
      row.querySelectorAll(".requiredPay, .amountPaid").forEach(input => {
        input.addEventListener("input", () => {
          if (input.value < 0) input.value = 0;
          updateTableTotals();
        });
      });

      row.querySelector(".removeRow").addEventListener("click", () => {
        row.remove();
        updateTableTotals();
      });
      updateTableTotals();
    }

    function updateTableTotals() {
      let totalRequired = 0,
        totalPaid = 0,
        totalBalance = 0,
        totalSub = 0;
      document.querySelectorAll("#paymentTable tbody tr").forEach(row => {
        const required = parseFloat(row.querySelector(".requiredPay").value) || 0;
        const paid = parseFloat(row.querySelector(".amountPaid").value) || 0;
        const balance = Math.max(required - paid, 0);
        const sub = paid;

        row.querySelector(".balance").textContent = balance;
        row.querySelector(".subTotal").textContent = sub;

        totalRequired += required;
        totalPaid += paid;
        totalBalance += balance;
        totalSub += sub;
      });

      document.getElementById("totalRequired").textContent = totalRequired;
      document.getElementById("totalPaid").textContent = totalPaid;
      document.getElementById("totalBalance").textContent = totalBalance;
      document.getElementById("totalSub").textContent = totalSub;
    }

    // ==================== Popups ====================
    document.querySelectorAll("input[name='idMode']").forEach(radio => {
      radio.addEventListener("change", function() {
        document.getElementById("nationalIdSection").style.display = this.value === "national" ? "block" : "none";
        document.getElementById("passportPopup").style.display = this.value === "passport" ? "block" : "none";
      });
    });

    document.querySelectorAll("input[name='income']").forEach(radio => {
      radio.addEventListener("change", function() {
        document.getElementById("formalPopup").style.display = this.value === "formal" ? "block" : "none";
        document.getElementById("casualPopup").style.display = this.value === "casual" ? "block" : "none";
        document.getElementById("businessPopup").style.display = this.value === "business" ? "block" : "none";
      });
    });

    function closePopup() {
      document.querySelectorAll(".popup").forEach(p => p.style.display = "none");
    }

    function closeId() {
      const idInput = document.getElementById('nationalId');
      if (!idInput.checkValidity()) {
        document.getElementById('nationalIdError').textContent = "Please enter a valid ID number.";
        return;
      }
      document.getElementById('nationalIdSection').style.display = 'none';
    }

    function closePassport() {
      const passInput = document.getElementById('passportNumber');
      if (!passInput.checkValidity()) {
        document.getElementById('passportError').textContent = "Please enter a valid Passport number.";
        return;
      }
      document.getElementById('passportPopup').style.display = 'none';
    }

    // ==================== Leasing Dates ====================
    document.getElementById("leasingPeriod").addEventListener("input", calculateEndDate);
    document.getElementById("leasingStart").addEventListener("change", calculateEndDate);
    document.getElementById("moveIn").addEventListener("change", calculateEndDate);

    function calculateEndDate() {
      const months = parseInt(document.getElementById("leasingPeriod").value) || 0;
      const startDate = new Date(document.getElementById("leasingStart").value);
      const moveInDate = new Date(document.getElementById("moveIn").value);

      if (months > 0 && !isNaN(startDate)) {
        const endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + months);
        const iso = endDate.toISOString().split("T")[0];
        document.getElementById("leasingEnd").value = iso;
        document.getElementById("moveOut").value = iso;
      }

      // Sync move-out with move-in + months if move-in is set
      if (months > 0 && !isNaN(moveInDate)) {
        const moveOut = new Date(moveInDate);
        moveOut.setMonth(moveOut.getMonth() + months);
        document.getElementById("moveOut").value = moveOut.toISOString().split("T")[0];
      }
    }

    // ==================== Init ====================
    document.addEventListener("DOMContentLoaded", () => {
      addRow(); // Start with one row
    });
  </script>

  <script>
    function loadChatMessages(tenant) {
      fetch("/JengoPay/landlord/pages/communications/actions/load_conversation.php?tenant=" + tenant)
        .then(res => res.text())
        .then(data => {
          document.getElementById("chatBody").innerHTML = data;
        });
    }
  </script>

  <script>
    document.querySelectorAll('.open-chat').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();

        let tenant = this.dataset.tenant;

        // Set tenant name in header
        document.getElementById("chatTenantName").textContent = tenant;

        // Open slide panel
        document.getElementById("chatPanel").classList.add("open");

        // TODO: Load chat messages via AJAX
        // loadChatMessages(tenant);
      });
    });

    // Close button
    document.querySelector('.close-chat').addEventListener('click', function() {
      document.getElementById("chatPanel").classList.remove("open");
    });
  </script>


  <!-- Main Js File -->
  <script src="../../assets/main.js"></script>
  <script src="js/main.js"></script>

  <!-- html2pdf depends on html2canvas and jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script type="module" src="./js/main.js"></script>
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
  <!-- pdf download plugin -->


  <!-- Scripts -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!--end::Body-->

</html>