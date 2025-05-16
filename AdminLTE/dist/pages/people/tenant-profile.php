
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
        <div id="sidebar"></div> <!-- This is where the sidebar is inserted -->
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

                                                     <!-- HEADER SECTION -->
            <!-- First Row -->
            <div class="row  " >
              <div class="col-sm-6">
                <div class="d-flex">
                  <h3 class="section_header tenantName"><i class="fas fa-user-tie icon" style="color:#FFC107"></i> <span id="first_name"> </span> <span id="middle_name"> </span> </h3>
                  <h6 style="color:#00192D; ">  <span id="residence" style="color:#767676;">Ebenezer/</span> <span id="unit" style=" margin-right:2px;">Unit</span> </h6>
                  <h6  class="active" id="status"></h6>
                </div>
                <div>
                  
                </div>

                <!-- <h3 >   </h3> -->


                    <div class="row mt-2  ">

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
          <button type="submit" class="btn btn-custom-submit" style= "background-color:#00192D; color:#FFC107;">
            <i class="fas fa-check-circle me-1"></i> Confirm Shift
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


            <!-- End first Row -->


                                                      <!-- SUMMARY SECTION -->
            <!-- start Row -->

                                                   <!--PERSONAL INFO  -->

              <!-- start row -->
                <div class="row mt-2 personal-info">

                      <h6 class="mb-0 contact_section_header mb-2"> </i> Personal Info</h6>
                  <div class="col-md-12">
                      <div class="row">

                              <div class="col-md-3">
                                <div class="personal-item d-flex justify-content-between bg-white">
                                <!-- <i class="fas fa-calculator"></i> -->
                                  <div class="labal-value p-2" style="display: flex; gap: 5px;   align-items: center;">
                                    <div class="label"> <i class="fa fa-envelope personal-info-icon "></i>
                                      <span class="personal-info item-name email" > Email,</span> </div>
                                    <div class="value" ><b id="email"> <span></span> </b></div>
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-3">
                                <div class="personal-item d-flex justify-content-between bg-white">
                                  <!-- <i class="fas fa-calculator"></i> -->
                                    <div class="category-number p-2" style="display: flex; gap: 5px;   align-items: center;">
                                      <div class="category"><i class="fas fa-phone icon personal-info-icon "></i> <span class="personal-info item-name" >Phone</span>  </div>
                                      <div class="phone"><b id="phone"></b></div>
                                    </div>
                                </div>

                              </div>

                              <div class="col-md-3">
                                <div class="personal-item d-flex justify-content-between bg-white">
                                <!-- <i class="fas fa-calculator"></i> -->
                                  <div class="category-number p-2" style="display: flex; gap: 5px;   align-items: center;">
                                    <div class="category"> <i class="fas fa-id-card personal-info-icon "></i> <span class="personal-info item-name">ID NO,</span></div>
                                    <div class="number" ><b id="id_no">45862394</b></div>
                                    <button class="btn view id rounded"> view </button>
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-3">
                                <div class="personal-item-edit d-flex justify-content-between">
                                <!-- <i class="fas fa-calculator"></i> -->
                               <button class="btn edit-btn personal-info rounded" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal"><i class="fas fa-edit icon"></i> Edit</button>
                              </div>
                      </div>
                    </div>

                    <div class="col-md-12 mt-2">
                      <div class="row">
                        

                            <div class="col-md-3">

                               <div class="personal-item d-flex justify-content-between bg-white">
                                <!-- <i class="fas fa-calculator"></i> -->
                                  <div class="category-number p-2" style="display: flex; gap: 5px;   align-items: center;">
                                    <div class="category"><i class="fas fa-briefcase personal-info-icon"></i> <span class="personal-info item-name" > Income Source,</span> </div>
                                    <div class="number" ><b id="income_source"></b></div>
                                  </div>
                                </div>

                            </div>

                            <div class="col-md-3">

                              <div class="personal-item d-flex justify-content-between bg-white">
                                    <!-- <i class="fas fa-calculator"></i> -->
                                      <div class="category-number p-2" style="display: flex; gap: 5px;   align-items: center;">
                                        <div class="category"><i class="fas fa-globe icon personal-info-icon "></i> <span class="personal-info item-name" >Employer,</span>  </div>
                                        <div class="number"><b id="work_place"></b></div>
                                      </div>
                                  </div>
                              </div>

                          <div class="col-md-3">
                            <div class="personal-item d-flex justify-content-between bg-white">
                            <!-- <i class="fas fa-calculator"></i> -->
                              <div class="category-number p-2" style="display: flex; gap: 5px;   align-items: center;">
                                <div class="category"> <i class="fas fa-id-card personal-info-icon "></i> <span class="personal-info item-name">Job Title,</span></div>
                                <div class="number"><b id="job_title"></b></div>
                              </div>
                            </div>
                          </div>


                      </div>
                  </div>
                </div>
              </div>

            <!-- Edit Personal Info Modal -->
              <div class="modal fade" id="editPersonalInfoModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content shadow-lg border-0 rounded-3">
                    <form id="editPersonalInfoForm" autocomplete="off">
                      <div class="modal-header   rounded-top" style= "background-color: #00192D; color:#FFC107;">
                        <h5 class="modal-title" id="editModalLabel">
                          <i class="fas fa-user-edit me-2"></i> Edit Personal Information
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <div class="modal-body px-4 py-3">
                        <!-- Email -->
                        <div class="form-floating mb-3">
                          <input type="email" class="form-control" id="editEmail" placeholder="Email" required>
                          <label for="editEmail"><i class="fas fa-envelope me-1"></i> Email Address</label>
                        </div>

                        <!-- Phone -->
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="editPhone" placeholder="Phone Number" required>
                          <label for="editPhone"><i class="fas fa-phone me-1"></i> Phone Number</label>
                        </div>

                        <!-- ID Number -->
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="editIDNo" placeholder="ID Number" required>
                          <label for="editIDNo"><i class="fas fa-id-card me-1"></i> National ID Number</label>
                        </div>

                        <!-- Income Source -->
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="editIncomeSource" placeholder="Income Source">
                          <label for="editIncomeSource"><i class="fas fa-briefcase me-1"></i> Income Source</label>
                        </div>

                        <!-- Employer -->
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="editEmployer" placeholder="Employer">
                          <label for="editEmployer"><i class="fas fa-building me-1"></i> Employer</label>
                        </div>

                        <!-- Job Title -->
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="editJobTitle" placeholder="Job Title">
                          <label for="editJobTitle"><i class="fas fa-user-tie me-1"></i> Job Title</label>
                        </div>
                      </div>

                      <div class="modal-footer bg-light d-flex justify-content-between">
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Make sure details are accurate</small>
                        <button type="submit" class="btn btn-success" style="background-color:#00192D; color:#FFC107;">
                          <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

             <!-- end row -->

             <!-- start row -->

              <div class="row mt-4 mb-3">
                <h6 class=" contact_section_header details mb-2 mt-2"> </i> Rent Details</h6>
                <div class="col-md-12 ">
                  <div class="row  mb-4">
                        <span class="summary title">Summary</span>
                      <div class="col-md-3">
                        <!-- <div class="summary-item d-flex justify-content-between bg-white"> -->
                        <div class="icon-category-number Deposit" style="display: flex; gap: 10px;   align-items: center; ">
                          <div class="icon"> <i class="fas fa-coins"></i></div>
                          <div>
                            <div class="rent-type-date-paid">
                              <div class="rent-type">Deposit</div>
                              <div class="date-paid">
                                <div><i>2023-12</i></div>
                              </div>
                            </div>

                            <div class="number"> $&nbsp;11000</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="icon-category-number paid-rent" style="display: flex; gap: 10px;   align-items: center; ">
                          <div class="icon"> <i class="fas fa-coins"></i></div>
                          <div>
                            <div class="rent-type-duration-stayed">
                              <div class="rent-type">Total Paid Rent</div>
                              <div class="duration-stayed"> 14 Mons</div>
                            </div>

                            <div class="number"> $&nbsp;12000</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 outstanding-rent">

                        <div class="icon-category-number outstanding-rent" style="display: flex; gap: 10px;   align-items: center; ">
                          <div class="icon"> <i class="fas fa-coins"></i></div>
                          <div>
                            <div class="rent-type">Outstanding Balance</div>
                            <div class="number"> $&nbsp;1000</div>
                          </div>
                        </div>

                      </div>
                      <div class="col-md-3">
                        <div class="icon-category-number" style="display: flex; gap: 10px;   align-items: center; ">
                          <div class="icon"> <i class="fas fa-coins"></i></div>
                          <div>
                            <div class="rent-type refundable">Total Refundable</div>
                            <div class="number"> $&nbsp;4000</div>
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
                        <div class="change-btn d-flex justify-content-end"><button class="btn edit rounded">Edit</button></div>
                        </div>

                      </div>
                  </div>
                </div>
              </div>

                 <!-- end row -->
                 <!-- start row -->
                  <div class="row mt-3">
                       <h6 class="mb-0 contact_section_header"> </i> Pets</h6>
                       <div class="col-md-9 pets">
                        <table id="pets-table">
                          <thead>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Weight</th>
                            <th>License Number</th>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>

                       </div>

                       <div class="col-md-3">
                        <button class="btn edit-btn personal-info"><i class="fas fa-plus icon"></i> ADD </button>
                       </div>

                  </div>

                  <div class="row">
                    <h6 class="mb-0 contact_section_header"> </i> Files</h6>
                    <div class="col-md-6">
                      <table id="files-table">
                        <thead>
                          <th>File Name</th>

                          <th>Actions</th>

                        </thead>

                        <tbody>
                          
                          
                        </tbody>

                      </table>
                    </div>
                    <div class="col-md-3">
                      <button class="btn edit-btn personal-info"><i class="fas fa-plus icon"></i> Add </button>
                    </div>


                  </div>


                </div>


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
