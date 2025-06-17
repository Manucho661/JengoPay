<?php
include '../db/connect.php'; // Make sure this defines $pdo for PDO

if (isset($_GET['success'])) {
  echo "<script>alert('Unit added successfully.');</script>";
} elseif (isset($_GET['duplicate'])) {
  echo "<script>alert('This unit already exists in the building.');</script>";
} elseif (isset($_GET['error'])) {
  echo "<script>alert('An error occurred while inserting the unit.');</script>";
}

$building_id = isset($_GET['building_id']) ? $_GET['building_id'] : null;

if ($building_id) {
    // Prepare the query to fetch units for a specific building
    $stmt = $pdo->prepare("SELECT u.unit_id, u.unit_number, u.rooms, u.room_type, u.unit_type, u.floor_number  FROM units u WHERE u.building_id = ?");

    // Check if preparation was successful
    if ($stmt === false) {
        die("Error preparing statement: " . $pdo->errorInfo()[2]);
    }

    $stmt->bindParam(1, $building_id, PDO::PARAM_INT);
} else {
    // Prepare the query to fetch all units if no building_id is provided
    $stmt = $pdo->prepare("SELECT u.unit_number, u.rooms, u.floor_number FROM units u");

    // Check if preparation was successful
    if ($stmt === false) {
        die("Error preparing statement: " . $pdo->errorInfo()[2]);
    }
}

$stmt->execute();
$units = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Fetch the data from the database using $pdo
$sql = "SELECT building_id, building_name, county, building_type, ownership_info, units_number FROM buildings";
$stmt = $pdo->query($sql);

// Check if a result is returned
if ($stmt->rowCount() > 0) {
    $building = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the first row of data
} else {
    // Handle the case where no data is returned (optional)
    echo "No records found.";
}


if (isset($_GET['building_id'])) {
  $buildingId = intval($_GET['building_id']);

  // Prepare the query
  $stmt = $pdo->prepare("SELECT * FROM buildings WHERE building_id = ?");
  $stmt->bindParam(1, $buildingId, PDO::PARAM_INT);
  $stmt->execute();

  // Get the result
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
// Optional: display or debug output
// print_r($units);
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

   <link rel="stylesheet" href="AllUnits.css">

    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- Bootstrap CSS (in <head>) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- scripts for data_table -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- <link rel="stylesheet" href="announcements.css"> -->

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <!-- Bootstrap Bundle JS (before </body>) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

       <!-- Place this in <head> or before </body> -->
<script>
function handleDelete(event, id, type) {
  event.stopPropagation(); // Prevents event bubbling

  if (confirm("Are you sure?")) {
    fetch('../actions/delete_record.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id=' + encodeURIComponent(id) + '&type=' + encodeURIComponent(type)
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      location.reload();
    })
    .catch(err => console.error('Delete error:', err));
  }
}

</script>


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
            <a href="../Dashboard/index2.html"><span class="brand-text fw-dark">BT JENGOPAY</span></a>
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

            <div class="col-sm-8">
              <div class="">
                <h3 style="color:#FFC107;" class="mb-0 contact_section_header">
                   <b>  <i class="fas fa-home icon"></i><?php echo htmlspecialchars($building['building_name']); ?></b>
                </h3>
                <h6  style="color:#193042;" class="property-type"><b><?php echo htmlspecialchars($building['building_type']); ?></b></h6>
              </div>
            </div>


            <!-- start row -->
            <div class="row mt-3 personal-info">
              <h6 style="color:black;" class="mb-0 contact_section_header mb-2"> </i> Basic Info</h6>
          <div class="col-md-12">
          <div class="row">
  <div class="col-md-3">
    <div class="personal-item d-flex justify-content-between bg-white">
      <div class="category-number p-2" style="display: flex; gap: 5px; align-items: center;">
        <div class="category"><i class="fas fa-briefcase personal-info-icon"></i> <span style="color:#193042;" class="personal-info item-name"> Location,</span> </div>
        <div  style="color:#FFC107;"  class="number"><b><?php echo htmlspecialchars($building['county']); ?></b></div>
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
            <span style="color:#193042;" class="personal-info item-name email"> Ownership,</span> </div>
          <div style="color:#FFC107;" class="value"><b><?php echo htmlspecialchars($building['ownership_info']); ?></b></div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="personal-item d-flex justify-content-between bg-white">
        <div class="category-number p-2" style="display: flex; gap: 5px; align-items: center;">
          <div class="category"><i class="fas fa-city personal-info-icon"></i> <span  style="color:#193042;" class="personal-info item-name">
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



