<?php
// header('Content-Type: application/json');
include '../db/connect.php';

if (!isset($_GET['id'])) {
  echo json_encode(['success' => false, 'message' => 'Tenant ID not provided']);
  exit;
}
else{
  
}

$user_id = intval($_GET['id']);

try {
 

} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Query failed', 'error' => $e->getMessage()]);
}
?>


<style>
  
.modal-backdrop.show {
        opacity: 0.3 !important; /* Adjust the value as needed */
      }

</style>

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
    <link rel="stylesheet" href="tenant-profile.css">


    <!-- scripts for data_table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>

</style>

  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper" style="background-color:rgba(128,128,128, 0.1);" >
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
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->

        <!--begin::Sidebar Wrapper-->
        <div ><?php include_once '../includes/sidebar1.php'; ?></div> <!-- This is where the sidebar is inserted -->
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

              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div  class="app-content">
          <div class="container-fluid">

            <!-- First Row -->
            <div class="row  " >
                <div class="col-sm-6">
                  <div class="d-flex align-items-center gap-3">
                    <h3 class="section_header tenantName">
                      <span class="me-2" aria-hidden="true">👤</span>
                      <span id="first_name" aria-label="First name"></span>
                      <span id="middle_name" aria-label="Middle name"></span>
                    </h3>
                    <p class="mb-0" style="color:#00192D;">
                      <span id="residence" class="text-muted">Ebenezer/</span>
                      <span id="unit" style="margin-right:2px;">Unit</span>
                    </p>
                    <p id="status" class="mb-0 fw-semibold text-success"></p>
                  </div>
                  <div>
                  </div>
                </div>
                <!-- Button to open modal -->
                  <!-- Shift & Vacate Buttons -->
                  <div class="col-sm-6">
                    <div class="headerButtons d-flex gap-2">
                      <div class="shift">
                        <button class="btn shift-tenant rounded" data-bs-toggle="modal" data-bs-target="#shiftTenantModal">
                          Shift Joseph
                        </button>
                      </div>

                      <div class="vacate">
                        <button class="btn vacate-tenant rounded">Vacate Joseph</button>
                      </div>
                    </div>
                  </div>

                    <!-- Shift Tenant Modal -->
                    <div class="modal fade" id="shiftTenantModal" tabindex="-1" aria-labelledby="shiftTenantModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content shadow">
                          <form id="shiftTenantForm">
                            <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
                              <h5 class="modal-title" id="shiftTenantModalLabel">
                                <i class="fas fa-exchange-alt me-2"></i> Shift Tenant - Joseph
                              </h5>
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" ></button>
                            </div>

                            <div class="modal-body px-4">
                              <!-- Tenant Name (Pre-filled) -->
                              <div class="mb-3">
                                <label class="form-label">Tenant Name</label>
                                <input type="text" class="form-control" name="tenantName" value="Joseph" readonly>
                              </div>

                              <!-- Select Building -->
                              <div class="mb-3">
                                <label for="buildingSelect" class="form-label">Select Building</label>
                                <select id="buildingSelect" class="form-select" required>
                                  <option value="">-- Select Building --</option>
                                  <option value="Manucho">Manucho</option>
                                  <option value="White House">White House</option>
                                  <option value="Pink House">Pink House</option>
                                  <option value="Silver">Silver</option>
                                </select>
                              </div>

                              <!-- Select Unit -->
                              <div class="mb-3">
                                <label for="unitSelect" class="form-label">Select Unit</label>
                                <select id="unitSelect" class="form-select" required>
                                  <option value="">-- Select Unit --</option>
                                  <option value="Unit 101">Unit 101</option>
                                  <option value="Unit 102">Unit 102</option>
                                  <option value="Unit 201">Unit 201</option>
                                </select>
                              </div>
                            </div>

                            <div class="modal-footer px-4">
                              <button type="submit" class="btn btn-primary" style="background-color: #00192D; color: #FFC107;">Shift</button>
                            </div>
                          </form>
                        </div>
                      </div>
                </div>
                <!-- End first Row -->
                    <!--PERSONAL INFO  -->
                    <!-- start row -->
                    <div class="row g-3">
                      <h6 class="mb-0 contact_section_header mb-2"> </i> Personal Info</h6>
                      <!-- Email Card -->
                      <div class="col-md-3">
                        <div class="personal-info-card shadow-sm bg-white p-3 rounded">
                          <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-envelope icon"></i>
                            <div>
                              <div class="personal-info-card-label">Email</div>
                              <b id="email" class="personal-info-card-value email"></b>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Phone Card -->
                      <div class="col-md-3">
                        <div class="personal-info-card shadow-sm bg-white p-3 rounded">
                          <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-phone icon"></i>
                            <div>
                              <div class="personal-info-card-label">Phone</div>
                              <b id="phone" class="personal-info-card-value phone"></b>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- ID Card -->
                      <div class="col-md-3">
                        <div class="personal-info-card shadow-sm bg-white p-3 rounded">
                          <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-id-card icon"></i>
                            <div>
                              <div class="personal-info-card-label">ID NO</div>
                              <b id="id_no" class="personal-info-card-value" >45862394</b>
                            </div>
                            <button class="btn btn-sm btn-outline-primary ms-auto">View</button>
                          </div>
                        </div>
                      </div>

                      <!-- Edit Button Card -->
                      <div class="col-md-3">
                        <div class="personal-info-card shadow-sm bg-white p-3 rounded d-flex align-items-center justify-content-center">
                          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal">
                            <i class="fas fa-edit me-1 icon"></i> Edit
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="row g-3 align-items-stretch">
                    <h6 class="contact_section_header details">Income Info</h6>
                      <!-- Income Type -->
                      <div class="col-md-3">
                        <div class="income-info-card shadow-sm bg-white p-3 rounded h-100">
                          <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-briefcase icon"></i>
                            <div>
                              <div class="income-info-card-label text-muted small">Income Type</div>
                              <b id="income_source" class="info-card-value"></b>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Employer -->
                      <div class="col-md-3">
                        <div class="income-info-card shadow-sm bg-white p-3 rounded h-100">
                          <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-globe icon"></i>
                            <div>
                              <div class="income-info-card-label text-muted small">Employer</div>
                              <b id="work_place" class="info-card-value"></b>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Job Title -->
                      <div class="col-md-3">
                        <div class="income-info-card shadow-sm bg-white p-3 rounded h-100">
                          <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-id-card icon"></i>
                            <div>
                              <div class="income-info-card-label text-muted small">Job Title</div>
                              <b id="job_title" class="info-card-value"></b>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Edit Button -->
                      <div class="col-md-3">
                        <div class="income-info-card shadow-sm bg-white p-3 rounded h-100 d-flex align-items-center justify-content-center">
                          <button class="btn btn-sm edit-btn income-info rounded" data-bs-toggle="modal" data-bs-target="#editIncomeInfoModal">
                            <i class="fas fa-edit icon"></i> Edit
                          </button>
                        </div>
                      </div>
                    </div>
                    <!-- Edit Income Info Modal -->
                    <div class="modal fade" id="editIncomeInfoModal" tabindex="-1" aria-labelledby="editIncomeInfoLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-m">
                        <div class="modal-content shadow-lg rounded-4 border-0">
                          <form id="editIncomeInfoForm" class="edit-income-info-modal" autocomplete="off" onsubmit="submitEditIncomeInfo (event)">
                            <!--your form inputs...-->
                            <input type="hidden" id="tenant_id" name="tenant_id" value="<?=htmlspecialchars($_GET['tenant_id'] ?? '') ?>"/>
                            

                            <input type="hidden" id="user_id" name="user_id" value="<?=htmlspecialchars($_GET['id'] ?? '') ?>">

                          
                            <!-- Modal Header -->
                            <div class="modal-header py-3 px-4" style="background-color: #00192D; color: #FFC107;">
                              <h5 class="modal-title" id="editIncomeInfoLabel">
                                <i class="fas fa-edit me-2"></i> Edit Income Information
                              </h5>
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body px-4 py-4 bg-light">

                              <!-- Income Source -->
                              <div class="form-floating mb-4">
                                <input type="text" class="form-control shadow-sm" id="editIncomeSource" placeholder="Income Source" 
                                      value="" name="income_source" required>
                                <label for="editIncomeSource"><i class="fas fa-briefcase me-1 text-muted"></i> Income Source</label>
                              </div>

                              <!-- Employer -->
                              <div class="form-floating mb-4">
                                <input type="text" class="form-control shadow-sm" id="editEmployer" placeholder="Employer" 
                                      value="" name="employer" required>
                                <label for="editEmployer"><i class="fas fa-building me-1 text-muted"></i> Employer</label>
                              </div>

                              <!-- Job Title -->
                              <div class="form-floating mb-4">
                                <input type="text" class="form-control shadow-sm" id="editJobTitle" placeholder="Job Title" 
                                      value="" name="job_title" required>
                                <label for="editJobTitle"><i class="fas fa-user-tie me-1 text-muted"></i> Job Title</label>
                              </div>
                            </div>

                            <!-- Modal Footer -->

                             <div class="modal-footer bg-light d-flex justify-content-between">
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Make sure details are accurate</small>

                              <button type="submit" class="btn btn-sm " style="background-color: #00192D; color: #FFC107;">
                                <i class="fas fa-save me-1"></i> Save Changes
                              </button>
                              
                            </div>

                          </form>
                        </div>
                      </div>
                    </div>




             <!-- start row -->
              <div class="row mt-4 mb-3">
                <h6 class=" contact_section_header details mb-2 mt-2"> </i> Rent Details</h6>
                <div class="col-md-12 ">
                 <div class="row mb-4 g-3">
                  <div class="col-12">
                    <span class="rent-summary-title">Summary</span>
                  </div>

                  <!-- Deposit -->
                  <div class="col-md-3">
                    <div class="rent-summary-card shadow-sm bg-white p-3 rounded">
                      <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center gap-2">
                          <i class="fas fa-coins fs-4 icon"></i>
                          <div>
                            <div class="detail-card-label">Deposit</div>
                            <div class="number fw-bold mt-1">$&nbsp;11,000</div>
                          </div>
                        </div>
                        <div class="small detail-card-date"><i>2023-12</i></div>
                      </div>
                    </div>
                  </div>

                  <!-- Total Paid Rent -->
                  <div class="col-md-3">
                    <div class="rent-summary-card shadow-sm bg-white p-3 rounded">
                      <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center gap-2">
                          <i class="fas fa-coins fs-4 icon"></i>
                          <div>
                            <div class="detail-card-label">Total Paid Rent</div>
                            <div class="number fw-bold mt-1">$&nbsp;12,000</div>
                          </div>
                        </div>
                        <div class="small detail-card-months">14 Mons</div>
                      </div>
                    </div>
                  </div>

                  <!-- Outstanding Balance -->
                  <div class="col-md-3">
                    <div class="rent-summary-card shadow-sm bg-white p-3 rounded">
                      <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-coins fs-4 icon"></i>
                        <div>
                          <div class="detail-card-label">Outstanding Balance</div>
                          <div class="number fw-bold mt-1">$&nbsp;1,000</div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Total Refundable -->
                  <div class="col-md-3">
                    <div class="rent-summary-card shadow-sm bg-white p-3 rounded">
                      <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-coins icon fs-4"></i>
                        <div>
                          <div class="detail-card-label">Total Refundable</div>
                          <div class="number fw-bold mt-1">$&nbsp;4,000</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                   <span class="details title mt-3">Details</span>
                  <div class="row">

                      <div class="col-md-9 " >

                        <div class="bg-white " style="box-shadow:0 4px 10px rgba(0, 0, 0, 0.1) ; border-radius: 5px;">
                          <div class="row p-2">
                            <div class="col-md-8" >
                              <select id="categoryFilter" class="categoryFilter">
                                <option value="">-- Select Year--</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                              </select>
                            </div>
                            <div class="col-md-4 d-flex justify-content-end" style="align-items: center; ">
                              <button class="pdf" ><i class="fas fa-file-pdf" style="color: red;"></i></button>
                              <button class="excel"><i class="fas fa-file-excel" style="color: green;"></i></button>
                            </div>
                          </div>

                          <div class="row p-2">
                            <div class="col-md-12 rent-table-details" >
                              <table>
                                <thead>
                                  <tr>
                                    <th>Month</th>
                                    <th>Rent Due</th>
                                    <th>PAID</th>
                                    <th>PENALTY</th>
                                    <th>ARREAS</th>
                                    <th>OPTIONS</th>
                                  </tr>
                                </thead>
                                <tbody>



                                    <tr >

                                      <td class="month">JANUARY
                                      <!-- Rating Section (stars) -->

                                      </td>
                                      <td class="rent">$ 50,000</td>
                                      <td class="rent paid">
                                        <div class="amount date d-flex ">
                                        <div class="amount">$ 50,000 </div>
                                        <div class="date"> 2025-04-31 </div>
                                        </div>


                                      </td>
                                      <td>
                                        <div class="pen amount d-flex">

                                          <div class="amount">$1000</div>
                                        </div>

                                      </td>
                                      <td class="rent arrears"> $ 51,000</td>
                                      <td>

                                      <button class="btn reciept"> <i class="fas fa-receipt"></i> Reciept </button>


                                      </td>
                                  </tr>
                                  <tr>

                                    <td class="month">FEBRUARY
                                      <!-- Rating Section (stars) -->

                                      </td>
                                      <td class="rent">$ 50,000</td>
                                      <td class="rent paid">
                                        <div class="amount date d-flex ">
                                        <div class="amount">$ 50,000 </div>
                                        <div class="date"> 31-4-2023 </div>
                                        </div>


                                      </td>
                                      <td>
                                        <div class="pen amount d-flex">

                                          <div class="amount">$0</div>
                                        </div>

                                      </td>
                                      <td class="rent arrears"> $ 0</td>
                                      <td>

                                      <button class="btn reciept"> <i class="fas fa-receipt"></i> Reciept </button>


                                      </td>

                                  </tr>
                                </tbody>
                              </table>
                        </div>

                          </div>

                        </div>




                      </div>

                      <div class="col-md-3" >
                        <div class="penalty-rate p-2 " style="background-color:white; box-shadow:  0px 4px 10px rgba(0, 0, 0, 0.15); border-radius: 5px; ">
                          <div class="label">Penalty Rate</div>
                        <div class="penalt_desc d-flex">
                          <div class="pen-rate">10%</div>
                          <div class="pen-desc">of the total rent</div>
                        </div>
                        <div class="change-btn d-flex justify-content-end">
                         <button class="btn edit rounded" data-bs-toggle="modal" data-bs-target="#editPenaltyModal">Edit</button>

                        </div>
                        </div>

                      </div>
                  </div>
                </div>
              </div>


    <!-- Edit Penalty Modal -->
