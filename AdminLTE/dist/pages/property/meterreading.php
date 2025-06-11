<?php
// Include database connection
include '../db/connect.php'; // Ensure this defines $pdo for PDO

// Handle success alert after redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('✅ Meter reading successfully added!');</script>";
}

// Check if 'building_id' is passed in the URL
if (isset($_GET['building_id'])) {
    $buildingId = intval($_GET['building_id']);

    // Fetch building
    $stmt = $pdo->prepare("SELECT * FROM buildings WHERE building_id = ?");
    $stmt->bindParam(1, $buildingId, PDO::PARAM_INT);
    $stmt->execute();
    $building = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$building) {
        echo "Building not found.";
        exit;
    }

    $stmt->closeCursor();
} else {
    echo "No building selected.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $reading_date = $_POST['reading_date'] ?? null;
    $unit_number = $_POST['unit_number'] ?? null;
    $meter_type = $_POST['meter_type'] ?? null;
    $previous_reading = floatval($_POST['previous_reading'] ?? 0);
    $current_reading = floatval($_POST['current_reading'] ?? 0);
    $consumption_units = $current_reading - $previous_reading;

   // Get the unit price based on meter type (from DB or session)
   if ($meter_type === "Water") {
    $unit_price = $_SESSION['water_price'] ?? 0; // Or fetch from DB
} else {
    $unit_price = $_SESSION['electricity_price'] ?? 0; // Or fetch from DB
}

$consumption_cost = $consumption_units * $unit_price;


    if (empty($reading_date) || empty($unit_number)) {
        echo "<p style='color:red;'>Both reading date and unit number are required.</p>";
    } else {
        // Insert meter reading
        $stmt = $pdo->prepare("INSERT INTO meter_readings (building_id, reading_date, unit_number, meter_type, previous_reading, current_reading, consumption_units,  consumption_cost)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $buildingId, PDO::PARAM_INT);
        $stmt->bindParam(2, $reading_date, PDO::PARAM_STR);
        $stmt->bindParam(3, $unit_number, PDO::PARAM_STR);
        $stmt->bindParam(4, $meter_type, PDO::PARAM_STR);
        $stmt->bindParam(5, $previous_reading, PDO::PARAM_STR);
        $stmt->bindParam(6, $current_reading, PDO::PARAM_STR);
        $stmt->bindParam(7, $consumption_units, PDO::PARAM_STR);
        $stmt->bindParam(8, $consumption_cost, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Redirect to avoid resubmission on reload
            header("Location: " . $_SERVER['PHP_SELF'] . "?building_id={$buildingId}&success=1");
            exit;
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->errorInfo()[2] . "</p>";
        }

        $stmt->closeCursor();
    }
}

// Get building_id from query parameters
$building_id = isset($_GET['building_id']) ? $_GET['building_id'] : null;

if ($building_id) {
    // Fetch units for the building
    $stmt = $pdo->prepare("SELECT u.unit_number FROM units u WHERE u.building_id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $pdo->errorInfo()[2]);
    }
    $stmt->bindParam(1, $building_id, PDO::PARAM_INT);
} else {
    echo "Building ID is missing!";
    exit;
}

$stmt->execute();
$units = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Fetch meter readings for the building
$sql = "
    SELECT mr.reading_date, mr.unit_number, mr.meter_type, mr.previous_reading, mr.current_reading, mr.consumption_units, mr.consumption_cost
    FROM meter_readings mr
    INNER JOIN units u ON mr.unit_number = u.unit_number
    WHERE u.building_id = :building_id
    ORDER BY mr.reading_date DESC
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':building_id', $buildingId, PDO::PARAM_INT);
$stmt->execute();
$readings = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
?>

<?php
include '../db/connect.php'; // Assuming this sets up $pdo

