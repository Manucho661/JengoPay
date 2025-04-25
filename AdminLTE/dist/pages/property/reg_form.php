<?php
// Database connection
$host = "localhost";
$dbname = "bt_jengopay";
$username = "root";
$password = "";

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {


    function uploadPhoto($fileInput, $targetDir = "uploads/") {
      if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
          return null;
      }

      $fileName = basename($_FILES[$fileInput]["name"]);
      $targetFile = $targetDir . uniqid() . "_" . $fileName;

      if (!is_dir($targetDir)) {
          mkdir($targetDir, 0755, true);
      }

      if (move_uploaded_file($_FILES[$fileInput]["tmp_name"], $targetFile)) {
          return $targetFile;
      }

      return null;
  }



      // Collect building data
      $id = $_POST['building_id'];
      $building_name = $_POST['building_name'];
      $county = $_POST['county'];
      $constituency = $_POST['constituency'];
      $ward = $_POST['ward'];
      $floor_number = $_POST['floor_number'];
      $units_number = $_POST['units_number'];
      $building_type = $_POST['building_type'] ?? '';
      $ownership_info = $_POST['ownership_info'];
      $title_deed_copy=uploadPhoto('title_deed_copy');
      $other_document_copy = uploadPhoto('title_deed_copy');
      $borehole_availability = $_POST['borehole_availability'] ?? '';
      $solar_availability = $_POST['solar_availability'] ?? '';
      $solar_brand = $_POST['solar_brand'] ?? '';
      $installation_company = $_POST['installation_company'] ?? '';
      $no_of_panels = $_POST['no_of_panels'] ?? '';
      $solar_primary_use = $_POST['solar_primary_use'] ?? '';
      $parking_lot = $_POST['parking_lot'] ?? '';
      $alarm_system = $_POST['alarm_system'] ?? '';
      $elevators = $_POST['elevators'] ?? '';
      $psds_accessibility = $_POST['psds_accessibility'] ?? '';
      $cctv = $_POST['cctv'] ?? '';
      $insurance_cover = $_POST['insurance_cover'] ?? '';
      $insurance_policy = $_POST['insurance_policy'] ?? '';
      $insurance_provider = $_POST['insurance_provider'] ?? '';
      $policy_from_date = $_POST['policy_from_date'] ?? null;
      $policy_until_date = $_POST['policy_until_date'] ?? null;
      $front_view_photo = uploadPhoto('front_view_photo');
      $rear_view_photo = uploadPhoto('rear_view_photo');
      $angle_view_photo = uploadPhoto('angle_view_photo');
      $interior_view_photo = uploadPhoto('interior_view_photo');



      // Default values for ownership columns
      $first_name = $last_name = $phone_number = $kra_pin = $email = '';
      $entity_name = $entity_phone = $entity_email = $entity_kra_pin = $entity_representative = $entity_rep_role = '';

      if ($ownership_info == 'Individual') {
          $first_name = $_POST['first_name'];
          $last_name = $_POST['last_name'];
          $phone_number = $_POST['phone_number'];
          $kra_pin = $_POST['kra_pin'];
          $email = $_POST['email'];
      } elseif ($ownership_info == 'Entity') {
          $entity_name = $_POST['entity_name'];
          $entity_phone = $_POST['entity_phone'];
          $entity_email = $_POST['entity_email'];
          $entity_kra_pin = $_POST['entity_kra_pin'];
          $entity_representative = $_POST['entity_representative'];
          $entity_rep_role = $_POST['entity_rep_role'];
      }

      // File Uploads
      $titleDeedPath = $otherDocPath = '';

      if (isset($_FILES['title_deed_copy']) && $_FILES['title_deed_copy']['error'] === UPLOAD_ERR_OK) {
          $titleDeedPath = 'uploads/' . basename($_FILES['title_deed_copy']['name']);
          move_uploaded_file($_FILES['title_deed_copy']['tmp_name'], $titleDeedPath);
      }

      if (isset($_FILES['other_document_copy']) && $_FILES['other_document_copy']['error'] === UPLOAD_ERR_OK) {
          $otherDocPath = 'uploads/' . basename($_FILES['other_document_copy']['name']);
          move_uploaded_file($_FILES['other_document_copy']['tmp_name'], $otherDocPath);
      }

        // Approvals
        $nca_approval = $_POST['nca_approval'] ?? 'No';
        $nca_approval_no = $_POST['nca_approval_no'] ?? '';
        $nca_approval_date = $_POST['nca_approval_date'] ?? '';

        $local_gov_approval = $_POST['local_gov_approval'] ?? 'No';
        $local_gov_approval_no = $_POST['local_gov_approval_no'] ?? '';
        $local_gov_approval_date = $_POST['local_gov_approval_date'] ?? '';

        $nema_approval = $_POST['nema_approval'] ?? 'No';
        $nema_approval_no = $_POST['nema_approval_no'] ?? '';
        $nema_approval_date = $_POST['nema_approval_date'] ?? '';

        $building_tax_pin = $_POST['building_tax_pin'] ?? '';

      // Insert all data
      $sql = "INSERT INTO buildings (
                  building_name, county, constituency, ward, floor_number, units_number, building_type,
                  ownership_info,
                  first_name, last_name, phone_number, kra_pin, email,
                  entity_name, entity_phone, entity_email, entity_kra_pin, entity_representative, entity_rep_role,
                  title_deed_copy, other_document_copy ,borehole_availability, solar_availability, solar_brand, installation_company, no_of_panels, solar_primary_use,
                  parking_lot, alarm_system, elevators, psds_accessibility, cctv,  nca_approval, nca_approval_no, nca_approval_date,
                    local_gov_approval, local_gov_approval_no, local_gov_approval_date,
                    nema_approval, nema_approval_no, nema_approval_date,
                    building_tax_pin, insurance_cover, insurance_policy, insurance_provider, policy_from_date, policy_until_date,front_view_photo, rear_view_photo, angle_view_photo, interior_view_photo, building_number

              ) VALUES (
                  :building_name, :county, :constituency, :ward, :floor_number, :units_number, :building_type,
                  :ownership_info,
                  :first_name, :last_name, :phone_number, :kra_pin, :email,
                  :entity_name, :entity_phone, :entity_email, :entity_kra_pin, :entity_representative, :entity_rep_role,
                  :title_deed_copy, :other_document_copy , :borehole_availability, :solar_availability, :solar_brand, :installation_company, :no_of_panels, :solar_primary_use,
                  :parking_lot, :alarm_system, :elevators, :psds_accessibility, :cctv , :nca_approval, :nca_approval_no, :nca_approval_date,
                    :local_gov_approval, :local_gov_approval_no, :local_gov_approval_date,
                    :nema_approval, :nema_approval_no, :nema_approval_date,
                    :building_tax_pin,  :insurance_cover, :insurance_policy, :insurance_provider, :policy_from_date, :policy_until_date, :front_view_photo, :rear_view_photo, :angle_view_photo, :interior_view_photo,  :building_number
              )";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([
          ':building_name' => $building_name,
          ':county' => $county,
          ':constituency' => $constituency,
          ':ward' => $ward,
          ':floor_number' => $floor_number,
          ':units_number' => $units_number,
          ':building_type' => $building_type,
          ':ownership_info' => $ownership_info,
          ':first_name' => $first_name,
          ':last_name' => $last_name,
          ':phone_number' => $phone_number,
          ':kra_pin' => $kra_pin,
          ':email' => $email,
          ':entity_name' => $entity_name,
          ':entity_phone' => $entity_phone,
          ':entity_email' => $entity_email,
          ':entity_kra_pin' => $entity_kra_pin,
          ':entity_representative' => $entity_representative,
          ':entity_rep_role' => $entity_rep_role,
          ':title_deed_copy' => $titleDeedPath,
          ':other_document_copy' => $otherDocPath,
          ':borehole_availability' => $borehole_availability,
          ':solar_availability' => $solar_availability,
          ':solar_brand' => $solar_brand,
          ':installation_company' => $installation_company,
          ':no_of_panels' => $no_of_panels,
          ':solar_primary_use' => $solar_primary_use,
          ':parking_lot' => $parking_lot,
          ':alarm_system' => $alarm_system,
          ':elevators' => $elevators,
          ':psds_accessibility' => $psds_accessibility,
          ':cctv' => $cctv,
          ':nca_approval' => $nca_approval,
            ':nca_approval_no' => $nca_approval_no,
            ':nca_approval_date' => $nca_approval_date,
            ':local_gov_approval' => $local_gov_approval,
            ':local_gov_approval_no' => $local_gov_approval_no,
            ':local_gov_approval_date' => $local_gov_approval_date,
            ':nema_approval' => $nema_approval,
            ':nema_approval_no' => $nema_approval_no,
            ':nema_approval_date' => $nema_approval_date,
            ':building_tax_pin' => $building_tax_pin,
            ':insurance_cover' => $insurance_cover,
            ':insurance_policy' => $insurance_policy,
            ':insurance_provider' => $insurance_provider,
            ':policy_from_date' => $policy_from_date,
            ':policy_until_date' => $policy_until_date,
            ':front_view_photo' => $front_view_photo,
            ':rear_view_photo' => $rear_view_photo,
            ':angle_view_photo' => $angle_view_photo,
            ':interior_view_photo' => $interior_view_photo,
            ':building_number' => $building_number,

      ]);

      // echo "<p style='color:green;'>Data and files submitted successfully!</p>";
  }

// Query the building_identification table
$sql = "SELECT building_id, building_name, county, building_type, ownership_info FROM buildings";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all buildings
$buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);

 // Fetch property data
//   $stmt = $pdo->query("SELECT
//     building_identification.building_name,
//     building_identification.county,
//     building_identification.building_type FROM building_identification ORDER BY id DESC");

//  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//    $county = htmlspecialchars($row['county']);
//    $building_name = htmlspecialchars($row['building_name']);
//    $building_type = htmlspecialchars($row['building_type']);

//  }


} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>