<div class="modal fade" id="editPenaltyModal" tabindex="-1" aria-labelledby="editPenaltyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <form id="editPenaltyForm">
        <!-- Header -->
        <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
          <h5 class="modal-title" id="editPenaltyModalLabel">
            <i class="fas fa-pen-alt me-2"></i> Edit Penalty Rates
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body px-4">
          <div class="mb-3">
            <label for="penaltyRate" class="form-label">Penalty Rate (%)</label>
            <input type="number" class="form-control" id="penaltyRate" name="penaltyRate" min="0" max="100" step="1" placeholder="Enter penalty rate" required>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          
          <button type="button" class="btn btn-sm " style="background-color: white; color: #00192D;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm " style="background-color: #00192D; color: #FFC107;">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>



                 <!-- end row -->
                
                 <!-- start row -->
<div class="row mt-3 align-items-center">
  <div class="col-md-9 flex align-items-center">
    <h6 class="mb-0 contact_section_header me-3">Pets</h6>
    <table id="pets-table" class="mb-0">
      <thead>
        <tr>
          <th>Type</th>
          <th>Weight</th>
          <th>License Number</th>
        </tr>
      </thead>
      <tbody>
        <!-- Pet rows will go here -->
      </tbody>
    </table>
  </div>

  <div class="col-md-3">
    <div class="add-pet-card shadow-sm bg-white px-2 py-2 rounded d-flex align-items-center justify-content-center">
      <button class="btn btn-sm rounded pet-modal"
              data-bs-toggle="modal"
              data-bs-target="#addPetModal"
              style="background-color: #00192D; color: #FFC107; padding: 4px 12px;">
        <i class="fas fa-plus me-1"></i> Add
      </button>
    </div>
  </div>
