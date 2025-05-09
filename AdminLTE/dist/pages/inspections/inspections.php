<?php
 include '../db/connect.php';
?>

<?php
  // Fetch tenants with their user details
  $sql = "SELECT
  inspections.inspection_number,
  inspections.building_name,
  inspections.unit_name,
  inspections.inspection_type,
  inspections.date
FROM inspections";

$stmt = $pdo->query($sql);

// Use fetchAll to get all rows as an array
$inspections = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Safely count the inspections
$inspectionsCount = is_array($inspections) ? count($inspections) : 0;

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
                                                    <!-- LINKS -->

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

   <link rel="stylesheet" href="inspections.css">
     <!-- scripts for data_table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">


    <style>
      .app-wrapper{
         background-color: rgba(128,128,128, 0.1);
      }
    </style>
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper"  >
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-white">
        <!--begin::Container-->
        <div class="container-fluid" >
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
                <span class="d-none d-md-inline" style="color: #00192D;" > <b> JENGO PAY  </b>  </span>
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
        <div > <?php include_once '../includes/sidebar.php'; ?>  </div> <!-- This is where the sidebar is inserted -->
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main" >

                      <!--MAIN MODALS -->
        <!-- add inspection -->
         <section class="add-inspection" id="add-inspection" >

         <div class="card content"  >
          <div class="card-header d-flex justify-content-between" style="background:  #00192D; padding:10px !important" >
            <div class="text-white">New Inspection Details</div>
              <div><button class="close-btn text-white" onclick="closeInspectionMDL()">×</button></div>
              
          </div>
          <div class="card-body">
              <div class="form-container">
                  <form id="form_new_inspection" onsubmit="submitInspectionForm(event)" >
                      <div class="row g-3">
                        <!-- Year -->
                          <div class="col-md-4 " >
                              <label class="form-label"><b>Inspection Number</b></label>
                              <input class="form-control" type="number" name="inspection_number" placeholder="124">
                          </div>
                          <!-- No -->
                          <div class="col-md-4">
                            <label class="form-label"><b> Building</b></label>
                            <select class="form-control" name="building_name" id="building_name" >
                              <option value="Crown Z">Crown Z</option>
                              <option value="Manucho">Manucho</option>
                              <option value="Pink House">Pink House</option>
                              <option value="White House">White House</option>
                            </select>
                          </div>
                          <!-- Expense of the Month -->
                          <div class="col-md-4 ">
                              <label class="form-label"><b> Unit</b></label>
                              <select class="form-control" name="unit_name" id="unit_name" >
                                <option value="C219">C219</option>
                                <option value="B14">B14</option>
                                <option value="M145">M56</option>
                                <option value="M5">M290</option>
                              </select>
                          </div>
                      </div>
                      <hr>
                      <div class="row g-3 mt-2">
                          <div class="col-md-6">
                              <label class="form-label"><b> Inspection Type</b></label>
                              <select class="form-control" name="inspection_type" id="inspection_type" >
                                <option value="Move In">Move In</option>
                                <option value="Move Out">Move Out</option>
                                
                              </select>
                          </div>

                          <div class="col-md-6">
                          <label class="form-label"><b> Inspection Date</b></label>
                          <input type="date" name="date">
                      </div>

                      <hr>

                      <div class="row g-3 mt-2">
                          
                          <div class="col-12 mt-2 d-flex justify-content-end">
                              <button type="submit" class="btn btn-custom btn-sm" style="height: fit-content; color:#FFC107; background:#00192D;">  SUBMIT</button>
                          </div>
                      </div>

                  </form>
              </div>
          </div>
         </div>

         </section>



        <!--begin::App Content Header-->

        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-8">

                <h3 class="mb-0 contact_section_header"> <i class="fas fa-search icon title-icon"></i>INSPECTIONS</h3>


                    <div class="row mt-2">

                </div>
              </div>

              <div class="col-sm-4 d-flex justify-content-end">

                    <button   class="btn schedule" onclick="addNewSchedule()" style="height: fit-content;"> <i class="fas fa-plus "></i> New Schedule</button>

            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>

        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!-- BEGIN ROW -->
            <div class="row">

              <h6 class="mb-0 contact_section_header summary mb-2"></i> SUMMARY</h6>

              <div class="container-fluid">
                <div class="row">
                  <div class="col-6 col-sm-3">
                    <div class="summary-card" >
                        <div class="summary-card_icon"> <i class="fas fa-clipboard-check"></i></div>
                      <div>
                        <div class="summary-card_label">Scheduled</div>
                        <div class="summary-card_value" >&nbsp; <?= $inspectionsCount ?> </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-6 col-sm-3">
                    <div class="summary-card" >
                      <div class="summary-card_icon"><i class="fas fa-check-circle"></i></div>
                      <div>
                        <div class="summary-card_label">Completed</div>
                        <div class="summary-card_value penalities" >&nbsp;300</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-6 col-sm-3">
                    <div class="summary-card">
                      <div class="summary-card_icon"> <i class="fas fa-spinner fa-spin"></i>  </div>
                      <div>
                        <div class="summary-card_label" >In Progress</div>
                        <div class="summary-card_value" >&nbsp;200</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-6 col-sm-3">
                    <div class="summary-card">
                      <div class="summary-card_icon" style="font-weight: bold;"><i class="fas fa-question-circle"></i>    </div>
                      <div>
                        <div class="summary-card_label">Incomplete</div>
                        <div class="summary-card_value">&nbsp;20</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            

            
              <!-- /end row -->
            


              <div class="row mt-2 mb-2 add-inspection-container">
                <div class="col-md-12 ">
                 <div class="add-inspection-btn bg-white p-2">
                        <a class="btn-link add-inspection-btn "
                            data-bs-toggle="collapse"
                            href="#selectInspectionSection"
                            aria-expanded="false"
                            aria-controls="moveInSection"
                            onclick="hideOther('moveOutSection')"
                            style="color: #00192D; font-weight:600; font-size: 16px; text-decoration: none;">
                          <span>+</span> Perform An Inspection
                        </a>
                 </div>
                        
                </div>

              <div class="col-md-12 collapse bg-white selectInspectionSection" id="selectInspectionSection" >
                      <div class="card-header">
                        <div class="title select-section-title"> Select Inspection Type:</div>

                        <a class="btn-link moveOut-btn select-btn"
                        data-bs-toggle="collapse"
                        href="#moveInSection"
                        aria-expanded="false"
                        aria-controls="moveInSection"
                        onclick="hideOther('moveOutSection')"
                        style="">
                        <i class="fas fa-arrow-right" style="color: green;"></i> Move In </a>

                    <!-- Move Out Button -->
                       <a class="btn-link moveIn-btn select-btn"
                        data-bs-toggle="collapse"
                        href="#moveOutSection"
                        aria-expanded="false"
                        aria-controls="moveOutSection"
                        onclick="hideOther('moveInSection')"
                        style="">
                         <i class="fas fa-door-open" style="color: red;"></i> Move Out
                      </a>

                </div>

                                      <!-- MOVE IN INSPECTION COLUMN -->
                <div class="col-md-12 collapse" id="moveInSection" >
                  <div class="card">
                    <div class="card-header" style="background: linear-gradient(to right, #00192D, #003D5B, #00788D); display: none;">
                        <h6 class="text-white">Move out</h6>
                    </div>
                    <div class="card-body">
                    <div id="stepper" class="bs-stepper">
                      <div class="bs-stepper-header">
                        <div class="step" data-target="#step1">
                          <button class="step-trigger">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Move In</span>
                          </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#step2">
                          <button class="step-trigger">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Move In</span>
                          </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#step3">
                          <button class="step-trigger">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Move In</span>
                          </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#step4">
                          <button class="step-trigger">
                            <span class="bs-stepper-circle">4</span>
                            <span class="bs-stepper-label">Move In</span>
                          </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#step5">
                          <button class="step-trigger">
                            <span class="bs-stepper-circle">5</span>
                            <span class="bs-stepper-label">Move In</span>
                          </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#step6">
                          <button class="step-trigger">
                            <span class="bs-stepper-circle">6</span>
                            <span class="bs-stepper-label">Move In</span>
                          </button>
                        </div>
                      </div>
                      <div id="step1" class="content">
                        <div class="inpection-section-headers">Property & Tenant Information</div>

                        <form>
                          <div class="row">
                            <div class="col-md-6">
                              <label class="form-label">Property Name</label>
                              <input type="text" class="form-control" placeholder="Ebenezer">
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Unit Number</label>
                              <input type="number" class="form-control" placeholder="Ebenezer">
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6">
                              <label class="form-label">Landlord Name</label>
                              <input type="text" class="form-control" placeholder="Ebenezer">
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Tenant Name</label>
                              <input type="text" class="form-control" placeholder="Ebenezer">
                            </div>

                          </div>

                          <div class="row">

                            <div class="col-md-6">
                              <label class="form-label"> Inspection Date</label>
                              <input type="date" class="form-control" placeholder="Ebenezer">
                            </div>

                            <div class="col-md-12 d-flex justify-content-end">
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper.next()">Next</button>

                            </div>
                          </div>

                        </form>
                      </div>
                      <div id="step2" class="content">
                        <div class="inpection-section-headers">Living Room & Common Areas</div>
                        <form>
                          <table class="inspection-table">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Condition at Move-In</th>
                                <th>Comments</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Walls & Paint</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="walls" value="Good"> Good</label>
                                  <label><input type="radio" name="walls" value="Fair"> Fair</label>
                                  <label><input type="radio" name="walls" value="Poor"> Poor</label>
                                </td>
                                <td><textarea placeholder="Add comments..."></textarea></td>
                              </tr>
                              <tr>
                                <td>Ceiling</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="ceiling" value="Good"> Good</label>
                                  <label><input type="radio" name="ceiling" value="Fair"> Fair</label>
                                  <label><input type="radio" name="ceiling" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Floor</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="flooring" value="Good"> Good</label>
                                  <label><input type="radio" name="flooring" value="Fair"> Fair</label>
                                  <label><input type="radio" name="flooring" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Doors & Locks</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="doors" value="Good"> Good</label>
                                  <label><input type="radio" name="doors" value="Fair"> Fair</label>
                                  <label><input type="radio" name="doors" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Windows & Glass</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="windows" value="Good"> Good</label>
                                  <label><input type="radio" name="windows" value="Fair"> Fair</label>
                                  <label><input type="radio" name="windows" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Lighting Fixtures</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="lights" value="Good"> Good</label>
                                  <label><input type="radio" name="lights" value="Fair"> Fair</label>
                                  <label><input type="radio" name="lights" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Electrical Outlets</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="outlets" value="Good"> Good</label>
                                  <label><input type="radio" name="outlets" value="Fair"> Fair</label>
                                  <label><input type="radio" name="outlets" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Curtain Rods</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="curtains" value="Good"> Good</label>
                                  <label><input type="radio" name="curtains" value="Fair"> Fair</label>
                                  <label><input type="radio" name="curtains" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                            </tbody>
                          </table>
                          <div class="col-md-12 d-flex justify-content-between">
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.previous()">Back</button>
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.next()">Next</button>
                          </div>

                        </form>
                      </div>
                      <div id="step3" class="content">
                        <div class="inpection-section-headers" >Kitchen </div>
                        <form>
                          <table class="inspection-table" >
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Condition at Move-In</th>
                                <th>Comments</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Cabinets and  Shelving</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="walls" value="Good"> Good</label>
                                  <label><input type="radio" name="walls" value="Fair"> Fair</label>
                                  <label><input type="radio" name="walls" value="Poor"> Poor</label>
                                </td>
                                <td><textarea placeholder="Add comments..."></textarea></td>
                              </tr>
                              <tr>
                                <td>CounterTops</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="ceiling" value="Good"> Good</label>
                                  <label><input type="radio" name="ceiling" value="Fair"> Fair</label>
                                  <label><input type="radio" name="ceiling" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Sink and Taps</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="flooring" value="Good"> Good</label>
                                  <label><input type="radio" name="flooring" value="Fair"> Fair</label>
                                  <label><input type="radio" name="flooring" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Plumbing(Leaks, Drainage)</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="doors" value="Good"> Good</label>
                                  <label><input type="radio" name="doors" value="Fair"> Fair</label>
                                  <label><input type="radio" name="doors" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Cooker/Oven (If Applicable)</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="windows" value="Good"> Good</label>
                                  <label><input type="radio" name="windows" value="Fair"> Fair</label>
                                  <label><input type="radio" name="windows" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Refrigerator</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="lights" value="Good"> Good</label>
                                  <label><input type="radio" name="lights" value="Fair"> Fair</label>
                                  <label><input type="radio" name="lights" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Lighting Fixture</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="outlets" value="Good"> Good</label>
                                  <label><input type="radio" name="outlets" value="Fair"> Fair</label>
                                  <label><input type="radio" name="outlets" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>

                            </tbody>
                          </table>
                          <div class="col-md-12 d-flex justify-content-between">
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.previous()">Back</button>
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.next()">Next</button>
                          </div>
                        </form>
                      </div>

                      <div id="step4" class="content">
                        <div class="inpection-section-headers" >Bedrooms </div>
                        <form>
                          <table class="inspection-table" >
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Condition at Move-In</th>
                                <th>Comments</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Wells and Paint</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="walls" value="Good"> Good</label>
                                  <label><input type="radio" name="walls" value="Fair"> Fair</label>
                                  <label><input type="radio" name="walls" value="Poor"> Poor</label>
                                </td>
                                <td><textarea placeholder="Add comments..."></textarea></td>
                              </tr>
                              <tr>
                                <td>Ceiling</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="ceiling" value="Good"> Good</label>
                                  <label><input type="radio" name="ceiling" value="Fair"> Fair</label>
                                  <label><input type="radio" name="ceiling" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Floor</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="flooring" value="Good"> Good</label>
                                  <label><input type="radio" name="flooring" value="Fair"> Fair</label>
                                  <label><input type="radio" name="flooring" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Window and Glass</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="doors" value="Good"> Good</label>
                                  <label><input type="radio" name="doors" value="Fair"> Fair</label>
                                  <label><input type="radio" name="doors" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Wardrobes</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="windows" value="Good"> Good</label>
                                  <label><input type="radio" name="windows" value="Fair"> Fair</label>
                                  <label><input type="radio" name="windows" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Curtain Rods</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="lights" value="Good"> Good</label>
                                  <label><input type="radio" name="lights" value="Fair"> Fair</label>
                                  <label><input type="radio" name="lights" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Lighting Fixtures</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="outlets" value="Good"> Good</label>
                                  <label><input type="radio" name="outlets" value="Fair"> Fair</label>
                                  <label><input type="radio" name="outlets" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>

                            </tbody>
                          </table>
                          <div class="col-md-12 d-flex justify-content-between">
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.previous()">Back</button>
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.next()">Next</button>
                          </div>

                        </form>
                      </div>

                      <div id="step5" class="content">
                        <div class="inpection-section-headers" >Washrooms</div>

                        <form>
                          <table class="inspection-table">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Condition at Move-In</th>
                                <th>Comments</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Sinks and Taps</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="walls" value="Good"> Good</label>
                                  <label><input type="radio" name="walls" value="Fair"> Fair</label>
                                  <label><input type="radio" name="walls" value="Poor"> Poor</label>
                                </td>
                                <td><textarea placeholder="Add comments..."></textarea></td>
                              </tr>
                              <tr>
                                <td>WC/Toilet</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="ceiling" value="Good"> Good</label>
                                  <label><input type="radio" name="ceiling" value="Fair"> Fair</label>
                                  <label><input type="radio" name="ceiling" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Showerhead</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="flooring" value="Good"> Good</label>
                                  <label><input type="radio" name="flooring" value="Fair"> Fair</label>
                                  <label><input type="radio" name="flooring" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Plumbing(Leaks, Drainage)</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="doors" value="Good"> Good</label>
                                  <label><input type="radio" name="doors" value="Fair"> Fair</label>
                                  <label><input type="radio" name="doors" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>
                              <tr>
                                <td>Bathroom Tiles and Grouting</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="windows" value="Good"> Good</label>
                                  <label><input type="radio" name="windows" value="Fair"> Fair</label>
                                  <label><input type="radio" name="windows" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>

                              <tr>
                                <td>Lighting Fixtures</td>
                                <td class="radio-group">
                                  <label><input type="radio" name="outlets" value="Good"> Good</label>
                                  <label><input type="radio" name="outlets" value="Fair"> Fair</label>
                                  <label><input type="radio" name="outlets" value="Poor"> Poor</label>
                                </td>
                                <td><textarea></textarea></td>
                              </tr>

                            </tbody>
                          </table>
                          <div class="col-md-12 d-flex justify-content-between">
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.previous()">Back</button>
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.next()">Next</button>
                          </div>
                        </form>
                      </div>

                      <div id="step6" class="content">
                        <form>
                          <div class="section">
                            <div class="inpection-section-headers" >Additional Notes & Observations</div>
                            <textarea name="additional_notes" placeholder="Write any notes here..."></textarea>
                          </div>

                          <div class="section">
                            <div class="inpection-section-headers" >Signatures</div>

                            <div class="signatures">
                              <div class="row">
                                  <div class="col-md-4">
                                    <label for="landlord_name">Landlord Name:</label>
                                    <input type="text" id="landlord_name" name="landlord_name">
                                  </div>
                                  <div class="col-md-4">
                                    <label for="landlord_signature">Signature:</label>
                                    <input type="text" id="landlord_signature" name="landlord_signature">
                                  </div>
                                  <div class="col-md-4">
                                    <label for="landlord_date">Date:</label>
                                  <input type="date" id="landlord_date" name="landlord_date">
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-4">
                                  <label for="tenant_name">Tenant Name:</label>
                                  <input type="text" id="tenant_name" name="tenant_name">
                                </div>
                                <div class="col-md-4">
                                  <label for="tenant_signature">Signature:</label>
                                  <input type="text" id="tenant_signature" name="tenant_signature">
                                </div>
                                <div class="col-md-4">
                                  <label for="tenant_date">Date:</label>
                                  <input type="date" id="tenant_date" name="tenant_date">
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12 d-flex justify-content-between mt-2">
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.previous()">Back</button>
                            <button type="button" class="btn btn-primary next-btn" onclick="stepper.nex()">Cancel</button>
                            <button type="submit" class="btn btn-success next-btn">Submit</button>
                          </div>

                        </form>
                      </div>

                    </div>
                   </div>
                  </div>
                </div>
                <!-- MOVE OUT INSPECTION COLUMN -->
                <div class="col-md-12 collapse" id="moveOutSection">
                  <div class="card">
                    <div class="card-header" style="background: linear-gradient(to right, #00192D, #003D5B, #00788D); display: none;">
                        <h6 class="text-white">Move out</h6>
                    </div>
                    <div class="card-body" style="overflow: auto;">
                      <div id="MoveOut" class="bs-stepper">
                        <div class="bs-stepper-header">
                          <div class="step" data-target="#stepMoveOut1">
                            <button class="step-trigger">
                              <span class="bs-stepper-circle">1</span>
                              <span class="bs-stepper-label">Move Out</span>
                            </button>
                          </div>
                          <div class="line"></div>
                          <div class="step" data-target="#stepMoveOut2">
                            <button class="step-trigger">
                              <span class="bs-stepper-circle">2</span>
                              <span class="bs-stepper-label">Move Out</span>
                            </button>
                          </div>
                          <div class="line"></div>
                          <div class="step" data-target="#stepMoveOut3">
                            <button class="step-trigger">
                              <span class="bs-stepper-circle">3</span>
                              <span class="bs-stepper-label">Move Out</span>
                            </button>
                          </div>
                          <div class="line"></div>
                          <div class="step" data-target="#stepMoveOut4">
                            <button class="step-trigger">
                              <span class="bs-stepper-circle">4</span>
                              <span class="bs-stepper-label">Move Out</span>
                            </button>
                          </div>
                          <div class="line"></div>
                          <div class="step" data-target="#stepMoveOut5">
                            <button class="step-trigger">
                              <span class="bs-stepper-circle">5</span>
                              <span class="bs-stepper-label">Move Out</span>
                            </button>
                          </div>
                          <div class="line"></div>
                          <div class="step" data-target="#stepMoveOut6">
                            <button class="step-trigger">
                              <span class="bs-stepper-circle">6</span>
                              <span class="bs-stepper-label">Move Out</span>
                            </button>
                          </div>
                          <div class="step" data-target="#stepMoveOut7">
                            <button class="step-trigger">
                              <span class="bs-stepper-circle">7</span>
                              <span class="bs-stepper-label">Move Out</span>
                            </button>
                          </div>
                        </div>

                        <div id="stepMoveOut1" class="content">
                          <div class="inpection-section-headers">Property & Tenant Information</div>

                          <form>
                            <div class="row">
                              <div class="col-md-6">
                                <label class="form-label">Property Name</label>
                                <input type="text" class="form-control" placeholder="Ebenezer">
                              </div>
                              <div class="col-md-6">
                                <label class="form-label">Unit Number</label>
                                <input type="number" class="form-control" placeholder="Ebenezer">
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-6">
                                <label class="form-label">Landlord Name</label>
                                <input type="text" class="form-control" placeholder="Ebenezer">
                              </div>
                              <div class="col-md-6">
                                <label class="form-label">Tenant Name</label>
                                <input type="text" class="form-control" placeholder="Ebenezer">
                              </div>

                            </div>

                            <div class="row">

                              <div class="col-md-6">
                                <label class="form-label"> Inspection Date</label>
                                <input type="date" class="form-control" placeholder="Ebenezer">
                              </div>

                              <div class="col-md-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary next-btn" onclick="stepper2.next()">Next</button>

                              </div>
                            </div>

                          </form>
                        </div>

                        <div id="stepMoveOut2" class="content">
                          <div class="inpection-section-headers">Living Room & Common Areas</div>
                          <form>
                            <table class="inspection-table">
                              <thead>
                                <tr>
                                  <th>Item</th>
                                  <th>Condition at Move-Out</th>
                                  <th>Comments</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Walls & Paint</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="walls" value="Good"> Good</label>
                                    <label><input type="radio" name="walls" value="Fair"> Fair</label>
                                    <label><input type="radio" name="walls" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea placeholder="Add comments..."></textarea></td>
                                </tr>
                                <tr>
                                  <td>Ceiling</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="ceiling" value="Good"> Good</label>
                                    <label><input type="radio" name="ceiling" value="Fair"> Fair</label>
                                    <label><input type="radio" name="ceiling" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Floor</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="flooring" value="Good"> Good</label>
                                    <label><input type="radio" name="flooring" value="Fair"> Fair</label>
                                    <label><input type="radio" name="flooring" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Doors & Locks</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="doors" value="Good"> Good</label>
                                    <label><input type="radio" name="doors" value="Fair"> Fair</label>
                                    <label><input type="radio" name="doors" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Windows & Glass</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="windows" value="Good"> Good</label>
                                    <label><input type="radio" name="windows" value="Fair"> Fair</label>
                                    <label><input type="radio" name="windows" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Lighting Fixtures</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="lights" value="Good"> Good</label>
                                    <label><input type="radio" name="lights" value="Fair"> Fair</label>
                                    <label><input type="radio" name="lights" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Electrical Outlets</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="outlets" value="Good"> Good</label>
                                    <label><input type="radio" name="outlets" value="Fair"> Fair</label>
                                    <label><input type="radio" name="outlets" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Curtain Rods</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="curtains" value="Good"> Good</label>
                                    <label><input type="radio" name="curtains" value="Fair"> Fair</label>
                                    <label><input type="radio" name="curtains" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                              </tbody>
                            </table>
                            <div class="col-md-12 d-flex justify-content-between">
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.previous()">Back</button>
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.next()">Next</button>
                            </div>

                          </form>
                        </div>

                        <div id="stepMoveOut3" class="content">
                          <div class="inpection-section-headers" >Kitchen </div>
                          <form>
                            <table class="inspection-table" >
                              <thead>
                                <tr>
                                  <th>Item</th>
                                  <th>Condition at Move-Out</th>
                                  <th>Comments</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Cabinets and  Shelving</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="walls" value="Good"> Good</label>
                                    <label><input type="radio" name="walls" value="Fair"> Fair</label>
                                    <label><input type="radio" name="walls" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea placeholder="Add comments..."></textarea></td>
                                </tr>
                                <tr>
                                  <td>CounterTops</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="ceiling" value="Good"> Good</label>
                                    <label><input type="radio" name="ceiling" value="Fair"> Fair</label>
                                    <label><input type="radio" name="ceiling" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Sink and Taps</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="flooring" value="Good"> Good</label>
                                    <label><input type="radio" name="flooring" value="Fair"> Fair</label>
                                    <label><input type="radio" name="flooring" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Plumbing(Leaks, Drainage)</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="doors" value="Good"> Good</label>
                                    <label><input type="radio" name="doors" value="Fair"> Fair</label>
                                    <label><input type="radio" name="doors" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Cooker/Oven (If Applicable)</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="windows" value="Good"> Good</label>
                                    <label><input type="radio" name="windows" value="Fair"> Fair</label>
                                    <label><input type="radio" name="windows" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Refrigerator</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="lights" value="Good"> Good</label>
                                    <label><input type="radio" name="lights" value="Fair"> Fair</label>
                                    <label><input type="radio" name="lights" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Lighting Fixture</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="outlets" value="Good"> Good</label>
                                    <label><input type="radio" name="outlets" value="Fair"> Fair</label>
                                    <label><input type="radio" name="outlets" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>

                              </tbody>
                            </table>
                            <div class="col-md-12 d-flex justify-content-between">
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.previous()">Back</button>
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.next()">Next</button>
                            </div>
                          </form>
                        </div>

                        <div id="stepMoveOut4" class="content">
                          <div class="inpection-section-headers" >Bedrooms </div>
                          <form>
                            <table class="inspection-table" >
                              <thead>
                                <tr>
                                  <th>Item</th>
                                  <th>Condition at Move-Out</th>
                                  <th>Comments</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Wells and Paint</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="walls" value="Good"> Good</label>
                                    <label><input type="radio" name="walls" value="Fair"> Fair</label>
                                    <label><input type="radio" name="walls" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea placeholder="Add comments..."></textarea></td>
                                </tr>
                                <tr>
                                  <td>Ceiling</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="ceiling" value="Good"> Good</label>
                                    <label><input type="radio" name="ceiling" value="Fair"> Fair</label>
                                    <label><input type="radio" name="ceiling" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Floor</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="flooring" value="Good"> Good</label>
                                    <label><input type="radio" name="flooring" value="Fair"> Fair</label>
                                    <label><input type="radio" name="flooring" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Window and Glass</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="doors" value="Good"> Good</label>
                                    <label><input type="radio" name="doors" value="Fair"> Fair</label>
                                    <label><input type="radio" name="doors" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Wardrobes</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="windows" value="Good"> Good</label>
                                    <label><input type="radio" name="windows" value="Fair"> Fair</label>
                                    <label><input type="radio" name="windows" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Curtain Rods</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="lights" value="Good"> Good</label>
                                    <label><input type="radio" name="lights" value="Fair"> Fair</label>
                                    <label><input type="radio" name="lights" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Lighting Fixtures</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="outlets" value="Good"> Good</label>
                                    <label><input type="radio" name="outlets" value="Fair"> Fair</label>
                                    <label><input type="radio" name="outlets" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>

                              </tbody>
                            </table>
                            <div class="col-md-12 d-flex justify-content-between">
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.previous()">Back</button>
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.next()">Next</button>
                            </div>

                          </form>
                        </div>

                        <div id="stepMoveOut5" class="content">
                          <div class="inpection-section-headers" >Washrooms</div>

                          <form>
                            <table class="inspection-table">
                              <thead>
                                <tr>
                                  <th>Item</th>
                                  <th>Condition at Move-Out</th>
                                  <th>Comments</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Sinks and Taps</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="walls" value="Good"> Good</label>
                                    <label><input type="radio" name="walls" value="Fair"> Fair</label>
                                    <label><input type="radio" name="walls" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea placeholder="Add comments..."></textarea></td>
                                </tr>
                                <tr>
                                  <td>WC/Toilet</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="ceiling" value="Good"> Good</label>
                                    <label><input type="radio" name="ceiling" value="Fair"> Fair</label>
                                    <label><input type="radio" name="ceiling" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Showerhead</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="flooring" value="Good"> Good</label>
                                    <label><input type="radio" name="flooring" value="Fair"> Fair</label>
                                    <label><input type="radio" name="flooring" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Plumbing(Leaks, Drainage)</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="doors" value="Good"> Good</label>
                                    <label><input type="radio" name="doors" value="Fair"> Fair</label>
                                    <label><input type="radio" name="doors" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Bathroom Tiles and Grouting</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="windows" value="Good"> Good</label>
                                    <label><input type="radio" name="windows" value="Fair"> Fair</label>
                                    <label><input type="radio" name="windows" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>

                                <tr>
                                  <td>Lighting Fixtures</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="outlets" value="Good"> Good</label>
                                    <label><input type="radio" name="outlets" value="Fair"> Fair</label>
                                    <label><input type="radio" name="outlets" value="Poor"> Poor</label>
                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>

                              </tbody>
                            </table>
                            <div class="col-md-12 d-flex justify-content-between">
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.previous()">Back</button>
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.next()">Next</button>
                            </div>
                          </form>
                        </div>

                        <div id="stepMoveOut6" class="content">
                          <div class="inpection-section-headers" >Move Out Summary</div>

                          <form>
                            <table class="inspection-table">
                              <thead>
                                <tr>
                                  <th>Item</th>
                                  <th>Decision</th>
                                  <th>Comments</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Damaged beyond normal and repair</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="walls" value="Good"> Yes</label>
                                    <label><input type="radio" name="walls" value="Fair"> No</label>

                                  </td>
                                  <td><textarea placeholder="Add comments..."></textarea></td>
                                </tr>
                                <tr>
                                  <td>Cleaning Required</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="ceiling" value="Good"> Yes</label>
                                    <label><input type="radio" name="ceiling" value="Fair"> No</label>

                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td>Repairs Required</td>
                                  <td class="radio-group">
                                    <label><input type="radio" name="flooring" value="Good"> Yes</label>
                                    <label><input type="radio" name="flooring" value="Fair"> No</label>

                                  </td>
                                  <td><textarea></textarea></td>
                                </tr>
                                <tr>
                                  <td >Estimated Cost Of repairs</td>
                                  <td colspan="2">
                                    <label for=""></label>
                                    <input type="number">


                                  </td>

                                </tr>
                              </tbody>
                            </table>
                            <div class="col-md-12 d-flex justify-content-between">
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.previous()">Back</button>
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper2.next()">Next</button>
                            </div>
                          </form>
                        </div>

                        <div id="stepMoveOut7" class="content">
                          <form>


                            <div class="section">
                              <div class="inpection-section-headers" >Signatures</div>

                              <div class="signatures">
                                <div class="row">
                                    <div class="col-md-4">
                                      <label for="landlord_name">Landlord Name:</label>
                                      <input type="text" id="landlord_name" name="landlord_name">
                                    </div>
                                    <div class="col-md-4">
                                      <label for="landlord_signature">Signature:</label>
                                      <input type="text" id="landlord_signature" name="landlord_signature">
                                    </div>
                                    <div class="col-md-4">
                                      <label for="landlord_date">Date:</label>
                                    <input type="date" id="landlord_date" name="landlord_date">
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="tenant_name">Tenant Name:</label>
                                    <input type="text" id="tenant_name" name="tenant_name">
                                  </div>
                                  <div class="col-md-4">
                                    <label for="tenant_signature">Signature:</label>
                                    <input type="text" id="tenant_signature" name="tenant_signature">
                                  </div>
                                  <div class="col-md-4">
                                    <label for="tenant_date">Date:</label>
                                    <input type="date" id="tenant_date" name="tenant_date">
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-between mt-2">
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper.previous()">Back</button>
                              <button type="button" class="btn btn-primary next-btn" onclick="stepper.nex()">Cancel</button>
                              <button type="submit" class="btn btn-success next-btn">Submit</button>
                            </div>

                          </form>
                        </div>

                      </div>

                    </div>
                  </div>
                </div>
              </div>

             </div>
          <!-- /end row -->
           


            <!-- End row -->
            <!-- Begin Row -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="inspection-details-container bg-white p-2">

                        <div id="filter-pdf-excel-section" class="filter-pdf-excel-section mb-2">

                          <div class="d-flex" style="gap: 10px;">
                            <div class="select-option-container">
                              <div class="custom-select">All Buildings</div>
                              <div class="select-options mt-1">
                                <div class="selected" data-value="all">All Buildings</div>
                                <div data-value="Manucho">Manucho</div>
                                <div data-value="Pink House">Pink House</div>
                                <div data-value="White House">White House</div>
                              </div>
                            </div>
                            <div id="custom-search">
                              <input type="text" id="searchInput" placeholder="Search tenant...">
                            </div>
                          </div>

                          <div>

                          </div>

                          <div class="d-flex">
                                <div id="custom-buttons"></div>
                          </div>

                        </div>

                        <table id="maintenance" class="display summary-table">
                                <thead class="mb-2">
                                <tr>
                                    <th>Date</th>
                                    <th>PROPERTY + UNIT</th>
                                    <th>TYPE</th>
                                    <th>Inspection No</th>                                    
                                    <th>Attached Files</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($inspectionsCount > 0): ?>
                                    <?php foreach ($inspections as $inspection): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($inspection['date']) ?></td>
                                            <td><?= htmlspecialchars($inspection['building_name']) ?></td>
                                            
                                            <td>Bed-sitter</td>
                                            <td><?= htmlspecialchars($inspection['inspection_number']) ?></td>
                                            <td></td>
                                            <td>
                                                <div class="status completed">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    In progress
                                                </div>
                                            </td>
                                            <td id="more">
                                                <button class="btn btn-sm" style="background-color: #193042; color:#fff; margin-right: 2px;" data-toggle="modal" data-target="#assignPlumberModal" title="View"><i class="fas fa-eye"></i></button>
                                                <button class="btn btn-sm" style="background-color: #193042; color:#FFCCCC; margin-right: 2px;" data-toggle="modal" data-target="#plumbingIssueModal" title="Get Full Report about this Repair Work"><i class="fa fa-trash"></i></button>
                                                <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="Get Full Report about this Repair Work"><i class="fa fa-edit"></i></button>
                                                
                                                <!-- File Upload Section -->
                                                <form action="/upload" method="POST" enctype="multipart/form-data">
                                                    <label for="fileUpload" class="attachment-icon">📎</label>
                                                    <input type="file" id="fileUpload" name="fileUpload" class="file-input" style="display:none;" onchange="submitForm()">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" style="text-align:center;">No inspections found.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>

                          </table>
                      </div> 

                  </div>
                              
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
          <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;" >JENGO PAY</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->


                                           <!-- OVERLAYS -->

    <script src="inspections.js"></script>
    <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
  <script>
    // Initialize Stepper
    const stepper = new Stepper(document.querySelector('#stepper'))
    const stepper2 = new Stepper(document.querySelector('#MoveOut'));

  </script>

  <!-- J  A V A S C R I PT -->

                                                <!-- LINKS -->
 <!-- steeper plugin -->
 <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>


