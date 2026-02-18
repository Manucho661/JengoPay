<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

require_once "../../../db/connect.php";
//  include_once 'includes/lower_right_popup_form.php';
?>

<?php

// Initialize variables to prevent undefined variable warnings
$unit_number = '';
$location = '';
$building_link = '';
$purpose = '';
$unit_category = '';
$occupancy_status = '';
$id = null;

include_once 'processes/encrypt_decrypt_function.php';

if (isset($_GET['rent']) && !empty($_GET['rent'])) {
  $id = $_GET['rent'];
  // $id = encryptor('decrypt', $id);

  try {
    if (!empty($id)) {
      $sql = "SELECT * FROM multi_rooms_units WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([':id' => $id]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) {
        $id = $row['id'];
        $unit_number = $row['unit_number'];
        $location = $row['location'];
        $building_link = $row['building_link'];
        $purpose = $row['purpose'];
        $unit_category = $row['unit_category'];
        $occupancy_status = $row['occupancy_status'];
      }
    }
  } catch (PDOException $e) {
    // Handle exception here if needed
  }
}

// actions
require_once "../actions/rent_multi_room_unit.php";
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
  <link rel="stylesheet" href="../../../assets/main.css" />
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
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="">
              <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/index2.php" style="text-decoration: none;">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Buildings/buildings.php" style="text-decoration: none;">Buildings</a></li>
              <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/units/units.php" style="text-decoration: none;">Units</a></li>
              <li class="breadcrumb-item active">Rent Mult-room unit</li>
            </ol>
          </nav>
          <!-- if the Rent Single Unit Button is Clicked -->
          <?php
          include_once 'processes/encrypt_decrypt_function.php';
          if (isset($_GET['rent']) && !empty($_GET['rent'])) {
            $id = $_GET['rent'];
            // $id = encryptor('decrypt', $id);
            try {
              if (!empty($id)) {
                $sql = "SELECT * FROM building_units WHERE id =:id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(':id' => $id));
                while ($row = $stmt->fetch()) {
                  $id = $row['id'];
                  $unit_number = $row['unit_number'];
                  $location = $row['location'];
                  $building_link = $row['building_type'];
                  $purpose = $row['purpose'];
                  $purpose = $row['purpose'];
                  $unit_category = $row['unit_category'];
                  $occupancy_status = $row['occupancy_status'];
                }
              }
            } catch (PDOException $e) {
              //if the execution fails
            }
          }
          ?>
          <!-- Get Some Details About the Unit and make Cards for it -->
          <div class="row mb-4">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="stat-card d-flex align-items-center rounded-2">
                <div>
                  <i class="fas fa-building me-3 text-warning"></i>
                </div>
                <div>
                  <p class="mb-0" style="font-weight: bold;">Unit No</p>
                  <h3><?= htmlspecialchars($unit_number); ?></h3>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div class="stat-card d-flex align-items-center rounded-2">
                <div>
                  <i class="fas fa-city me-3 text-warning"></i>
                </div>
                <div>
                  <p class="mb-0" style="font-weight: bold;">Unit Floor</p>
                  <h3><?= htmlspecialchars($location); ?></h3>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div class="stat-card d-flex align-items-center rounded-2">
                <div>
                  <i class="fas fa-house-damage me-3 text-warning"></i>
                </div>
                <div>
                  <p class="mb-0" style="font-weight: bold;">Building</p>
                  <h3><?= htmlspecialchars($building_link); ?></h3>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div class="stat-card d-flex align-items-center rounded-2">
                <div>
                  <i class="fas fa-city me-3 text-warning"></i>
                </div>
                <div>
                  <p class="mb-0" style="font-weight: bold;">Number of Units</p>
                  <h3><?= htmlspecialchars($purpose); ?></h3>
                </div>
              </div>
            </div>
          </div>

          <!-- Form Start -->
          <?php include_once '../includes/tenant_form.php'; ?>

        </div>
    </main>
    <!--end::App Main-->

    <!--begin::Footer-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!-- end::footer -->

  </div>
  <!--end::App Wrapper-->

  <!-- plugin for pdf -->


  <!-- Main Js File -->

  <script src="../../../../landlord/assets/main.js"></script>

  <script src="js/main.js"></script>
  <!-- html2pdf depends on html2canvas and jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script type="module" src="./js/main.js"></script>
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



  <!-- Scripts -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!--end::Body-->

</html>