</div>


                  <!-- Add Pet Modal -->
<div class="modal fade" id="addPetModal" tabindex="-1" aria-labelledby="addPetModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow rounded">
      <form id="addPetForm">
             <input type="hidden" id="tenantIdaddPetForm" name="tenant_id" value="">

        <!-- Modal Header -->
        <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
          <h5 class="modal-title" id="addPetModalLabel"><i class="fas fa-paw me-2"></i> Add Pet</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body px-4 py-3">
        

          <!-- Pet Type -->
          <div class="mb-3">
            <label for="petType" class="form-label">Pet Type</label>
            <select class="form-select" id="petType" name="type" required>
              <option value="" selected disabled>Select type</option>
              <option value="Dog">Dog</option>
              <option value="Cat">Cat</option>
              <option value="Bird">Bird</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <!-- Pet Weight -->
          <div class="mb-3">
            <label for="petWeight" class="form-label">Weight (kg)</label>
            <input type="number" class="form-control" id="petWeight" name="weight" step="0.1" min="0" required placeholder="Enter pet weight">
          </div>

          <!-- License Number -->
          <div class="mb-3">
            <label for="licenseNumber" class="form-label">License Number</label>
            <input type="text" class="form-control" id="licenseNumber" name="license" required placeholder="Enter license number">
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: white; color: #00192D;">Cancel</button>
          <button type="submit" class="btn" style="background-color: #00192D; color: #FFC107;">Add Pet</button>
        </div>
      </form>


    </div>
  </div>