<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>BT JENGOPAY |</title>
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
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="styling.css">

    <!-- scripts for data_table -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="announcements.css">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

        <style>
          body
          {
            font-size: 16px;
          }
        </style>
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
                        src="../../../dist/assets/img/user8-128x128.jpg"
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
                        src="../../../dist/assets/img/user3-128x128.jpg"
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
                  src="../../../dist/assets/img/user2-160x160.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">Alexander Pierce</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="../../../dist/assets/img/user2-160x160.jpg"
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
              src="../../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            /> -->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
           <span class="brand-text fw-dark">BT JENGOPAY</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div id="sidebar"></div> <!-- This is where the header will be inserted -->

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
              <div class="col-sm-6"><h3 class="mb-0"></h3></div>
              <div class="col-sm-6">
                <!-- <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#"></a></li>
                  <li class="breadcrumb-item active" aria-current="page"></li>
                </ol> -->
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!-- Indicators Section Start Here -->
  <div class="row mt-2" style="justify-content:center; align-items:center;">
    <!-- Step One Introduction Section -->
    <div class="col-md-1 text-center">
      <b class="shadow"
        style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:15px; padding-right:15px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;"
        id="stepOneIndicatorNo">1</b>
      <p class="mt-2" id="stepOneIndicatorText" style="font-size:13px;">
        Overview
      </p>
    </div>
    <!-- Step Two Building Identification Details -->
    <div class="col-md-1 text-center">
      <b class="shadow"
        style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:15px; padding-right:15px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;"
        id="stepTwoIndicatorNo">2</b>
      <p class="mt-2" id="stepTwoIndicatorText" style="font-size:13px;">
        Identification</p>
    </div>
    <!-- Step Three Ownership Information -->
    <div class="col-md-1 text-center">
      <b class="shadow"
        style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:15px; padding-right:15px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;"
        id="stepThreeIndicatorNo">3</b>
      <p class="mt-2" id="stepThreeIndicatorText" style="font-size:13px;">
        Ownership
      </p>
    </div>
    <!-- Step 4 Utilities and Infrastructure -->
    <div class="col-md-1 text-center">
      <b class="shadow"
        style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:15px; padding-right:15px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;"
        id="stepFourIndicatorNo">4</b>
      <p class="mt-2" id="stepFourIndicatorText" style="font-size:13px;">
        Utilities
      </p>
    </div>
    <!-- Step Five Legal and Regulatory Details -->
    <div class="col-md-1 text-center">
      <b class="shadow"
        style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:15px; padding-right:15px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;"
        id="stepFiveIndicatorNo">5</b>
      <p class="mt-2" id="stepFiveIndicatorText" style="font-size:13px;">
        Regulations
      </p>
    </div>
    <!-- Step Six Insurance and Financial Information -->
    <div class="col-md-1 text-center">
      <b class="shadow"
        style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:15px; padding-right:15px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;"
        id="stepSixIndicatorNo">6</b>
      <p class="mt-2" id="stepSixIndicatorText" style="font-size:13px;">
        Insurance
      </p>
    </div>
    <!-- Step Seven Photographs -->
    <div class="col-md-1 text-center">
      <b class="shadow"
        style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:15px; padding-right:15px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;"
        id="stepSevenIndicatorNo">7</b>
      <p class="mt-2" id="stepSevenIndicatorText" style="font-size:13px;">
        Photos</p>
    </div>
    <!-- Step Eight Confirmation and Submission -->
    <div class="col-md-1 text-center">
      <b class="shadow"
        style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:15px; padding-right:15px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;"
        id="stepEightIndicatorNo">8</b>
      <p class="mt-2" id="stepEightIndicatorText" style="font-size:13px;">
        Confirmation
      </p>
    </div>
  </div>
  <!-- Section One Overview Starts Here -->
  <div class="card" id="sectionOne">
    <div class="card-header">
      <b>Brief Overview</b>
    </div>
    <div class="card-body text-center p-3">
      <p>Welcome to Biccount Property Registration Section. We'll collect
        some
        information regarding your property. This is essential for the
        correct
        record keeping, tracking and decision making for all the
        stakeholders
        including Landlord, Property Managers, Tenants Third Party
        Service
        Provides to mention but a few. Click Next to start the
        Registration
        process.</p>
    </div>
    <div class="card-footer text-right">
      <button type="button" class="btn btn-sm next-btn" id="stepOneNextBtn">Next</button>
    </div>
  </div>
  <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
    <!-- Section Two Building Identification Information -->
    <div class="card" id="sectionTwo">
      <div class="card-header">
        <b>Building Identification</b>
      </div>
      <div class="card-body">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Building Name</label> <sup class="text-danger"><b>*</b></sup>
                                  <input type="text" class="form-control" id="buildingName" name="building_name"
                                    placeholder="Building Name">
                                </div>
                              </div>
                            </div>
                            <h5 class="text-center" style="font-weight: bold;">Location
                              Information</h5>
                            <div class="row">
                              <div class="col-12 col-sm-4">
                                <div class="form-group">
                                  <label>County</label>
                                  <select name="county" id="county" onchange="loadConstituency()"  class="form-control select2 select2-danger"
                                    data-dropdown-css-class="select2-danger"
                                    style="width: 100%; height:300px !important;">
                                    <option value="" hidden selected>-- Select Option --</optio>
                                    <option>Mombasa</option>
                                    <option>Kwale</option>
                                    <option>Kilifi</option>
                                    <option>Tana River</option>
                                    <option>Lamu</option>
                                    <option>Taita Taveta</option>
                                    <option>Garissa</option>
                                    <option>Wajir</option>
                                    <option>Mandera</option>
                                    <option>Marsabit</option>
                                    <option>Isiolo</option>
                                    <option>Meru</option>
                                    <option>Tharaka-Nithi</option>
                                    <option>Embu</option>
                                    <option>Kitui</option>
                                    <option>Machakos</option>
                                    <option>Makueni</option>
                                    <option>Nyandarua</option>
                                    <option>Nyeri</option>
                                    <option>Kirinyaga</option>
                                    <option>Murang'a</option>
                                    <option>Kiambu</option>
                                    <option>Turkana</option>
                                    <option>West Pokot</option>
                                    <option>Samburu</option>
                                    <option>Trans Nzoia</option>
                                    <option>Uasin Gishu</option>
                                    <option>Elgeyo/Marakwet</option>
                                    <option>Nandi</option>
                                    <option>Baringo</option>
                                    <option>Laikipia</option>
                                    <option>Nakuru</option>
                                    <option>Narok</option>
                                    <option>Kajiado</option>
                                    <option>Kericho</option>
                                    <option>Bomet</option>
                                    <option>Kakamega</option>
                                    <option>Vihiga</option>
                                    <option>Bungoma</option>
                                    <option>Busia</option>
                                    <option>Siaya</option>
                                    <option>Kisumu</option>
                                    <option>Homa Bay</option>
                                    <option>Migori</option>
                                    <option>Kisii</option>
                                    <option>Nyamira</option>
                                    <option>Nairobi</option>

                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Constituency</label>
                                  <select name="constituency" id="constituency" onchange="loadWard()" class="form-control" required>
                                    <option value="" selected hidden>-- Choose
                                      Constituency
                                      --</option>
                                    <option value="Nairobi">Westlands</option>
                                    <option value="Kisumu">Starehe</option>
                                    <option value="Vihiga">Embakasi</option>
                                  </select>
                                  <b class="errorMessages" id="constituencyError"></b>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Ward</label>
                                  <select name="ward" id="ward"    class="form-control">
                                    <option value="" selected hidden>-- Choose Ward
                                      --
                                    </option>
                                    <option value="Nairobi">Kangemi</option>
                                    <option value="Kisumu">Kiambu</option>
                                    <option value="Vihiga">Pipeline</option>
                                  </select>
                                  <b class="errorMessages" id="wardError"></b>
                                </div>
                              </div>
                            </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Number of Floors</label>
              <input type="text" class="form-control" name="floor_number" id="floorNumber"
                placeholder="Number of Floors">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Number of Units</label>
              <input type="text" class="form-control" id="unitsnumber" name="units_number" placeholder="Number of Units">
            </div>
          </div>
          <div class="col-md-4">
            <label>Building Type</label>
            <select name="building_type" id="buildingType" class="form-control">
              <option value="" selected hidden>--Select Building
                Type--</option>
              <option value="Residential">Residential</option>
              <option value="Commercial">Commercial</option>
              <option value="Commercial">Commercial</option>
              <option value="Industrial">Industrial</option>
              <option value="Industrial">Industrial</option>
              <option value="Mixed-Use">Mixed-Use</option>
            </select>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepTwoBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepTwoNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Three Ownership Information -->
    <div class="card" id="sectionThree">
      <div class="card-header">
        <b>Ownership Information</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Building Owned By</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="ownership_info" id="showIndividualOwnerRadio"
                      onclick="showIndividualOwner();" value="Individual">
                    <label for="showIndividualOwnerRadio"> Individual
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="ownership_info" id="showEntityOwnerRadio"
                      onclick="showEntityOwner();" value="Entity" value="Individual">
                    <label for="showEntityOwnerRadio"> Entity
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div id="individualInfoDiv" style="display: none;">
              <div class="card">
                <div class="card-header"><b>Enter Individual's
                    Information</b></div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" id="firstName"
                          placeholder="First Name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" id="lastName"
                          placeholder="Last Name">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" id="phoneNumber"
                     pattern="07[0-9]{8}" maxlength="10"
                      placeholder="Phone Number">
                  </div>
                  <div class="form-group">
                    <label>Kra pin</label>
                    <input type="text" name="kra_pin" class="form-control" id="kra_pin"
                      placeholder="Kra pin">
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email"  name="email" class="form-control" id="ownerEmail" placeholder="Email">
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button type="button" class="btn btn-sm"
                    style="background-color: #cc0001; color:#fff;"
                    id="individualCloseBtn">Close</button>
                </div>
              </div>
            </div>
            <div id="entityInfoDiv" style="display: none;">
              <div class="card">
                <div class="card-header"><b>Enter Entity's
                    Information</b></div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Entity
                          Name</label>
                        <input type="text" name="entity_name" class="form-control" id="entityName"
                          placeholder="Entity Name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Official Phone
                          Number</label>
                        <input type="text" name="entity_phone" class="form-control" id="entityPhone"
                          placeholder="Official Phone Number">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Official Email</label>
                    <input type="text"  name="entity_email" class="form-control" id="entityEmail"
                      placeholder="Entity Email">
                  </div>
                  <div class="form-group">
                    <label>Kra pin</label>
                    <input type="text" name="entity_kra_pin" class="form-control" id="kra_pin"
                      placeholder="Kra pin">
                  </div>
                  <div class="form-group">
                    <label>Entity
                      Representative</label>
                    <input type="text" name="entity_representative" class="form-control" id="entityRepresentative"
                      placeholder="Entity Representative">
                  </div>

                  <div class="form-group">
                    <label>Role</label>
                    <select name="entity_rep_role" id="entityRepRole" class="form-control">
                      <option value="" selected hidden>
                        --Select Role --</option>
                      <option value="CEO">CEO</option>
                      <option value="Treasury">Treasury
                      </option>
                      <option value="Board Member">Board
                        Member</option>
                      <option value="Signatory">Signatory
                      </option>
                      <option value="Founder">Founder
                      </option>
                      <option value="Co-Founder">
                        Co-Founder</option>
                    </select>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-sm" id="entityCloseDivBtn"
                    style="background-color: #cc0001; color:#fff">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <label>Title Deed Copy</label>
                  <input type="file" onchange="handleFiles(event)" class="form-control" id="titleDeedCopy">

                  <!-- Section to display selected files' previews and sizes -->
                  <div id="filePreviews"></div>

                </div>
                <div class="col-md-6">
                  <label>Other Legal Document</label>
                  <input type="file" onchange="handleFiles(event)" class="form-control" id="otherDocumentCopy">

                  <!-- Section to display selected files' previews and sizes -->
                  <div id="filePreviews"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepThreeBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepThreeNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Four Utilities and Infrastructure -->
    <div class="card" id="sectionFour">
      <div class="card-header"><b>Utilities and Infrastructure</b></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Is there a Borehole?</label>
              <select name="borehole_availability" id="boreHoleVailability" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Do you Have Solar System?</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="solar_availability" id="solarAvailabilityYes"
                      onclick="specifySolarProvider();" value="Yes">
                    <label for="solarAvailabilityYes"> Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="solar_availability" id="solarAvailabilityNo"
                      onclick="hideSolarProvider();" value="No">
                    <label for="solarAvailabilityNo"> No
                    </label>
                  </div>
                </div>
              </div>
              <div class="card mt-2" id="specifySolarPrivider" style="display: none;">
                <div class="card-header"><b>Please Specify</b></div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Solar Panel Brand</label>
                    <input type="text" name="solar_brand" class="form-control" id="solarBrand"
                      placeholder="Solar Brand">
                  </div>
                  <div class="form-group">
                    <label>Installation Company</label>
                    <input type="text" name="installation_company" class="form-control" id="installationCompany"
                      placeholder="Installation Company">
                  </div>
                  <div class="form-group">
                    <label>Number of Panels</label>
                    <input type="text" name="no_of_panels" class="form-control" id="noOfPanels"
                      placeholder="Number of Panels">
                  </div>
                  <div class="form-group">
                    <label>Primary Use</label>
                    <select name="solar_primary_use" id="solarPrimaryUse" class="form-control">
                      <option value="" selected hidden>-- Select Option --</option>
                      <option value="Lighting">Lighting</option>
                      <option value="Water Heating">Water Heating</option>
                      <option value="Power Backup">Power Backup</option>
                      <option value="Multi-Purpose">Multi-Purpose</option>
                    </select>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-sm" type="button"
                    style="background-color: #cc0001; color:#fff;"
                    id="closeSolarProviderBtn">Close</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Is there Parking Lot?</label>
              <select name="parking_lot" id="parkingLot" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Is there Alarm Security System?</label>
              <select name="alarm_system" id="alarmSystem" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Is there Elevator(s)?</label>
              <select name="elevators" id="elevators" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Is there PSD's Accessibility?</label>
              <select name="psds_accessibility" id="psds" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Is there CCTV?</label>
              <select name="cctv" id="cctv" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepFourBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepFourNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Five Legal and Regulatory Details -->
    <div class="card" id="sectionFive">
      <div class="card-header"><b>Legal and Regulatory Details</b></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Do you have NCA Approval</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="nca_approval" onclick="attachNcaApproval();"
                      value="Yes" id="showNcaContents">
                    <label for="showNcaContents"> Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="nca_approval" onclick="closeAttachNcaApproval();"
                      value="No" id="noNcaContent">
                    <label for="noNcaContent"> No
                    </label>
                  </div>

                </div>
              </div>
            </div>
            <div class="card" id="ncaApprivalCard" style="display:none;">
              <div class="card-header"><b>Construction Approval</b></div>
              <div class="card-body">
                <div class="form-group">
                  <label>Approval Number</label>
                  <input type="text" name="nca_approval_no"  class="form-control" id="approvalNo"
                    placeholder="Approval Number">
                </div>
                <div class="form-group">
                  <label>Approval Date</label>
                  <input type="date" name="date" class="form-control" id="approvalDate">
                </div>
                <div class="formm-control">
                  <label>NCA Approval Copy</label>
                  <input type="file"  class="form-control" id="ncaApprovalCopy">
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-sm" id="closeNcaApprovalBtn" type="button"
                  style="background-color: #cc0001; color:#fff;">Close</button>
              </div>
            </div>
            <div class="form-group">
              <label>Do You a Local Government Approval</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="local_gov_approval"
                      onclick="showLocalGovernmentApproval();" value="Yes" id="localGovApproval">
                    <label for="localGovApproval"> Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="local_gov_approval"
                      onclick="hideLocalGovernmentApproval();" value="Yes" id="noLocalGov">
                    <label for="noLocalGov"> No
                    </label>
                  </div>
                </div>
              </div>
              <div class="card" id="localGovSpecifications" style="display: none;">
                <div class="card-header"><b>Local Government Approval Details</b></div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Approval Number</label>
                    <input type="text" name="local_gov_approval_no" class="form-control" id="localGovApprovalNo">
                  </div>
                  <div class="form-group">
                    <label>Approval Date</label>
                    <input type="date" name="local_gov_approval_date" class="form-control" id="localGovApprovalDate">
                  </div>
                  <div class="form-group">
                    <label>Approval Copy</label>
                    <input type="file" class="form-control" id="localGovApprovalCopy">
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-sm" id="closeLocalGovSpecifications" type="button"
                    style="background-color: #cc0001; color:#fff;">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Do you have NEMA Approval</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="nema_approval" onclick="nemaApprovalShow();"
                      id="nemaApprovalYes" value="Yes">
                    <label for="nemaApprovalYes"> Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="nema_approval" onclick="nemaApprovalHide();"
                      id="nemaApprovalNo" value="No">
                    <label for="nemaApprovalNo"> No
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="card" id="nemaApprovalSpecify" style="display: none;">
              <div class="card-header"><b>NEMA Approval Specifications</b></div>
              <div class="card-body">
                <div class="form-group">
                  <label>Approval Number</label>
                  <input type="text"  name="nema_approval_no" class="form-control" id="nemaApprovalNumber"
                    placeholder="Approval Number">
                </div>
                <div class="form-group">
                  <label>Approval Date</label>
                  <input type="date" name="nema_approval_date" class="form-control" id="nemaApprovalDate">
                </div>
                <div class="form-group">
                  <label>Approval Copy</label>
                  <input type="file"  class="form-control" id="nemaApprovalCopy">
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-sm" id="closeNemaApproval" type="button"
                  style="background-color: #cc0001; color:#fff;">Close</button>
              </div>
            </div>
            <div class="form-group">
              <label>TAX PIN for the Building</label>
              <input type="text" name="building_tax_pin" class="form-control" id="buildingTaxPin"
                placeholder="TAX PIN for the Building">
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepFiveBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepFiveNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Six Insurance Information -->
    <div class="card" id="sectionSix">
      <div class="card-header"><b>Insurance and Financial Information</b></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Do your Building have Insurance Cover?</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="insurance_cover" id="yesInsurance"
                      onclick="insuranceCoverYes();" value="Yes">
                    <label for="yesInsurance"> Yes
                  </div>

                </div>
                <div class="col-md-6">
                <div class="icheck-dark d-inline">
                    <input type="radio" name="insurance_cover" id="noInsurance"
                      onclick="insuranceCoverYes();" value="No">
                    <label for="noInsurance"> No
                  </div>


                </div>
              </div>
              <div class="card mt-2" id="specifyInsuranceCoverInfoCard" style="display: none;">
                <div class="card-header"><b>Insurance Cover Details</b></div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Specify Insurance Policy</label>
                    <input type="text" name="insurance_policy" class="form-control" id="insurance_policy"
                      placeholder="Insurance Policy">
                  </div>
                  <div class="form-group">
                    <label>Insurance Policy Provider</label>
                    <input type="text" class="form-control" name="insurance_provider" id="insurance_provider"
                      placeholder="Insurance Policy Provider">
                  </div>
                  <div class="form-group">
                    <label>Covered From</label>
                    <input type="date" class="form-control"  name="policy_from_date"  id="policy_from_date">
                  </div>
                  <div class="form-group">
                    <label>Covered Until</label>
                    <input type="date" name="policy_until_date" class="form-control" id="policy_until_date">
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-sm" id="closeInsuranceInfoBtn"
                    style="background-color: #cc0001; color:#fff">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6"></div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepSixBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepSixNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Seven Photos -->
    <div class="card" id="sectionSeven">
      <div class="card-header"><b>Photographs and Documentations</b></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Front View</label>
              <input type="file" class="form-control"  name="front_view_photo" id="front_view_photo">
            </div>
            <div class="form-group">
              <label>Rear View</label>
              <input type="file" class="form-control" name="rear_view_photo" id="front_view_photo">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Angle View</label>
              <input type="file" class="form-control" name="angle_view_photo" id="angle_view_photo">
            </div>
            <div class="form-group">
              <label>Interior</label>
              <input type="file" class="form-control" name="interior_view_photo" id="interior_view_photo">
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepSevenBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepSevenNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Eight Confirmation -->
    <div class="card" id="sectionEight">
      <div class="card-header"><b>Confirmation</b></div>
      <div class="card-body text-center">
        <input type="checkbox" required> I here by confirm that all the
        information filled in this form is accurare. I therefore issue my
        consent to Biccount Technologies to go ahead and register my rental
        property for further property management services that I will be
        receiving.
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepEightBackBtn">Back</button>
        <button type="submit" class="btn btn-sm next-btn" id="stepEightNextBtn">Submit</button>
      </div>
    </div>
  </form>

      <!-- Specify Solar Avilability DOM -->


      <hr>
      <!--begin::Row-->
