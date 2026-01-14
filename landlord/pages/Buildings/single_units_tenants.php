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
                  <tr> <!-- Added missing <tr> -->
                    <th>Name</th>
                    <th>Unit | Building</th>
                    <th>Unit Category</th>
                    <th>Contacts</th>
                    <th>Identification</th>
                    <th>Move In Date</th>
                    <th>Added On</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  include_once '../processes/encrypt_decrypt_function.php';

                  // CORRECTED SQL QUERY - Based on your actual table structure
                  $select = $pdo->prepare("
                        SELECT 
                            t.*,
                            b.building_name
                        FROM tenants t
                        LEFT JOIN buildings b 
                            ON t.building_id = b.id
                        WHERE t.unit_category = :unit_category
                            AND t.tenant_status = 'Active'
                        ORDER BY t.created_at DESC
                    ");

                  $select->execute([
                    ':unit_category' => 'single_unit'  // or whatever value indicates single units
                  ]);

                  $tenants = $select->fetchAll(PDO::FETCH_ASSOC);


                  if (count($tenants) > 0) {
                    foreach ($tenants as $row) {
                      $id = encryptor('encrypt', $row['id']);
                  ?>
                      <tr>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); ?></td>
                        <td>
                          <?=
                          !empty($row['unit_number']) ? htmlspecialchars($row['unit_number']) : 'N/A';
                          ?> |
                          <?=
                          !empty($row['building_name']) ? htmlspecialchars($row['building_name']) : (!empty($row['building_id']) ? htmlspecialchars($row['building_id']) : 'N/A');
                          ?>
                        </td>
                        <td><?= htmlspecialchars($row['unit_category'] ?? 'N/A'); ?></td>
                        <td>
                          <?php if (!empty($row['main_contact'])): ?>
                            <a href="tel:<?= htmlspecialchars($row['main_contact']); ?>">
                              <i class="bi bi-telephone"></i> <?= htmlspecialchars($row['main_contact']); ?>
                            </a><br>
                          <?php endif; ?>
                          <?php if (!empty($row['alt_contact'])): ?>
                            <a href="tel:<?= htmlspecialchars($row['alt_contact']); ?>">
                              <i class="bi bi-telephone"></i> <?= htmlspecialchars($row['alt_contact']); ?>
                            </a>
                          <?php endif; ?>
                        </td>
                        <td>
                          <i class="bi bi-person-vcard"></i>
                          <?=
                          !empty($row['id_no']) ? htmlspecialchars($row['id_no']) : (!empty($row['national_id']) ? htmlspecialchars($row['national_id']) : 'N/A');
                          ?>
                          <?php if (!empty($row['idMode'])): ?>
                            <br><small>(<?= htmlspecialchars(ucfirst($row['idMode'])); ?>)</small>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?=
                          !empty($row['move_in_date']) ? date('d/m/Y', strtotime($row['move_in_date'])) : 'N/A';
                          ?>
                        </td>
                        <td>
                          <?=
                          !empty($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : 'N/A';
                          ?>
                        </td>
                        <td>
                          <?php if ($row['tenant_status'] == 'Active'): ?>
                            <span class="badge bg-success">
                              <i class="bi bi-person-check"></i> <?= $row['tenant_status']; ?>
                            </span>
                          <?php else: ?>
                            <span class="badge bg-danger">
                              <i class="bi bi-person-exclamation"></i> <?= $row['tenant_status']; ?>
                            </span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                              Actions
                            </button>
                            <div class="dropdown-menu">
                              <!-- WhatsApp -->
                              <?php if (!empty($row['phone'])): ?>
                                <a class="dropdown-item" href="https://wa.me/<?= $row['phone']; ?>?text=Hello <?= urlencode($row['first_name']); ?>" target="_blank">
                                  <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                              <?php endif; ?>

                              <!-- Email -->
                              <?php if (!empty($row['email'])): ?>
                                <a class="dropdown-item" href="mailto:<?= $row['email']; ?>">
                                  <i class="fas fa-envelope"></i> Email
                                </a>
                              <?php endif; ?>

                              <!-- Profile -->
                              <a class="dropdown-item" href="tenant_profile.php?profile=<?= $id; ?>">
                                <i class="fas fa-user"></i> Profile
                              </a>

                              <!-- Edit -->
                              <a class="dropdown-item" href="edit_tenant_info.php?edit_tenant=<?= $id; ?>">
                                <i class="fas fa-edit"></i> Edit
                              </a>

                              <!-- Invoice -->
                              <a class="dropdown-item" href="single_unit_tenant_invoice.php?invoice=<?= $id; ?>">
                                <i class="fas fa-file-invoice"></i> Invoice
                              </a>
                            </div>
                          </div>
                        </td>
                      </tr>
                  <?php
                    }
                  } else {
                    echo '<tr><td colspan="9" class="text-center py-3">No single unit tenants found</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

        <!-- /.content -->

        <!-- Help Pop Up Form -->
        <?php 
        // include_once 'includes/lower_right_popup_form.php'; 
        ?>
      
      <!-- /.content-wrapper -->
    
        <!-- ./wrapper -->
        <!-- Required Scripts -->
        <?php 
        // include_once 'includes/required_scripts.php'; 
        
        ?>
      <!-- Meter Readings JavaScript -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </main>
    <!--end::App Main-->

    <!--begin::Footer-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!-- end footer -->
 
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