</div>


<div class="row mt-3 align-items-start">
  <!-- Left Side: Heading + Table -->
  <div class="col-md-6">
    <h6 class="mb-2 contact_section_header">Files</h6>
    <table id="files-table" class="table mb-0">
      <thead>
        <tr>
          <th>File Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- File rows will go here -->
      </tbody>
    </table>
  </div>

  <!-- Right Side: Add Button aligned with header row -->
  <div class="col-md-3 d-flex align-items-end" style="padding-top: 38px;">
    <div class="add-file-card shadow-sm bg-white px-2 py-2 rounded d-flex align-items-center justify-content-center" style="width: 300px;">
      <button class="btn btn-sm rounded File-modal" 
              data-bs-toggle="modal" 
              data-bs-target="#addFileModal" 
              style="background-color: #00192D; color: #FFC107; padding: 4px 12px;">
        <i class="fas fa-plus me-1"></i> Add
      </button>
    </div>
  </div>
</div>






                  

<!-- Add File Modal -->
<div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">

      <!-- Modal Header -->
      <div class="modal-header" style="background-color:#00192D; color:#FFC107;">
        <h5 class="modal-title" id="addFileModalLabel"><i class="fas fa-file-upload me-2"></i> Add File</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <form id="addFileForm" enctype="multipart/form-data">
     <input type="hidden" id="tenantIdFile" name="tenant_id" value="">


        <div class="modal-body px-4">
          <!-- File Name -->
          <div class="mb-3">
            <label for="fileName" class="form-label">File Name</label>
            <input type="text" class="form-control" id="fileName" name="file_name" placeholder="Enter file name" required>
          </div>

          <!-- File Upload -->
          <div class="mb-3">
            <label for="fileUpload" class="form-label">Choose File</label>
            <input type="file" class="form-control" id="fileUpload" name="file_upload" required>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: white; color: #00192D;">Cancel</button>
          <button class="btn  add-file-btn" data-tenant-id="39011877" data-bs-toggle="modal" data-bs-target="#addFileModal" style="background-color: #00192D; color: #FFC107;">Upload</button>
        </div>
      </form>

      <!-- Add File Modal -->