<!-- <hr> -->
<div style="display: flex;gap: 25px;">
<!-- <a href="../property/AllUnits.html"><p>Units(5)</p></a> -->

</div>
<hr>
</div>

<div style="display: flex; gap: 25px;">
    <b><a href="../property/Units.php" style="color:#193042;"><p>Unit list</p></a></b>
    <?php
// This could be inside a loop where you list multiple buildings
echo '<b><a style="color:#193042;" href="../property/meterreading.php?building_id=' . $building['building_id'] . '  ">Meter Reading</a></b>';
?>
    <!-- <a href="../property/meterreading.php"  style="color: #FFC107;"><p>Meter Reading</p></a> -->
</div>

<div class="justify-content-end d-flex" style="padding-bottom:2%">
  <a href="../property/AddUnit.php">
    <button class="edit-btn">
    <i class="fas fa-plus"></i>
    New Unit</button></a>
  </div>

<!--begin::Row-->
<div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <div class="card-header" style="background-color:#00192D;">
          <h5 style="color:#FFC107;" class="card-title"><b>Registered Units</b></h5>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
              <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
              <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
            </button>
            <div class="btn-group">
              <button
                type="button"
                class="btn btn-tool dropdown-toggle"
                data-bs-toggle="dropdown"
              >
                <i class="bi bi-wrench"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a href="#" class="dropdown-item">Action</a>
                <a href="#" class="dropdown-item">Another action</a>
                <a href="#" class="dropdown-item"> Something else here </a>
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
          <!--begin::Row-->
          <div class="row">
            <table id="myTableOne"  class="display table table-striped table-hover" style="width: 100%; font-size: 16px;">
              <thead class="">
                <tr>
                  <th >Units</th>
                  <th >Tenants</th>
                  <th >Room</th>
                  <th >Unit Type</th>
                  <th >Room Type</th>
                  <th >Floor Number</th>
                 <th >Action</th>
                </tr>
              </thead>
              <tbody>
    <?php foreach ($units as $unit): ?>
            <tr data-unit="<?= htmlspecialchars($unit['unit_number']) ?>">
            <td><?= htmlspecialchars($unit['unit_number']) ?></td>
            <td><?= htmlspecialchars($unit['tenant_name'] ?? 'Not Assigned') ?></td> <!-- Replace with dynamic tenant if needed -->
            <td><?= htmlspecialchars($unit['rooms']) ?></td>
            <td><?= htmlspecialchars($unit['unit_type']) ?></td>
            <td><?= htmlspecialchars($unit['room_type']) ?></td>
            <td><?= htmlspecialchars($unit['floor_number']) ?></td>
            <td>

          <!-- Edit Button -->
          <button
  class="btn btn-sm"
  style="background-color:#193042; color:#ffc107;"
  title="Edit this unit"
  data-bs-toggle="modal"
  data-bs-target="#editUnitModal"
  data-unit-id="<?php echo htmlspecialchars($unit['unit_id'] ?? ''); ?>"
  data-unit-number="<?php echo htmlspecialchars($unit['unit_number'] ?? ''); ?>"
  data-room="<?php echo htmlspecialchars($unit['room'] ?? ''); ?>"
  data-unit-type="<?php echo htmlspecialchars($unit['unit_type'] ?? ''); ?>"
  data-room-type="<?php echo htmlspecialchars($unit['room_type'] ?? ''); ?>"
  data-floor-number="<?php echo htmlspecialchars($unit['floor_number'] ?? ''); ?>"
>
  <i class="fa fa-edit" style="font-size: 12px;"></i> EDIT
</button>



<button class="btn btn-sm view-unit-btn"
        style="background-color: #193042; color:#FFC107; margin-right: 2px;"
        data-unit-id="<?= $unit['unit_id']; ?>">
  <i class="fas fa-eye"> View</i>
</button>



    <button
    onclick="handleDelete(event, <?= $unit['unit_id'] ?>, 'unit')"
    class="btn btn-sm btn-delete-unit"
    style="background-color: red; color: white;"
    title="Delete this unit">
    <i class="fa fa-trash" style="font-size: 12px;"></i>
