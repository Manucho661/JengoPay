
<?php
 include '../db/connect.php';
?>

<?php


  // Fetch tenants with their user details
  $sql = "SELECT
              users.id,
              users.name,
              users.email,
              tenants.phone_number,
              tenants.user_id,
              tenants.residence,
              tenants.id_no,
              tenants.unit,
              tenants.status
          FROM tenants
          INNER JOIN users ON tenants.user_id = users.id";

  $stmt = $pdo->query($sql);
  $tenantsy = $stmt->fetchAll();


 // Tenants Count
            $count = count($tenantsy);
            $activeTenantsCount  = 0;
            $inactiveTenantsCount = 0;

            foreach ($tenantsy as $tenant) {
                if (strtolower($tenant['status']) === 'active') {
                    $activeTenantsCount++;
                } else {
                    $inactiveTenantsCount++;
                }
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
    <meta name="author" content="ColorlibHQ"/>

    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />

    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />

    <!-- loading out and in progress -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->



    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

   <link rel="stylesheet" href="tenants.css">
     <!-- scripts for data_table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <style>
.app-content{
  flex: 1;
  align-items: stretch;
  display: flex;
  flex-direction: column;
}
.app-content .container-fluid{
  flex: 1;
  align-items: stretch;
  display: flex;
  flex-direction: column;
}
.container-fluid .row.details{
  flex: 1;
  align-items: stretch;
  display: flex;
  flex-direction: column;
}
.col-md-12.details{
  flex: 1;
  align-items: stretch;
  display: flex;
  flex-direction: column;
}
.details-container{
  flex: 1;
  align-items: stretch;
  display: flex;
  flex-direction: column;
}
    </style>
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper"style="background-color:rgba(128,128,128, 0.1);" >
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
                  src="17.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">  <b>JENGO PAY</b>  </span>
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

                                                            <!-- MAIN -->
      <!--begin::App Main-->
      <main class="app-main" id="mainElement">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-8">

                <h3 class="mb-0 contact_section_header">  <i class="fas fa-user-tie icon"></i> Tenants</h3>


                </div>


              <div class="col-sm-4 d-flex justify-content-end">
                  <div class="vacate">
                    <button class="vacate-tenant rounded" style="height: fit-content;" onclick="openPopup()" > ADD TENANT</button>
                  </div>
              </div>

            </div>
            <!--end::Row-->

                                              <!-- SUMMARY -->
            <!-- Start Row-->
            <div class="row">
              <div class="col-md-12">

                <h6 class="mb-0 contact_section_header summary mb-2"> </i> Summary</h6>

                <div class="row">

                  <div class="col-md-3">

                    <div class="summary-card p-2">
                        <div ><i class="fas fa-user-tie summary-card_icon"></i> <span class="summary-card_label" > Total,</span> </div>
                        <div class="summary-card_value"><b> <?= $count ?> </b></div>
                    </div>

                  </div>

                  <div class="col-md-3">

                    <div class="summary-card p-2">
                        <div ><i class="fas fa-user-tie summary-card_icon"></i> <span class="summary-card_label" > Active,</span> </div>
                        <div class="summary-card_value active"><b> <?= $activeTenantsCount ?> </b></div>
                    </div>

                  </div>

                  <div class="col-md-3">

                    <div class="summary-card p-2">
                        <div ><i class="fas fa-user-tie summary-card_icon"></i> <span class="summary-card_label" > Inactive,</span> </div>
                        <div class="summary-card_value inactive"><b> <?= $inactiveTenantsCount ?> </b></div>
                    </div>

                  </div>

                  <div class="col-md-3">

                    <div class="summary-card p-2">
                        <div ><i class="fas fa-user-tie summary-card_icon"></i> <span class="summary-card_label" > New Applicants,</span> </div>
                        <div class="summary-card_value"><b> 2000</b></div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- End row -->
          </div>
          <!--end::Container-fluid-->
        </div>

                                                      <!-- CONTENT  -->
        <div class="app-content mt-4">
          <!--begin::Container-->
          <div class="container-fluid">

            <h6 class="mb-0 contact_section_header summary mb-2"> </i> Details</h6>
            <!--begin::Row-->
            <div class="row details">
              <!-- Start col -->
              <div class="col-md-12 details">
                <div class="details-container bg-white p-2 rounded">
                   <h3 class="details-container_header text-start"> <span id="displayed_building">All Tenants</span>  &nbsp;	|&nbsp;	 <span style="color:#FFC107">3 enteries</span></h3>
                   <div class="table-responsive">
                    <div id="top-bar" class="filter-pdf-excel mb-2">
                      <div class="d-flex" style="gap: 10px;">
                        <div class="select-option-container">
                          <div class="custom-select">All Buildings</div>
                          <div class="select-options mt-1">
                            <div class="selected" data-value="item1">All Buildings</div>
                            <div data-value="Manucho">Manucho</div>
                            <div data-value="item2">Ebenezer</div>
                            <div data-value="item3">Crown Z</div>
                          </div>
                        </div>

                        <div id="custom-search">
                          <input type="text" id="searchInput" placeholder="Search tenant...">
                        </div>

                      </div>

                      <div>

                      </div>

                      <div class="d-flex">

                        <button id="add_provider_btn"  class="btn shift-tenant rounded" style="height: fit-content;" onclick="openPopup()" > Shift Tenant</button>


                            <div id="custom-buttons"></div>
                      </div>

                    </div>

                    <table class="table table-hover" id="users-table">
                        <thead class="thead bg-gradient" >
                            <tr>

                              <th>Full Name</th>
                              <th>ID</th>
                              <th>RESIDENCE + UNIT</th>
                              <th>CONTACT</th>
                              <th>STATUS</th>
                              <th>ACTIONS</th>

                            </tr>
                          </thead>

                          <tbody>


                          </tbody>
                    </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
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
          <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;"> JENGO PAY</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->


                                              <!-- OVERLAYS -->

   <!-- Add Tenant -->
   <div class="popup-overlay" id="addTenantModal">
  <div class="popup-content">
    <button class="close-btn text-secondary" onclick="closePopup()">×</button>

    <!-- Form with onsubmit event handler to call the JavaScript function -->
    <form id="tenantForm" class="complaint-form" onsubmit="submitTenantForm(event)">
      <h2 class="text-start addTenantHeader">Add Tenant</h2>
      <label for="name">Tenant Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="number">Identification No:</label>
      <input type="number" id="number" name="id" required>

      <label for="email">Email Address:</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Phone Number:</label>
      <input type="tel" id="phone" name="phone" required>

      <label for="property">Property:</label>
      <select id="property" name="residence" required>
        <option value="" disabled selected>Select Property</option>
        <option value="Manucho">Manucho</option>
        <option value="White House">White House</option>
        <option value="Pink House">Pink House</option>
        <option value="Silver">Silver</option>
      </select>

      <label for="unit">Rental Unit:</label>
      <input type="text" id="unit" name="unit" required>

      <!-- Submit Button -->
      <button type="submit" class="submit-btn" style="background-color: #00192D; color: #f1f1f1;">SUBMIT</button>
    </form>
  </div>
</div>

        <!--End Add Tenant -->

        <!-- Shift Tenant -->
          <div class="shiftpopup-overlay" id="shiftPopup">
            <div class="shiftpopup-content">
              <button class="close-btn text-secondary" onclick="closeshiftPopup()">×</button>
              <div class="shift">
              <h2 style="color: #00192D;">Shift Tenant</h2>
              <label for="tenant">Select Tenant:</label>

              <select id="tenant"  style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;">
                  <option value="John Doe">John Doe</option>
                  <option value="Jane Smith">Jane Smith</option>
                  <option value="Mike Johnson">Mike Johnson</option>
              </select>

              <label for="property" >Select New Property:</label>
              <select id="property" style="padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;">
                  <option value="Apartment 101">Apartment 101</option>
                  <option value="House B3">House B3</option>
                  <option value="Condo 23A">Condo 23A</option>
              </select>
              <label for="property">Select Unit:</label>
              <select id="property" style="width: 100%;padding: 10px;margin-bottom: 15px;border-radius: 5px;border: 1px solid #ccc;">
                  <option value="Apartment 101">A55</option>
                  <option value="House B3">B3</option>
                  <option value="Condo 23A">CA</option>
              </select>
              <br>

              <button  type="submit" class="submit-btn" onclick="shiftTenant()"  style="background-color: #00192D; color: #f1f1f1;">Confirm Shift</button>

            </div>
            </div>
          </div>

          <script src="tenants.js"></script>


                                           <!-- PLUGINS -->

            <!-- LOADING AND OUT PROGRESS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
            <!-- EnD LOADING AND OUT PROGRESS -->


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



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


      
    
    <!-- Script for datatable -->
    <script>

            document.addEventListener("DOMContentLoaded", function() {


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
    <!-- End script for data_table -->

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
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>


    <!-- OPEN TENANT PAGE -->
    <script>
      function goToDetails(userId) {
        window.location.href = `../people/tenant-profile.php?id=${userId}`;
      }
    </script>

    

    </script>



  </script>


  </body>
</html>