<div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
  <!-- (modal content stays the same) -->
</div>

<!-- Uploaded Files List (place this after modal) -->
<div class="mt-4">
 
  <ul id="fileList" class="mt-3" style="list-style-type: none; padding-left: 0;">
    <!-- Newly uploaded files will appear here -->
  </ul>
</div>

    </div>
  </div>
</div>



        <!-- OVERLAYS -->
        <!-- Edit Personal Info Modal -->
              <div class="modal fade" id="editPersonalInfoModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content shadow-lg border-0 rounded-3">

                  <form id="editPersonalInfoForm" class="edit-personal-info-modal" autocomplete="off" onsubmit="submitEditPersonalInfoModal(event)">
                     <!-- your form inputs... -->
                    <input type="hidden" id="user_id" name="user_id" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">


                      <div class="modal-header   rounded-top" style= "background-color: #00192D; color:#FFC107;">
                        <h5 class="modal-title" id="editModalLabel">
                          <i class="fas fa-user-edit me-2"></i> Edit Personal Information
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <div class="modal-body px-4 py-3">

                      <!-- Email -->
                        <div class="form-floating mb-3">
                          <input type="email" class="form-control" id="editEmail" placeholder="Email"
                                value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                          <label for="editEmail"><i class="fas fa-envelope me-1"></i> Email Address</label>
                        </div>

                        <!-- Phone -->
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="editPhone" placeholder="Phone Number"
                                value="<?= htmlspecialchars($data['phone_number'] ?? '') ?>" required>
                          <label for="editPhone"><i class="fas fa-phone me-1"></i> Phone Number</label>
                        </div>