<div class="row">
<div class="col-md-12">
<div class="card mb-4">
 <div class="card-header">
   <h5 class="card-title text-warning" style="font-size: 20px; font-weight: bold;">Registered Buildings</h5>
   <div class="card-tools">
     <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
       <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
       <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
     </button>
     <div class="btn-group">
       <button type="button" class="btn btn-tool dropdown-toggle" data-bs-toggle="dropdown">
         <i class="bi bi-wrench"></i>
       </button>
       <div class="dropdown-menu dropdown-menu-end" role="menu">
         <a href="#" class="dropdown-item">Action</a>
         <a href="#" class="dropdown-item">Another action</a>
         <a href="#" class="dropdown-item">Something else here</a>
         <a class="dropdown-divider"></a>
         <a href="#" class="dropdown-item">Separated link</a>
       </div>
     </div>
     <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
       <i class="bi bi-x-lg"></i>
     </button>
   </div>
 </div>
 <!-- /.card-header -->
 <div class="card-body">
   <div class="row">
     <table id="myTableOne" class="display table table-striped table-hover" style="width: 100%; font-size: 16px;">
       <thead class="table-dark">
         <tr>
           <th>Property</th>
           <th >Location</th>
           <th >Tenant</th>
           <th >Ownership Information</th>
           <th >Building Type</th>
           <th >Action</th>
         </tr>
       </thead>
       <tbody>
       <?php foreach ($buildings as $building): ?>
    <tr onclick="window.open('Units.php?building_id=<?= $building['building_id'] ?>', '_blank')">
    <td><?= htmlspecialchars($building['building_name'])?></td>
    <td><?= htmlspecialchars($building['county'])?></td>
    <td>Patrick Musila</td> <!-- Replace with dynamic tenant if needed -->
    <td><?=htmlspecialchars($building['ownership_info'])?></td> <!-- Manager goes here -->
    <td><?= htmlspecialchars($building['building_type']) ?></td>
    <td>
      <button class="btn btn-sm" style="background-color: #193042; color:#fff; margin-right: 2px;" data-toggle="modal" data-target="#assignPlumberModal" title="View">
        <i class="fas fa-eye"></i>
      </button>
      <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Assign this Task to a Plumbing Service Provider">
        <i class="fa fa-trash" style="font-size: 12px;"></i>
      </button>
    </td>
  </tr>
<?php endforeach; ?>

       </tbody>
     </table>

                    <!--end::Row-->
                  </div>
                  <!-- ./card-body -->
                  <div class="card-footer">
                    <!--begin::Row-->

                      <!-- /.col -->

                    <!--end::Row-->
                  </div>
                  <!-- /.card-footer -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>
            <!-- End mantainance row -->



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
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->


    <script>
