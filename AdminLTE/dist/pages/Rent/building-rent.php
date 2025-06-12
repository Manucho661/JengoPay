<?php
include '../db/connect.php';

$building = $_GET['building'] ?? null;

if ($building) {
    $stmt = $pdo->prepare("SELECT * FROM rent_payments WHERE building_name = ?");
    $stmt->execute([$building]);
    $payments = $stmt->fetchAll();

    // Display payments table or details...
} else {
    echo "Building not selected.";
}


?>
<?php
// Include your DB connection
require_once '../db/connect.php'; // adjust path as needed

$buildingName = 'Unknown';

if (isset($_GET['building_id'])) {
    $building_id = intval($_GET['building_id']);

    $stmt = $pdo->prepare("SELECT building_name FROM building_rent_summary WHERE id = ?");
    $stmt->execute([$building_id]);

    $building = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($building) {
        $buildingName = htmlspecialchars($building['building_name']);
    }
}

try {
    // Existing query: fetch tenant details (you might want to filter by building here, if needed)
    $stmt = $pdo->query("SELECT tenant_name, penalty_days, arrears, overpayment FROM tenant_rent_summary");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // New query: fetch summary totals grouped by building
    $summaryQuery = $pdo->query("
        SELECT
          building_name AS Building,
          CONCAT('KSH ', FORMAT(SUM(amount_paid), 2)) AS Collected,
          CONCAT('KSH ', FORMAT(SUM(penalty), 2)) AS Penalties,
          CONCAT('KSH ', FORMAT(SUM(arrears), 2)) AS Arrears,
          CONCAT('KSH ', FORMAT(SUM(overpayment), 2)) AS Overpayment
        FROM tenant_rent_summary
        GROUP BY building_name
        ORDER BY building_name
    ");
    $buildingSummaries = $summaryQuery->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

?>

<?php
include '../db/connect.php';

$buildingName = $_GET['building'] ?? '';

// If nothing is selected, show all buildings
if ($buildingName === '') {
    $stmt = $pdo->query("SELECT * FROM tenant_rent_summary");
} else {
    $stmt = $pdo->prepare("SELECT * FROM tenant_rent_summary WHERE building_name = ?");
    $stmt->execute([$buildingName]);
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("
    SELECT
        u.first_name,
        u.middle_name,
        t.residence,
        t.unit,
        t.phone_number,
        t.job_title,
        t.income_source,
        t.work_place,
        t.status
    FROM tenants t
    JOIN users u ON t.user_id = u.id
    WHERE t.status = 'active'
");
$tenants = $stmt->fetchAll();

try {
  $stmt = $pdo->query("SELECT tenant_name, unit_code, amount_paid, payment_date, penalty, penalty_days, arrears, overpayment FROM tenant_rent_summary");
  $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching tenants: " . $e->getMessage());
}
?>
<?php
include '../db/connect.php';

// Get the building name from the URL parameter
$buildingName = isset($_GET['building']) ? urldecode($_GET['building']) : '';

// Fetch data for the specific building
$stmt = $pdo->prepare("SELECT * FROM tenant_rent_summary WHERE building_name = ?");
$stmt->execute([$buildingName]);
$tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$totalCollected = 0;
$totalPenalties = 0;
$totalArrears = 0;
$totalOverpayment = 0;

foreach ($tenants as $tenant) {
    $totalCollected += floatval($tenant['amount_paid']);
    $totalPenalties += floatval($tenant['penalty']);
    $totalArrears += floatval($tenant['arrears']);
    $totalOverpayment += floatval($tenant['overpayment']);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="building-rent.css">

    <!-- scripts for data_table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="announcements.css">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- loading out and in progress -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">

<style>
.btn.print {
    background-color:#00192D;
    color:#FFC107;
    padding: 4px 8px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.2s ease-in-out;
}

.btn.print i {
    font-size: 16px;
}

.btn.print:hover {
    background-color: #00192D;
}


</style>

  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper" style="background-color:rgba(128,128,128, 0.1);">
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
            <img
              src="../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div id="sidebar"></div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main id="mainElement" class="app-main fade-out" >
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0"></h3></div>
              <div class="col-sm-6">

              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div  class="app-content">

          <div class="container-fluid">
            <!-- First Row -->
            <div class="row" >
              <div class="col-sm-8">
                <div class="d-flex">
                <h3 class="contact_section_header">
  <i class="fas fa-coins icon"></i>
  Rental Roll &nbsp;/&nbsp;<span class="building"><?php echo $buildingName;?></span>
</h3>

                    <h6 class="month"><i class="fas fa-calendar-alt"></i>
                      April-2025</h6>
                </div>

              </div>
              <div class="col-sm-4 home">
                <div class="row float-sm-end">
                     <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#" style="color: #00192D;">  <i class="bi bi-house"></i> Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                      </ol>
                </div>
              </div>
            </div>
            <!-- End first Row -->

            <?php
            $totalCollected = 0;
            $totalPenalties = 0;
            $totalArrears = 0;
            $totalOverpayment = 0;
            ?>

<?php foreach ($tenants as $tenant): ?>
    <?php
        $amountPaid = floatval($tenant['amount_paid']);
        $penalty = floatval($tenant['penalty']);
        $arrears = floatval($tenant['arrears']);
        $overpayment = floatval($tenant['overpayment']);

        // Add to totals
        $totalCollected += $amountPaid;
        $totalPenalties += $penalty;
        $totalArrears += $arrears;
        $totalOverpayment += $overpayment;

        // Formatting values for display
        $nameParts = explode(" ", $tenant['tenant_name']);
        $firstName = $nameParts[0] ?? '';
        $middleName = $nameParts[1] ?? '';
        $unit = $tenant['unit_code'];
        $amount = number_format($amountPaid, 2);
        $penaltyFormatted = number_format($penalty, 2);
        $arrearsFormatted = number_format($arrears, 2);
        $overpaymentFormatted = number_format($overpayment, 2);
        $penaltyDays = (int)$tenant['penalty_days'];
        $paymentDate = date("d-F", strtotime($tenant['payment_date']));
    ?>
    <!-- existing <tr> rendering here -->
<?php endforeach; ?>


            <!-- START ROW -->
                                           <!-- SUMMARY -->
                                           <div class="row">
                                            <h6 class="mb-0 contact_section_header summary mb-2" style="color: #00192D;"> </i> Summary</h6>

                                            <div class="col-md-3">
                                              <div class="summary-item collected" style="display: flex; gap: 10px;   align-items: center; ">
                                                <div class="icon"> <i class="fas fa-house"></i></div>
                                                <div>
                                                  <div class="label">Total Units</div>
                                                  <div class="value"> &nbsp;100</div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="col-md-3">
                                              <div class="summary-item collected" style="display: flex; gap: 10px;   align-items: center; ">
                                                <div class="icon"> <i class="fas fa-house"></i></div>
                                                <div>
                                                  <div class="label">Occupied</div>
                                                  <div class="value"> &nbsp;50</div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="col-md-3">
                                              <div class="summary-item collected" style="display: flex; gap: 10px;   align-items: center; ">
                                                <div class="icon"> <i class="fas fa-coins"></i></div>
                                                <div>
                                                  <div class="label">Collected</div>
                                                  <div class="value"> KSH&nbsp;<?= number_format($totalCollected, 2) ?></div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="col-md-3">
                                              <div class="summary-item collected" style="display: flex; gap: 10px;   align-items: center; ">
                                                <div class="icon"> <i class="fas fa-coins"></i></div>
                                                <div>
                                                  <div class="label">Penalities</div>
                                                  <div class="value penalities"> KSH&nbsp;<?= number_format($totalPenalties, 2) ?></div>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="col-md-3 mt-1">
                                              <div class="summary-item collected" style="display: flex; gap: 10px;   align-items: center; ">
                                                <div class="icon"> <i class="fas fa-coins"></i></div>
                                                <div>
                                                  <div class="label">Arreas</div>
                                                  <div class="value"> KSH&nbsp;<?= number_format($totalArrears, 2) ?></div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="col-md-3 mt-1">
                                              <div class="summary-item collected" style="display: flex; gap: 10px;   align-items: center; ">
                                                <div class="icon"> <i class="fas fa-coins"></i></div>
                                                <div>
                                                  <div class="label">Overpayment</div>
                                                  <div class="value"> KSH&nbsp;<?= number_format($totalOverpayment, 2) ?></div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <!-- END ROW -->

                                          <!-- START ROW -->
             <div class="row mt-4">
              <!-- <div class="row"> -->
                <h6 class="mb-0 contact_section_header summary mb-2 mt-2" style="color: #00192D;"> </i> Details</h6>

                  <div class="col-md-9">
                    <div class="rent-info">
                        <div class="rent-info filter ">
                          <div class="filter-boxes">

                            <div class="select-option-container ">

                              <div class="custom-select" style="overflow: hidden;">All Buildings</div>
                              <div class="select-options mt-1">
                                <div class="selected" data-value="item1">All Units</div>
                                <div data-value="item1">Single Rooms</div>
                                <div data-value="item2">Bedsitters</div>

                              </div>
                            </div>

                            <div class="select-option-container ">
                              <div class="custom-select">2025</div>
                              <div class="select-options mt-1">
                                <div class="selected"  data-value="item1">2025</div>
                                <div data-value="item2">2024</div>
                                <div data-value="item3">2023</div>
                              </div>
                            </div>

                            <div class="select-option-container ">
                              <div class="custom-select">April</div>
                              <div class="select-options mt-1">
                                <div class="selected" data-value="item1">April</div>
                                <div data-value="item1">January</div>
                                <div data-value="item2">February</div>
                                <div data-value="item3">March</div>
                              </div>
                            </div>
                          </div>

                          <div class="">
                          <form method="post" action="generating-pdf.php" style="display: inline;">
                          <button  id="download-pdf"  type="submit" class="pdf">
                            <i class="fas fa-file-pdf" style="color: red;"></i>
                          </button>
                        </form>

                         <!-- <button  class="pdf" ><i class="fas fa-file-pdf" style="color: red;"></i></button> -->
                          <!-- <button class="excel"><i class="fas fa-file-excel" style="color: green;"></i></button> -->
                          <div class="">
                          <form method="post" action="exporting-excel.php">
                            <button type="submit" class="excel">
                              <i class="fas fa-file-excel" style="color: green;"></i>
                            </button>
                          </form>
                          </div>
                          </div>
                          </div>
                        <div class="rentTable section" >
                          <table id="rent" class="tableRent" style="font-size: small;" >
                            <thead>
                                <tr>
                                        <th scope="col">Tenant + unit</th>
                                        <th scope="col">
                                          <div>Paid</div>
                                        </th>
                                        <th scope="col">Penalty&nbsp;(l.days)</th>
                                        <th scope="col">Arreas</th>
                                        <th scope="col">Overpayment</th>
                                        <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $currentBuilding = '';
    foreach ($tenants as $tenant):
        $building = $tenant['building_name'] ?? '';

        // Show building name as a header row if it has changed
        if ($building !== $currentBuilding):
            $currentBuilding = $building;
    ?>
        <tr class="table-group-header bg-light">
            <td colspan="6" style="font-weight: bold; color: #007bff;">
                <?= htmlspecialchars($currentBuilding) ?>
            </td>
        </tr>
    <?php
        endif;

        $nameParts = explode(" ", $tenant['tenant_name'] ?? '');
        $firstName = $nameParts[0] ?? '';
        $middleName = $nameParts[1] ?? '';
        $unit = htmlspecialchars($tenant['unit_code'] ?? '');
        $amount = number_format((float)($tenant['amount_paid'] ?? 0), 2);
        $penalty = number_format((float)($tenant['penalty'] ?? 0), 2);
        $arrears = number_format((float)($tenant['arrears'] ?? 0), 2);
        $overpayment = number_format((float)($tenant['overpayment'] ?? 0), 2);
        $penaltyDays = (int)($tenant['penalty_days'] ?? 0);
        $paymentDate = !empty($tenant['payment_date']) ? date("d-F", strtotime($tenant['payment_date'])) : '';
    ?>
        <tr>
            <th>
                <div class="d-flex justify-content-between">
                    <div><?= htmlspecialchars("$firstName $middleName") ?></div>
                    <div class="value" style="color:#FFC107">&nbsp;<?= $unit ?></div>
                </div>
            </th>
            <td>
                <div class="rent paid">
                    <div>KSH&nbsp;<?= $amount ?></div>
                    <div class="date late"><?= $paymentDate ?></div>
                </div>
            </td>
            <td>
                <div class="rent penalit">
                    KSH&nbsp;<?= $penalty ?>
                    (<span class="rent lateDays">-<?= $penaltyDays ?></span>)
                </div>
            </td>
            <td class="rent collected">KSH&nbsp;<?= $arrears ?></td>
            <td class="rent overpayment">KSH&nbsp;<?= $overpayment ?></td>
            <td>
                <button class="btn view">
                    <a class="view-link" href="../people/tenant-profile.php">View</a>
                </button>
                <button class="btn print" onclick="window.open('print-receipt.php?tenant_id=<?= $tenant['id'] ?>', '_blank')">
                <i class="fas fa-file-invoice"></i>Receipt
</button>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

                    </table>

                        </div>
                    </div>


                  </div>

                  <div class="col-md-3" >


                    <div class="p-2 rent-payment-deadline">
                      <div class="label ">
                        RENT PAYMENT DEADLINE </div>
                      <div class="deadline date"><i class="fas fa-calendar-alt"></i> 30-4-2023</div>
                      <div class="change-btn d-flex justify-content-end"><button class="btn edit rounded">Edit</button></div>
                    </div>


                    <div class="penalty-rate p-2 mt-2 " >

                      <div class="label">PENALTY RATE</div>
                      <div class="penalt_desc d-flex">
                          <div class="pen-rate">10%</div>
                          <div class="pen-desc " style="white-space: nowrap; overflow: hidden;">of the total rent</div>
                      </div>
                       <div class="change-btn d-flex justify-content-end">
    <button class="btn edit rounded" data-bs-toggle="modal" data-bs-target="#editPenaltyModal">Edit</button>
  </div>

                  </div>
           </div>


           <!-- Modal for Editing Penalty Rate -->
<div class="modal fade" id="editPenaltyModal" tabindex="-1" aria-labelledby="editPenaltyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-3">
      <div class="modal-header" style="background-color: #00192D;">
        <h5 class="modal-title" id="editPenaltyModalLabel" style="background-color: #00192D; color: #FFC107;">Edit Penalty Rate</h5>
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
      </div>
      <div class="modal-body">
        <form id="penaltyRateForm">
          <div class="mb-3">
            <label for="penaltyRateInput" class="form-label">Penalty Rate (%)</label>
            <input type="number" class="form-control" id="penaltyRateInput" value="10" min="0" step="0.1" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: white; color: #00192D">Cancel</button>
        <button type="button" class="btn btn-primary" id="savePenaltyRateBtn" style="background-color: #00192D; color: #FFC107;">Save Changes</button>
      </div>
    </div>
  </div>
</div>

          <!-- END ROW -->

          </div>
        </div>
      </div>

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



          <!-- Edit Rent Deadline Modal -->
<div class="modal fade" id="editRentDeadlineModal" tabindex="-1" aria-labelledby="editRentDeadlineModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="rent-deadline-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editRentDeadlineModalLabel">Edit Rent Payment Deadline</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label for="rent-deadline-date">Select New Deadline:</label>
          <input type="date" id="rent-deadline-date" name="rent_deadline" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>



<script>
  document.getElementById('rent-deadline-form').addEventListener('submit', function(e) {
  e.preventDefault();

  const newDate = document.getElementById('rent-deadline-date').value;

  // Send to PHP backend via fetch or AJAX
  fetch('update_rent_deadline.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `rent_deadline=${encodeURIComponent(newDate)}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      document.getElementById('current-deadline').innerHTML = `<i class="fas fa-calendar-alt"></i> ${newDate}`;
      var modal = bootstrap.Modal.getInstance(document.getElementById('editRentDeadlineModal'));
      modal.hide();
    } else {
      alert('Failed to update deadline.');
    }
  });
});

</script>


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

</script>




</script>

<!-- End script for data_table -->
<!-- Js for edit penalty rates -->
<script>
document.getElementById('savePenaltyRateBtn').addEventListener('click', function () {
  const input = document.getElementById('penaltyRateInput');
  const newRate = input.value;

  if (newRate !== '' && !isNaN(newRate)) {
    document.getElementById('currentPenaltyRate').textContent = `${newRate}%`;

    // Optionally, make an AJAX call here to save the new rate to the server

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('editPenaltyModal'));
    modal.hide();
  }
});
</script>



<!--Begin sidebar script -->
<script>
  fetch('../bars/sidebar.html')  // Fetch the file
      .then(response => response.text()) // Convert it to text
      .then(data => {
          document.getElementById('sidebar').innerHTML = data; // Insert it
      })
      .catch(error => console.error('Error loading the file:', error)); // Handle errors
</script>
<!-- end sidebar script -->


    <!-- LOADING AND OUT PROGRESS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <!-- EnD LOADING AND OUT PROGRESS -->

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

<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelector('.pdf').addEventListener('click', function () {
        // Open the PDF report in a new tab
        window.open('download-report.php?type=pdf', '_blank');
      });

      document.querySelector('.excel').addEventListener('click', function () {
        // Open the Excel report in a new tab
        window.open('download-report.php?type=excel', '_blank');
      });
    });
  </script> -->

  <script>
