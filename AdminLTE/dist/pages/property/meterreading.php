<?php
// Include database connection
include '../db/connect.php'; // Ensure this defines $conn for mysqli

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $buildingId = intval($_GET['id']); // Get the building ID from the URL

    // Prepare the query to fetch building data based on the 'id'
    $stmt = $conn->prepare("SELECT * FROM building_identification WHERE id = ?");
    $stmt->bind_param("i", $buildingId);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();
    $building = $result->fetch_assoc();

    // Check if building data is found
    if (!$building) {
        echo "Building not found.";
        exit;
    }

    $stmt->close();
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

  if (empty($reading_date) || empty($unit_number)) {
      echo "<p style='color:red;'>Reading date and unit number are required.</p>";
  } else {
      $stmt = $conn->prepare("INSERT INTO meter_readings (reading_date, unit_number, meter_type, previous_reading, current_reading, consumption_units)
                              VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssddd", $reading_date, $unit_number, $meter_type, $previous_reading, $current_reading, $consumption_units);

      if ($stmt->execute()) {
          // echo "<p style='color:green;'>Meter reading successfully added! Consumption: <b>$consumption_units</b></p>";
      } else {
          echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
      }

      $stmt->close();
  }
}


// Fetch all units for the dropdown
$units = [];
$sql = "SELECT unit_number FROM unit_information";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      $units[] = $row['unit_number'];
  }
}
/// Fetch the meter readings from the database
$sql = "SELECT * FROM meter_readings";
$result = $conn->query($sql);

// Check if any data is returned
$readings = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $readings[] = $row;
    }
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
                <h3 class="mb-0 contact_section_header"> <i class="fas fa-home icon"></i> <?php echo htmlspecialchars($building['building_name']); ?>
                <!-- <span>,Units</span> </h3> -->
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
        <div class="category"><i class="fas fa-briefcase personal-info-icon"></i> <span class="personal-info item-name"> Location,</span> </div>
        <div class="number"><b><?php echo htmlspecialchars($building['county']); ?></b></div>
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
  <div class="personal-item-edit d-flex btn personal-info justify-content-between">
    <button class="btn edit-btn personal-info rounded" data-bs-toggle="modal" data-bs-target="#editModal">
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
            <span class="personal-info item-name email"> Ownership,</span> </div>
          <div class="value"><b><?php echo htmlspecialchars($building['ownership_info']); ?></b></div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="personal-item d-flex justify-content-between bg-white">
        <div class="category-number p-2" style="display: flex; gap: 5px; align-items: center;">
          <div class="category"><i class="fas fa-city personal-info-icon"></i> <span class="personal-info item-name">
            Units,</span> </div>
          <div class="phone"><b><?php echo htmlspecialchars($building['units_number']); ?></b></div> <!-- Assuming static data for units, you could replace it with dynamic data -->
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


<div style="display: flex; gap: 25px;">
   <a href="#" style="color: #FFC107;"> <p>Unit list</p></a>
    <a href="../property/meterreading.php" style="color: #FFC107;"><p>Meter Reading</p></a>
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
        <th>Reading Date</th>
        <th>Unit</th>
        <th>Meter Type</th>
        <th>Previous Reading</th>
        <th>Current Reading</th>
        <th>Consumption Units</th>
        <th>Action</th>
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
            <td>
                <button onclick="openshiftPopup()" class="btn btn-sm" style="background-color: #0C5662; color:#fff;">
                    <i class="fa fa-file"></i>
                </button>
                <button class="btn btn-sm" style="background-color: #193042; color:#fff;" title="Delete">
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



<!-- Shift Tenant -->
<div class="shiftpopup" id="shiftPopup">
  <div class="shiftpopup-content">
    <button id="close-btns" class="text-secondary" onclick="closeshiftPopup()">×</button>
    <h2 class="assign-title">Detailed Information Of Meter Reading</h2>
    <button class="edit-btn"><i class="fas fa-edit"></i>EDIT</button>
    <form class="wide-form">
      <div class="form-group">
        <b><label for="dateInput" class="filter-label">Reading Date</label></b>
        <input type="" id="dateInput" name="reading_date" class="form-control" placeholder="11-02-2023" required disabled/>
        <label for="units">Unit:</label>
        <select id="units"  disabled>
            <option>D17</option>
            <option>B18</option>
            <option>B11</option>
            <option>C10</option>
        </select>
    </div>
        <div class="form-group">
            <label for="meter_type">Meter Type:</label>
            <select id="meter_type" required disabled>
              <option>Water</option>
              <option>Electrical</option>
          </select>
        </div>

        <div class="form-group">
            <label for="previous_reading">Previous Reading:</label>
            <input type="number" id="previous_reading" name="previous_reading" placeholder="11" required disabled>
        </div>

        <div class="form-group">
            <label for="current_reading">Current Reading:</label>
            <input type="number" id="current_reading" name="current_reading" placeholder="15" required disabled>
        </div>

       <button type="submit" class="submit-btn">Submit</button>
    </form>
  </div>
  </div>
</div>
<!-- End shift popup -->


      <!-- meterreading popup -->
      <div class="meterpopup-overlay" id="meterPopup">
        <div class="meterpopup-content wide-form">
            <button id="close-btns" class="text-secondary" onclick="meterreadingclosePopup()">×</button>
            <h2 class="assign-title">Add A Meter Reading</h2>
            <form class="wide-form"  method="POST" action="">
              <div class="form-group">
             <b><label for="dateInput" class="filter-label">Reading Date</label></b>
              <input type="date" id="dateInput" name="reading_date" class="form-control" required />

                <label for="units">Units:</label>
            <select id="units" name="unit_number" required>
              <?php foreach ($units as $unit): ?>
                  <option value="<?php echo htmlspecialchars($unit); ?>"><?php echo htmlspecialchars($unit); ?></option>
              <?php endforeach; ?>
            </select>
            </div>
            <div class="form-group">
        <label for="meter_type">Meter Type:</label>
        <select id="meter_type" name="meter_type" required>
            <option>Water</option>
            <option>Electrical</option>
        </select>
    </div>

    <div class="form-group">
        <label for="previous_reading">Previous Reading:</label>
        <input type="number" id="previous_reading" name="previous_reading" placeholder="Previous Reading" required>
    </div>

    <div class="form-group">
        <label for="current_reading">Current Reading:</label>
        <input type="number" id="current_reading" name="current_reading" placeholder="Current Reading" required>
    </div>

    <div class="form-group">
    <label>Consumption Units:</label>
    <p id="consumption_preview"><i>Calculated automatically</i></p>
    </div>


    <button type="submit" name="submit" class="submit-btn">Create Meter Reading</button>
            </form>
        </div>
    </div>
    <!-- end -->







    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
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