const data = {
 "Mombasa": {
    "Changamwe": ["Port Reitz", "Kipevu", "Airport", "Changamwe", "Chaani"],
    "Jomvu": ["Jomvu Kuu", "Miritini", "Mikindani"],
    "Kisauni": ["Mjambere", "Junda", "Bamburi", "Mwakirunge", "Mtopanga", "Magogoni"],
    "Nyali": ["Frere Town", "Ziwa la Ngombe", "Mkomani", "Kongowea", "Kadzandani"],
    "Likoni": ["Mtongwe", "Shika Adabu", "Bofu", "Likoni", "Timbwani"],
    "Mvita": ["Ganjoni", "Majengo", "Tudor", "Tononoka", "Shimanzi/Ganjoni"]
  },
  "Kwale": {
    "Msambweni": ["Gombato Bongwe", "Ukunda", "Kinondo", "Ramisi"],
    "Lunga Lunga": ["Dzombo", "Pongwe/Kikoneni", "Mwereni", "Vanga"],
    "Matuga": ["Tsimba Golini", "Waa", "Tiwi", "Kubo South", "Mkongani"],
    "Kinango": ["Ndavaya", "Puma", "Kinango", "Chengoni/Samburu", "Mackinnon Road", "Mwavumbo"]
  },
  "Kilifi": {
    "Kilifi North": ["Tezo", "Sokoni", "Kibarani", "Dabaso", "Matsangoni", "Watamu", "Mnarani"],
    "Kilifi South": ["Junju", "Mwarakaya", "Shimo la Tewa", "Chasimba", "Mtepeni", "Madzimboni"],
    "Kaloleni": ["Kayafungo", "Kaloleni", "Mwanamwinga", "Rabai/Kambe"],
    "Rabai": ["Ruruma", "Rabai/Kisurutini", "Mwawesa", "Ruruma"],
    "Ganze": ["Sokoke", "Bamba", "Jaribuni", "Ganze"],
    "Malindi": ["Ganda", "Malindi Town", "Shella", "Jilore", "Kakuyuni"],
    "Magarini": ["Marafa", "Magarini", "Gongoni", "Adu", "Garashi", "Sabaki"]
  },
  "Tana River": {
    "Bura": ["Chewele", "Hirimani", "Bangale", "Madogo", "Bura"],
    "Galole": ["Kinakomba", "Mikinduni", "Chewani", "Wayu"],
    "Garsen": ["Garsen Central", "Garsen South", "Garsen North", "Kipini East", "Kipini West"]
  },
  "Lamu": {
    "Lamu East": ["Faza", "Kiunga", "Basuba"],
    "Lamu West": ["Shella", "Mkomani", "Hindi", "Mkunumbi", "Hongwe", "Bahari", "Witu"]
  },
  "Taita Taveta": {
    "Taveta": ["Chala", "Mahoo", "Bomani", "Mboghoni"],
    "Wundanyi": ["Wumingu/Kishushe", "Mwanda/Mgange", "Wundanyi/Mbale", "Werugha"],
    "Mwatate": ["Ronge", "Mwatate", "Bura", "Chawia"],
    "Voi": ["Mbololo", "Ngolia", "Kasigau", "Sagalla"]
  },
  "Garissa": {
    "Garissa Township": ["Waberi", "Galbet", "Township", "Iftin"],
    "Balambala": ["Balambala", "Saka", "Sankuri"],
    "Lagdera": ["Modogashe", "Benane", "Goreale", "Maalimin", "Sabena", "Baraki"],
    "Dadaab": ["Dadaab", "Labasigale", "Damajale", "Liboi", "Abakaile"],
    "Fafi": ["Bura", "Dekaharia", "Jarajila", "Nanighi", "Fafi"],
    "Ijara": ["Masalani", "Sangailu", "Ijara", "Hulugho"]
  },
  "Wajir": {
    "Wajir North": ["Gurar", "Bute", "Korondile", "Malkagufu", "Batalu", "Danaba"],
    "Wajir East": ["Wagberi", "Township", "Barwago", "Khorof/Harar"],
    "Tarbaj": ["Elben", "Sarman", "Tarbaj", "Wargadud"],
    "Wajir West": ["Arbajahan", "Hadado/Athibohol", "Ademasajide", "Ganyure/Wagalla"],
    "Eldas": ["Elnur/Tula Tula", "Della", "Lakoley South/Basir", "Eldas"],
    "Wajir South": ["Benane", "Habasswein", "Lagboghol South", "Burder", "Dadaja Bulla", "Ibrahim Ure"]
  },
  "Mandera": {
    "Mandera West": ["Takaba South", "Takaba", "Lagsure", "Gither"],
    "Banissa": ["Banissa", "Derkhale", "Guba", "Malkamari", "Malkamari"],
    "Mandera North": ["Ashabito", "Guticha", "Rhamu", "Rhamu Dimtu", "Morothile"],
    "Mandera South": ["Wargadud", "Kutulo", "Elwak South", "Elwak North", "Shimbir Fatuma"],
    "Mandera East": ["Neboi", "Township", "Khalalio", "Libehia", "Sala"],
    "Lafey": ["Fino", "Lafey", "Waranqara", "Alango Gof", "Sala"]
  },
  "Marsabit": {
    "Moyale": ["Butiye", "Sololo", "Heillu/Manyatta", "Uran", "Obbu"],
    "North Horr": ["Dukana", "Maikona", "Turbi", "Illeret"],
    "Saku": ["Sagam", "Karare", "Marsabit Central"],
    "Laisamis": ["Laisamis", "Korr/Ngurunit", "Logologo", "Loiyangalani"]
  },
  "Isiolo": {
    "Isiolo North": ["Wabera", "Bulla Pesa", "Chari", "Cherab", "Ngare Mara", "Burat", "Oldonyiro"],
    "Isiolo South": ["Garbatulla", "Kinna", "Sericho"]
  },
  "Tharaka-Nithi": {
    "Chuka/Igambang'ombe": ["Chuka", "Igambang'ombe", "Karingani", "Magumoni"],
    "Maara": ["Mitheru", "Muthambi", "Mwimbi", "Ganga"],
    "Tharaka": ["Chiakariga", "Marimanti", "Mukothima", "Gatunga"]
  },
  "Embu": {
    "Manyatta": ["Kithimu", "Ngandori East", "Ngandori West", "Central Ward"],
    "Runyenjes": ["Kyeni North", "Kyeni South", "Gaturi South", "Gaturi North"],
    "Mbeere North": ["Muminji", "Nthawa", "Mavuria"],
    "Mbeere South": ["Makima", "Mwea", "Kiambere"]
  },
  "Meru": {
  "Igembe South": ["Athiru Gaiti", "Akachiu", "Kanuni", "Maua", "Kiegoi/Antubochiu"],
  "Igembe Central": ["Akirang'ondu", "Athiru", "Antuambui", "Njia", "Kangeta"],
  "Igembe North": ["Ntunene", "Antuanga", "Antubetwe Kiongo", "Naathu", "Amwathi"],
  "Tigania West": ["Athwana", "Akithii", "Kianjai", "Nkomo", "Mbeu"],
  "Tigania East": ["Thangatha", "Mikinduri", "Karama", "Muthara", "Kiguchwa"],
  "North Imenti": ["Municipality", "Ntima East", "Ntima West", "Nyaki East", "Nyaki West"],
  "Buuri": ["Timau", "Kisima", "Kiirua/Naari", "Ruiri/Rwarera", "Kibirichia"],
  "Central Imenti": ["Mwanganthia", "Abothuguchi Central", "Abothuguchi West", "Kiagu"],
  "South Imenti": ["Mitunguu", "Igoji East", "Igoji West", "Abogeta East", "Abogeta West", "Nkuene"]
},
  "Kitui": {
    "Kitui Central": ["Miambani", "Township", "Kyangwithya East", "Kyangwithya West"],
    "Kitui East": ["Nzambani", "Chuluni", "Mutito", "Endau/Malalani"],
    "Kitui South": ["Ikanga/Kyatune", "Mutomo", "Mutha", "Ikutha"],
    "Kitui West": ["Mutonguni", "Matinyani", "Kisasi", "Kwa Mutonga/Kithumula"],
    "Mwingi Central": ["Central", "Kivou", "Nguni", "Nuu"],
    "Mwingi North": ["Kyuso", "Ngomeni", "Tharaka", "Mumoni"],
    "Mwingi West": ["Migwani", "Kiomo/Kyethani", "Nzeluni", "Waita"]
  },
  "Kiambu": {
    "Gatundu North": ["Gituamba", "Githobokoni", "Chania", "Mang'u"],
    "Gatundu South": ["Kiamwangi", "Kiganjo", "Ndarugo", "Ngenda"],
    "Githunguri": ["Githunguri", "Githiga", "Ikinu", "Ngewa", "Komothai"],
    "Juja": ["Murera", "Theta", "Juja", "Witeithie", "Kalimoni"],
    "Kabete": ["Gitaru", "Muguga", "Nyathuna", "Kabete", "Uthiru"],
    "Kiambaa": ["Cianda", "Karuri", "Ndenderu", "Muchatha", "Kihara"],
    "Kiambu Town": ["Ting'ang'a", "Ndumberi", "Riabai", "Township"],
    "Kikuyu": ["Karai", "Nachu", "Sigona", "Kikuyu", "Kinoo"],
    "Limuru": ["Bibirioni", "Limuru Central", "Ndeiya", "Limuru East", "Ngecha/Tigoni"],
    "Lari": ["Kinale", "Kijabe", "Nyanduma", "Kamburu", "Lari/Kirenga"],
    "Ruiru": ["Gitothua", "Biashara", "Gatongora", "Kahawa/Sukari", "Kahawa Wendani", "Kiuu", "Mwiki", "Mwihoko"],
    "Thika Town": ["Township", "Kamenu", "Hospital", "Gatuanyaga", "Ngoliba"]
  },
  "Machakos": {
    "Machakos Town": ["Kalama", "Mua", "Mutituni", "Mumbuni North"],
    "Mwala": ["Masii", "Muthetheni", "Wamunyu", "Kibauni"],
    "Kangundo": ["Kangundo North", "Kangundo Central", "Kangundo East", "Kangundo South"],
    "Matungulu": ["Matungulu North", "Matungulu East", "Matungulu West", "Tala"],
    "Kathiani": ["Mitaboni", "Kathiani Central", "Upper Kaewa/Iveti", "Lower Kaewa/Kaani"],
    "Mavoko": ["Athi River", "Kinanie", "Mlolongo", "Syokimau/Mulolongo"],
    "Yatta": ["Ndalani", "Matuu", "Kithimani", "Katangi"]
  },
  "Makueni": {
    "Makueni": ["Wote", "Mavindini", "Kathonzweni", "Mbitini"],
    "Kibwezi East": ["Masongaleni", "Mtito Andei", "Thange", "Ivingoni/Nzambani"],
    "Kibwezi West": ["Makindu", "Nguumo", "Nguu/Masumba", "Kikumbulyu North", "Kikumbulyu South"],
    "Kaiti": ["Kilungu", "Ilima", "Ukia", "Kee"],
    "Kilome": ["Mukaa", "Kasikeu", "Kiima Kiu/Kalanzoni"],
    "Mbooni": ["Tulimani", "Mbooni", "Kithungo/Kitundu", "Kisau/Kithuki"]
  },
  "Nyandarua": {
    "Ol Kalou": ["Githioro", "Karau", "Mirangine", "Rurii"],
    "Ol Jorok": ["Gathanji", "Gatimu", "Weru", "Charagita"],
    "Kinangop": ["Engineer", "Githabai", "North Kinangop", "Murungaru"],
    "Kipipiri": ["Wanjohi", "Kipipiri", "Geta", "Githioro"],
    "Ndaragwa": ["Leshau Pondo", "Kiriita", "Central", "Shamata"]
  },
  "Nyeri": {
    "Tetu": ["Dedan Kimathi", "Aguthi-Gaaki", "Wamagana"],
    "Kieni": ["Mweiga", "Naromoru/Kiamathaga", "Gatarakwa", "Thegu River"],
    "Mathira": ["Karatina Town", "Ruguru", "Iriaini", "Konyu"],
    "Othaya": ["Chinga", "Iriaini", "Karima", "Mahiga"],
    "Mukurweini": ["Gikondi", "Rugi", "Mukurweini Central", "Mukure"],
    "Nyeri Town": ["Rware", "Kamakwa/Mukaro", "Kiganjo/Mathari", "Gatitu/Muruguru"]
  },
  "Kirinyaga": {
    "Mwea": ["Mutithi", "Tebere", "Wamumu", "Thiba"],
    "Gichugu": ["Kabare", "Ngariama", "Karumandi", "Njukiini"],
    "Ndia": ["Mukure", "Kiine", "Kariti", "Baragwi"],
    "Kirinyaga Central": ["Inoi", "Mutira", "Kerugoya", "Kanyekini"]
  },
  "Murang'a": {
    "Kangema": ["Kanyenyaini", "Muguru", "Rwathia"],
    "Mathioya": ["Gitugi", "Kiru", "Kamacharia"],
    "Kiharu": ["Mugoiri", "Mbiri", "Township", "Kimathi"],
    "Kigumo": ["Kangari", "Kinyona", "Kagunduini", "Kagaa"],
    "Maragua": ["Ichagaki", "Kimorori/Wempa", "Makuyu", "Kambiti"],
    "Kandara": ["Gaichanjiru", "Ng'araria", "Muruka", "Ithiru"],
    "Gatanga": ["Kakuzi/Mitubiri", "Kihumbuini", "Kariara", "Kang'ari"]
  },
  "Kiambu": {
    "Gatundu South": ["Kiamwangi", "Kiganjo", "Ng'enda", "Ndarugu"],
    "Gatundu North": ["Gituamba", "Mang'u", "Chania", "Kahuguini"],
    "Juja": ["Theta", "Witeithie", "Murera", "Juja", "Kalimoni"],
    "Thika Town": ["Township", "Kamenu", "Hospital", "Gatuanyaga", "Ngoliba"],
    "Ruiru": ["Biashara", "Gitothua", "Gatongora", "Kahawa Sukari", "Kahawa Wendani", "Mwiki", "Mwihoko", "Kiuu"],
    "Githunguri": ["Githunguri", "Githiga", "Ikinu", "Komothai", "Komo"],
    "Kiambu": ["Ting'ang'a", "Ndumberi", "Riabai", "Kiambu Township"],
    "Kiambaa": ["Cianda", "Karuri", "Ndenderu", "Muchatha", "Kihara"],
    "Kikuyu": ["Kikuyu", "Sigona", "Karai", "Nachu", "Kinoo"],
    "Kabete": ["Uthiru", "Kabete", "Gitaru", "Muguga", "Nyathuna"],
    "Limuru": ["Limuru Central", "Limuru East", "Ngecha-Tigoni", "Biberioni", "Ndeiya"],
    "Lari": ["Kinale", "Kamburu", "Nyanduma", "Kijabe", "Lari"]
  },
  "Turkana": {
    "Turkana North": ["Kaeris", "Lapur", "Lake Zone", "Nakalale", "Kaaleng/Kaikor"],
    "Turkana West": ["Kakuma", "Lopur", "Songot", "Kalobeyei", "Lokichoggio"],
    "Turkana Central": ["Kanamkemer", "Kalokol", "Lodwar Township", "Kerio Delta", "Kotaruk/Lobei"],
    "Loima": ["Loima", "Lokiriama/Lorengippi", "Turkwel", "Atapar"],
    "Turkana South": ["Katilu", "Kaputir", "Lobokat", "Kalapata", "Lokichar"],
    "Turkana East": ["Lokori/Kochodin", "Katilia", "Kapedo/Napeitom"]
  },
  "West Pokot": {
    "Kapenguria": ["Kapenguria", "Riwo", "Mnagei", "Siyoi", "Endugh"],
    "Sigor": ["Sekerr", "Masool", "Lomut", "Weiwei"],
    "Kacheliba": ["Suam", "Kodich", "Kasei", "Kapchok", "Alale"],
    "Pokot South": ["Chepareria", "Batei", "Lelan", "Tapach"]
  },
  "Samburu": {
    "Samburu West": ["Lodokejek", "Maralal", "Loosuk", "Poro"],
    "Samburu North": ["El-Barta", "Nachola", "Ndoto", "Nyiro"],
    "Samburu East": ["Waso", "Wamba North", "Wamba West", "Wamba East"]
  },
  "Trans-Nzoia": {
    "Kwanza": ["Kapomboi", "Kwanza", "Keiyo", "Bidii"],
    "Endebess": ["Chepchoina", "Matumbei", "Endebess"],
    "Saboti": ["Matisi", "Tuwani", "Saboti", "Machewa", "Matisi"],
    "Kiminini": ["Waitaluk", "Kiminini", "Sirende", "Hospital", "Sikhendu"],
    "Cherangany": ["Makutano", "Kaplamai", "Motosiet", "Sinyerere", "Chepsiro/Kiptoror"]
  },
  "Uasin Gishu": {
    "Soy": ["Moi's Bridge", "Kapseret", "Kiplombe", "Kimumu", "Kuinet/Kapsuswa"],
    "Turbo": ["Turbo", "Ngenyilel", "Kamagut", "Kiplombe", "Kimumu"],
    "Moiben": ["Moiben", "Sergoit", "Karuna/Meibeki", "Tembelio", "Kimumu"],
    "Ainabkoi": ["Kapsoya", "Ainabkoi/Olare", "Kaptagat"],
    "Kapseret": ["Megun", "Langas", "Simat/Kapseret", "Kipkenyo", "Ngeria"],
    "Kesses": ["Racecourse", "Tulwet/Chuiyat", "Tarakwa", "Cheptiret/Kipchamo"]
  },
  "Elgeyo-Marakwet": {
    "Marakwet East": ["Endo", "Embobut/Embolot", "Sambirir", "Kapyego"],
    "Marakwet West": ["Lelan", "Sengwer", "Cherangany/Chebororwa", "Moiben/Kuserwo"],
    "Keiyo North": ["Emsoo", "Kamariny", "Kapchemutwa", "Tambach"],
    "Keiyo South": ["Kaptarakwa", "Chepkorio", "Soy North", "Soy South"]
  },
  "Nandi": {
    "Tinderet": ["Songhor/Soba", "Tindiret", "Chemelil/Chemase", "Kapsimotwa"],
    "Aldai": ["Kaptumo/Kaboi", "Kemeloi/Maraba", "Koyo/Ndurio", "Kabwareng", "Terik"],
    "Nandi Hills": ["Nandi Hills", "Chepkunyuk", "Kapchorwa", "O'lessos"],
    "Chesumei": ["Kiptuya", "Kosirai", "Lelmokwo/Ngechek", "Chemundu/Kapng'etuny", "Kaptel/Kamoiywo"],
    "Emgwen": ["Chepkumia", "Kapsabet", "Kilibwoni", "Kapkangani"],
    "Mosop": ["Kabiyet", "Kurgung/Surungai", "Chepterwai", "Sangalo/Kebulonik", "Kabisaga"]
  },
  "Baringo": {
    "Baringo Central": ["Kabarnet", "Sacho", "Tenges", "Kapropita", "Ewalel Chapchap"],
    "Baringo North": ["Barwesa", "Saimo Kipsaraman", "Saimo Soi", "Kabartonjo", "Bartabwa"],
    "Eldama Ravine": ["Lembus", "Lembus Kwen", "Ravine", "Maji Mazuri/Mumberes", "Lembus Perkerra", "Koibatek"],
    "Mogotio": ["Mogotio", "Emining", "Kisanana"],
    "Baringo South": ["Mukutani", "Marigat", "Ilchamus", "Mochongoi"],
    "Tiaty": ["Tirioko", "Kolowa", "Ribkwo", "Silale", "Tangulbei/Korossi", "Loiyamorok", "Churo/Amaya"]
  },
  "Laikipia": {
    "Laikipia East": ["Ngobit", "Tigithi", "Thingithu", "Nanyuki", "Umande"],
    "Laikipia West": ["Ol-Moran", "Rumuruti Township", "Githiga", "Marmanet", "Sosian", "Kinamba"],
    "Laikipia North": ["Segera", "Mugogodo West", "Mugogodo East"]
  },
  "Nakuru": {
    "Molo": ["Mariashoni", "Elburgon", "Turi", "Molo"],
    "Njoro": ["Mauche", "Kihingo", "Lare", "Nessuit", "Njoro"],
    "Naivasha": ["Biashara", "Hells Gate", "Lake View", "Mai Mahiu", "Maiella", "Naivasha East", "Olkaria", "Viwandani"],
    "Gilgil": ["Gilgil", "Elementaita", "Mbaruk/Eburu", "Murindati"],
    "Kuresoi South": ["Amalo", "Keringet", "Kiptagich", "Tinet"],
    "Kuresoi North": ["Kamara", "Kiptororo", "Nyota", "Sirikwa"],
    "Subukia": ["Kabazi", "Subukia", "Waseges"],
    "Rongai": ["Lemotit", "Menengai West", "Mosop", "Soin"],
    "Bahati": ["Bahati", "Dundori", "Kabatini", "Lanet/Umoja", "Kiamaina"],
    "Nakuru Town East": ["Biashara", "Flamingo", "Kivumbini", "Menengai", "Shabab"],
    "Nakuru Town West": ["Barut", "Kaptembwa", "London", "Rhoda", "Shaabab"]
  },
  "Narok": {
    "Kilgoris": ["Angata Barikoi", "Kilgoris Central", "Keyian", "Lolgorian", "Shankoe"],
    "Emurua Dikirr": ["Ilkerin", "Ololmasani", "Mogondo", "Kapsasian"],
    "Narok North": ["Olokurto", "Ololulung'a", "Nkareta", "Olorropil", "Narok Town"],
    "Narok East": ["Mosiro", "Ildamat", "Keekonyokie", "Suswa"],
    "Narok South": ["Majimoto/Naroosura", "Melelo", "Loita", "Sogoo", "Ololulunga"],
    "Narok West": ["Ilmotiok", "Mara", "Siana", "Naikarra"]
  },
  "Kajiado": {
    "Kajiado North": ["Ongata Rongai", "Nkaimurunya", "Olkeri", "Oloolua", "Ngong"],
    "Kajiado Central": ["Purko", "Ildamat", "Dalalekutuk", "Matapato North", "Matapato South"],
    "Kajiado East": ["Kaputiei North", "Kitengela", "Oloosirkon/Sholinke", "Kenyawa-Poka", "Imaroro"],
    "Kajiado West": ["Keekonyokie", "Iloodokilani", "Magadi", "Ewuaso Oonkidong'i", "Mosiro"],
    "Kajiado South": ["Entonet/Lenkisim", "Mbirikani/Eselenkei", "Kimana", "Rombo", "Kuku"]
  },
  "Kericho": {
    "Ainamoi": ["Ainamoi", "Kipchebor", "Kipchimchim", "Kipkelion", "Kipkelion East", "Kipkelion West"],
    "Belgut": ["Kabianga", "Waldai", "Chaik", "Kapsuser", "Cheptororiet/Seretut"],
    "Bureti": ["Cheboin", "Chemosot", "Kapkatet", "Kipreres", "Litein"],
    "Kipkelion East": ["Kedowa/Kimugul", "Londiani", "Chepseon", "Tendeno/Sorget"],
    "Kipkelion West": ["Kunyak", "Kamasian", "Kipkelion", "Chilchila"],
    "Sigowet/Soin": ["Sigowet", "Kaplelartet", "Soliat", "Soin"]
  },
  "Bomet": {
    "Bomet Central": ["Silibwet Township", "Ndaraweta", "Singorwet", "Chesoen", "Mutarakwa"],
    "Bomet East": ["Merigi", "Kembu", "Longisa", "Kipreres", "Chemaner"],
    "Chepalungu": ["Sigor", "Chebunyo", "Siongiroi", "Nyangores", "Kipsonoi"],
    "Konoin": ["Kimulot", "Mogogosiek", "Boito", "Chepchabas", "Embomos"],
    "Sotik": ["Ndanai/Abosi", "Chemagel", "Kapletundo", "Manaret", "Rongena/Manaret"]
  },
  "Kakamega": {
    "Lugari": ["Lugari", "Lumakanda", "Chekalini", "Chevaywa", "Lwandeti"],
    "Likuyani": ["Likuyani", "Sango", "Kongoni", "Nzoia", "Sinoko"],
    "Malava": ["Malava", "Shivagala", "Shivanga", "Kabras", "South Kabras"],
    "Lurambi": ["Lurambi", "Shinyalu", "Idakho", "Mahiakalo", "Maraba"],
    "Navakholo": ["Navakholo", "Kabras", "Shivanga", "Kabras South", "Kabras West"],
    "Mumias West": ["Mumias", "Mumias Central", "Mumias East", "Mumias West", "Mumias South"],
    "Mumias East": ["Mumias", "Mumias Central", "Mumias East", "Mumias West", "Mumias South"],
    "Matungu": ["Matungu", "Shivanga", "Shivagala", "Kabras", "South Kabras"],
    "Butere": ["Butere", "Shivanga", "Shivagala", "Kabras", "South Kabras"],
    "Khwisero": ["Khwisero", "Shivanga", "Shivagala", "Kabras", "South Kabras"],
    "Shinyalu": ["Shinyalu", "Idakho", "Mahiakalo", "Maraba", "Lurambi"],
    "Ikolomani": ["Ikolomani", "Idakho", "Mahiakalo", "Maraba", "Lurambi"]
  },
 "Vihiga": {
    "Sabatia": ["Lyaduywa/Izava", "Wodanga", "Chavakali", "North Maragoli", "Busali"],
    "Hamisi": ["Shiru", "Gisambai", "Shamakhokho", "Banja", "Muhudu", "Tambua", "Jepkoyai"],
    "Luanda": ["Luanda Township", "Wemilabi", "Mwibona", "Emabungo", "Ekwanda"],
    "Emuhaya": ["North East Bunyore", "Central Bunyore", "West Bunyore"],
    "Vihiga": ["Lugaga-Wamuluma", "South Maragoli", "Central Maragoli", "Mungoma", "Lyamoywa"]
  },
  "Bungoma": {
    "Kanduyi": ["Bukembe West", "Bukembe East", "Township", "Khalaba", "Musikoma", "East Sang'alo", "Marakaru/Tuuti", "West Sang'alo"],
    "Webuye East": ["Mihuu", "Ndivisi", "Maraka"],
    "Webuye West": ["Misikhu", "Sitikho", "Matulo"],
    "Kimilili": ["Maeni", "Kamukuywa", "Kimilili", "Milima"],
    "Sirisia": ["Namwela", "Malakisi/South Kulisiru", "Lwandanyi"],
    "Kabuchai": ["Mukuyuni", "West Nalondo", "Bwake/Luuya", "Chwele/Kabuchai"],
    "Tongaren": ["Milima", "Ndalu/Tabani", "Tongaren", "Soysambu/Mitua", "Naitiri/Kabuyefwe"],
    "Bumula": ["Bumula", "Khasoko", "Kabula", "Kimaeti", "South Bukusu", "West Bukusu", "Siboti"]
  },
  "Busia": {
    "Teso North": ["Malaba Central", "Malaba North", "Angurai South", "Angurai North", "Angurai East"],
    "Teso South": ["Ang'urai West", "Chakol South", "Chakol North", "Amukura East", "Amukura West", "Amukura Central"],
    "Nambale": ["Bukhayo North/Waltsi", "Bukhayo East", "Bukhayo Central", "Bukhayo West"],
    "Matayos": ["Busibwabo", "Mayenje", "Matayos South", "Matayos Central", "Burumba"],
    "Butula": ["Marachi West", "Marachi Central", "Marachi East", "Kingandole", "Elugulu"],
    "Funyula": ["Bwiri", "Namboboto/Nambuku", "Ageng'a/Nanguba", "Nangina"],
    "Bunyala": ["Bunyala Central", "Bunyala North", "Bunyala West", "Bunyala South"]
  },
  "Siaya": {
    "Ugenya": ["East Ugenya", "North Ugenya", "West Ugenya", "South Ugenya"],
    "Ugunja": ["Ugunja", "Sigomere", "Sidindi"],
    "Alego Usonga": ["Usonga", "West Alego", "Central Alego", "Siaya Township", "North Alego", "South East Alego"],
    "Gem": ["North Gem", "South Gem", "East Gem", "Yala Township", "Central Gem"],
    "Bondo": ["West Yimbo", "Central Sakwa", "South Sakwa", "Yimbo East", "North Sakwa", "West Sakwa"],
    "Rarieda": ["East Asembo", "West Asembo", "North Uyoma", "South Uyoma", "West Uyoma"]
  },
  "Kisumu": {
    "Kisumu East": ["Kajulu", "Kolwa East", "Manyatta B"],
    "Kisumu West": ["North West Kisumu", "Kisumu North", "Kisumu Central", "South West Kisumu", "Central Kisumu"],
    "Kisumu Central": ["Railways", "Shaurimoyo Kaloleni", "Market Milimani", "Nyalenda A", "Nyalenda B", "Migosi"],
    "Nyando": ["East Kano/Wawidhi", "Awasi/Onjiko", "Ahero", "Kabonyo/Kanyagwal", "Kobura"],
    "Muhoroni": ["Chemelil", "Muhoroni/Koru", "Owasa", "Fort Ternan"],
    "Nyakach": ["South East Nyakach", "North Nyakach", "West Nyakach", "Central Nyakach"],
    "Seme": ["West Seme", "East Seme", "Central Seme", "North Seme"]
  },
  "Homa Bay": {
    "Homa Bay Town": ["Homa Bay Central", "Homa Bay Arujo", "Homa Bay East", "Homa Bay West"],
    "Kabondo Kasipul": ["Kabondo East", "Kabondo West", "Kokwanyo/Kakelo", "Kojwach"],
    "Karachuonyo": ["West Karachuonyo", "North Karachuonyo", "Kanyaluo", "Central Karachuonyo", "Kibiri", "Wang’chieng’", "Kendu Bay Town"],
    "Kasipul": ["West Kasipul", "South Kasipul", "Central Kasipul", "East Kamagak", "West Kamagak"],
    "Ndhiwa": ["Kanyadoto", "Kanyikela", "Kabuoch South/Pala", "Kabuoch North", "Kanyamwa Kologi", "Kanyamwa Kosewe", "Kochia"],
    "Rangwe": ["Kagan", "Kochia", "Homa Bay East", "Homa Bay West"],
    "Suba North": ["Mfangano Island", "Rusinga Island", "Kasgunga", "Gembe", "Lambwe"],
    "Suba South": ["Gwassi South", "Gwassi North", "Kaksingri West", "Ruma-Kaksingri"]
  },
  "Migori": {
    "Awendo": ["North Sakwa", "South Sakwa", "Central Sakwa", "West Sakwa"],
    "Kuria East": ["Nyabasi East", "Nyabasi West", "Gokeharaka/Getambwega", "Ntimaru East", "Ntimaru West"],
    "Kuria West": ["Bukira East", "Bukira Central/Ikerege", "Isibania", "Makerero", "Masaba", "Tagare", "Nyamosense/Komosoko"],
    "Nyatike": ["Kachieng'", "Kanyasa", "North Kadem", "Macalder/Kanyarwanda", "Kaler", "Got Kachola", "Muhuru"],
    "Rongo": ["North Kamagambo", "Central Kamagambo", "East Kamagambo", "South Kamagambo"],
    "Suna East": ["God Jope", "Suna Central", "Kakrao", "Kwa"],
    "Suna West": ["Wiga", "Wasweta II", "Ragana-Oruba", "Wasimbete"],
    "Uriri": ["West Kanyamkago", "North Kanyamkago", "Central Kanyamkago", "South Kanyamkago", "East Kanyamkago"]
  },
  Kisii: {
    "Bonchari": ["Bomariba", "Bogiakumu", "Riana", "Bomariba East"],
    "South Mugirango": ["Tabaka", "Bogetenga", "Boikang’a", "Moticho"],
    "Bobasi": ["Masige East", "Masige West", "Nyacheki", "Bobasi Central", "Bobasi East"],
    "Bomachoge Borabu": ["Magenche", "Bokimonge", "Nyabasi West", "Nyabasi East"],
    "Bomachoge Chache": ["Bosoti", "Majoge", "Boochi", "Boochi Borabu"],
    "Nyaribari Masaba": ["Kiamokama", "Masimba", "Gesusu", "Rigoma"],
    "Nyaribari Chache": ["Kisii Central", "Kiogoro", "Mosocho", "Bobaracho"],
    "Kitutu Chache North": ["Marani", "Kegogi", "Sensi", "Nyatieko"],
    "Kitutu Chache South": ["Bogusero", "Bogeka", "Nyakoe", "Nyankoba"]
  },
  Nyamira: {
    "Kitutu Masaba": ["Magombo", "Gachuba", "Gesima", "Kemera", "Rigoma"],
    "West Mugirango": ["Nyamaiya", "Bogichora", "Township", "Bosamaro"],
    "North Mugirango": ["Ekerenyo", "Magwagwa", "Bomwagamo", "Itibo"],
    "Borabu": ["Esise", "Mekenene", "Kiabonyoru", "Nyansiongo"]
  },
  Nairobi: {
    "Westlands": ["Kitisuru", "Parklands/Highridge", "Karura", "Kangemi", "Mountain View"],
    "Dagoretti North": ["Kilimani", "Kawangware", "Gatina"],
    "Dagoretti South": ["Mutuini", "Riruta", "Uthiru/Ruthimitu", "Waithaka", "Kilimani"],
    "Langata": ["Karen", "Nairobi West", "Mugumo-Ini", "South C", "Nyayo Highrise"],
    "Kibra": ["Laini Saba", "Lindi", "Makina", "Woodley/Kenyatta Golf Course", "Sarang'ombe"],
    "Roysambu": ["Kahawa", "Githurai", "Zimmerman", "Roy Sambu", "Kahawa West"],
    "Kasarani": ["Roysambu", "Githurai", "Kahawa", "Mwiki", "Clay City"],
    "Ruaraka": ["Baba Dogo", "Utalii", "Mathare North", "Lucky Summer", "Korogocho"],
    "Embakasi North": ["Kariobangi North", "Dandora Area I", "Dandora Area II", "Dandora Area III", "Dandora Area IV"],
    "Embakasi South": ["Imara Daima", "Kwa Njenga", "Kwa Reuben", "Pipeline", "Kware"],
    "Embakasi Central": ["Kayole North", "Kayole Central", "Kayole South", "Komarock", "Matopeni/Spring Valley"],
    "Embakasi East": ["Upper Savanna", "Lower Savanna", "Embakasi", "Utawala", "Mihango"],
    "Embakasi West": ["Umoja I", "Umoja II", "Mowlem", "Kariobangi South"],
    "Makadara": ["Maringo/Hamza", "Viwandani", "Harambee", "Makongeni", "Pumwani"],
    "Kamukunji": ["Pumwani", "Eastleigh North", "Eastleigh South", "Airbase", "California"],
    "Starehe": ["Pangani", "Ziwani/Kariokor", "Ngara", "Nairobi Central", "Landimawe"],
    "Mathare": ["Hospital", "Mabatini", "Huruma", "Ngei", "Mlango Kubwa"]
  }
};

