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
        <main class="app-main mt-4">
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
        <div class="container-fluid">
          <?php
            // CHANGED: Query tenants table instead of bedsitter_units
            $bedsitter_tenants = "SELECT tenant_status, COUNT(*) AS total FROM tenants GROUP BY tenant_status";
            $result_bedsitter_tenants = $pdo->prepare($bedsitter_tenants);
            $result_bedsitter_tenants->execute();
            
            //Initialize the countings for all the buildings
            $counts = [
                      'Active' => 0,
                      'Vacated'   => 0
                    ];
                    
            while($row = $result_bedsitter_tenants->fetch()) {
                $counts[$row['tenant_status']] = $row['total'];
            }

            // Assign icons for each building type
            $icons = [
                'Active' => 'bi-check',
                'Vacated'   => 'bi-house'
            ];
            ?>

          <div class="row">
              <?php foreach ($counts as $type => $total): ?>
                <div class="col-md-3">
                  <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                    <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="bi  <?php echo $icons[$type]; ?>"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text"><?php echo $type; ?></span>
                      <span class="info-box-number"><?php echo $total; ?></span>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
            </div><hr>
          <div class="card shadow">
            <div class="card-header" style="background-color:#00192D; color:#fff;"><b>All Bed Sitter Tenants</b></div>
            <div class="card-body">
              <table class="table table-striped" id="dataTable">
                <thead>
                  <th>Name</th>
                  <th>Unit Account No</th>
                  <th>Unit Category</th>
                  <th>Contacts</th>
                  <th>Identification</th>
                  <th>Move In Date</th>
                  <th>Added On</th>
                  <th>Status</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                  include_once 'processes/encrypt_decrypt_function.php';
                    
                    // CHANGED: Query tenants table instead of bedsitter_units
                    $select = $pdo->prepare("SELECT * FROM tenants ORDER BY tenant_reg DESC");
                    $select->execute();
                    
                    $hasRecords = false;
                    
                    while($row = $select->fetch()){
                      $hasRecords = true;
                      $id = encryptor('encrypt', $row['id']);
                      ?>
                        <tr>
                          <td><?= htmlspecialchars($row['first_name'].' '.$row['middle_name']);?></td>
                          <td><?= htmlspecialchars($row['account_no']);?></td>
                          <td><?= htmlspecialchars($row['unit_category']) ;?></td>
                          <td>
                            <a href="tel:<?= htmlspecialchars($row['main_contact']);?>"><?= htmlspecialchars($row['main_contact']);?></a><br>
                            <a href="tel:<?= htmlspecialchars($row['alt_contact']);?>"> <?= htmlspecialchars($row['alt_contact']);?></a>
                        </td>
                          <td><i class="bi bi-person-vcard"></i> <?= htmlspecialchars($row['pass_no'].''.$row['id_no']. ' ('.ucfirst($row['idMode']).')');?></td>
                          <td><?= htmlspecialchars($row['move_in_date']);?></td>
                          <td><?= htmlspecialchars($row['tenant_reg']);?></td>
                          <td>
                            <?php
                              if($row['tenant_status'] == 'Active') {
                                ?>
                                  <button class="btn btn-xs shadow" style="background-color:#24953E; color:#fff"><i class="bi bi-person-check"></i> <?= htmlspecialchars($row['tenant_status']);?></button>
                                <?php
                              } else {
                                ?>
                                  <button class="btn btn-xs shadow" style="background-color: #cc0001; color:#fff"><i class="bi bi-person-exclamation"></i> <?= htmlspecialchars($row['tenant_status']);?></button>
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
                                  if($row['tenant_status'] == 'Active') {
                                    ?>
                                      <a class="dropdown-item" href="<?= 'https://wa.me/'.$row['main_contact'].'/?text=Hello,'." ". $row['first_name']. '' ?>" target="_blank"><i class="fa fa-whatsapp"></i> WhatsApp</a>
                                      <a class="dropdown-item" href="messaging.php?mesage=<?= $row['first_name'] ;?>"><i class="fa fa-envelope"></i> Message</a>
                                      <a class="dropdown-item" href="tenant_profile.php?profile=<?= encryptor('encrypt', $row['id']);?>"><i class="fas fa-newspaper"></i> Profile</a>
                                      <a class="dropdown-item" href="edit_tenant_info.php?edit_tenant=<?= encryptor('encrypt', $row['id']);?>"><i class="fas fa-edit"></i> Edit</a>
                                      <a class="dropdown-item" href="bed_sitter_tenant_invoice.php?invoice=<?= encryptor('encrypt', $row['id']);?>"><i class="fa fa-newspaper"></i> Invoice</a>
                                      <a class="dropdown-item" href="all_individual_tenant_invoices.php?invoice=<?= encryptor('encrypt', $row['id']);?>"><i class="fa fa-table"></i> All Invoices</a>
                                      <a class="dropdown-item" data-toggle="modal" data-target="#vacateTenantModal<?= htmlspecialchars($row['id']);?>" href="#"><i class="fa fa-arrow-right"></i> Vacate</a>
                                      <a class="dropdown-item" data-toggle="modal" data-target="#shiftTenantModal<?= htmlspecialchars($row['id']);?>" href="#"><i class="fa fa-refresh"></i> Shift</a>
                                      <a class="dropdown-item" href="email_tenant.php?email=<?= encryptor('encrypt', $row['id']);?>"><i class="fa fa-envelope-open"></i> Email</a>
                                      <a class="dropdown-item" href="tenant_payment.php?payment=<?= encryptor('encrypt', $row['id']);?>"><i class="fas fa-money"></i> Payment</a>
                                      <!--<div class="dropdown-divider"></div>-->
                                      <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addInfoModal<?= htmlspecialchars($row['id']);?>"><i class="fa fa-plus-square"></i> Add Info</a>
                                    <?php
                                  } else {
                                    ?>
                                      <a class="dropdown-item" href="tenant_profile.php?profile=<?= encryptor('encrypt', $row['id']);?>"><i class="fas fa-newspaper"></i> Profile</a>
                                      <a class="dropdown-item" href="all_individual_tenant_invoices.php?invoice=<?= encryptor('encrypt', $row['id']);?>"><i class="bi bi-receipt"></i> All Invoices</a>
                                    <?php
                                  }
                                ?>
                              </div>
                            </div>
                          </td>
                        </tr>
                        
                        <!-- Vacate Tenant Modal Display -->
                        <div class="modal fade shadow" id="vacateTenantModal<?= htmlspecialchars($row['id']);?>">
                          <div class="modal-dialog modal-md">
                            <div class="modal-content">
                              <div class="modal-header" style="background-color:#00192D; color:#fff;">
                                <b class="modal-title">Vacate <?= htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']);?></b>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <form action="" method="post" autocomplete="off">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']);?>">
                                <input type="hidden" name="account_no" value="<?= htmlspecialchars($row['account_no']);?>">
                                <input type="hidden" name="unit_category" value="<?= htmlspecialchars($row['unit_category']);?>">
                                <div class="modal-body">
                                  <div class="form-group">
                                   <p class="text-center"><b><?= htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']);?></b> Will be Vacated from this Unit. Note that Other Actions will be Disabled</p>
                                    <label for="">Tenant Status</label>
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
                      <div class="modal fade shadow" id="shiftTenantModal<?= htmlspecialchars($row['id']);?>" style="border: 1px solid rgb(0,24,45,.3);">
                        <div class="modal-dialog modal-md">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color:#00192D; color:#fff;">
                              <b class="modal-title">Shift <?= htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['middle_name']).' '.htmlspecialchars($row['last_name']);?></b>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>

                            <div class="modal-body">
                              <p class="text-center" style="font-weight:bold;">Please Specify the Unit to Shift the Tenant</p>
                              <div class="row text-center">
                                <div class="col-md-4">
                                  <input type="radio" name="unit_type_<?= htmlspecialchars($row['id']);?>" value="Single" class="unit-type-radio"> Single Units
                                </div>
                                <div class="col-md-4">
                                  <input type="radio" name="unit_type_<?= htmlspecialchars($row['id']);?>" value="BedSitter" class="unit-type-radio"> Bed Sitter
                                </div>
                                <div class="col-md-4">
                                  <input type="radio" name="unit_type_<?= htmlspecialchars($row['id']);?>" value="MultiRooms" class="unit-type-radio"> Multi-Rooms
                                </div>
                              </div>

                              <!-- Show the Form to Display Vacant Single Units -->
                              <div class="Single box" style="display:none; margin-top: 10px; margin-bottom: 10px;">
                                <div class="card shadow" style="border: 1px solid rgb(0, 25, 45, .3);">
                                  <div class="card-header" style="background-color:#00192D; color: #fff;">
                                    <b class="modal-title">Shift <?= htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']);?></b>
                                  </div>

                                  <?php
                                    // Get building_link from tenant record
                                    $building_link = $row['building_link'];
                                    
                                    // Get vacant single units in the same building
                                    $getVacantSingle = $pdo->prepare("
                                        SELECT * FROM single_units
                                        WHERE occupancy_status = 'Vacant'
                                        AND building_link = :building_link
                                    ");
                                    $getVacantSingle->execute([':building_link' => $building_link]);
                                    $allVacantSingleUnits = $getVacantSingle->fetchAll(PDO::FETCH_ASSOC);
                                  ?>

                                  <form action="" method="post" autocomplete="off">
                                    <input type="hidden" name="tenant_id" value="<?= htmlspecialchars($row['id']);?>">
                                    <input type="hidden" name="current_account_no" value="<?= htmlspecialchars($row['account_no']);?>">
                                    <input type="hidden" name="current_unit_category" value="<?= htmlspecialchars($row['unit_category']);?>">
                                    <input type="hidden" name="building_link" value="<?= htmlspecialchars($building_link);?>">
                                    <div class="card-body">
                                      <p class="text-center">You are Shifting <span style="font-weight:bold; background-color: #cc0001; color: #fff; padding: 2px; border-radius: 2px;"><?= htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']);?></span> to Available Single Units within <?= htmlspecialchars($building_link);?></p>
                                      <div class="form-group">
                                        <label for="">Current Unit No</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['account_no']);?>" readonly>
                                      </div>
                                      <div class="form-group">
                                        <label>Assign New Unit</label>
                                        <select name="new_account_no" class="form-control" required>
                                          <option value="" selected hidden>-- Select Vacant Unit --</option>
                                          <?php if (count($allVacantSingleUnits) > 0): ?>
                                              <?php foreach ($allVacantSingleUnits as $unit): ?>
                                                  <option value="<?= htmlspecialchars($unit['unit_number']); ?>">
                                                    <?= htmlspecialchars($unit['unit_number']); ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          <?php else: ?>
                                              <option disabled>No Vacant Units Found</option>
                                          <?php endif; ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="card-footer text-right">
                                      <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="shift_tenant"><i class="bi bi-send"></i> Shift Tenant</button>
                                    </div>
                                  </form>
                                </div>
                              </div>

                              <!-- Show the Form to Display Bed Sitter Units -->
                              <div class="BedSitter box" style="display:none; margin-top: 10px;">
                                <div class="card shadow" style="border: 1px solid rgb(0, 25, 45, .3);">
                                  <div class="card-header" style="background-color:#00192D; color: #fff;">
                                    <b class="modal-title">Shift <?= htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']);?></b>
                                  </div>
                                  
                                  <?php
                                    // Get vacant bedsitter units in the same building
                                    $getVacantBedSitter = $pdo->prepare("
                                        SELECT * FROM bedsitter_units
                                        WHERE occupancy_status = 'Vacant'
                                        AND building_link = :building_link
                                        AND unit_number != :current_unit
                                    ");
                                    $getVacantBedSitter->execute([
                                        ':building_link' => $building_link,
                                        ':current_unit' => $row['account_no']
                                    ]);
                                    $allVacantBedSitterUnits = $getVacantBedSitter->fetchAll(PDO::FETCH_ASSOC);
                                  ?>
                                  
                                  <form action="" method="post" autocomplete="off">
                                    <input type="hidden" name="tenant_id" value="<?= htmlspecialchars($row['id']);?>">
                                    <input type="hidden" name="current_account_no" value="<?= htmlspecialchars($row['account_no']);?>">
                                    <input type="hidden" name="building_link" value="<?= htmlspecialchars($building_link);?>">
                                    <div class="card-body">
                                      <p class="text-center" style="font-weight:bold;">You are Shifting <?= htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']);?> to Available Bed Sitter Units within <?= htmlspecialchars($building_link);?></p>
                                      <div class="form-group">
                                        <label for="">Current Unit No</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['account_no']);?>" readonly>
                                      </div>
                                      <div class="form-group">
                                        <label>Assign New Unit</label>
                                        <select name="new_account_no" class="form-control" required>
                                          <option value="" selected hidden>-- Select Vacant Unit --</option>
                                          <?php if (count($allVacantBedSitterUnits) > 0): ?>
                                              <?php foreach ($allVacantBedSitterUnits as $unit): ?>
                                                  <option value="<?= htmlspecialchars($unit['unit_number']); ?>">
                                                    <?= htmlspecialchars($unit['unit_number']); ?>
                                                  </option>
                                              <?php endforeach; ?>
                                          <?php else: ?>
                                              <option disabled>No Vacant Units Found</option>
                                          <?php endif; ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="card-footer text-right">
                                      <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="shift_tenant"><i class="bi bi-send"></i> Shift Tenant</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Add More Info for the Tenant Modal Display -->
                      <div class="modal fade shadow" id="addInfoModal<?= htmlspecialchars($row['id']);?>">
                        <div class="modal-dialog modal-md">
                          <div class="modal-content">
                            <div class="modal-header" style="background-color:#00192D; color:#fff;">
                              <b class="modal-title">Add Info for <?= htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']);?></b>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="" method="post" autocomplete="off">
                              <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']);?>">
                              <div class="modal-body">
                                <p class="text-center">These will be the Login Credentials for the Tenant to Access the Software Features</p>
                                <div class="form-group">
                                  <label for="">Username</label>
                                  <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" required>
                                </div>
                                <div class="form-group">
                                  <label for="">Password</label>
                                  <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
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
                    }
                    
                    if (!$hasRecords) {
                      echo "<tr><td colspan='9' class='text-center'>No tenants found</td></tr>";
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
           
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
    
    </div>
    <!-- ./wrapper -->
    <!-- Required Scripts -->
   
    <!-- Meter Readings JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JavaScript for radio button toggling -->
    <script>
    $(document).ready(function() {
        // Handle radio button changes for shift tenant modal
        $('input[class="unit-type-radio"]').change(function() {
            var tenantId = this.name.replace('unit_type_', '');
            var selectedValue = this.value;
            
            // Hide all boxes first
            $('.box').hide();
            
            // Show selected box
            $('.' + selectedValue + '.box').show();
        });
    });
    </script>
 
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
        // =============== VACATE TENANT ====================
        if (isset($_POST['vacate_tenant'])) {
            $tenant_id = $_POST['id'] ?? '';
            $tenant_status = $_POST['tenant_status'] ?? '';
            $account_no = $_POST['account_no'] ?? '';
            $unit_category = $_POST['unit_category'] ?? '';
            
            try {
                // Update tenant status
                $updateTenant = $pdo->prepare("UPDATE tenants SET tenant_status = :tenant_status, tenant_occupancy_status = 'INACTIVE', move_out_date = NOW() WHERE id = :id");
                $updatedTenant = $updateTenant->execute([
                    ':tenant_status' => $tenant_status,
                    ':id' => $tenant_id
                ]);
                
                // Update unit occupancy status based on unit category
                if ($unit_category == 'Single Unit') {
                    $updateUnit = $pdo->prepare("UPDATE single_units SET occupancy_status = 'Vacant' WHERE unit_number = :account_no");
                } elseif ($unit_category == 'Bed Sitter') {
                    $updateUnit = $pdo->prepare("UPDATE bedsitter_units SET occupancy_status = 'Vacant' WHERE unit_number = :account_no");
                }
                
                if (isset($updateUnit)) {
                    $updatedUnit = $updateUnit->execute([':account_no' => $account_no]);
                }

                if($updatedTenant) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Vacate Successful!',
                            text: 'Tenant has been Vacated Successfully.',
                            confirmButtonColor: '#00192D'
                        }).then(() => {
                            window.location.href = 'bed_sitter_tenants.php';
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: 'Unable to update tenant status. Please try again.',
                            confirmButtonColor: '#00192D'
                        });
                    </script>";
                }
            } catch (PDOException $e) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Database Error',
                        text: '".addslashes($e->getMessage())."',
                        confirmButtonColor: '#00192D'
                    });
                </script>";
            }
        }

        // =============== SHIFT TENANT ====================
        if(isset($_POST['shift_tenant'])) {
            $tenant_id = $_POST['tenant_id'] ?? '';
            $current_account_no = $_POST['current_account_no'] ?? '';
            $new_account_no = $_POST['new_account_no'] ?? '';
            $current_unit_category = $_POST['current_unit_category'] ?? '';
            $building_link = $_POST['building_link'] ?? '';
            
            try {
                // Begin transaction
                $pdo->beginTransaction();
                
                // 1. Vacate current unit based on category
                if ($current_unit_category == 'Single Unit') {
                    $vacateCurrent = $pdo->prepare("UPDATE single_units SET occupancy_status = 'Vacant' WHERE unit_number = :current_account_no");
                } elseif ($current_unit_category == 'Bed Sitter') {
                    $vacateCurrent = $pdo->prepare("UPDATE bedsitter_units SET occupancy_status = 'Vacant' WHERE unit_number = :current_account_no");
                }
                
                if (isset($vacateCurrent)) {
                    $vacateCurrent->execute([':current_account_no' => $current_account_no]);
                }
                
                // 2. Determine new unit category based on table pattern
                // Check if new unit exists in single_units table
                $checkSingleUnit = $pdo->prepare("SELECT COUNT(*) as count FROM single_units WHERE unit_number = :new_account_no");
                $checkSingleUnit->execute([':new_account_no' => $new_account_no]);
                $singleUnitExists = $checkSingleUnit->fetch()['count'] > 0;
                
                if ($singleUnitExists) {
                    // Occupy new single unit
                    $occupyNew = $pdo->prepare("UPDATE single_units SET occupancy_status = 'Occupied' WHERE unit_number = :new_account_no");
                    $new_unit_category = 'Single Unit';
                } else {
                    // Check bedsitter_units table
                    $checkBedSitterUnit = $pdo->prepare("SELECT COUNT(*) as count FROM bedsitter_units WHERE unit_number = :new_account_no");
                    $checkBedSitterUnit->execute([':new_account_no' => $new_account_no]);
                    $bedSitterUnitExists = $checkBedSitterUnit->fetch()['count'] > 0;
                    
                    if ($bedSitterUnitExists) {
                        $occupyNew = $pdo->prepare("UPDATE bedsitter_units SET occupancy_status = 'Occupied' WHERE unit_number = :new_account_no");
                        $new_unit_category = 'Bed Sitter';
                    } else {
                        throw new Exception("New unit not found in any unit table");
                    }
                }
                
                if (isset($occupyNew)) {
                    $occupyNew->execute([':new_account_no' => $new_account_no]);
                }
                
                // 3. Update tenant's account number and unit category
                $updateTenant = $pdo->prepare("UPDATE tenants SET account_no = :new_account_no, unit_category = :unit_category WHERE id = :id");
                $updateTenant->execute([
                    ':new_account_no' => $new_account_no,
                    ':unit_category' => $new_unit_category,
                    ':id' => $tenant_id
                ]);
                
                // Commit transaction
                $pdo->commit();
                
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Shift Successful!',
                        text: 'Tenant has been successfully shifted to new unit.',
                        confirmButtonColor: '#00192D'
                    }).then(() => {
                        window.location.href = 'bed_sitter_tenants.php';
                    });
                </script>";
                
            } catch (PDOException $e) {
                // Rollback transaction on error
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Database Error',
                        text: '".addslashes($e->getMessage())."',
                        confirmButtonColor: '#00192D'
                    });
                </script>";
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '".addslashes($e->getMessage())."',
                        confirmButtonColor: '#00192D'
                    });
                </script>";
            }
        }

        // =============== ADD TENANT LOGIN INFO ====================
        if(isset($_POST['add_tenant_info'])) {
            $id = trim($_POST['id'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                // Check if username already exists in tenants table
                $check_username = $pdo->prepare("SELECT * FROM tenants WHERE username = :username");
                $check_username->execute([':username' => $username]);
                
                if(empty($username) || empty($password)) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Missing Fields',
                            text: 'Username or Password Missing.',
                            confirmButtonColor: '#00192D'
                        });
                    </script>";
                    exit;
                }
                
                if ($check_username->rowCount() > 0) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Double Registration',
                            text: 'Username Entered Already Registered. Choose a Different One.',
                            confirmButtonColor: '#00192D'
                        });
                    </script>";
                    exit;
                } else {
                    // Update tenant with login credentials
                    $addInfo = $pdo->prepare("UPDATE tenants SET username = :username, password = :password WHERE id = :id");
                    $addInfo->execute([
                        ':username' => $username,
                        ':password' => $hashedPassword,
                        ':id' => $id,
                    ]);

                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Confirmation',
                            text: 'Tenant Login Credentials Added Successfully.',
                            confirmButtonColor: '#00192D'
                        }).then(() => {
                            window.location.href = 'bed_sitter_tenants.php';
                        });
                    </script>";
                }
            } catch (Exception $e) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred: " . addslashes($e->getMessage()) . "',
                        confirmButtonColor: '#00192D'
                    });
                </script>";
            }
        }
    ?>
</body>
<!--end::Body-->

</html>