</button>

  </div>
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

    <!--View Unit Modal-->
<div class="modal fade" id="viewUnitModal" tabindex="-1" aria-labelledby="viewUnitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content shadow-lg rounded-4 border-0">
      <form>
        <div class="modal-header py-3" style="background-color: #00192D;">
          <h5 class="modal-title fw-bold" id="viewUnitModalLabel" style="color:#FFC107;">
            <i class="fas fa-info-circle me-2"></i>Unit Details
          </h5>
          <!--<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>-->
        </div>

        <div class="modal-body px-4 pt-4 pb-2">
          <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle">
              <tbody class="fs-6">
                <tr>
                  <th class="text-end text-nowrap  fw-semibold" style="width: 35%; color: #00192D;">
                    <i class="fas fa-door-open me-2 text-warning"></i>Unit Name:
                  </th>
                  <td id="viewUnitName" class="text-dark fw-medium"></td>
                </tr>
                <tr>
                  <th class="text-end text-nowrap  fw-semibold" style="color: #00192D;">
                    <i class="fas fa-user me-2 text-warning"></i>Tenant Name:
                  </th>
                  <td id="viewTenantName" class="text-dark fw-medium"></td>
                </tr>
                <tr>
                  <th class="text-end text-nowrap  fw-semibold" style="color: #00192D;">
                    <i class="fas fa-bed me-2 text-warning"></i>Room:
                  </th>
                  <td id="viewRoom" class="text-dark fw-medium"></td>
                </tr>
                <tr>
                  <th class="text-end text-nowrap  fw-semibold" style="color: #00192D;">
                    <i class="fas fa-building me-2 text-warning"></i>Unit Type:
                  </th>
                  <td id="viewUnitType" class="text-dark fw-medium"></td>
                </tr>
                <tr>
                  <th class="text-end text-nowrap  fw-semibold" style="color: #00192D;">
                    <i class="fas fa-door-closed me-2 text-warning"></i>Room Type:
                  </th>
                  <td id="viewRoomType" class="text-dark fw-medium"></td>
                </tr>
                <tr>
                  <th class="text-end text-nowrap  fw-semibold " style="color: #00192D;">
                    <i class="fas fa-layer-group me-2 text-warning" ></i>Floor Number:
                  </th>
                  <td id="viewFloorNumber" class="text-dark fw-medium"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer justify-content-center pb-4">
          <button type="button" class="btn px-4 py-2 fw-semibold" data-bs-dismiss="modal" style="background-color:#00192D; color:#FFC107; border-radius: 30px;">
            <i class="fas fa-times-circle me-1"></i> Close
          </button>
        </div>
      </form>
    </div>
  </div>
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

<!-- Edit Unit Modal -->
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="editUnitForm" method="POST">
        <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
          <h5 class="modal-title" id="editUnitModalLabel">
            <i class="fas fa-edit me-1"></i> Edit Unit Details
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="unit_id" id="unitId"> <!-- Hidden input for unit_id -->

          <!-- Unit Number -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="unitNumber" name="unit_number" placeholder="Unit Number" required>
            <label for="unitNumber"><i class="fas fa-door-open me-1"></i> Unit Number</label>
          </div>

          <!-- Room -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="rooms" name="rooms" placeholder="Rooms" required>
            <label for="rooms"><i class="fas fa-door-closed me-1"></i> Room</label>
          </div>

          <!-- Unit Type -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="unitType" name="unit_type" placeholder="Unit Type" required>
            <label for="unitType"><i class="fas fa-home me-1"></i> Unit Type</label>
          </div>

          <!-- Room Type -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="roomType" name="room_type" placeholder="Room Type" required>
            <label for="roomType"><i class="fas fa-couch me-1"></i> Room Type</label>
          </div>

          <!-- Floor Number -->
          <div class="form-floating mb-3">
            <input type="number" class="form-control" id="floorNumber" name="floor_number" placeholder="Floor Number" required>
            <label for="floorNumber"><i class="fas fa-layer-group me-1"></i> Floor Number</label>
          </div>
        </div>

        <div class="modal-footer bg-light d-flex justify-content-between">
          <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Update all relevant fields</small>
          <button type="submit" class="btn btn-success" style="background-color: #00192D; color: #FFC107;">
            <i class="fas fa-save me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>





<!-- edit info -->