$building_id = 137;
$stmt = $pdo->prepare("SELECT water_price, electricity_price FROM buildings WHERE building_id = ?");
$stmt->execute([$building_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$water_rate = $row['water_price'];
$electricity_rate = $row['electricity_price'];

echo "Water Rate: $water_rate<br>";
echo "Electricity Rate: $electricity_rate";
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
   <link rel="stylesheet" href="meterreading.css">
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- scripts for data_table -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- <link rel="stylesheet" href="announcements.css"> -->

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

        <style>
          body{
            font-size: 16px;
          }
          #chargeFormContainer {
  padding-top: 60px;
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
            <img
              src="../../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
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

            <div class="col-sm-8">
              <div class="">
                <h3 style="color:#FFC107;" class="mb-0 contact_section_header"> <i class="fas fa-home icon"></i> <?php echo htmlspecialchars($building['building_name']); ?></h3>
                <h6 class="property-type"><b><?php echo htmlspecialchars($building['building_type']); ?></b></h6>
              </div>
            </div>


            <!-- start row -->
            <div class="row mt-3 personal-info">
              <h6 class="mb-0 contact_section_header mb-2"> </i> Basic Info</h6>
          <div class="col-md-12">
          <div class="row">
  <div class="col-md-3">
    <div class="personal-item d-flex justify-content-between bg-white">
      <div class="category-number p-2" style="display: flex; gap: 5px; align-items: center;">
        <div class="category"><i class="fas fa-briefcase personal-info-icon"></i> <span style="color:#193042;" class="personal-info item-name"> Location,</span> </div>
        <div style="color:#FFC107;" class="number"><b><?php echo htmlspecialchars($building['county']); ?></b></div>
      </div>
    </div>
  </div>

  <!-- <div class="col-md-3">
    <div class="personal-item d-flex justify-content-between bg-white">
      <div class="category-number p-2" style="display: flex; gap: 5px; align-items: center;">
        <div class="category"><i class="fas fa-globe icon personal-info-icon"></i> <span class="personal-info item-name">Origin,</span> </div>
        <div class="number"><b>Kenyan</b></div>  Or use dynamic data if needed -->
      <!-- </div>
    </div>
  </div> -->



  <div class="col-md-3">
  <div class="d-flex justify-content-between">
    <button
  class="btn edit-btn personal-info rounded"
  data-building-id="<?php echo htmlspecialchars($building['building_id'] ?? ''); ?>"
>
  <i class="fas fa-edit icon"></i> Edit
</button>

  </div>
</div>


<div class="col-md-12 mt-2">
  <div class="row">
    <div class="col-md-3">
      <div class="personal-item d-flex justify-content-between bg-white">
        <div class="labal-value p-2" style="display: flex; gap: 5px; align-items: center;">
          <div class="label"><i class="fa fa-envelope personal-info-icon"></i>
            <span style="color:#193042;"  class="personal-info item-name email"> Ownership,</span> </div>
          <div style="color:#FFC107;" class="value"><b><?php echo htmlspecialchars($building['ownership_info']); ?></b></div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="personal-item d-flex justify-content-between bg-white">
        <div class="category-number p-2" style="display: flex; gap: 5px; align-items: center;">
          <div class="category"><i class="fas fa-city personal-info-icon"></i> <span style="color:#193042;" class="personal-info item-name">
            Units,</span> </div>
          <div style="color:#FFC107;" class="phone"><b><?php echo htmlspecialchars($building['units_number']); ?></b></div> <!-- Assuming static data for units, you could replace it with dynamic data -->
        </div>
      </div>
    </div>

    <!-- <div class="col-md-3">
      <div class="personal-item d-flex justify-content-between bg-white">
        <div class="category-number p-2" style="display: flex; gap: 5px; align-items: center;">
          <div class="category"><i class="fas fa-id-card personal-info-icon"></i> <span class="personal-info item-name">Address,</span></div>
          <div class="number"><b>50202, </b></div>  Replace with dynamic data if available -->
        </div>
      </div>
    </div>
  </div>
</div>


<div style="display: flex;gap: 25px;">
<!-- <a href=""><p>Summary</p></a> -->
<!-- <a href="../property/AllUnits.html"><p>Units(5)</p></a> -->

</div>
<hr>
</div>


<!-- Edit Property Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form action="update_property.php" method="POST" id="editPropertyForm">
        <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
          <h5 class="modal-title" id="editModalLabel"><i class="fas fa-edit me-1"></i> Edit Property Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="building_id" id="buildingId"> <!-- Hidden input for building_id -->

          <!-- Location -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="editLocation" name="county" placeholder="Location" required>
            <label for="editLocation"><i class="fas fa-map-marker-alt me-1"></i> Location</label>
          </div>

          <!-- Ownership -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="editOwnership" name="ownership_info" placeholder="Ownership Type" required>
            <label for="editOwnership"><i class="fas fa-user-tag me-1"></i> Ownership Type</label>
          </div>

          <!-- Units -->
          <div class="form-floating mb-3">
            <input type="number" class="form-control" id="editUnits" name="units_number" placeholder="Number of Units" required>
            <label for="editUnits"><i class="fas fa-building me-1"></i> Number of Units</label>
          </div>
        </div>

        <div class="modal-footer bg-light d-flex justify-content-between">
          <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Make sure all details are correct</small>
          <button type="submit" class="btn btn-success" style="background-color: #00192D; color: #FFC107;">
            <i class="fas fa-save me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- end -->




<div style="display: flex; gap: 25px;">
   <a href="../property/Units.php" style="color:#193042;"> <p>Unit list</p></a>
    <a href="../property/meterreading.php" style="color:#193042;"><p>Meter Reading</p></a>
</div>


<div class="justify-content-end d-flex">
<button onclick="meterreadingopenPopup()" class="edit-btn">
    <i class="fas fa-plus"></i>
    Add Meter Reading</button></a>
  </div>

<div class="row">
  <table id="myTableOne" class="display" >
    <thead>
      <tr>
        <th style="color: #FFC107;">Reading Date</th>
        <th style="color: #FFC107;">Unit</th>
        <th style="color: #FFC107;">Meter Type</th>
        <th style="color: #FFC107;">Previous Reading</th>
        <th style="color: #FFC107;">Current Reading</th>
        <th style="color: #FFC107;">Consumption Units</th>
        <th style="color: #FFC107;">Consumption Cost</th>
        <th style="color: #FFC107;">Action</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($readings as $reading): ?>
        <tr>
            <td><?php echo htmlspecialchars($reading['reading_date']); ?></td>
            <td><?php echo htmlspecialchars($reading['unit_number']); ?></td>
            <td><?php echo htmlspecialchars($reading['meter_type']); ?></td>
            <td><?php echo htmlspecialchars($reading['previous_reading']); ?></td>
            <td><?php echo htmlspecialchars($reading['current_reading']); ?></td>
            <td><?php echo htmlspecialchars($reading['consumption_units']); ?></td>
            <td>Ksh <?= number_format($reading['consumption_cost'], 2) ?></td>
            <td>


 <!-- View Button -->
<button
    class="btn btn-sm view-btn"
    style="background-color: #00192D; color: #FFC107;"
    data-reading-date="<?= htmlspecialchars($reading['reading_date']) ?>"
    data-unit="<?= htmlspecialchars($reading['unit_number']) ?>"
    data-meter-type="<?= htmlspecialchars($reading['meter_type']) ?>"
    data-prev-reading="<?= htmlspecialchars($reading['previous_reading']) ?>"
    data-current-reading="<?= htmlspecialchars($reading['current_reading']) ?>"
    data-consumption-units="<?= htmlspecialchars($reading['consumption_units']) ?>"
    data-consumption-cost="<?= number_format($reading['consumption_cost'], 2) ?>"
>
    <i class="fa fa-file"></i>
</button>

                <button class="btn btn-sm" style="background-color: red; color:#fff;" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>

  </table>
            <!-- /.col -->
          </div>
          <!--end::Row-->



                      <!-- /.col -->
                    </div>
                    <!--end::Row-->
                  <!-- ./card-body -->

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


    <!-- View Modal Structure -->
<div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-m">
    <div class="modal-content border-0 rounded-3 shadow">
      <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
        <h5 class="modal-title" id="shiftModalLabel">Reading Details</h5>
        <!--<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button> -->
      </div>
      <div class="modal-body px-4">
        <table class="table table-bordered table-striped align-middle">
          <tbody>
            <tr>
              <th style="background-color: #FFC107; width: 40%;">Reading Date</th>
              <td id="readingDate"></td>
            </tr>
            <tr>
              <th style="background-color: #FFC107;">Unit</th>
              <td id="unit"></td>
            </tr>
            <tr>
              <th style="background-color: #FFC107;">Meter Type</th>
              <td id="meterType"></td>
            </tr>
            <tr>
              <th style="background-color: #FFC107;">Previous Reading</th>
              <td id="prevReading"></td>
            </tr>
            <tr>
              <th style="background-color: #FFC107;">Current Reading</th>
              <td id="currentReading"></td>
            </tr>
            <tr>
              <th style="background-color: #FFC107;">Consumption Units</th>
              <td id="consumptionUnits"></td>
            </tr>
            <tr>
              <th style="background-color: #FFC107;">Consumption Cost</th>
              <td id="consumptionCost"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer" style="background-color: #F8F9FA;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #00192D; color: #FFC107;">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- meterreading popup -->
<div class="meterpopup-overlay" id="meterPopup">
    <div class="meterpopup-content wide-form">
        <button id="close-btns" class="text-secondary" onclick="meterreadingclosePopup()">×</button>
        <h2 class="assign-title">Add A Meter Reading</h2>
        <form class="wide-form" id="meterForm" method="POST" action="">
            <div class="form-group">
                <b><label for="dateInput" class="filter-label">Reading Date</label></b>
                <input type="date" id="dateInput" name="reading_date" class="form-control" required />
            </div>
            <div class="form-group">
                <select id="units" name="unit_number" required onchange="checkPreviousReading()">
                    <option value="">-- Select Unit --</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?php echo htmlspecialchars($unit['unit_number']); ?>">
                            <?php echo htmlspecialchars($unit['unit_number']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="meter_type">Meter Type:</label>
                <select id="meter_type" name="meter_type" required>
                    <option value="">-- Select Meter Type --</option>
                    <option value="Water">Water</option>
                    <option value="Electrical">Electrical</option>
                </select>
            </div>

            <div class="form-group">
                <label for="previous_reading">Previous Reading:</label>
                <input type="number" id="previous_reading" name="previous_reading" placeholder="Previous Reading" required>
                <small id="prev_reading_note" style="color: gray; display: none;">
                    This is the first reading for this unit.
                </small>
            </div>

            <div class="form-group">
                <label for="current_reading">Current Reading:</label>
                <input type="number" id="current_reading" name="current_reading" placeholder="Current Reading" required>
            </div>

            <div class="form-group">
                <label>Consumption Units:</label>
                <p id="consumption_preview"><i>Calculated automatically</i></p>
            </div>


            <div class="form-group">
                <label>Consumption Cost:</label>
                <p id="consumption_cost"><i>Calculated automatically</i></p>
                <input type="hidden" id="consumption_cost_value" name="consumption_cost">
            </div>

            <button type="submit" name="submit" class="submit-btn">Create Meter Reading</button>
        </form>
    </div>
</div>
<!-- end -->
<!-- popup -->
<!-- Create Meter Reading Button -->
<!-- <button type="button" class="submit-btn" onclick="showChargeForm()">Create Meter Reading</button> -->

<!-- Container where the Charge Form will appear -->
<!-- <div id="chargeFormContainer" style="display: none; min-height: 70vh;" class="d-flex justify-content-center align-items-start mt-5">
  <div class="col-md-10 col-lg-8 bg-white p-4 shadow rounded">

    <div class="mb-4">
      <label for="chargeName" class="form-label fw-bold">Charge Name</label>
      <input type="text" id="chargeName" class="form-control shadow-sm" placeholder="Enter charge name">
    </div>

    <div class="row mb-4">
      <div class="col-md-4">
          <label for="unitPrice" class="form-label fw-bold">Unit Price (Ksh)</label>
          <input type="number" id="unitPrice" class="form-control shadow-sm" placeholder="Enter unit price">
      </div>
      <div class="col-md-4">
          <label for="quantity" class="form-label fw-bold">Consumption Units</label>
          <input type="number" id="quantity" class="form-control shadow-sm" placeholder="Enter Consumption Units">
      </div>
      <div class="col-md-4 d-flex align-items-end">
          <button class="btn btn-primary w-100 shadow-sm" onclick="addCharge()">
              <i class="fas fa-plus"></i> Add Charge
          </button>
      </div>
    </div>

     List of Charges -->
    <!-- <div id="chargeList" class="mb-3"></div> -->

    <!-- Charges Summary Table -->
    <!-- <h4 class="mt-4 text-secondary">Charges Summary</h4>
    <table class="table table-striped table-bordered mt-2 shadow-sm">
        <thead class="bg-info text-white">
            <tr>
                <th>Charge Name</th>
                <th>Unit Price (Ksh)</th>
                <th>Consumption Units</th>
                <th>Total (Ksh)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="chargesTable"> -->
            <!-- Dynamic Rows Will Be Added Here -->
        <!-- </tbody>
    </table> -->

    <!-- Total Amount -->
    <!-- <div class="row">
      <div class="col-md-4 mt-3">
        <label class="form-label fw-bold">Total Amount (Ksh)</label>
        <input type="text" id="totalAmount" class="form-control shadow-sm" readonly>
      </div>
    </div>

    <a href="../financials/invoices.html">
        <button class="btn btn-success mt-3 w-100 shadow-sm">
            <i class="fas fa-file-invoice"></i> Send To Invoice
        </button>
    </a>

  </div>
</div> -->


    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->

    <!-- start -->
    <!-- <script>
  document.getElementById("meterForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form from submitting
    document.getElementById("chargeFormContainer").style.display = "none"; // Show the hidden container
  });
</script> -->

<!-- </script> -->



    <!-- end -->





    <!-- begin -->
    <script>
 document.addEventListener("DOMContentLoaded", function () {
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const editPropertyForm = document.getElementById('editPropertyForm');

    // Open modal and populate fields with property data
    document.querySelectorAll('.editPropertyBtn').forEach(button => {
        button.addEventListener('click', function () {
            const buildingId = this.getAttribute('data-building-id');
            document.getElementById('buildingId').value = buildingId;

            // Fetch property details
            fetch(`get_property_details.php?building_id=${buildingId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate modal with data
                        document.getElementById('editLocation').value = data.building.county;
                        document.getElementById('editOwnership').value = data.building.ownership_info;
                        document.getElementById('editUnits').value = data.building.units_number;
                    } else {
                        alert('Failed to load property details.');
                    }
                })
                .catch(error => console.error('Error:', error));

            // Open the modal
            editModal.show();
        });
    });

    // Handle the form submission via AJAX
    editPropertyForm.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevent the default form submission

        const formData = new FormData(editPropertyForm);

        fetch('update_property.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);  // Success message
                editModal.hide();  // Close the modal
                // Reload the page (or update the page dynamically)
                location.reload();  // You can replace this with a more targeted update if needed
            } else {
                alert('Error: ' + data.message);  // Error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving changes.');
        });
    });

    // Optional: Reset form when modal is hidden (to clear any stale data)
    editModal._element.addEventListener('hidden.bs.modal', function () {
        editPropertyForm.reset();  // Reset the form when the modal closes
    });
});


</script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const steps = document.querySelectorAll(".form-step");
            const progressSteps = document.querySelectorAll(".step");
            const nextBtns = document.querySelectorAll(".next-btn");
            const prevBtns = document.querySelectorAll(".prev-btn");
            const form = document.getElementById("property-form");

            let currentStep = 0;

            function showStep(stepIndex) {
                // Hide all steps and reset progress
                steps.forEach((step, index) => {
                    step.classList.remove("active");
                    if (index === stepIndex) {
                        step.classList.add("active");
                    }
                });

                // Update progress bar (show tick for completed steps)
                progressSteps.forEach((step, index) => {
                    step.classList.remove("active", "completed");
                    if (index < stepIndex) {
                        step.classList.add("completed"); // Mark previous steps as completed (✔️)
                    }
                    if (index === stepIndex) {
                        step.classList.add("active"); // Highlight current step
                    }
                });
            }

            function validateStep() {
                const inputs = steps[currentStep].querySelectorAll("input");
                for (let input of inputs) {
                    if (input.value.trim() === "") {
                        alert("Please fill in all fields before proceeding.");
                        return false;
                    }
                }
                return true;
            }

            nextBtns.forEach((button) => {
                button.addEventListener("click", () => {
                    if (validateStep()) {
                        if (currentStep < steps.length - 1) {
                            currentStep++;
                            showStep(currentStep);
                        }
                    }
                });
            });

            prevBtns.forEach((button) => {
                button.addEventListener("click", () => {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            form.addEventListener("submit", function (event) {
                event.preventDefault();
                alert("Property Registered Successfully!");
            });

            // Show first step on load
            showStep(currentStep);
        });
            </script>


            <script>
              function openmeterPopup() {
                  document.getElementById('meteringPopup').style.display = 'flex'; // Make the overlay visible
              }

              function closemeterPopup() {
                  document.getElementById('meteringPopup').style.display = 'none'; // Hide the overlay
              }
          </script>



<script>
  document.getElementById('units').addEventListener('change', function () {
    const unitNumber = this.value;
    fetch('fetch_unit_prices.php?unit=' + encodeURIComponent(unitNumber))
        .then(response => response.json())
        .then(data => {
            window.unitPrices = data; // Store globally
            updateConsumption();
        });
});

</script>

<script>
function checkPreviousReading() {
    const unitNumber = document.getElementById("units").value;
    const meterType = document.getElementById("meter_type").value;

    if (unitNumber && meterType) {
        fetch("get_unit_price.php?unit=" + unitNumber + "&type=" + meterType)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.unit_price = parseFloat(data.price); // Store globally
                } else {
                    alert("Price not found.");
                    window.unit_price = 0;
                }
            });
    }
}

document.getElementById("current_reading").addEventListener("input", () => {
    const prev = parseFloat(document.getElementById("previous_reading").value);
    const current = parseFloat(document.getElementById("current_reading").value);
    const consumption = current - prev;

    if (!isNaN(consumption) && window.unit_price) {
        document.getElementById("consumption_preview").innerText =
            `Units: ${consumption} | Total Cost: Ksh ${consumption * window.unit_price}`;
    }
});

</script>

<!--
<script>
document.addEventListener("DOMContentLoaded", function () {
  // Attach click event to all edit buttons
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function () {
      const propertyId = this.getAttribute('data-building-id');
      openEditPropertyModal(propertyId);
    });
  });

  // Function to fetch and populate the modal
  function openEditPropertyModal(propertyId) {
    console.log('Fetching property ID:', propertyId);

    fetch('get_property_details.php?id=' + propertyId)
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          const data = result.data;

          // Prefill modal fields using correct IDs
          document.getElementById('buildingId').value = propertyId;
          document.getElementById('editLocation').value = data.county;
          document.getElementById('editOwnership').value = data.ownership_info;
          document.getElementById('editUnits').value = data.units_number;

          // Show the modal
          const modal = new bootstrap.Modal(document.getElementById('editModal'));
          modal.show();
        } else {
          alert('Failed to fetch property details: ' + result.message);
        }
      })
      .catch(error => {
        console.error('Error fetching property data:', error);
        alert('An error occurred while fetching property data.');
      });
  }
});
</script> -->




<script>
  // Function to meter the meter popup
  function meterreadingopenPopup() {
    document.getElementById("meterPopup").style.display = "flex";
  }

  // Function to close the meter popup
  function meterreadingclosePopup() {
    document.getElementById("meterPopup").style.display = "none";
  }
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

<!-- <script>
  function calculateConsumption() {
    const meterType = document.getElementById("meter_type").value;
    const prevReading = parseFloat(document.getElementById("previous_reading").value) || 0;
    const currReading = parseFloat(document.getElementById("current_reading").value) || 0;

    if (currReading <= prevReading) {
        alert("Current reading must be higher than previous reading!");
        return;
    }

    const consumption = currReading - prevReading;
    document.getElementById("consumption_preview").textContent = `${consumption} units`;

    // Get prices (with fallback)
    const waterPrice = parseFloat(localStorage.getItem("waterPrice")) || 0;
    const electricityPrice = parseFloat(localStorage.getItem("electricityPrice")) || 0;

    const unitPrice = meterType === "Water" ? waterPrice : electricityPrice;
    const cost = consumption * unitPrice;

    document.getElementById("consumption_cost").textContent = `Ksh ${cost.toFixed(2)}`;
    document.getElementById("consumption_cost_value").value = cost.toFixed(2);
}
</script> -->

<script>
// Function to calculate Consumption Units & Cost
function calculateConsumptionAndCost() {
    // Get input values
    const meterType = document.getElementById("meter_type").value;
    const prevReading = parseFloat(document.getElementById("previous_reading").value) || 0;
    const currReading = parseFloat(document.getElementById("current_reading").value) || 0;

    // Validate readings
    if (currReading <= prevReading) {
        // alert("Current reading must be higher than previous reading!");
        return;
    }

    // Calculate Consumption Units
    const consumptionUnits = currReading - prevReading;
    document.getElementById("consumption_preview").textContent = consumptionUnits + " units";

    // Get Unit Price (from localStorage or hardcoded)
    let unitPrice = 0;
    if (meterType === "Water") {
        unitPrice = localStorage.getItem("waterPrice") || 200; // Default: Ksh 200
    } else if (meterType === "Electrical") {
        unitPrice = localStorage.getItem("electricityPrice") || 50; // Default: Ksh 50
    }

    // Calculate Cost
    const consumptionCost = consumptionUnits * unitPrice;
    document.getElementById("consumption_cost").textContent = "Ksh " + consumptionCost.toFixed(2);
    document.getElementById("consumption_cost_value").value = consumptionCost.toFixed(2); // Hidden input for form submission
}

// Trigger calculation when inputs change
document.getElementById("meter_type").addEventListener("change", calculateConsumptionAndCost);
document.getElementById("previous_reading").addEventListener("input", calculateConsumptionAndCost);
document.getElementById("current_reading").addEventListener("input", calculateConsumptionAndCost);

// Initialize calculation on page load (optional)
document.addEventListener("DOMContentLoaded", calculateConsumptionAndCost);
</script>
<!--
<script>
  // Fetch prices from backend and store in localStorage
function fetchPricesFromBackend() {
    fetch("get_prices.php")
        .then(response => {
            if (!response.ok) throw new Error("Network error");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                localStorage.setItem("waterPrice", data.water_price);
                localStorage.setItem("electricityPrice", data.electricity_price); // Note: Fix typo if your PHP uses 'electricity_price'
                console.log("Prices fetched:", data);
            } else {
                console.error("Failed to fetch prices:", data.error);
                // Fallback to default prices
                localStorage.setItem("waterPrice", 0);
                localStorage.setItem("electricityPrice", 0);
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            // Use cached prices or defaults
            const cachedWaterPrice = localStorage.getItem("waterPrice") || 0;
            const cachedElectricityPrice = localStorage.getItem("electricityPrice") || 0;
            localStorage.setItem("waterPrice", cachedWaterPrice);
            localStorage.setItem("electricityPrice", cachedElectricityPrice);
        });
}

// Call this when the form loads
document.addEventListener("DOMContentLoaded", function() {
    fetchPricesFromBackend(); // Fetch latest prices
    // Add other event listeners...
});
</script> -->

<!-- <script>
// Function to calculate consumption & cost
function calculateConsumption() {
    const meterType = document.getElementById("meter_type").value;
    const prevReading = parseFloat(document.getElementById("previous_reading").value) || 0;
    const currReading = parseFloat(document.getElementById("current_reading").value) || 0;

    if (prevReading >= currReading) {
        alert("Current reading must be higher than previous reading!");
        return;
    }

    const consumption = currReading - prevReading;
    document.getElementById("consumption_preview").textContent = consumption + " units";

    // Fetch unit price (from localStorage or backend)
    let unitPrice = 0;
    if (meterType === "Water") {
        unitPrice = localStorage.getItem("waterPrice") || 0;
    } else if (meterType === "Electrical") {
        unitPrice = localStorage.getItem("electricityPrice") || 0;
    }

    const cost = consumption * unitPrice;
    document.getElementById("consumption_cost").textContent = "Ksh " + cost.toFixed(2);
    document.getElementById("consumption_cost_value").value = cost.toFixed(2);
}

// Fetch prices when the page loads (or from localStorage)
document.addEventListener("DOMContentLoaded", function() {
    // Example: Fetch prices via AJAX (if stored in DB)
    // fetchPricesFromBackend();

    // Or use localStorage if prices are set in reg_form.php
    const waterPrice = localStorage.getItem("waterPrice");
    const electricityPrice = localStorage.getItem("electricityPrice");
    console.log("Water Price:", waterPrice, "| Electricity Price:", electricityPrice);

    // Update UI when inputs change
    document.getElementById("meter_type").addEventListener("change", calculateConsumption);
    document.getElementById("previous_reading").addEventListener("input", calculateConsumption);
    document.getElementById("current_reading").addEventListener("input", calculateConsumption);
});

// Optional: Fetch prices from backend (if stored in DB)
function fetchPricesFromBackend() {
    fetch("get_prices.php") // Replace with your API endpoint
        .then(response => response.json())
        .then(data => {
            localStorage.setItem("waterPrice", data.water_price);
            localStorage.setItem("electricityPrice", data.electricity_price);
        });
}
</script> -->

<!-- <script>
// Function to fetch building prices when unit is selected
function fetchBuildingPrices() {
    const unitSelect = document.getElementById('units');
    const selectedOption = unitSelect.options[unitSelect.selectedIndex];
    const buildingId = selectedOption.getAttribute('data-building-id');

    if (buildingId) {
        // Make an AJAX call to fetch building prices
        fetch('get_building_prices.php?building_id=' + buildingId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('water_unit_price').value = data.water_price;
                    document.getElementById('electricity_unit_price').value = data.electricity_price;
                    updatePriceDisplay(); // Update the price display
                } else {
                    alert('Failed to fetch unit prices');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

// Function to update the displayed unit price based on meter type
function updatePriceDisplay() {
    const meterType = document.getElementById('meter_type').value;
    const waterPrice = document.getElementById('water_unit_price').value;
    const electricityPrice = document.getElementById('electricity_unit_price').value;

    const priceDisplay = document.getElementById('current_unit_price_display');

    if (meterType === 'Water' && waterPrice) {
        priceDisplay.innerHTML = `Ksh ${waterPrice} per unit`;
    } else if (meterType === 'Electrical' && electricityPrice) {
        priceDisplay.innerHTML = `Ksh ${electricityPrice} per unit`;
    } else {
        priceDisplay.innerHTML = '<i>Select unit and meter type to see price</i>';
    }

    // Recalculate if we already have readings
    calculateConsumption();
}

// Function to calculate consumption and cost
function calculateConsumption() {
    const prevReading = parseFloat(document.getElementById('previous_reading').value) || 0;
    const currReading = parseFloat(document.getElementById('current_reading').value) || 0;
    const meterType = document.getElementById('meter_type').value;

    if (currReading < prevReading) {
        document.getElementById('consumption_preview').innerHTML = '<span style="color:red;">Current reading cannot be less than previous reading</span>';
        document.getElementById('consumption_cost').innerHTML = '<i>Invalid reading</i>';
        return;
    }

    const consumption = currReading - prevReading;
    document.getElementById('consumption_preview').innerHTML = consumption.toFixed(2) + ' units';

    // Calculate cost based on meter type
    if (meterType === 'Water') {
        const unitPrice = parseFloat(document.getElementById('water_unit_price').value) || 0;
        const cost = consumption * unitPrice;
        document.getElementById('consumption_cost').innerHTML = 'Ksh ' + cost.toFixed(2);
        document.getElementById('consumption_cost_value').value = cost.toFixed(2);
    } else if (meterType === 'Electrical') {
        const unitPrice = parseFloat(document.getElementById('electricity_unit_price').value) || 0;
        const cost = consumption * unitPrice;
        document.getElementById('consumption_cost').innerHTML = 'Ksh ' + cost.toFixed(2);
        document.getElementById('consumption_cost_value').value = cost.toFixed(2);
    } else {
        document.getElementById('consumption_cost').innerHTML = '<i>Select meter type</i>';
    }
}
</script> -->

<!-- <script>
// This should be populated with the prices from your database
// You might want to fetch this via AJAX or include it in your PHP template
var unitPrices = {
    <?php foreach ($units as $index => $unit): ?>
        '<?php echo $unit['unit_number']; ?>': {
            water: <?php echo isset($unit['water_price']) ? floatval($unit['water_price']) : 0; ?>,
            electrical: <?php echo isset($unit['electricity_price']) ? floatval($unit['electricity_price']) : 0; ?>
        }<?php echo $index < count($units) - 1 ? ',' : ''; ?>
    <?php endforeach; ?>
},

function calculateConsumption() {
    const unitNumber = document.getElementById('units').value;
    const meterType = document.getElementById('meter_type').value;
    const prevReading = parseFloat(document.getElementById('previous_reading').value) || 0;
    const currReading = parseFloat(document.getElementById('current_reading').value) || 0;

    if (unitNumber && meterType && currReading > prevReading) {
        const consumption = currReading - prevReading;
        document.getElementById('consumption_units').textContent = consumption + ' units';

        // Get the unit price based on the selected unit and meter type
        const unitPrice = unitPrices[unitNumber] ?
                         (meterType === 'Water' ? unitPrices[unitNumber].water : unitPrices[unitNumber].electrical) : 0;

        document.getElementById('unit_price_display').textContent = 'Ksh ' + unitPrice;
        document.getElementById('unit_price').value = unitPrice;

        const totalCost = consumption * unitPrice;
        document.getElementById('total_cost').textContent = 'Ksh ' + totalCost.toFixed(2);
        document.getElementById('calculated_cost').value = totalCost.toFixed(2);
    } else {
        document.getElementById('consumption_units').textContent = 'Invalid input';
        document.getElementById('total_cost').textContent = 'Cannot calculate';
    }
}

// Call calculateConsumption when any input changes
document.getElementById('previous_reading').addEventListener('input', calculateConsumption);
document.getElementById('current_reading').addEventListener('input', calculateConsumption);
document.getElementById('meter_type').addEventListener('change', calculateConsumption);
document.getElementById('units').addEventListener('change', function() {
    checkPreviousReading();
    calculateConsumption();
});
</script> -->

<!-- <script>
  document.getElementById("current_reading").addEventListener("input", calculateConsumption);
  document.getElementById("previous_reading").addEventListener("input", calculateConsumption);
  document.getElementById("meter_type").addEventListener("change", calculateConsumption);

  function calculateConsumption() {
    const prev = parseFloat(document.getElementById("previous_reading").value);
    const curr = parseFloat(document.getElementById("current_reading").value);
    const meterType = document.getElementById("meter_type").value;

    if (!isNaN(prev) && !isNaN(curr) && curr >= prev) {
      const units = curr - prev;

      // Define your rate logic
      let rate = 0;
      if (meterType === "Water") {
        rate = 200; // example rate per unit
      } else if (meterType === "Electrical") {
        rate = 15; // example rate per unit
      }

      const cost = units * rate;

      // Update the DOM
      document.getElementById("consumption_preview").textContent = units + " units";
      document.getElementById("consumption_cost").textContent = "KES " + cost.toFixed(2);
      document.getElementById("consumption_cost_value").value = cost.toFixed(2);
    } else {
      document.getElementById("consumption_preview").textContent = "Invalid readings";
      document.getElementById("consumption_cost").textContent = "";
      document.getElementById("consumption_cost_value").value = "";
    }
  }
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
      $('#rent').DataTable({
          "lengthChange": false,
          "dom": 'Bfrtip',
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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
  const slides = document.querySelector('.slides');
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');

let index = 0;
const totalSlides = document.querySelectorAll('.slide').length;

function updateSlide() {
    slides.style.transform = `translateX(-${index * 100}%)`;
}

// Next button
nextBtn.addEventListener('click', () => {
    index = (index + 1) % totalSlides;
    updateSlide();
});

// Previous button
prevBtn.addEventListener('click', () => {
    index = (index - 1 + totalSlides) % totalSlides;
    updateSlide();
});

// Auto-slide every 3 seconds
setInterval(() => {
    index = (index + 1) % totalSlides;
    updateSlide();
}, 3000);

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
  // Function to open the complaint popup
  function openshiftPopup() {
    document.getElementById("shiftPopup").style.display = "flex";
  }

  // Function to close the complaint popup
  function closeshiftPopup() {
    document.getElementById("shiftPopup").style.display = "none";
  }
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


<!-- JavaScript to Open View Modal -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.view-btn').forEach(button => {
      button.addEventListener('click', function () {
        // Populate modal fields
        document.getElementById('readingDate').textContent = this.dataset.readingDate;
        document.getElementById('unit').textContent = this.dataset.unit;
        document.getElementById('meterType').textContent = this.dataset.meterType;
        document.getElementById('prevReading').textContent = this.dataset.prevReading;
        document.getElementById('currentReading').textContent = this.dataset.currentReading;
        document.getElementById('consumptionUnits').textContent = this.dataset.consumptionUnits;
        document.getElementById('consumptionCost').textContent = this.dataset.consumptionCost;

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('shiftModal'));
        modal.show();
      });
    });
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
 <script>
  function checkPreviousReading() {
    const unitSelect = document.getElementById('units');
    const unitNumber = unitSelect.value;
    const previousReadingInput = document.getElementById('previous_reading');
    const prevReadingNote = document.getElementById('prev_reading_note');

    // Reset state
    previousReadingInput.value = '';
    previousReadingInput.readOnly = false;
    prevReadingNote.style.display = 'none';

    if (!unitNumber) return;

    console.log('Fetching previous reading for unit:', unitNumber);

    // Add cache-buster to prevent wrong cached responses
    const timestamp = new Date().getTime();
    fetch(`get_previous_reading.php?unit=${encodeURIComponent(unitNumber)}&_=${timestamp}`)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);

            // Verify the response matches our request
            if (data.unit_number && data.unit_number !== unitNumber) {
                console.error('Wrong unit returned! Requested:', unitNumber, 'Received:', data.unit_number);
                return;
            }

            if (data.success && data.previous_reading !== null) {
                // Existing reading found
                previousReadingInput.value = data.previous_reading;
                previousReadingInput.readOnly = true;
                prevReadingNote.style.display = 'none';
                console.log(`Found previous reading ${data.previous_reading} for unit ${unitNumber}`);
            } else {
                // No previous reading found
                previousReadingInput.readOnly = false;
                prevReadingNote.style.display = 'inline';
                console.log(`No previous reading found for unit ${unitNumber}`);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            previousReadingInput.readOnly = false;
        });
}

// Add event listener with proper binding
document.getElementById('units').addEventListener('change', checkPreviousReading);
 </script>
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