<script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous">
</script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous">
</script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous">
</script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="../../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->

<!-- links for dataTaable buttons -->
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
<!--End links for dataTaable buttons -->

<!-- index.js -->
<script src="index.js"></script>
<!-- End index.js -->

<!-- End links -->


                                  <!-- FILE UPLOAD -->

<script>
  function uploadFile() {
      const fileInput = document.getElementById('fileUpload');
      if (fileInput.files.length > 0) {
          // Handle file upload logic here (e.g., send it to the server)
          alert("File Selected: " + fileInput.files[0].name);
      }
  }
</script>


                                  <!-- ADD INSPECTION CONTAINER SCRIPTS-->

<!-- Script to collapse the other section when one is opened -->
<script>
                      function hideOther(idToHide) {
                        const other = document.getElementById(idToHide);
                        if (other && other.classList.contains('show')) {
                          new bootstrap.Collapse(other, { toggle: true }).hide();
                        }
                      }
</script>
<!-- Active effect for the select add-inspection buttons -->
<script>
                      const buttons = document.querySelectorAll('.select-btn');

                      buttons.forEach(btn => {
                        btn.addEventListener('click', () => {
                          // Remove 'active' from all buttons
                          buttons.forEach(b => b.classList.remove('active'));

                          // Add 'active' to clicked button
                          btn.classList.add('active');
                        });
                      });