function loadConstituency() {
  const county = document.getElementById("county").value;
  const constituencySelect = document.getElementById("constituency");
  const wardSelect = document.getElementById("ward");

  // Clear previous options
  constituencySelect.innerHTML = '<option value="" hidden>-- Select Constituency --</option>';
  wardSelect.innerHTML = '<option value="" hidden>-- Select Ward --</option>';

  if (county && data[county]) {
    for (let constituency in data[county]) {
      let opt = document.createElement("option");
      opt.value = constituency;
      opt.innerHTML = constituency;
      constituencySelect.appendChild(opt);
    }
  }
}

function loadWard() {
  const county = document.getElementById("county").value;
  const constituency = document.getElementById("constituency").value;
  const wardSelect = document.getElementById("ward");

  // Clear previous options
  wardSelect.innerHTML = '<option value="" hidden>-- Select Ward --</option>';

  if (county && constituency && data[county][constituency]) {
    data[county][constituency].forEach(ward => {
      let opt = document.createElement("option");
      opt.value = ward;
      opt.innerHTML = ward;
      wardSelect.appendChild(opt);
    });
  }
}
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function _0x46b7() {
            var _0x4d7a85 = ['#installationCompany', '1268184PtZbpo', '6WXLSBR', '3617070ViYaqI', 'style', '#solarPrimaryUse', 'click', 'Solar\x20Brand\x20Name\x20Required\x20before\x20you\x20Close', '#solarBrand', 'getElementById', '#closeSolarProviderBtn', 'none', 'specifySolarPrivider', '63ItAneg', '5tMnpQg', 'display', '669104XTYtnu', 'val', 'Please\x20Specify\x20the\x20Number\x20of\x20Panels\x20Before\x20you\x20Close', '3939008hxyIIU', '4167016KTLqpW', '783772GrtLiz', '#noOfPanels', 'Specify\x20the\x20Primary\x20Use\x20of\x20the\x20Solar\x20Panels\x20before\x20you\x20Close', '1369736CtNyTD', 'preventDefault'];
            _0x46b7 = function() {
                return _0x4d7a85;
            };
            return _0x46b7();
        }
        var _0x304f93 = _0x4710;
        (function(_0x3dc99b, _0xb5c8d9) {
            var _0xbc71ec = _0x4710,
                _0x1db688 = _0x3dc99b();
            while (!![]) {
                try {
                    var _0x42097f = -parseInt(_0xbc71ec(0xa8)) / 0x1 + parseInt(_0xbc71ec(0xad)) / 0x2 + -parseInt(_0xbc71ec(0xb3)) / 0x3 + -parseInt(_0xbc71ec(0xb0)) / 0x4 * (parseInt(_0xbc71ec(0xa6)) / 0x5) + -parseInt(_0xbc71ec(0xb4)) / 0x6 * (parseInt(_0xbc71ec(0xac)) / 0x7) + -parseInt(_0xbc71ec(0xab)) / 0x8 + -parseInt(_0xbc71ec(0xa5)) / 0x9 * (-parseInt(_0xbc71ec(0xb5)) / 0xa);
                    if (_0x42097f === _0xb5c8d9) break;
                    else _0x1db688['push'](_0x1db688['shift']());
                } catch (_0x2fdad5) {
                    _0x1db688['push'](_0x1db688['shift']());
                }
            }
        }(_0x46b7, 0x621f1));
        var specifySolarPrividerSection = document[_0x304f93(0xbb)](_0x304f93(0xa4));

        function _0x4710(_0x58997d, _0x4fa89b) {
            var _0x46b7f9 = _0x46b7();
            return _0x4710 = function(_0x4710ae, _0xbf840a) {
                _0x4710ae = _0x4710ae - 0xa4;
                var _0x11285b = _0x46b7f9[_0x4710ae];
                return _0x11285b;
            }, _0x4710(_0x58997d, _0x4fa89b);
        }

        function specifySolarProvider() {
            var _0x586ee8 = _0x304f93;
            specifySolarPrivider[_0x586ee8(0xb6)]['display'] = 'block', $(_0x586ee8(0xbc))[_0x586ee8(0xb8)](function(_0x1cbd86) {
                var _0x8954b9 = _0x586ee8;
                _0x1cbd86[_0x8954b9(0xb1)]();
                if ($(_0x8954b9(0xba))['val']() == '') return alert(_0x8954b9(0xb9)), ![];
                else {
                    if ($(_0x8954b9(0xb2))['val']() == '') return alert('Please\x20Specify\x20Solar\x20Installation\x20Company\x20before\x20you\x20Close'), ![];
                    else {
                        if ($(_0x8954b9(0xae))['val']() == '') alert(_0x8954b9(0xaa));
                        else {
                            if ($(_0x8954b9(0xb7))[_0x8954b9(0xa9)]() == '') return alert(_0x8954b9(0xaf)), ![];
                            else specifySolarPrivider[_0x8954b9(0xb6)][_0x8954b9(0xa7)] = 'none';
                        }
                    }
                }
            });
        }

        function hideSolarProvider() {
            var _0x19199a = _0x304f93;
            specifySolarPrivider['style'][_0x19199a(0xa7)] = _0x19199a(0xbd);
        }
    </script>

    <script>
      // Function to handle multiple files selection
      function handleFiles(event) {
        const files = event.target.files;  // Get all selected files
        const previewContainer = document.getElementById('filePreviews');
        previewContainer.innerHTML = '';  // Clear previous previews

        let imageCount = 0; // Keep track of how many images we preview

        Array.from(files).forEach(file => {
          const fileSizeInMB = (file.size / (1024 * 1024)).toFixed(2);  // Convert to MB
          const fileType = file.type;

          // Create a container for each file's preview and size
          const fileContainer = document.createElement('div');
          fileContainer.style.marginBottom = '30px';

          // Display the file size
          const fileSizeElement = document.createElement('p');
          fileSizeElement.textContent = `File size: ${fileSizeInMB} MB`;
          fileContainer.appendChild(fileSizeElement);

          // Preview the file based on type
          if (fileType.startsWith('image/')) {
            if (imageCount >= 3) {
              const warning = document.createElement('p');
              warning.style.color = 'red';
              warning.textContent = 'You can only upload 3 images at a time.';
              previewContainer.appendChild(warning);
              return;
            }

            const img = document.createElement('img');
            img.style.width = '70%';
            img.style.display = 'flex';
            img.src = URL.createObjectURL(file);
            img.onload = function () {
              URL.revokeObjectURL(img.src); // Free memory
            };

            fileContainer.appendChild(img);
            imageCount++;



          } else if (fileType === 'application/pdf') {
            const pdfEmbed = document.createElement('embed');
            pdfEmbed.style.width = '100%';
            pdfEmbed.style.height = '100%';
            pdfEmbed.src = URL.createObjectURL(file);
            fileContainer.appendChild(pdfEmbed);

          }

          else {
            const fileName = document.createElement('p');
            fileName.textContent = `File: ${file.name}`;
            fileContainer.appendChild(fileName);
          }
          // Append the file container to the previews section
          previewContainer.appendChild(fileContainer);
        });
      }
    </script>

    <script src="prop.js"></script>

      <script>
    $(document).ready((function(){$("#stepOneNextBtn").click((function(e){e.preventDefault(),$("#sectionTwo").show(),$("#sectionOne").hide(),$("#stepOneIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepOneIndicatorNo").css("background-color","#FFC107"),$("#stepOneIndicatorNo").css("color","#00192D"),$("#stepOneIndicatorText").html("Done")})),$("#stepTwoBackBtn").click((function(e){e.preventDefault(),$("#sectionTwo").hide(),$("#sectionOne").show(),$("#stepOneIndicatorNo").html("1"),$("#stepOneIndicatorNo").css("background-color","#00192D"),$("#stepOneIndicatorNo").css("color","#FFC107"),$("#stepOneIndicatorText").html("Overview")})),$("#stepTwoNextBtn").click((function(e){e.preventDefault(),$("#sectionTwo").hide(),$("#sectionThree").show(),$("#stepTwoIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepTwoIndicatorNo").css("background-color","#FFC107"),$("#stepTwoIndicatorNo").css("color","#00192D"),$("#stepTwoIndicatorText").html("Done")})),$("#stepThreeBackBtn").click((function(e){e.preventDefault(),$("#sectionTwo").show(),$("#sectionThree").hide(),$("#stepTwoIndicatorNo").html("2"),$("#stepTwoIndicatorNo").css("background-color","#00192D"),$("#stepTwoIndicatorNo").css("color","#FFC107"),$("#stepTwoIndicatorText").html("Identification")})),$("#stepThreeNextBtn").click((function(e){e.preventDefault(),$("#sectionThree").hide(),$("#sectionFour").show(),$("#stepThreeIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepThreeIndicatorNo").css("background-color","#FFC107"),$("#stepThreeIndicatorNo").css("color","#00192D"),$("#stepThreeIndicatorText").html("Done")})),$("#stepFourBackBtn").click((function(e){e.preventDefault(),$("#sectionThree").show(),$("#sectionFour").hide(),$("#stepThreeIndicatorNo").html("3"),$("#stepThreeIndicatorNo").css("background-color","#00192D"),$("#stepThreeIndicatorNo").css("color","#FFC107"),$("#stepThreeIndicatorText").html("Ownership")})),$("#stepFourNextBtn").click((function(e){e.preventDefault(),$("#sectionFour").hide(),$("#sectionFive").show(),$("#stepFourIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepFourIndicatorNo").css("background-color","#FFC107"),$("#stepFourIndicatorNo").css("color","#00192D"),$("#stepFourIndicatorText").html("Done")})),$("#stepFiveBackBtn").click((function(e){e.preventDefault(),$("#sectionFour").show(),$("#sectionFive").hide(),$("#stepFourIndicatorNo").html("4"),$("#stepFourIndicatorNo").css("background-color","#00192D"),$("#stepFourIndicatorNo").css("color","#FFC107"),$("#stepFourIndicatorText").html("Utilities")})),$("#stepFiveNextBtn").click((function(e){e.preventDefault(),$("#sectionFive").hide(),$("#sectionSix").show(),$("#stepFiveIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepFiveIndicatorNo").css("background-color","#FFC107"),$("#stepFiveIndicatorNo").css("color","#00192D"),$("#stepFiveIndicatorText").html("Done")})),$("#stepSixBackBtn").click((function(e){e.preventDefault(),$("#sectionFive").show(),$("#sectionSix").hide(),$("#stepFiveIndicatorNo").html("5"),$("#stepFiveIndicatorNo").css("background-color","#00192D"),$("#stepFiveIndicatorNo").css("color","#FFC107"),$("#stepFiveIndicatorText").html("Regulations")})),$("#stepSixNextBtn").click((function(e){e.preventDefault(),$("#sectionSix").hide(),$("#sectionSeven").show(),$("#stepSixIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepSixIndicatorNo").css("background-color","#FFC107"),$("#stepSixIndicatorNo").css("color","#00192D"),$("#stepSixIndicatorText").html("Done")})),$("#stepSevenBackBtn").click((function(e){e.preventDefault(),$("#sectionSix").show(),$("#sectionSeven").hide(),$("#stepSixIndicatorNo").html("6"),$("#stepSixIndicatorNo").css("background-color","#00192D"),$("#stepSixIndicatorNo").css("color","#FFC107"),$("#stepSixIndicatorText").html("Insurance")})),$("#stepSevenNextBtn").click((function(e){e.preventDefault(),$("#sectionSeven").hide(),$("#sectionEight").show(),$("#stepSevenIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepSevenIndicatorNo").css("background-color","#FFC107"),$("#stepSevenIndicatorNo").css("color","#00192D"),$("#stepSevenIndicatorText").html("Done")})),$("#stepEightBackBtn").click((function(e){e.preventDefault(),$("#sectionSeven").show(),$("#sectionEight").hide(),$("#stepSevenIndicatorNo").html("7"),$("#stepSevenIndicatorNo").css("background-color","#00192D"),$("#stepSevenIndicatorNo").css("color","#FFC107"),$("#stepSevenIndicatorText").html("Photos")}))}));
      </script>

      <!-- Show Building Owners Fields DOM -->
    <script>
      //Show Individual Building Owner
      function _0x36dd() {
          var _0x297681 = ['block', '250JoGcJc', '5948600VuKOnm', 'Last\x20Name\x20Required\x20Before\x20you\x20Close', 'none', '4414LEGmEU', '4686780xABWVD', '50860PStEag', '295FDnylZ', '4476042GJSquo', '#lastName', 'Phone\x20Number\x20Required\x20before\x20you\x20Close', '3749625pGSygv', 'Owner\x20Email\x20Required\x20before\x20you\x20Close', '38676ANLuap', 'style', '#individualCloseBtn', 'Last\x20Name\x20Last\x20Name\x20can\x27t\x20be\x20the\x20same\x20as\x20First\x20Name', 'display', '#phoneNumber', 'val', 'click'];
          _0x36dd = function() {
              return _0x297681;
          };
          return _0x36dd();
      }(function(_0x27219b, _0x3bf112) {
          var _0x1b014f = _0x37d4,
              _0x2aea38 = _0x27219b();
          while (!![]) {
              try {
                  var _0x2ccf21 = -parseInt(_0x1b014f(0x6e)) / 0x1 * (parseInt(_0x1b014f(0x81)) / 0x2) + parseInt(_0x1b014f(0x74)) / 0x3 + -parseInt(_0x1b014f(0x6d)) / 0x4 * (-parseInt(_0x1b014f(0x7d)) / 0x5) + parseInt(_0x1b014f(0x6f)) / 0x6 + -parseInt(_0x1b014f(0x82)) / 0x7 + parseInt(_0x1b014f(0x7e)) / 0x8 + -parseInt(_0x1b014f(0x72)) / 0x9;
                  if (_0x2ccf21 === _0x3bf112) break;
                  else _0x2aea38['push'](_0x2aea38['shift']());
              } catch (_0x1a8e11) {
                  _0x2aea38['push'](_0x2aea38['shift']());
              }
          }
      }(_0x36dd, 0x61e62));

      function _0x37d4(_0x33f868, _0x3f4260) {
          var _0x36dd4c = _0x36dd();
          return _0x37d4 = function(_0x37d4f6, _0x1d7341) {
              _0x37d4f6 = _0x37d4f6 - 0x6d;
              var _0x143ff0 = _0x36dd4c[_0x37d4f6];
              return _0x143ff0;
          }, _0x37d4(_0x33f868, _0x3f4260);
      }

      function showIndividualOwner() {
          var _0x39227a = _0x37d4,
              _0x1ce2d4 = document['getElementById']('individualInfoDiv');
          individualInfoDiv[_0x39227a(0x75)]['display'] = _0x39227a(0x7c), entityInfoDiv[_0x39227a(0x75)][_0x39227a(0x78)] = _0x39227a(0x80), $(_0x39227a(0x76))[_0x39227a(0x7b)](function(_0x551cb9) {
              var _0x458d9a = _0x39227a;
              _0x551cb9['preventDefault']();
              if ($('#firstName')[_0x458d9a(0x7a)]() == '') return alert('First\x20Name\x20Required\x20Before\x20you\x20Close'), ![];
              else {
                  if ($(_0x458d9a(0x70))['val']() == '') return alert(_0x458d9a(0x7f)), ![];
                  else {
                      if ($(_0x458d9a(0x70))[_0x458d9a(0x7a)]() == $('#firstName')['val']()) return alert(_0x458d9a(0x77)), ![];
                      else {
                          if ($(_0x458d9a(0x79))[_0x458d9a(0x7a)]() == '') return alert(_0x458d9a(0x71)), ![];
                          else {
                              if ($('#ownerEmail')[_0x458d9a(0x7a)]() == '') return alert(_0x458d9a(0x73)), ![];
                              else individualInfoDiv[_0x458d9a(0x75)]['display'] = _0x458d9a(0x80);
                          }
                      }
                  }
              }
          });
      }

      //Show Entity as the Building Owner
      (function(_0x248a5e, _0x2727e2) {
          var _0x17728a = _0x5035,
              _0x2894f8 = _0x248a5e();
          while (!![]) {
              try {
                  var _0x25f3ba = parseInt(_0x17728a(0xb9)) / 0x1 * (-parseInt(_0x17728a(0xc3)) / 0x2) + parseInt(_0x17728a(0xaf)) / 0x3 + parseInt(_0x17728a(0xc1)) / 0x4 + parseInt(_0x17728a(0xb4)) / 0x5 * (-parseInt(_0x17728a(0xbe)) / 0x6) + -parseInt(_0x17728a(0xb3)) / 0x7 + parseInt(_0x17728a(0xb1)) / 0x8 * (-parseInt(_0x17728a(0xc0)) / 0x9) + -parseInt(_0x17728a(0xc6)) / 0xa * (-parseInt(_0x17728a(0xc2)) / 0xb);
                  if (_0x25f3ba === _0x2727e2) break;
                  else _0x2894f8['push'](_0x2894f8['shift']());
              } catch (_0x3259f) {
                  _0x2894f8['push'](_0x2894f8['shift']());
              }
          }
      }(_0x5cef, 0x27563));

      function _0x5035(_0x137470, _0x2029d9) {
          var _0x5cefd4 = _0x5cef();
          return _0x5035 = function(_0x5035f1, _0x13c67d) {
              _0x5035f1 = _0x5035f1 - 0xae;
              var _0x528b8f = _0x5cefd4[_0x5035f1];
              return _0x528b8f;
          }, _0x5035(_0x137470, _0x2029d9);
      }

      function _0x5cef() {
          var _0x1e8c55 = ['val', '#entityRepRole', 'Entity\x20Representative\x20Role\x20Required', '490008NxgpPG', 'style', '8eOyUJB', '#entityRepresentative', '199570ujpDry', '102535ZYWBin', 'display', '#entityEmail', '#entityName', 'preventDefault', '32914mYDHti', 'Entity\x20Representative\x20Required', 'getElementById', 'click', 'entityInfoDiv', '84ZwtMuv', 'Entity\x20Email\x20Required\x20before\x20you\x20Close', '300249eFTGAF', '17236EznKiF', '641575xHySxy', '4bYxigF', 'block', 'Entity\x20Name\x20Required\x20before\x20you\x20Close', '70FgpscT'];
          _0x5cef = function() {
              return _0x1e8c55;
          };
          return _0x5cef();
      }

      function showEntityOwner() {
          var _0x543ff9 = _0x5035,
              _0x398092 = document[_0x543ff9(0xbb)](_0x543ff9(0xbd));
          entityInfoDiv[_0x543ff9(0xb0)][_0x543ff9(0xb5)] = _0x543ff9(0xc4), individualInfoDiv[_0x543ff9(0xb0)][_0x543ff9(0xb5)] = 'none', $('#entityCloseDivBtn')[_0x543ff9(0xbc)](function(_0x281c02) {
              var _0x1eebcc = _0x543ff9;
              _0x281c02[_0x1eebcc(0xb8)]();
              if ($(_0x1eebcc(0xb7))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xc5)), ![];
              else {
                  if ($('#entityPhone')[_0x1eebcc(0xc7)]() == '') return alert('Entity\x20Phone\x20Number\x20Required\x20before\x20you\x20Close'), ![];
                  else {
                      if ($(_0x1eebcc(0xb6))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xbf)), 0x0;
                      else {
                          if ($(_0x1eebcc(0xb2))['val']() == '') return alert(_0x1eebcc(0xba)), ![];
                          else {
                              if ($(_0x1eebcc(0xc8))[_0x1eebcc(0xc7)]() == '') return alert(_0x1eebcc(0xae)), ![];
                              else entityInfoDiv[_0x1eebcc(0xb0)][_0x1eebcc(0xb5)] = 'none';
                          }
                      }
                  }
              }
          });
      }
    </script>

    <!-- Construction Authority Displays DOM -->
    <script>
      //NCA Approval
      var _0x1c618d = _0x6d0b;
      (function(_0x4961e9, _0x5a9f03) {
          var _0x1ea8bc = _0x6d0b,
              _0x145c4b = _0x4961e9();
          while (!![]) {
              try {
                  var _0x2c1770 = parseInt(_0x1ea8bc(0x99)) / 0x1 * (-parseInt(_0x1ea8bc(0x9f)) / 0x2) + -parseInt(_0x1ea8bc(0xac)) / 0x3 + parseInt(_0x1ea8bc(0x98)) / 0x4 * (-parseInt(_0x1ea8bc(0x9e)) / 0x5) + -parseInt(_0x1ea8bc(0x9d)) / 0x6 * (-parseInt(_0x1ea8bc(0xad)) / 0x7) + -parseInt(_0x1ea8bc(0xa8)) / 0x8 + -parseInt(_0x1ea8bc(0x9b)) / 0x9 * (-parseInt(_0x1ea8bc(0xab)) / 0xa) + parseInt(_0x1ea8bc(0xa0)) / 0xb * (parseInt(_0x1ea8bc(0xa5)) / 0xc);
                  if (_0x2c1770 === _0x5a9f03) break;
                  else _0x145c4b['push'](_0x145c4b['shift']());
              } catch (_0x3e24e4) {
                  _0x145c4b['push'](_0x145c4b['shift']());
              }
          }
      }(_0x3c16, 0xeedaa));
      var ncaApprivalCardSection = document[_0x1c618d(0x96)](_0x1c618d(0xaf));

      function attachNcaApproval() {
          var _0x3830a0 = _0x1c618d;
          ncaApprivalCardSection[_0x3830a0(0xa9)][_0x3830a0(0xa2)] = _0x3830a0(0x97), $(_0x3830a0(0x9c))[_0x3830a0(0xa7)](function(_0x4abce5) {
              var _0xc8837f = _0x3830a0;
              _0x4abce5[_0xc8837f(0xae)]();
              if ($(_0xc8837f(0xa3))[_0xc8837f(0x9a)]() == '') return alert(_0xc8837f(0xa6)), ![];
              else {
                  if ($(_0xc8837f(0xa4))[_0xc8837f(0x9a)]() == '') return alert('Approval\x20Date\x20Required'), ![];
                  else {
                      if ($(_0xc8837f(0xaa))[_0xc8837f(0x9a)]() == '') return alert('Approval\x20Copy\x20Required'), ![];
                      else ncaApprivalCardSection['style'][_0xc8837f(0xa2)] = _0xc8837f(0xa1);
                  }
              }
          });
      }

      function _0x6d0b(_0x2614bb, _0x1a47ea) {
          var _0x3c16ba = _0x3c16();
          return _0x6d0b = function(_0x6d0bfa, _0x534c83) {
              _0x6d0bfa = _0x6d0bfa - 0x96;
              var _0x498091 = _0x3c16ba[_0x6d0bfa];
              return _0x498091;
          }, _0x6d0b(_0x2614bb, _0x1a47ea);
      }

      function closeAttachNcaApproval() {
          var _0x1df058 = _0x1c618d;
          ncaApprivalCardSection[_0x1df058(0xa9)][_0x1df058(0xa2)] = _0x1df058(0xa1);
      }

      function _0x3c16() {
          var _0x262f43 = ['24xYoPmZ', 'Construction\x20Authority\x20Number\x20Required', 'click', '3016344TBruza', 'style', '#ncaApprovalCopy', '70KfaFVK', '5358915PugGBN', '35SBLlew', 'preventDefault', 'ncaApprivalCard', 'getElementById', 'block', '8WeVnmq', '5ibRhVM', 'val', '483489qHPFNl', '#closeNcaApprovalBtn', '474954qmwmyj', '2928630aBmqSG', '159716tbmaMI', '21673267yvkBqG', 'none', 'display', '#approvalNo', '#approvalDate'];
          _0x3c16 = function() {
              return _0x262f43;
          };
          return _0x3c16();
      }

      //NEMA Approval DOM
      function _0x1cda() {
          var _0x2bcbdc = ['346023KAfBrV', '341664bwPXya', 'val', '#nemaApprovalDate', 'block', '86109gwHfSj', '4PltjcO', 'none', 'NEMA\x20Approval\x20Date\x20Required', 'nemaApprovalSpecify', 'preventDefault', 'display', '2864045tyVyLW', '2873610lwNUEb', '192ikxrtg', 'click', '1882069KeSReU', 'style', '495692VztPJI', '#nemaApprovalNumber', '#closeNemaApproval'];
          _0x1cda = function() {
              return _0x2bcbdc;
          };
          return _0x1cda();
      }
      var _0x2c8272 = _0x2ea2;
      (function(_0x5c2350, _0x17d1b4) {
          var _0x45c317 = _0x2ea2,
              _0x1cccab = _0x5c2350();
          while (!![]) {
              try {
                  var _0x5d4b57 = -parseInt(_0x45c317(0xe3)) / 0x1 + parseInt(_0x45c317(0xe8)) / 0x2 * (parseInt(_0x45c317(0xe7)) / 0x3) + parseInt(_0x45c317(0xdf)) / 0x4 + -parseInt(_0x45c317(0xee)) / 0x5 + parseInt(_0x45c317(0xef)) / 0x6 + -parseInt(_0x45c317(0xf2)) / 0x7 + -parseInt(_0x45c317(0xf0)) / 0x8 * (-parseInt(_0x45c317(0xe2)) / 0x9);
                  if (_0x5d4b57 === _0x17d1b4) break;
                  else _0x1cccab['push'](_0x1cccab['shift']());
              } catch (_0x110420) {
                  _0x1cccab['push'](_0x1cccab['shift']());
              }
          }
      }(_0x1cda, 0x61924));
      var nemaApprovalSpecifySection = document['getElementById'](_0x2c8272(0xeb));

      function _0x2ea2(_0x2f2ba9, _0x3dc41f) {
          var _0x1cda2c = _0x1cda();
          return _0x2ea2 = function(_0x2ea2be, _0x1e0963) {
              _0x2ea2be = _0x2ea2be - 0xdf;
              var _0x1ac9d9 = _0x1cda2c[_0x2ea2be];
              return _0x1ac9d9;
          }, _0x2ea2(_0x2f2ba9, _0x3dc41f);
      }

      function nemaApprovalShow() {
          var _0x3e9817 = _0x2c8272;
          nemaApprovalSpecifySection[_0x3e9817(0xf3)][_0x3e9817(0xed)] = _0x3e9817(0xe6), $(_0x3e9817(0xe1))[_0x3e9817(0xf1)](function(_0x41d4df) {
              var _0xec7c9e = _0x3e9817;
              _0x41d4df[_0xec7c9e(0xec)]();
              if ($(_0xec7c9e(0xe0))[_0xec7c9e(0xe4)]() == '') return alert('Nema\x20Approval\x20Number\x20Required'), ![];
              else {
                  if ($(_0xec7c9e(0xe5))[_0xec7c9e(0xe4)]() == '') return alert(_0xec7c9e(0xea)), ![];
                  else {
                      if ($('#nemaApprovalCopy')[_0xec7c9e(0xe4)]() == '') return alert('NEMA\x20Approval\x20Copy\x20Required'), ![];
                      else nemaApprovalSpecifySection['style'][_0xec7c9e(0xed)] = _0xec7c9e(0xe9);
                  }
              }
          });
      }

      function nemaApprovalHide() {
          var _0x1051b7 = _0x2c8272;
          nemaApprovalSpecifySection[_0x1051b7(0xf3)][_0x1051b7(0xed)] = 'none';
      }

      //Local Government Specifications DOM
      var _0x514fd3 = _0x194b;
      (function(_0x143c7b, _0x3e2e5a) {
          var _0x5074ad = _0x194b,
              _0x4afc78 = _0x143c7b();
          while (!![]) {
              try {
                  var _0x4128cf = parseInt(_0x5074ad(0x8f)) / 0x1 * (parseInt(_0x5074ad(0x88)) / 0x2) + -parseInt(_0x5074ad(0x80)) / 0x3 + parseInt(_0x5074ad(0x85)) / 0x4 + -parseInt(_0x5074ad(0x81)) / 0x5 * (-parseInt(_0x5074ad(0x92)) / 0x6) + parseInt(_0x5074ad(0x94)) / 0x7 + parseInt(_0x5074ad(0x87)) / 0x8 + -parseInt(_0x5074ad(0x83)) / 0x9 * (parseInt(_0x5074ad(0x96)) / 0xa);
                  if (_0x4128cf === _0x3e2e5a) break;
                  else _0x4afc78['push'](_0x4afc78['shift']());
              } catch (_0x50a160) {
                  _0x4afc78['push'](_0x4afc78['shift']());
              }
          }
      }(_0x5af9, 0x914ea));
      var localGovSpecificationsSection = document[_0x514fd3(0x93)](_0x514fd3(0x84));

      function _0x194b(_0x118b21, _0x3a4f8e) {
          var _0x5af90a = _0x5af9();
          return _0x194b = function(_0x194b18, _0x111a33) {
              _0x194b18 = _0x194b18 - 0x7f;
              var _0x48a932 = _0x5af90a[_0x194b18];
              return _0x48a932;
          }, _0x194b(_0x118b21, _0x3a4f8e);
      }

      function showLocalGovernmentApproval() {
          var _0x56391d = _0x514fd3;
          localGovSpecificationsSection[_0x56391d(0x82)][_0x56391d(0x8b)] = _0x56391d(0x86), $(_0x56391d(0x91))[_0x56391d(0x8c)](function() {
              var _0x4fe001 = _0x56391d;
              if ($(_0x4fe001(0x8a))['val']() == '') return alert(_0x4fe001(0x90)), ![];
              else {
                  if ($(_0x4fe001(0x95))[_0x4fe001(0x8e)]() == '') return alert(_0x4fe001(0x8d)), ![];
                  else {
                      if ($(_0x4fe001(0x89))[_0x4fe001(0x8e)]() == '') return alert('Local\x20Government\x20Approval\x20Copy\x20Required'), ![];
                      else localGovSpecificationsSection[_0x4fe001(0x82)][_0x4fe001(0x8b)] = _0x4fe001(0x7f);
                  }
              }
          });
      }

      function _0x5af9() {
          var _0xbff494 = ['Local\x20Government\x20Approval\x20Number\x20Required', '#closeLocalGovSpecifications', '6MppNfR', 'getElementById', '8094352MNXAtW', '#localGovApprovalDate', '3530fpRMsq', 'none', '159696cLXDtW', '1876370YKDDhM', 'style', '91683thckcI', 'localGovSpecifications', '4370420mhFssb', 'block', '8563352MCOvih', '157082ekJuOe', '#localGovApprovalCopy', '#localGovApprovalNo', 'display', 'click', 'Local\x20Government\x20Approval\x20Date\x20Required', 'val', '7jtmRls'];
          _0x5af9 = function() {
              return _0xbff494;
          };
          return _0x5af9();
      }

      function hideLocalGovernmentApproval() {
          var _0x5da196 = _0x514fd3;
          localGovSpecificationsSection[_0x5da196(0x82)]['display'] = _0x5da196(0x7f);
      }

      //Insurance Policy Scripts
      function _0x21cb(_0x4f2690, _0x32aca6) {
          var _0xc109c6 = _0xc109();
          return _0x21cb = function(_0x21cb18, _0x29df81) {
              _0x21cb18 = _0x21cb18 - 0x9f;
              var _0x144932 = _0xc109c6[_0x21cb18];
              return _0x144932;
          }, _0x21cb(_0x4f2690, _0x32aca6);
      }
      var _0x1e19e0 = _0x21cb;
      (function(_0x4c8f09, _0x38f0d3) {
          var _0x589c90 = _0x21cb,
              _0x113959 = _0x4c8f09();
          while (!![]) {
              try {
                  var _0x5f6e02 = parseInt(_0x589c90(0xa0)) / 0x1 + parseInt(_0x589c90(0xac)) / 0x2 + parseInt(_0x589c90(0xa9)) / 0x3 * (parseInt(_0x589c90(0x9f)) / 0x4) + parseInt(_0x589c90(0xa3)) / 0x5 + parseInt(_0x589c90(0xb2)) / 0x6 * (-parseInt(_0x589c90(0xaa)) / 0x7) + parseInt(_0x589c90(0xae)) / 0x8 + parseInt(_0x589c90(0xb0)) / 0x9 * (-parseInt(_0x589c90(0xa6)) / 0xa);
                  if (_0x5f6e02 === _0x38f0d3) break;
                  else _0x113959['push'](_0x113959['shift']());
              } catch (_0x45d490) {
                  _0x113959['push'](_0x113959['shift']());
              }
          }
      }(_0xc109, 0xbad63));

      function _0xc109() {
          var _0x35a3d4 = ['#policy_until_date', '#closeInsuranceInfoBtn', '2804UQEFsX', '1462464OTAVNC', 'Please\x20Specify\x20Policy\x20Expiry\x20Date', 'preventDefault', '7035540xsatDb', 'val', 'Insurance\x20Policy\x20Initial\x20Date\x20Required', '5925290mwjMyt', 'style', 'getElementById', '4839BzIiwv', '7sETDLJ', 'display', '2104552OWzbUa', 'none', '9083640Tkimbi', 'Insurance\x20Policy\x20Provider\x20Required', '63TxLyEZ', '#insurance_provider', '7650180WPUXDo'];
          _0xc109 = function() {
              return _0x35a3d4;
          };
          return _0xc109();
      }
      var specifyInsuranceCoverInfoCardInfo = document[_0x1e19e0(0xa8)]('specifyInsuranceCoverInfoCard');

      function insuranceCoverYes() {
          var _0x34ea4d = _0x1e19e0;
          specifyInsuranceCoverInfoCardInfo[_0x34ea4d(0xa7)][_0x34ea4d(0xab)] = 'block', $(_0x34ea4d(0xb4))['click'](function(_0x3eb00c) {
              var _0x224936 = _0x34ea4d;
              _0x3eb00c[_0x224936(0xa2)]();
              if ($('#insurance_policy')[_0x224936(0xa4)]() == '') return alert('Insurance\x20Policy\x20Provider\x20Required'), ![];
              else {
                  if ($(_0x224936(0xb1))['val']() == '') return alert(_0x224936(0xaf)), ![];
                  else {
                      if ($('#policy_from_date')[_0x224936(0xa4)]() == '') return alert(_0x224936(0xa5)), ![];
                      else {
                          if ($(_0x224936(0xb3))[_0x224936(0xa4)]() == '') return alert(_0x224936(0xa1)), ![];
                          else specifyInsuranceCoverInfoCardInfo[_0x224936(0xa7)][_0x224936(0xab)] = _0x224936(0xad);
                      }
                  }
              }
          });
      }

      function insuranceCoverNo() {
          var _0x2e3351 = _0x1e19e0;
          specifyInsuranceCoverInfoCardInfo[_0x2e3351(0xa7)]['display'] = _0x2e3351(0xad);
      }

      //Deposits DOM
      var depositCardSection = document.getElementById('depositCard');

      function showDepositBox() {
          depositCardSection.style.display = 'block';
      }

      function hideDepositBox() {
          depositCardSection.style.display = 'none';
      }

      //Step by Step Building Registration and Validations DOM -->
      $(document).ready(function() {

          $("#stepOneNextBtn").click(function(e) {
              e.preventDefault();
              $("#sectionTwo").show();
              $("#sectionOne").hide();

              $("#stepOneIndicatorNo").html('<i class="fa fa-check"><i>');
              $("#stepOneIndicatorNo").css('background-color', '#FFC107');
              $("#stepOneIndicatorNo").css('color', '#00192D');
              $("#stepOneIndicatorText").html('Done');
          });

          $("#stepTwoBackBtn").click(function(e) {
              e.preventDefault();
              $("#sectionTwo").hide();
              $("#sectionOne").show();

              $("#stepOneIndicatorNo").html('1');
              $("#stepOneIndicatorNo").css('background-color', '#00192D');
              $("#stepOneIndicatorNo").css('color', '#FFC107');
              $("#stepOneIndicatorText").html('Overview');
          });

          $("#stepTwoNextBtn").click(function(e) {
              e.preventDefault();
              $("#sectionTwo").hide();
              $("#sectionThree").show();

              $("#stepTwoIndicatorNo").html('<i class="fa fa-check"><i>');
              $("#stepTwoIndicatorNo").css('background-color', '#FFC107');
              $("#stepTwoIndicatorNo").css('color', '#00192D');
              $("#stepTwoIndicatorText").html('Done');
          });

          $("#stepThreeBackBtn").click(function(e) {
              e.preventDefault();
              $("#sectionTwo").show();
              $("#sectionThree").hide();

              $("#stepTwoIndicatorNo").html('2');
              $("#stepTwoIndicatorNo").css('background-color', '#00192D');
              $("#stepTwoIndicatorNo").css('color', '#FFC107');
              $("#stepTwoIndicatorText").html('Identification');
          });

          $("#stepThreeNextBtn").click(function(e) {
              e.preventDefault();
              $("#sectionThree").hide();
              $("#sectionFour").show();

              $("#stepThreeIndicatorNo").html('<i class="fa fa-check"><i>');
              $("#stepThreeIndicatorNo").css('background-color', '#FFC107');
              $("#stepThreeIndicatorNo").css('color', '#00192D');
              $("#stepThreeIndicatorText").html('Done');
          });

          $("#stepFourBackBtn").click(function(e) {
              e.preventDefault();
              $("#sectionThree").show();
              $("#sectionFour").hide();

              $("#stepThreeIndicatorNo").html('3');
              $("#stepThreeIndicatorNo").css('background-color', '#00192D');
              $("#stepThreeIndicatorNo").css('color', '#FFC107');
              $("#stepThreeIndicatorText").html('Ownership');
          });

          $("#stepFourNextBtn").click(function(e) {
              e.preventDefault();
              $("#sectionFour").hide();
              $("#sectionFive").show();

              $("#stepFourIndicatorNo").html('<i class="fa fa-check"><i>');
              $("#stepFourIndicatorNo").css('background-color', '#FFC107');
              $("#stepFourIndicatorNo").css('color', '#00192D');
              $("#stepFourIndicatorText").html('Done');
          });

          $("#stepFiveBackBtn").click(function(e) {
              e.preventDefault();
              $("#sectionFour").show();
              $("#sectionFive").hide();

              $("#stepFourIndicatorNo").html('4');
              $("#stepFourIndicatorNo").css('background-color', '#00192D');
              $("#stepFourIndicatorNo").css('color', '#FFC107');
              $("#stepFourIndicatorText").html('Utilities');
          });

          $("#stepFiveNextBtn").click(function(e) {
              e.preventDefault();
              $("#sectionFive").hide();
              $("#sectionSix").show();

              $("#stepFiveIndicatorNo").html('<i class="fa fa-check"><i>');
              $("#stepFiveIndicatorNo").css('background-color', '#FFC107');
              $("#stepFiveIndicatorNo").css('color', '#00192D');
              $("#stepFiveIndicatorText").html('Done');
          });

          $("#stepSixBackBtn").click(function(e) {
              e.preventDefault();
              $("#sectionFive").show();
              $("#sectionSix").hide();

              $("#stepFiveIndicatorNo").html('5');
              $("#stepFiveIndicatorNo").css('background-color', '#00192D');
              $("#stepFiveIndicatorNo").css('color', '#FFC107');
              $("#stepFiveIndicatorText").html('Regulations');
          });

          $("#stepSixNextBtn").click(function(e) {
              e.preventDefault();
              $("#sectionSix").hide();
              $("#sectionSeven").show();

              $("#stepSixIndicatorNo").html('<i class="fa fa-check"><i>');
              $("#stepSixIndicatorNo").css('background-color', '#FFC107');
              $("#stepSixIndicatorNo").css('color', '#00192D');
              $("#stepSixIndicatorText").html('Done');
          });

          $("#stepSevenBackBtn").click(function(e) {
              e.preventDefault();
              $("#sectionSix").show();
              $("#sectionSeven").hide();

              $("#stepSixIndicatorNo").html('6');
              $("#stepSixIndicatorNo").css('background-color', '#00192D');
              $("#stepSixIndicatorNo").css('color', '#FFC107');
              $("#stepSixIndicatorText").html('Insurance');
          });

          $("#stepSevenNextBtn").click(function(e) {
              e.preventDefault();
              $("#sectionSeven").hide();
              $("#sectionEight").show();

              $("#stepSevenIndicatorNo").html('<i class="fa fa-check"><i>');
              $("#stepSevenIndicatorNo").css('background-color', '#FFC107');
              $("#stepSevenIndicatorNo").css('color', '#00192D');
              $("#stepSevenIndicatorText").html('Done');
          });

          $("#stepEightBackBtn").click(function(e) {
              e.preventDefault();
              $("#sectionSeven").show();
              $("#sectionEight").hide();

              $("#stepSevenIndicatorNo").html('7');
              $("#stepSevenIndicatorNo").css('background-color', '#00192D');
              $("#stepSevenIndicatorNo").css('color', '#FFC107');
              $("#stepSevenIndicatorText").html('Photos');
          });

      });
    </script>



