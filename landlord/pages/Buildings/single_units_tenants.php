<?php session_start() ?>
<?php
require_once "../db/connect.php";
//  include_once 'includes/lower_right_popup_form.php';
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
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row align-items-center mb-3">
              <div class="col-12 d-flex align-items-center">
                <!-- Small colored bar on the left -->
                <span style="width:5px; height:28px; background:#F5C518;" class="rounded"></span>

                <!-- Header text -->
                <h3 class="mb-0 ms-3">Single Unit Tenants</h3>
              </div>
            </div>

            <?php
            $single_unit_tenants = "SELECT tenant_status, COUNT(*) AS total FROM tenants GROUP BY tenant_status";
            $stmt = $pdo->prepare($single_unit_tenants);
            $stmt->execute();

            $counts = [
              'Active'  => 0,
              'Vacated' => 0
            ];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $counts[$row['tenant_status']] = $row['total'];
            }

            $icons = [
              'Active'  => 'bi-person-check',
              'Vacated' => 'bi-person-dash'
            ];
            ?>

            <div class="row g-3">
              <?php foreach ($counts as $type => $total): ?>
                <?php
                // Defaults
                $iconColor  = 'text-warning';
                $displayType = ucfirst($type);
                $iconClass  = $icons[$type] ?? 'bi-people';

                if ($type === 'Active') {
                  $iconColor   = 'text-success';
                  $displayType = 'Active Tenants';
                } elseif ($type === 'Vacated') {
                  $iconColor   = 'text-secondary';
                  $displayType = 'Vacated Tenants';
                }

                // Fixed gold color for left bar
                $barColor = '#F5C518';
                ?>

                <div class="col-lg-3 col-md-6 d-flex">
                  <div class="stat-card d-flex align-items-stretch rounded-2 p-3 w-100 shadow"
                    style="border:1px solid rgba(0,25,45,.2); background:#fff;">

                    <!-- Left colored bar -->
                    <span class="me-3 rounded"
                      style="width:5px; background:<?= $barColor ?>;"></span>

                    <div class="d-flex align-items-center">
                      <div class="me-3">
                        <i class="bi <?= $iconClass ?> fs-2 <?= $iconColor ?>"></i>
                      </div>
                      <div>
                        <p class="mb-0 fw-bold text-muted"><?= $displayType ?></p>
                        <h5 class="mb-0 fw-bold"><?= $total ?></h5>
                      </div>
                    </div>

                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <b>
              <hr>
            </b>

            <!-- <div class="row mb-3 mt-3">
                    <div class="col-md-6 d-flex">
                        <input
                            type="text"
                            class="form-control filter-shadow"
                            placeholder="Search requests..."
                            style="border-radius: 25px 0 0 25px;">

                         Search Button -->
            <!-- <button
                            class="btn text-white"
                            style="border-radius: 0 25px 25px 0; background: linear-gradient(135deg, #00192D, #002B5B)">
                            Search
                        </button> -->
          </div>

          <br>
          <div class="card shadow">
            <div class="card-header" style="background-color:#00192D; color:#fff;"><b>All Single Unit Tenants</b></div>
            <div class="card-body">
              <table class="table table-striped" id="dataTable">
                <thead>
                  <th>Name</th>
                  <th>Unit | Building</th>
                  <th>Contacts</th>
                  <th>Identification</th>
                  <th>Move In Date</th>
                  <th>Added On</th>
                  <th>Status</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                  include_once '../processes/encrypt_decrypt_function.php';
                  $select = $pdo->prepare("
                    SELECT 
                        tenants.*, 
                        building_units.unit_number AS unit_number, 
                        buildings.building_name
                    FROM tenants
                    INNER JOIN tenancies 
                        ON tenants.id = tenancies.tenant_id
                        AND tenancies.status = 'active'
                    INNER JOIN building_units 
                        ON tenancies.unit_id = building_units.id
                    INNER JOIN unit_categories 
                        ON building_units.unit_category_id = unit_categories.id
                    INNER JOIN buildings
                        ON building_units.building_id = buildings.id
                    WHERE unit_categories.category_name = :unit_category
                    ORDER BY tenants.tenant_reg DESC
                ");

                  $select->execute([
                    ':unit_category' => 'single_unit'
                  ]);

                  $row = 0;


                  while ($row = $select->fetch()) {
                    $id = encryptor('encrypt', $row['id']);
                    if ($row > 0) {
                      echo "<script>
                              Swal.fire({
                                icon: 'success',
                                title: 'Data Loaded',
                                text: 'Tenant information retrieved successfully.',
                                timer: 2000,
                                showConfirmButton: false
                              });
                            </script>";
                  ?>
                      <tr>
                        <td><?= $row['first_name'] . ' ' . $row['middle_name']; ?></td>
                        <td><?= $row['unit_number'] . ' (' . $row['building_name'] . ')'; ?></td>
                        <td><?= ""
                            // htmlspecialchars($row['unit_category']); 
                            ?>
                        </td>
                        <td>
                          <a href="tel:<?= $row['phone']; ?>"><?= $row['phone']; ?></a><br>
                          <a href="tel:<?= $row['alt_phone']; ?>"><?= $row['alt_phone']; ?></a>
                        </td>
                        <td><i class="bi bi-person-vcard"></i>
                          <?= ""
                          // $row['pass_no'] . '' . $row['id_no'] . ' (' . ucfirst($row['idMode']) . ')'; 
                          ?>
                        </td>
                        <td><?= $row['move_in_date']; ?></td>
                        <td><?= $row['tenant_reg']; ?></td>
                        <td>
                          <?php
                          if ($row['tenant_status'] == 'Active') {
                          ?>
                            <button class="btn btn-xs shadow" style="background-color:#24953E; color:#fff"><i class="bi bi-person-check"></i> <?= $row['tenant_status']; ?></button>
                          <?php
                          } else {
                          ?>
                            <button class="btn btn-xs shadow" style="background-color: #cc0001; color:#fff"><i class="bi bi-person-exclamation"></i> <?= $row['tenant_status']; ?></button>
                          <?php
                          }
                          ?>
                        </td>
                        <td>
                          <div class="btn-group shadow">
                            <button type="button" class="btn btn-default btn-xs" style="border:1px solid rgb(0, 25, 45 ,.3);">Action</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-xs" data-toggle="dropdown" style="border:1px solid rgb(0, 25, 45 ,.3);">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu shadow" role="menu" style="border:1px solid rgb(0, 25, 45 ,.3);">
                              <?php
                              if ($row['tenant_status'] == 'Active') {
                              ?>
                                <a class="dropdown-item" href="<?= 'https://wa.me/' . $row['main_contact'] . '/?text=Hello,' . " " . $row['first_name'] . '' ?>" target="_blank"><i class="fa fa-whatsapp"></i> WhatsApp</a>
                                <a class="dropdown-item" href="messaging.php?mesage=<?= $row['first_name']; ?>"><i class="fa fa-envelope"></i> Message</a>
                                <a class="dropdown-item" href="tenant_profile.php?profile=<?= encryptor('encrypt', $row['id']); ?>"><i class="fas fa-newspaper"></i> Profile</a>
                                <a class="dropdown-item" href="edit_tenant_info.php?edit_tenant=<?= encryptor('encrypt', $row['id']); ?>"><i class="fas fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="single_unit_tenant_invoice.php?invoice=<?= encryptor('encrypt', $row['id']); ?>"><i class="fa fa-newspaper"></i> Invoice</a>
                                <a class="dropdown-item" href="all_individual_tenant_invoices.php?invoice=<?= encryptor('encrypt', $row['id']); ?>"><i class="fa fa-table"></i> All Invoices</a>
                                <a class="dropdown-item" data-toggle="modal" data-target="#vacateTenantModal<?= htmlspecialchars($row['id']); ?>" href="#"><i class="fa fa-arrow-right"></i> Vacate</a>
                                <a class="dropdown-item" data-toggle="modal" data-target="#shiftTenantModal<?= htmlspecialchars($row['id']); ?>" href="#"><i class="fa fa-refresh"></i> Shift</a>
                                <a class="dropdown-item" href="email_tenant.php?email=<?= encryptor('encrypt', $row['id']); ?>"><i class="fa fa-envelope-open"></i> Email</a>
                                <a class="dropdown-item" href="tenant_payment.php?payment=<?= encryptor('encrypt', $row['id']); ?>"><i class="fas fa-money"></i> Payment</a>
                                <!--<div class="dropdown-divider"></div>-->
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addInfoModal<?= htmlspecialchars($row['id']); ?>"><i class="fa fa-plus-square"></i> Add Info</a>
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
                      <!-- Vacate Tenant Modal Display -->
                      <div class="modal fade shadow" id="vacateTenantModal<?= htmlspecialchars($row['id']); ?>">
                        <div class="modal-dialog modal-md">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color:#00192D; color:#fff;">
                              <b class="modal-title">Vacate <?= htmlspecialchars($row['tfirst_name']) . ' ' . htmlspecialchars($row['tlast_name']); ?></b>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="" method="post" autocomplete="off">
                              <input type="hidden" name="id" value="<?= $row['id']; ?>">
                              <input type="hidden" name="occupancy_status" value="Vacant">
                              <div class="modal-body">
                                <div class="form-group">
                                  <p class="text-center"><b><?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></b> Will be Vacated from this Unit within this Bulding. Note that Other Actions will be Disabled</p>
                                  <label for="">Occupancy tatus</label>
                                  <input type="text" class="form-control" name="tenant_status" id="tenant_status" value="Vacated" readonly>
                                </div>
                              </div>
                              <div class="modal-footer text-right">
                                <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="vacate_tenant"><i class="bi bi-send"></i> Vacate</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      <!-- Shift Tenant Modal Display -->
                      <div class="modal fade shadow" id="shiftTenantModal<?= htmlspecialchars($row['id']); ?>" style="border: 1px solid rgb(0,24,45,.3);">
                        <div class="modal-dialog modal-md">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color:#00192D; color:#fff;">
                              <b class="modal-title">Shift <?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['middle_name']) . ' ' . htmlspecialchars($row['tlast_name']); ?></b>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>

                            <div class="modal-body">
                              <p class="text-center" style="font-weight:bold;">Please Specify the Unit to Shift the Tenant</p>
                              <div class="row text-center">
                                <div class="col-md-4">
                                  <input type="radio" name="#" value="Single"> Single Units
                                </div>
                                <div class="col-md-4">
                                  <input type="radio" name="#" value="BedSitter"> Bed Sitter
                                </div>
                                <div class="col-md-4">
                                  <input type="radio" name="#" value="MultiRooms"> Multi-Rooms
                                </div>
                              </div>

                              <!-- Show the Form to Display Vacant Single Units with in the Specific Building -->
                              <div class="Single box" style="display:none; margin-top: 10px; margin-bottom: 10px;">
                                <div class="card shadow" style="border: 1px solid rgb(0, 25, 45, .3);">
                                  <div class="card-header" style="background-color:#00192D; color: #fff;">
                                    <b class="modal-title">Shift <?= htmlspecialchars($row['tfirst_name']) . ' ' . htmlspecialchars($row['tlast_name']); ?></b>
                                  </div>

                                  <!-- This PHP Code will Extract Single Vacant Units within the Building -->
                                  <?php
                                  $building_link = $row['building_link'];
                                  // $unit_category = $row['unit_category'];

                                  // $getVacantSingle = $pdo->prepare("
                                  //               SELECT * FROM tenants
                                  //               WHERE unit_category = :unit_category
                                  //                 AND occupancy_status = 'Vacant'
                                  //                 AND building_link = :building_link
                                  //                 AND tenant_status = 'Vacated'
                                  //           ");

                                  // $getVacantSingle->execute([
                                  //   ':unit_category' => $unit_category,
                                  //   ':building_link'      => $building_link
                                  // ]);

                                  // $allVacantSingleUnits = $getVacantSingle->fetchAll(PDO::FETCH_ASSOC);
                                  ?>

                                  <form action="" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                    <div class="card-body">
                                      <p class="text-center">You are Shifting <span style="font-weight:bold; background-color: #cc0001; color: #fff; padding: 2px; border-radius: 2px;"><?= htmlspecialchars($row['tfirst_name']) . ' ' . htmlspecialchars($row['tlast_name']); ?></span> to Available <?=
                                                                                                                                                                                                                                                                                                        htmlspecialchars($unit_category);
                                                                                                                                                                                                                                                                                                        ?> Units within <?= htmlspecialchars($row['building_link']); ?></p>
                                      <div class="form-group">
                                        <label for="">Current Unit No</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['account_no']); ?>" readonly name="account_no">
                                      </div>
                                      <div class="form-group">
                                        <label>Updated Status</label>
                                        <input class="form-control" value="Vacant" name="occupancy_status" readonly>
                                      </div>
                                      <div class="form-group">
                                        <label>Assign New Unit</label>
                                        <select name="account_no" id="account_no" class="form-control" required>
                                          <option value="" selected hidden>-- Select Vacant Unit --</option>
                                          <?php if (count($allVacantSingleUnits) > 0): ?>
                                            <?php foreach ($allVacantSingleUnits as $unit): ?>
                                              <option value="<?= htmlspecialchars($unit['account_no']); ?>">
                                                <?= htmlspecialchars($unit['account_no']); ?>
                                              </option>
                                            <?php endforeach; ?>
                                          <?php else: ?>
                                            <option disabled>No Vacant Units Found</option>
                                          <?php endif; ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="card-footer text-right">
                                      <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="shift_to_single_unit"><i class="bi bi-send"></i> Shift Tenant</button>
                                    </div>
                                  </form>
                                </div>
                              </div>

                              <!-- Show the Form to Display Only Bed Sitter Units with in the Specific Building -->
                              <div class="BedSitter box" style="display:none; margin-top: 10px;">
                                <div class="card shadow" style="border: 1px solid rgb(0, 25, 45, .3);">
                                  <div class="card-header" style="background-color:#00192D; color: #fff;">
                                    <b class="modal-title">Shift <?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></b>
                                  </div>
                                  <!-- This PHP Code will Extract Bed Sitter Vacant Units within the Building -->
                                  <?php
                                  $getVacantBedSitter = $pdo->prepare("
                                                SELECT * FROM building_units
                                                WHERE unit_category = :unit_category
                                                  AND occupancy_status = 'Vacant'
                                                  AND building_link = :building_link
                                                  AND tenant_status = 'Vacated'
                                            ");

                                  $getVacantBedSitter->execute([
                                    ':unit_category' => $unit_category,
                                    ':building_link'      => $building_link
                                  ]);

                                  $allVacantBedSitterUnits = $getVacantBedSitter->fetchAll(PDO::FETCH_ASSOC);
                                  ?>
                                  <form action="" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                    <div class="card-body">
                                      <p class="text-center">You are Shifting <span style="font-weight:bold; background-color: #cc0001; color: #fff; padding: 2px; border-radius: 2px;"><?= htmlspecialchars($row['tfirst_name']) . ' ' . htmlspecialchars($row['tlast_name']); ?></span> to Available <?= htmlspecialchars($unit_category); ?> Units within <?= htmlspecialchars($row['building_link']); ?></p>
                                      <div class="form-group">
                                        <label for="">Current Unit No</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['account_no']); ?>" readonly name="account_no">
                                      </div>
                                      <div class="form-group">
                                        <label>Updated Status</label>
                                        <input class="form-control" value="Vacant" name="occupancy_status" readonly>
                                      </div>
                                      <div class="form-group">
                                        <label>Assign New Unit</label>
                                        <select name="account_no" id="account_no" class="form-control" required>
                                          <option value="" selected hidden>-- Select Vacant Unit --</option>
                                          <?php if (count($allVacantBedSitterUnits) > 0): ?>
                                            <?php foreach ($allVacantBedSitterUnits as $unit): ?>
                                              <option value="<?= htmlspecialchars($unit['account_no']); ?>">
                                                <?= htmlspecialchars($unit['account_no']); ?>
                                              </option>
                                            <?php endforeach; ?>
                                          <?php else: ?>
                                            <option disabled>No Vacant Units Found</option>
                                          <?php endif; ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="card-footer text-right">
                                      <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="shift_to_single_unit"><i class="bi bi-send"></i> Shift Tenant</button>
                                    </div>
                                  </form>
                                </div>
                              </div>

                              <!-- Show the Form to Display Only Multi-Rooms with in the Specific Building -->
                              <div class="MultiRooms box" style="display:none; margin-top: 10px;">
                                <div class="card shadow">
                                  <div class="card-header" style="background-color:#00192D; color: #fff;">
                                    <b class="modal-title">Shift <?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></b>
                                  </div>
                                  <!-- This PHP Code will Extract Multi Rooms Vacant Units within the Building -->
                                  <?php
                                  $building = $row['building_link'];           // current building name
                                  $multiRoomCategory = 'Multi Room'; // Specify the Unit Category which is Multi Rooms

                                  //Query Out all the Vacant Single Units within the Building
                                  $getVacantMultiRoom = $pdo->prepare("SELECT * FROM tenants WHERE unit_category = :unit_category AND building = :building AND status = 'Vacated'");

                                  $getVacantMultiRoom->execute([
                                    ':unit_category' => $multiRoomCategory,
                                    ':building'      => $building
                                  ]);
                                  $allVacantMultiRooms = $getVacantMultiRoom->fetchAll(PDO::FETCH_ASSOC);
                                  ?>
                                  <form action="" method="post" autocomplete="off">
                                    <div class="card-body">
                                      <p class="text-center" style="font-weight:bold;">You are Shifting <?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?> to Available <?= htmlspecialchars($multiRoomCategory); ?> Units within <?= htmlspecialchars($row['building_link']); ?></p>
                                      <div class="form-group">
                                        <label for="">Current Unit No</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['account_no']); ?>" readonly name="account_no">
                                      </div>
                                      <div class="form-group">
                                        <label>Assign New Unit</label>
                                        <select name="account_no" id="account_no" class="form-control" required>
                                          <option value="" selected hidden>-- Select Vacant Unit --</option>
                                          <?php if (count($allVacantMultiRooms) > 0): ?>
                                            <?php foreach ($allVacantMultiRooms as $unit): ?>
                                              <option value="<?= htmlspecialchars($unit['account_no']); ?>">
                                                <?= htmlspecialchars($unit['account_no']); ?>
                                              </option>
                                            <?php endforeach; ?>
                                          <?php else: ?>
                                            <option disabled>No Vacant Units Found</option>
                                          <?php endif; ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="card-footer text-right">
                                      <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="shift_to_multi_room_unit"><i class="bi bi-send"></i> Shift Tenant</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Add More Info for the Tenant Modal Display -->
                      <div class="modal fade shadow" id="addInfoModal<?= htmlspecialchars($row['id']); ?>">
                        <div class="modal-dialog modal-md">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color:#00192D; color:#fff;">
                              <b class="modal-title">Add Info for <?= htmlspecialchars($row['tfirst_name']) . ' ' . htmlspecialchars($row['tlast_name']); ?></b>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="" method="post" autocomplete="off">
                              <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                              <div class="modal-body">
                                <p class="text-center">These will be the Login Credentials for the Tenant to Access the Software Features</p>
                                <div class="form-group">
                                  <label for="">Username</label>
                                  <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username"
                                    </div>
                                  <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                                  </div>
                                  <div class="form-group" style="display:none;">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="password_confirm" id="password" placeholder="Enter Password">
                                  </div>
                                </div>
                                <div class="modal-footer text-right">
                                  <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="add_tenant_info"><i class="bi bi-send"></i> Submit</button>
                                </div>
                            </form>
                          </div>
                        </div>
                      </div>
                  <?php
                    } else {
                      echo "<script>
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'No Data',
                                    text: 'No tenants found in the database.'
                                });
                            </script>";
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
      </section>

      <!-- /.content -->

      <!-- Help Pop Up Form -->
      <?php include_once 'includes/lower_right_popup_form.php'; ?>
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer -->
  <?php include_once 'includes/footer.php'; ?>

  </div>
  <!-- ./wrapper -->
  <!-- Required Scripts -->
  <?php include_once 'includes/required_scripts.php'; ?>
  <!-- Meter Readings JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  </div>
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
      <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;">JENGO PAY</a>.
    </strong>
    All rights reserved.
    <!--end::Copyright-->
  </footer>

  </div>
  <!--end::App Wrapper-->

  <!-- plugin for pdf -->


  <!-- Main Js File -->
  <script src="../../js/adminlte.js"></script>
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

  <?php
  if (isset($_POST['vacate_tenant'])) {
    $tenant_id = $_POST['id'] ?? '';
    $occupancy_status = $_POST['occupancy_status'] ?? '';
    $tenant_status = $_POST['tenant_status'] ?? '';
    try {
      $update = $pdo->prepare("UPDATE tenants SET occupancy_status =:occupancy_status, tenant_status = :tenant_status, vacated_on = NOW() WHERE id = :id");
      $updated = $update->execute([
        ':occupancy_status' => $occupancy_status,
        ':tenant_status' => $tenant_status,
        ':id' => $tenant_id
      ]);

      if ($updated) {
        echo
        "<script>
                      Swal.fire({
                      icon: 'success',
                      title: 'Vacate Successful!',
                      text: 'Tenant has been Vacated Successfully. This Unit is Now Vacant.',
                      confirmButtonColor: '#00192D'
                      }).then(() => {
                      window.location.href = 'all_tenants.php'; // redirect after confirmation
                      });
                    </script>";
      } else {
        echo "
                  <script>
                    Swal.fire({
                      icon: 'error',
                      title: 'Update Failed',
                      text: 'Unable to update tenant status. Please try again.',
                      confirmButtonColor: '#00192D'
                    });
                  </script>";
      }
    } catch (PDOException $e) {
      echo "
            <script>
              Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: '" . addslashes($e->getMessage()) . "',
                confirmButtonColor: '#00192D'
              });
            </script>";
    }
  }

  //=============== PHP Shift Tenant to a Single Vacant Unit ====================
  if (isset($_POST['shift_to_single_unit'])) {
    $tenant_id = $_POST['id'] ?? '';
    $tenant_status = $_POST['tenant_status'] ?? '';
    $newoccupancy_status = 'Occupied';

    try {
      $shiftTenant = $pdo->prepare("UPDATE tenants SET occupancy_status =:occupancy_status WHERE id =:id");
      $shiftTenantConfirm = $shiftTenant->execute([
        ':occupancy_status' => $newoccupancy_status,
        ':id' => $tenant_id
      ]);

      if ($shiftTenantConfirm) {
        echo
        "<script>
                      Swal.fire({
                      icon: 'success',
                      title: 'Shift Successful!',
                      text: 'Tenant has been Vacated Successfully Shifted to another Unit.',
                      confirmButtonColor: '#00192D'
                      }).then(() => {
                      window.location.href = 'all_tenants.php'; // redirect after confirmation
                      });
                    </script>";
      } else {
        echo "
                  <script>
                    Swal.fire({
                      icon: 'error',
                      title: 'Update Failed',
                      text: 'Unable to Shift tenant status. Please try again.',
                      confirmButtonColor: '#00192D'
                    });
                  </script>";
      }
    } catch (PDOException $e) {
      echo "
            <script>
              Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: '" . addslashes($e->getMessage()) . "',
                confirmButtonColor: '#00192D'
              });
            </script>";
    }
  }

  //==================== Add Tenant Informatin for Loging innto the System ====================
  if (isset($_POST['add_tenant_info'])) {
    //Sanitize and validate the inputs
    $id = trim($_POST['id'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //Secure the Password
    try {
      //Check for the Existance of the Same Username in the Database to avoid double regustration
      $check_username = $pdo->prepare("SELECT * FROM tenants WHERE username =:username");
      $check_username->execute([
        'username' => $username
      ]);
      //Check for Empty Fields and Avoid Submission
      if (empty($username) || empty($password)) {
        //Show Message for required Inputs
        echo "
                <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Username or Password Missing.',
                    confirmButtonColor: '#00192D'
                });
                </script>";
        exit;
        //Check if the Username Already Exists in the Database
      }
      if ($check_username->rowCount() > 0) {
        //Show Message warning the Existance of the Username
        echo "
                <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Double Registration',
                    text: 'Username Entered Already Registered. Choose a Different One.',
                    confirmButtonColor: '#00192D'
                });
                </script>";
        exit;
      } else {
        //If All is well, Submit data
        $addInfo = $pdo->prepare("UPDATE tenants SET username =:username, password=:password WHERE id =:id");
        $addInfo->execute([
          ':username' => $username,
          ':password' => $hashedPassword,
          ':id' => $id,
        ]);

        //Alert Message Showing Successful Submision of Added Data
        echo
        "<script>
                  Swal.fire({
                    icon: 'success',
                    title: 'Confirmation',
                    text: 'Tenant Login Credentials Added Successfully.',
                    confirmButtonColor: '#00192D'
                  }).then(() => {
                    window.location.href = 'all_tenants.php'; // redirect after confirmation
                  });
                </script>";
      }
    } catch (Exception $e) {
    }
  }
  ?>
</body>
<!--end::Body-->

</html>