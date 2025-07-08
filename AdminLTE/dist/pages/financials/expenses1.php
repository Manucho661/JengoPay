<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
     <meta name="title" content="AdminLTE | Dashboard v2" />
    <meta name="author" content="ColorlibHQ" />
    <title>Document</title>
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

   <link rel="stylesheet" href="expenses.css">
     <!-- scripts for data_table -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
 
</head>
<body>
    <div class="app-wrapper">
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
        <div > <?php include_once '../includes/sidebar1.php'; ?>  </div> <!-- This is where the sidebar is inserted -->
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
        <main class="app-main">
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
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row mt-2 mb-2">
                        <div class="col-md-3">
                            <div class="personal-info-card shadow-sm bg-white p-3 rounded">
                                <div class="d-flex align-items-center gap-2">
                                <i class="fa fa-envelope icon"></i>
                                <div>
                                    <div class="personal-info-card-label">ITEMS</div>
                                    <b id="email" class="personal-info-card-value email">300</b>
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
                                <div class="personal-info-card-label">This Month</div>
                                <b id="phone" class="personal-info-card-value phone">Ksh 100,000</b>
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
                                <div class="personal-info-card-label">Pending Approval</div>
                                <b id="id_no" class="personal-info-card-value" >45862394</b>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="card shadow">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(to right, #00192D, #003D5B);">
                                <a class="text-white fw-bold text-decoration-none" data-bs-toggle="collapse" href="#addExpense" role="button" aria-expanded="false" aria-controls="addExpense" onclick="toggleIcon(this)">
                                <span id="toggleIcon">➕</span> Click Here to Add an Expense
                                </a>
                            </div>

                            <div class="collapse" id="addExpense">
                                <div class="card-body border-top border-2">
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-header text-white" style="background: linear-gradient(to right, #00192D, #00788D);">
                                    <h6 class="mb-0">Enter Expense Details</h6>
                                    </div>
                                    <div class="card-body">
                                    <form>
                                        <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Expense No</label>
                                            <input type="number" class="form-control" placeholder="123" />
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold">Expense for the Month of</label>
                                            <select class="form-select">
                                            <option selected disabled>Select Month</option>
                                            <option>January</option><option>February</option><option>March</option>
                                            <option>April</option><option>May</option><option>June</option>
                                            <option>July</option><option>August</option><option>September</option>
                                            <option>October</option><option>November</option><option>December</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Year</label>
                                            <input type="number" class="form-control" placeholder="2025" />
                                        </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold">Entry Date</label>
                                            <input type="date" class="form-control" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Supplier</label>
                                            <input type="text" class="form-control" placeholder="Supplier" />
                                        </div>
                                        </div>

                                        <!-- Spend Items Table -->
                                        <div class="mt-4">
                                        <h6 class="text-center fw-bold text-secondary">Add the Spend items below</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                <th>Item (Service)</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>Taxes</th>
                                                <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                <td>
                                                    <select class="form-select">
                                                    <option disabled selected>Select Option</option>
                                                    <option>Rent</option>
                                                    <option>Water Bill</option>
                                                    <option>Garbage</option>
                                                    </select>
                                                </td>
                                                <td><textarea class="form-control" rows="1" placeholder="Description"></textarea></td>
                                                <td><input type="number" class="form-control" placeholder="1" /></td>
                                                <td><input type="number" class="form-control" placeholder="123" /></td>
                                                <td>
                                                    <select class="form-select">
                                                    <option selected disabled>Select Option</option>
                                                    <option>VAT 16% Inclusive</option>
                                                    <option>VAT 16% Exclusive</option>
                                                    <option>Zero Rated</option>
                                                    <option>Exempted</option>
                                                    </select>
                                                </td>
                                                <td class="d-flex align-items-center">
                                                    <input type="text" class="form-control me-2" placeholder="0" readonly />
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteRow(this)" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                                </tr>
                                            </tbody>
                                            </table>
                                        </div>
                                        </div>

                                        <div class="row mt-3">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-outline-primary" onclick="addRow()">
                                            ➕ Add More
                                            </button>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <button type="submit" class="btn btn-success">✅ Submit</button>
                                        </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card Content">
                                <div class="card-header">
                                    <b>All Operational Expenses</b>
                                </div>
                                <div class="card-body" style="overflow-x: auto;">
                                    <table class="table-striped" id="repaireExpenses" style="width: 100%; min-width: 600px;">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Supplier</th>
                                                <th>Expense No</th>
                                                <th>Totals</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>20 Nov 2013</td>
                                                <td>Quickmart</td>
                                                <td>001</td>
                                                <td>KSH 20,000</td>
                                                <td>
                                                <button onclick="openexpensePopup()" class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="view"><i class="fa fa-file"></i></button>
                                                <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Remove"><i class="fa fa-trash"></i>
                                                </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>25 Oct 2013</td>
                                                <td>Naivas</td>
                                                <td>002</td>
                                                <td>KSH 28,000</td>
                                                <td>
                                                <button onclick="openexpensePopup()" class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="view"><i class="fa fa-file"></i></button>
                                                <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Assign this Task to a Plumbing Service Providersingle_units.php"><i class="fa fa-trash"></i>
                                                </button>                                    </td>
                                            </tr>
                                            <tr>
                                            <td>20 Nov 2013</td>
                                            <td>Quickmart</td>
                                            <td>001</td>
                                            <td>KSH 20,000</td>
                                            <td>
                                                <button onclick="openexpensePopup()" class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="view"><i class="fa fa-file"></i></button>
                                                <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Remove"><i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>20 Nov 2013</td>
                                            <td>Quickmart</td>
                                            <td>001</td>
                                            <td>KSH 20,000</td>
                                            <td>
                                            <button onclick="openexpensePopup()" class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="view"><i class="fa fa-file"></i></button>
                                            <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Remove"><i class="fa fa-trash"></i>
                                            </button>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td>20 Nov 2013</td>
                                        <td>Quickmart</td>
                                        <td>003</td>
                                        <td>KSH 20,000</td>
                                        <td>
                                            <button onclick="openexpensePopup()" class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="view"><i class="fa fa-file"></i></button>
                                            <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Remove"><i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>20 Nov 2013</td>
                                        <td>Quickmart</td>
                                        <td>004</td>
                                        <td>KSH 20,000</td>
                                        <td>
                                        <button onclick="openexpensePopup()" class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="view"><i class="fa fa-file"></i></button>
                                        <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Remove"><i class="fa fa-trash"></i>
                                        </button>
                                        </td>
                                    </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </main>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <!-- <script src="../../../../dist/js/adminlte.js"></script>  -->
      <script src="../../../dist/js/adminlte.js"></script>

    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
        <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
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
        function toggleIcon(anchor) {
        console.log('yoyo');
        const icon = anchor.querySelector('#toggleIcon');
        const isExpanded = anchor.getAttribute('aria-expanded') === 'true';
        icon.textContent = isExpanded ? '➕' : '✖';
        }

        function deleteRow(button) {
        const row = button.closest('tr');
        row.remove();
        }

        function addRow() {
        // You'll need to implement dynamic row cloning if required.
        alert('Add row functionality goes here');
        }
    </script>
</body>
</html>