<script>
        $(document).ready(function () {
            $('#myTableOne').DataTable();
        });
        $(document).ready(function () {
            $('#myTableThree').DataTable();
        });
        $(document).ready(function () {
            $('#myTableFour').DataTable();
        });


        $(document).ready(function() {
   $('#myTable').DataTable({
       "paging": true,
       "searching": true,
       "info": true,
       "lengthMenu": [5, 10, 25, 50],
       "language": {
           "search": "Filter records:",
           "lengthMenu": "Show _MENU_ entries"
       }
   });
});

</script>


<!-- Sidebar script -->
<script>
  fetch('../bars/sidebar.html')  // Fetch the file
      .then(response => response.text()) // Convert it to text
      .then(data => {
          document.getElementById('sidebar').innerHTML = data; // Insert it
      })
      .catch(error => console.error('Error loading the file:', error)); // Handle errors
</script>

<!-- End sidebar script -->

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
  const cty = document.getElementById('rentalTrends').getContext('2d');

  new Chart(cty, {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [
        {
          label: 'CROWN Z TOWERS',
          data: [35,'000', 30,'000', 32,'000', 30,'000', 33,'000', 35,'000', 34,'000', 33,'000', 34,'000', 35,'000', 34,'000', 35,'000'],
          borderColor: 'cyan',
          backgroundColor: 'transparent',
          tension: 0.4
        },
        {
          label: 'Manucho Apartments',
          data: [32,'000', 33,'000', 38,'000', 34,'000', 33,'000', 34,'000', 38,'000', 36,'000', 37,'000', 32,'000', 31,'000', 34,'000'],
          borderColor: 'green',
          backgroundColor: 'transparent',
          tension: 0.4
        },
        {
          label: 'The Mansion Apartments',
          data:[31,'000', 32,'000', 39,'000', 35,'000', 32,'000', 33,'000', 39,'000', 37,'000', 39,'000', 31,'000', 32,'000', 34,'000'],
          borderColor: 'black',
          backgroundColor: 'transparent',
          tension: 0.4
        },
        {
          label: 'Bsty Apartments',
          data: [34,'000', 39,'000', 34,'000', 32,'000', 34,'000', 36,'000', 38,'000', 37,'000', 34,'000', 33,'000', 32,'000', 31,'000'],
          borderColor: 'red',
          backgroundColor: 'transparent',
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top'
        },

      },
      scales: {
        y: {
          beginAtZero: false
        }
      }
    }
  });
</script>

<script>
  const ctx = document.getElementById('myPieChart').getContext('2d');

  const myPieChart = new Chart(ctx, {
      type: 'pie',
      data: {
          labels: ['Occupied','Vacant', 'Vacant Soon'],
          datasets: [{
              data: [30, 50, 20],
              backgroundColor: ['#28a745', '#ffc107', '#dc3545']
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          onClick: function(event, elements) {
              if (elements.length > 0) {
                  let index = elements[0].index;
                  let label = this.data.labels[index];
                  // let links = { "Approved": "approved.html", "Pending": "pending.html", "Rejected": "rejected.html" };
                  let links = { "Occupied": "occupied.html", "Vacant": "vacant.html", "Vacant Soon": "vacantsoon.html"};
                  if (links[label]) window.location.href = links[label];
              }
          }
      }
  });
</script>



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
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