document.getElementById('download-pdf').addEventListener('click', function () {
  const selected = document.querySelector('.custom-select').textContent.trim();
  const building = encodeURIComponent(selected);

  // Redirect to PDF generator (opens in new tab)
  window.open(`generating-pdf.php?building=${building}`, '_blank');
});

 </script>

  <script>
function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("#rentTable tr");

    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        for (var j = 0; j < cols.length; j++)
            row.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
        csv.push(row.join(","));
    }

    // Download CSV
    var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
    var downloadLink = document.createElement("a");
    downloadLink.href = URL.createObjectURL(csvFile);
    downloadLink.download = filename;
    downloadLink.click();
}

function printTable() {
    var printContent = document.querySelector(".rentTable").outerHTML;
    var win = window.open('', '', 'height=700,width=900');
    win.document.write('<html><head><title>Rent Report</title>');
    win.document.write('<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #000; padding: 8px; }</style>');
    win.document.write('</head><body>');
    win.document.write(printContent);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>





<script>
  document.querySelectorAll('.select-option-container').forEach(container => {
      const select = container.querySelector('.custom-select');
      const optionsContainer = container.querySelector('.select-options');
      const options = optionsContainer.querySelectorAll('div');

      // Toggle dropdown on select click
      select.addEventListener('click', () => {
        const isOpen = optionsContainer.style.display === 'block';

        // Close all other dropdowns before opening a new one
        document.querySelectorAll('.select-options').forEach(opt => opt.style.display = 'none');
        document.querySelectorAll('.custom-select').forEach(sel => {
          sel.classList.remove('open');

        });

        // Toggle current dropdown
        optionsContainer.style.display = isOpen ? 'none' : 'block';
        select.classList.toggle('open', !isOpen);
      });

      // Option click handler
      options.forEach(option => {
        option.addEventListener('click', () => {
          select.textContent = option.textContent;
          select.setAttribute('data-value', option.getAttribute('data-value'));

          options.forEach(opt => opt.classList.remove('selected'));
          option.classList.add('selected');

          optionsContainer.style.display = 'none';
          select.classList.remove('open');
        });

        option.addEventListener('mouseenter', () => {
          options.forEach(opt => opt.classList.remove('selected'));
          option.classList.add('selected');
        });

      });
    });

    // Close dropdowns on outside click
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.select-option-container')) {
        document.querySelectorAll('.select-options').forEach(opt => opt.style.display = 'none');
        document.querySelectorAll('.custom-select').forEach(sel => {
          sel.classList.remove('open');
          sel.style.borderRadius = '5px';
        });
      }
    });

  </script>
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


    <!--end::Script-->


    <!-- Loading out and in script -->
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        // Fade in effect on page load
        const mainElement = document.getElementById("mainElement");

        if (mainElement) {
          mainElement.classList.remove("fade-out");
        }

        // Intercept link clicks
        document.querySelectorAll("a").forEach(link => {
          link.addEventListener("click", function (e) {
            const target = this.getAttribute("target");
            const href = this.getAttribute("href");

            if (!target || target === "_self") {
              e.preventDefault();
              NProgress.start();
              mainElement.classList.add("fade-out");

              setTimeout(() => {
                window.location.href = href;
              }, 500);
            }
          });
        });
      });
    </script>

<script>
document.querySelector('.pdf').addEventListener('click', function () {
    import('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js').then(jsPDFModule => {
        const { jsPDF } = jsPDFModule;
        const doc = new jsPDF();
        let content = document.querySelector("#rent").outerHTML;
        doc.html(content, {
            callback: function (doc) {
                doc.save("rent_summary.pdf");
            },
            x: 10,
            y: 10
        });
    });
});

document.querySelector('.excel').addEventListener('click', function () {
    const table = document.getElementById("rent");
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(table);
    XLSX.utils.book_append_sheet(wb, ws, "Rent Summary");
    XLSX.writeFile(wb, "rent_summary.xlsx");
});
</script>

<!-- <script>
  document.querySelector(".pdf").addEventListener("click", function () {
    window.location.href = "generate_pdf.php"; // Link to your PHP script for PDF
  });

  document.querySelector(".excel").addEventListener("click", function () {
    window.location.href = "generate_excel.php"; // Link to your PHP script for Excel
  });
</script> -->


    <!-- End Loading out and in script -->
  </body>
  <!--end::Body-->
</html>