<!-- ID Number -->
<div class="form-floating mb-3">
  <input type="text" class="form-control" id="editIDNo" placeholder="ID Number"
         value="<?= htmlspecialchars($data['id_no'] ?? '') ?>" required>
  <label for="editIDNo"><i class="fas fa-id-card me-1"></i> National ID Number</label>
</div>

                      <div class="modal-footer bg-light d-flex justify-content-between">
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Make sure details are accurate</small>
                        <button type="submit" class="btn btn-changes" style="background-color:#00192D; color:#FFC107;">
                          <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Shift Tenant Modal -->
              <div class="modal fade" id="shiftTenantModal" tabindex="-1" aria-labelledby="shiftTenantModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content shadow">
                    <form id="shiftTenantForm">
                      <!-- Custom Modal Header -->
                      <div class="modal-header "style="background-color:#00192D; color:#FFC107;">
                        <h5 class="modal-title" id="shiftTenantModalLabel">
                          <i class="fas fa-exchange-alt me-2"></i> Shift Tenant - Joseph
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <!-- Modal Body -->
                      <div class="modal-body px-4">
                        <!-- Tenant name -->
                        <div class="mb-3">
                          <label class="form-label">Tenant Name</label>
                          <input type="text" class="form-control" value="Joseph" readonly>
                        </div>

                        <!-- Building list -->
                        <div class="mb-3">
                          <label for="buildingSelect" class="form-label">Select Building</label>
                          <select id="buildingSelect" class="form-select" required>
                            <option value="">-- Select Building --</option>
                            <option value="Building A">Building A</option>
                            <option value="Building B">Building B</option>
                            <option value="Building C">Building C</option>
                          </select>
                        </div>

                        <!-- Unit list -->
                        <div class="mb-3">
                          <label for="unitSelect" class="form-label">Select Unit</label>
                          <select id="unitSelect" class="form-select" required>
                            <option value="">-- Select Unit --</option>
                            <option value="Unit 101">Unit 101</option>
                            <option value="Unit 102">Unit 102</option>
                            <option value="Unit 201">Unit 201</option>
                          </select>
                        </div>
                      </div>

                      <!-- Custom Modal Footer -->
                      <div class="modal-footer custom-footer">
                        <button type="submit" class="btn btn-shift" style= "background-color:#00192D; color:#FFC107;">
                          <i class="fas fa-check-circle me-1"></i> Confirm Shift
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>




        <?php if (isset($_GET['id'])): ?>
          <script>
            const user_id = <?= json_encode($_GET['id']) ?>;
            console.log("UsersID:", user_id);
          </script>
        <?php endif; ?>



        <script src="tenant-profile.js"></script>
        <script>
          fetch('../bars/sidebar.html')  // Fetch the file
              .then(response => response.text()) // Convert it to text
              .then(data => {
                  document.getElementById('sidebar').innerHTML = data; // Insert it
              })
              .catch(error => console.error('Error loading the file:', error)); // Handle errors
        </script>

        <!-- Begin script for datatable -->
        <script>
          $(document).ready(function() {
          $('#rent').DataTable({
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

        $(document).ready(function() {
          $('#agreements').DataTable({
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

        <!-- End script for data_table -->


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


<!--  -->



  </body>
  <!--end::Body-->
</html>