<div class="units-overlay" id="unitsPopup">
  <div class="units-content wide-form">
      <button class="close-btn" onclick="closeunitsPopup()">×</button>
      <div class="modal-body">
          <input type="hidden" name="id" value="<?= $building['id'] ?>">

          <div class="form-group row g-3">
            <div class="col-md-6">
              <label for="buildingName">Building Name</label>
              <input type="text" class="form-control" name="building_name" value="<?= htmlspecialchars($building['building_name']) ?>" required>
            </div>

            <div class="col-md-6">
              <label for="location">Location</label>
              <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($building['county']) ?>" required>
            </div>

            <div class="col-md-6">
              <label for="buildingType">Building Type</label>
              <input type="text" class="form-control" name="building_type" value="<?= htmlspecialchars($building['building_type']) ?>" required>
            </div>

            <div class="col-md-6">
              <label for="ownership">Ownership</label>
              <input type="text" class="form-control" name="ownership_info" value="<?= htmlspecialchars($building['ownership_info']) ?>" required>
            </div>
            <!-- Add more fields here as needed -->
          </div>
        </div>
  </div>
</div>
<!-- end -->
      </div>
      </div>
    </div>

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

          <!--View unit js-->

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const editUnitModal = new bootstrap.Modal(document.getElementById('editUnitModal'));
    const editUnitForm = document.getElementById('editUnitForm');

    // When the modal is shown, load the unit details into the form
    document.getElementById('editUnitModal').addEventListener('show.bs.modal', function (event) {
        // Assuming you have unit details in your page or fetched via an API
        const button = event.relatedTarget; // Button that triggered the modal
        const unitId = button.getAttribute('data-unit-id');

        // Fetch unit data (replace with your AJAX code to get data from the server)
        fetch(`get_unit_details.php?unit_id=${unitId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate modal fields with the unit data
                    document.getElementById('unitId').value = data.unit.unit_id;
                    document.getElementById('unitNumber').value = data.unit.unit_number;
                    document.getElementById('rooms').value = data.unit.rooms;
                    document.getElementById('unitType').value = data.unit.unit_type;
                    document.getElementById('roomType').value = data.unit.room_type;
                    document.getElementById('floorNumber').value = data.unit.floor_number;
                } else {
                    alert('Failed to load unit details.');
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Handle the form submission via AJAX
    editUnitForm.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevent the default form submission

        const formData = new FormData(editUnitForm);

        fetch('update_unit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Success message
                editUnitModal.hide(); // Close the modal
                location.reload(); // Reload the page (or use AJAX to update the page dynamically)
            } else {
                alert(data.message); // Error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving changes.');
        });
    });
});
</script>

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
function deleteUnit(unitId) {
    if (confirm('Are you sure you want to delete this unit?')) {
        // Create a form dynamically
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_unit.php'; // Your PHP processing file

        // Add the unit ID as a hidden input
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'unit_id';
        input.value = unitId;
        form.appendChild(input);

        // Add CSRF token if needed (recommended)
        var csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = 'csrf_token';
        csrf.value = '<?php echo $_SESSION["csrf_token"] ?? ""; ?>';
        form.appendChild(csrf);

        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }
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


<!-- <script>
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
document.addEventListener("DOMContentLoaded", function () {
  const viewButtons = document.querySelectorAll(".view-unit-btn");

  viewButtons.forEach(button => {
    button.addEventListener("click", function () {
      const unitId = button.getAttribute("data-unit-id");

      fetch(`get_unit_basic_details.php?unit_id=${unitId}`)
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            const data = result.data;
            document.getElementById("viewUnitName").textContent = data.unit_number || '-';
            document.getElementById("viewTenantName").textContent = 'N/A'; // You can change this if you have tenant info
            document.getElementById("viewRoom").textContent = data.rooms || '-';
            document.getElementById("viewUnitType").textContent = data.unit_type || '-';
            document.getElementById("viewRoomType").textContent = data.room_type || '-';
            document.getElementById("viewFloorNumber").textContent = data.floor_number || '-';

            const viewModal = new bootstrap.Modal(document.getElementById("viewUnitModal"));
            viewModal.show();
          } else {
            alert(result.message || "Failed to fetch unit data.");
          }
        })
        .catch(error => {
          console.error("Fetch error:", error);
          alert("Something went wrong while fetching unit details.");
        });
    });
  });
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



<script src="units.js"></script>
    <!--end::Script-->
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
