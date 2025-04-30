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
        $building_name = $_POST['building_name'];
        $county = $_POST['county'];
        $constituency = $_POST['constituency'];
        $ward = $_POST['ward'];
        $floor_number = $_POST['floor_number'];
        $units_number = $_POST['units_number'];
        $building_type = $_POST['building_type'] ?? '';
        $ownership_info = $_POST['ownership_info'];

        $titleDeedPath = uploadPhoto('title_deed_copy');
        $otherDocPath = uploadPhoto('other_document_copy');
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
        // $building_number = $_POST['building_number'];

        // Ownership fields
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

        // SQL Insert
        $sql = "INSERT INTO buildings (
            building_name, county, constituency, ward, floor_number, units_number, building_type,
            ownership_info,
            first_name, last_name, phone_number, kra_pin, email,
            entity_name, entity_phone, entity_email, entity_kra_pin, entity_representative, entity_rep_role,
            title_deed_copy, other_document_copy, borehole_availability, solar_availability, solar_brand,
            installation_company, no_of_panels, solar_primary_use, parking_lot, alarm_system, elevators,
            psds_accessibility, cctv, nca_approval, nca_approval_no, nca_approval_date,
            local_gov_approval, local_gov_approval_no, local_gov_approval_date,
            nema_approval, nema_approval_no, nema_approval_date,
            building_tax_pin, insurance_cover, insurance_policy, insurance_provider,
            policy_from_date, policy_until_date, front_view_photo, rear_view_photo, angle_view_photo, interior_view_photo
        ) VALUES (
            :building_name, :county, :constituency, :ward, :floor_number, :units_number, :building_type,
            :ownership_info,
            :first_name, :last_name, :phone_number, :kra_pin, :email,
            :entity_name, :entity_phone, :entity_email, :entity_kra_pin, :entity_representative, :entity_rep_role,
            :title_deed_copy, :other_document_copy, :borehole_availability, :solar_availability, :solar_brand,
            :installation_company, :no_of_panels, :solar_primary_use, :parking_lot, :alarm_system, :elevators,
            :psds_accessibility, :cctv, :nca_approval, :nca_approval_no, :nca_approval_date,
            :local_gov_approval, :local_gov_approval_no, :local_gov_approval_date,
            :nema_approval, :nema_approval_no, :nema_approval_date,
            :building_tax_pin, :insurance_cover, :insurance_policy, :insurance_provider,
            :policy_from_date, :policy_until_date, :front_view_photo, :rear_view_photo, :angle_view_photo, :interior_view_photo
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
            // ':building_number' => $building_number,
        ]);

        // ✅ Redirect to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Fetch buildings for display
    $sql = "SELECT building_id, building_name, county, building_type, ownership_info FROM buildings";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
      <button onclick="handleDelete(event, <?= $building['building_id'] ?>, 'building')"
        class="btn btn-sm"
        style="background-color: red; color: white;">
    <i class="fa fa-trash" data-toggle="tooltip" title="Delete Building" style="font-size: 12px;"></i>
</button>


    </td>
  </tr>
<?php endforeach; ?>
       </tbody>
     </table>

                    <!--end::Row-->
                  </div>
                  <!-- ./card-body -->
                  <!-- <div class="card-footer"> -->
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


             <!-- units popup -->



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