</script>



                                  <!-- DATE TABLES -->
<script>

                        $(document).ready(function () {
                          const table = $('#maintenance').DataTable({
                            dom: 'Brtip', // ⬅ Changed to include Buttons in DOM
                            order: [], // ⬅ disables automatic ordering by DataTables
                            buttons: [
                              {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                exportOptions: {
                                  columns: ':not(:last-child)' // ⬅ Exclude last column
                                }
                              },                              
                              {
                                extend: 'pdfHtml5',
                                text: 'PDF',
                                exportOptions: {
                                  columns: ':not(:last-child)' // ⬅ Exclude last column
                                },
                                customize: function (doc) {
                                  // Center table
                                  doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                                  // Optional: center-align the entire table
                                  doc.styles.tableHeader.alignment = 'center';
                                  doc.styles.tableBodyEven.alignment = 'center';
                                  doc.styles.tableBodyOdd.alignment = 'center';

                                  const body = doc.content[1].table.body;
                                      for (let i = 1; i < body.length; i++) { // start from 1 to skip header
                                        if (body[i][4]) {
                                          body[i][4].color = 'blue'; // set email column to blue
                                        }
                                      }

                                }
                              },
                                  {
                                extend: 'print',
                                text: 'Print',
                                exportOptions: {
                                  columns: ':not(:last-child)' // ⬅ Exclude last column from print
                                }
                              }
                            ]
                          });

            // Append buttons to your div
            table.buttons().container().appendTo('#custom-buttons');

            // Custom search
            $('#searchInput').on('keyup', function () {
              table.search(this.value).draw();
            });

          });
</script>




<script>

                  // const   next_text = document.getElementById('respond_btn');
                  const   respond_window = document.getElementById('respond');
                  const   close_text_overlay = document.getElementById("closeTextWindow");

                  next_text.addEventListener('click', ()=>{

                    respond_window.style.display= "flex";
                    document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
                    const now = new Date();
                            const formattedTime = now.toLocaleString(); // Format the date and time
                            timestamp.textContent = `Sent on: ${formattedTime}`;


                  });

                  close_text_overlay.addEventListener('click', ()=>{

                    respond_window.style.display= "none";
                    document.querySelector('.app-wrapper').style.opacity = '1';
                    });

</script>




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
  </body>
  <!--end::Body-->
</html>