<script src="registration.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="prop.js"></script>
<script>
$(document).ready((function(){$("#stepOneNextBtn").click((function(e){e.preventDefault(),$("#sectionTwo").show(),$("#sectionOne").hide(),$("#stepOneIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepOneIndicatorNo").css("background-color","#FFC107"),$("#stepOneIndicatorNo").css("color","#00192D"),$("#stepOneIndicatorText").html("Done")})),$("#stepTwoBackBtn").click((function(e){e.preventDefault(),$("#sectionTwo").hide(),$("#sectionOne").show(),$("#stepOneIndicatorNo").html("1"),$("#stepOneIndicatorNo").css("background-color","#00192D"),$("#stepOneIndicatorNo").css("color","#FFC107"),$("#stepOneIndicatorText").html("Overview")})),$("#stepTwoNextBtn").click((function(e){e.preventDefault(),$("#sectionTwo").hide(),$("#sectionThree").show(),$("#stepTwoIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepTwoIndicatorNo").css("background-color","#FFC107"),$("#stepTwoIndicatorNo").css("color","#00192D"),$("#stepTwoIndicatorText").html("Done")})),$("#stepThreeBackBtn").click((function(e){e.preventDefault(),$("#sectionTwo").show(),$("#sectionThree").hide(),$("#stepTwoIndicatorNo").html("2"),$("#stepTwoIndicatorNo").css("background-color","#00192D"),$("#stepTwoIndicatorNo").css("color","#FFC107"),$("#stepTwoIndicatorText").html("Identification")})),$("#stepThreeNextBtn").click((function(e){e.preventDefault(),$("#sectionThree").hide(),$("#sectionFour").show(),$("#stepThreeIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepThreeIndicatorNo").css("background-color","#FFC107"),$("#stepThreeIndicatorNo").css("color","#00192D"),$("#stepThreeIndicatorText").html("Done")})),$("#stepFourBackBtn").click((function(e){e.preventDefault(),$("#sectionThree").show(),$("#sectionFour").hide(),$("#stepThreeIndicatorNo").html("3"),$("#stepThreeIndicatorNo").css("background-color","#00192D"),$("#stepThreeIndicatorNo").css("color","#FFC107"),$("#stepThreeIndicatorText").html("Ownership")})),$("#stepFourNextBtn").click((function(e){e.preventDefault(),$("#sectionFour").hide(),$("#sectionFive").show(),$("#stepFourIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepFourIndicatorNo").css("background-color","#FFC107"),$("#stepFourIndicatorNo").css("color","#00192D"),$("#stepFourIndicatorText").html("Done")})),$("#stepFiveBackBtn").click((function(e){e.preventDefault(),$("#sectionFour").show(),$("#sectionFive").hide(),$("#stepFourIndicatorNo").html("4"),$("#stepFourIndicatorNo").css("background-color","#00192D"),$("#stepFourIndicatorNo").css("color","#FFC107"),$("#stepFourIndicatorText").html("Utilities")})),$("#stepFiveNextBtn").click((function(e){e.preventDefault(),$("#sectionFive").hide(),$("#sectionSix").show(),$("#stepFiveIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepFiveIndicatorNo").css("background-color","#FFC107"),$("#stepFiveIndicatorNo").css("color","#00192D"),$("#stepFiveIndicatorText").html("Done")})),$("#stepSixBackBtn").click((function(e){e.preventDefault(),$("#sectionFive").show(),$("#sectionSix").hide(),$("#stepFiveIndicatorNo").html("5"),$("#stepFiveIndicatorNo").css("background-color","#00192D"),$("#stepFiveIndicatorNo").css("color","#FFC107"),$("#stepFiveIndicatorText").html("Regulations")})),$("#stepSixNextBtn").click((function(e){e.preventDefault(),$("#sectionSix").hide(),$("#sectionSeven").show(),$("#stepSixIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepSixIndicatorNo").css("background-color","#FFC107"),$("#stepSixIndicatorNo").css("color","#00192D"),$("#stepSixIndicatorText").html("Done")})),$("#stepSevenBackBtn").click((function(e){e.preventDefault(),$("#sectionSix").show(),$("#sectionSeven").hide(),$("#stepSixIndicatorNo").html("6"),$("#stepSixIndicatorNo").css("background-color","#00192D"),$("#stepSixIndicatorNo").css("color","#FFC107"),$("#stepSixIndicatorText").html("Insurance")})),$("#stepSevenNextBtn").click((function(e){e.preventDefault(),$("#sectionSeven").hide(),$("#sectionEight").show(),$("#stepSevenIndicatorNo").html('<i class="fa fa-check"><i>'),$("#stepSevenIndicatorNo").css("background-color","#FFC107"),$("#stepSevenIndicatorNo").css("color","#00192D"),$("#stepSevenIndicatorText").html("Done")})),$("#stepEightBackBtn").click((function(e){e.preventDefault(),$("#sectionSeven").show(),$("#sectionEight").hide(),$("#stepSevenIndicatorNo").html("7"),$("#stepSevenIndicatorNo").css("background-color","#00192D"),$("#stepSevenIndicatorNo").css("color","#FFC107"),$("#stepSevenIndicatorText").html("Photos")}))}));
  // </script>
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

<script
  src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
  integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
  crossorigin="anonymous"
></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
 <script src="../../../dist/js/adminlte.js"></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
 <!--end::OverlayScrollbars Configure-->
 <!-- OPTIONAL SCRIPTS -->
<!-- apexcharts -->
    <end::Script-->
  </body>
  <!--end::Body-->
</html>
