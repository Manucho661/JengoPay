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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../../../dist/css/adminlte.css" />
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
      body{
        font-size: 16px;
      }
      .summary-table {
        float: right;
        width: 20px;
        margin-top: 20px;
      }

      .summary-table th {
        width: 50%;
        text-align: left;
      }

      .summary-table td input {
        width: 70%;
        font-size: 0.8rem; /* Adjusting the font size */
      }

        .items-table {
          font-size: 0.9rem;
          margin-top: 20px;
        }

        .items-table th, .items-table td {
          vertical-align: middle !important;
          text-align: center;
        }

        .items-table select,
        .items-table textarea,
        .items-table input {
          width: 100%;
          padding: 4px 6px;
          font-size: 0.9rem;
        }

        .items-table textarea {
          resize: vertical;
        }

        .items-table .delete-btn {
          margin-top: 4px;
        }

        .btn-primary.mt-2 {
          margin-top: 15px;
        }

        /* Optional: Style the table headers */
        .items-table thead {
          background-color: #f8f9fa;
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
        <!-- <div id="sidebar"></div> -->
        <div > <?php include_once '../../includes/sidebar1.php'; ?>  </div> <!-- This is where the sidebar is inserted -->

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
              <div class="col-sm-8">

                        <h3 class="mb-0 contact_section_header">  <i class="fas fa-tools"></i>Expenses</h3>

                        <div class="row Summary mt-6" >
                          <!-- Summary -->
                          <div class="col-md-12 ">

                            <div class="summary-section text-center p-2 row">
                              <div class="col-6 col-md-4 summary-item total">
                                  <i class="fas fa-calculator"></i>
                                   <div class="label">Total Items <b class="value" > 100</b>  </div>
                              </div>

                              <div class="col-6 col-md-4 summary-item total">
                                <i class="fas fa-calculator"></i>
                                 <div class="label">Total Spend <b class="value" >KSH 500</b>  </div>

                            </div>
                            </div>

                          </div>

                        </div>






              </div>

              <div class="col-sm-4">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#" style="color: #00192D;">  <i class="bi bi-house"></i> Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>

            </div>
            <!--end::Row-->
            <!-- begin row -->
             <div class="row mt-2">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                      <a class="btn btn-link add-expense-btn" data-bs-toggle="collapse" href="#addExpense" aria-expanded="false" aria-controls="addExpenseAccordion" style="color: #00192D; font-weight:bold; text-decoration: none;">
                          <span id="toggleIcon">+</span> Click Here to Add An Expense
                      </a>
                  </div>

                  <div class="card-body collapse" id="addExpense">
                    <div class="card">
                      <div class="card-header" style="background: linear-gradient(to right, #00192D, #003D5B, #00788D);">
                          <h6 class="text-white">Enter Expense Details</h6>
                      </div>
                      <div class="card-body">
                          <div class="form-container">
                              <form>
                                  <div class="row g-3">
                                      <!-- No -->
                                      <div class="col-md-3">
                                          <label class="form-label"><b>Expense No</b></label>
                                          <input type="number" class="form-control" placeholder="123">
                                      </div>

                                      <!-- Expense of the Month -->
                                      <div class="col-md-5">
                                          <label class="form-label"><b>Expense for the Month of</b></label>
                                          <select class="form-select">
                                              <option selected disabled>Select Month</option>
                                              <option>January</option>
                                              <option>February</option>
                                              <option>March</option>
                                              <option>April</option>
                                              <option>May</option>
                                              <option>June</option>
                                              <option>July</option>
                                              <option>August</option>
                                              <option>September</option>
                                              <option>October</option>
                                              <option>November</option>
                                              <option>December</option>
                                          </select>
                                      </div>

                                      <!-- Year -->
                                      <div class="col-md-4">
                                          <label class="form-label"><b>Year</b></label>
                                          <input type="number" class="form-control" placeholder="2025">
                                      </div>
                                  </div>

                                  <div class="row g-3 mt-2">
                                      <div class="col-md-8">
                                          <label class="form-label"><b>Entry Date</b></label>
                                          <input type="date" class="form-control">
                                      </div>
                                  </div>

                                  <div class="col-md-4">
                                    <label class="form-label"><b>Supplier</b></label>
                                    <input type="text" class="form-control" placeholder="Supplier">
                                </div>


                                  <!-- Expense Table -->
                                  <div class="row g-3 mt-2">
                                    <h6 class="text-center" style="color:linear-gradient(to right, #00192D, #003D5B, #00788D) ;"> <b>Add the Spend items in the fields below</b> </h6>
                                    <div style="overflow-x: auto;">

                                    <!-- Expense Table -->
                                    <!-- Expense Table -->
                                    <table class="items-table table table-bordered">
                                      <thead>
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
                                            <select name="payment_method" required>
                                              <option value="" disabled selected>Select Option</option>
                                              <option value="credit_card">Rent</option>
                                              <option value="paypal">Water Bill</option>
                                              <option value="bank_transfer">Garbage</option>
                                            </select>
                                          </td>
                                          <td><textarea name="Description" placeholder="Description" rows="1" required></textarea></td>
                                          <td><input type="number" class="form-control quantity" placeholder="1"></td>
                                          <td><input type="number" class="form-control unit-price" placeholder="123"></td>
                                          <td>
                                            <select class="form-select vat-option">
                                              <option value="" disabled selected>Select Option</option>
                                              <option value="inclusive">VAT 16% Inclusive</option>
                                              <option value="exclusive">VAT 16% Exclusive</option>
                                              <option value="zero">Zero Rated</option>
                                              <option value="exempted">Exempted</option>
                                            </select>
                                          </td>
                                          <td>
                                            <input type="text" class="form-control total" placeholder="0" readonly>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
                                              <i class="fa fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>

                                    <!-- Button to Add Row -->
                                    <!-- <button type="button" class="btn btn-primary mt-2" onclick="addRow()">Add Row</button> -->


                                      <!-- <button id="addRow" class="btn btn-primary">Add Row</button> -->

                                       <!-- Summary Table -->

                                                                            </div>
                                </div>


                                  <div class="row g-3 mt-2">
                                      <div class="col-6">
                                        <button type="button" class="add-btn" onclick="addRow()">
                                          <i class="fa fa-plus"></i>
                                          ADD MORE</button>
                                      </div>
                                      <div class="col-6 mt-2 d-flex justify-content-end">
                                          <button type="submit" class="btn btn-custom btn-sm" style="height: fit-content;"> Submit</button>
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
            <!-- /end row -->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!-- Info boxes -->

            <!-- /.row -->
            <!--begin::Row-->
            <div class="row first mb-2 mt-2 rounded-circle">


              <!-- /.col -->
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row">

            </div>
            <!--end::Row-->
           <!--begin::Row-->
           <div class="row">
            <!-- Start col -->
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


<!-- Overlay Cards -->
<!-- units popup -->
<div class="expenses-overlay" id="expensePopup">
  <div class="expenses-content">
    <button class="close-btn" onclick="closeexpensePopup()" aria-label="Close Popup">×</button>
    <h2 class="assign-title">Expense Details</h2>
    <!-- <p>Here you can view the details of the selected unit. You can enable editing by clicking the button below.</p> -->
    <button class="edit-btn" onclick="enableEditing()"><i class="fas fa-edit"></i>Enable Editing</button>
    <form class="wide-form">
      <div class="form-group">
        <div class="row g-3">
          <!-- Expense Number -->
          <div class="col-md-6">
            <label for="unit_number">Expense Number:</label>
            <input type="number" id="unit_number" name="unit_number" placeholder="001" required disabled>
          </div>

          <!-- Size -->
          <div class="col-md-6">
            <label for="date">Entry Date:</label>
            <input type="text" id="size" name="size" placeholder="10/02/2014" required disabled>
          </div>


          <div class="col-md-6">
            <label for="size">Month:</label>
            <input type="text" id="size" name="size" placeholder="January" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Year:</label>
            <input type="text" id="size" name="size" placeholder="2025" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Account:</label>
            <input type="text" id="size" name="size" placeholder="Salaries" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Description:</label>
            <input type="text" id="size" name="size" placeholder="lorem ipsum" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Quantity:</label>
            <input type="text" id="size" name="size" placeholder="1" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Supplier:</label>
            <input type="text" id="size" name="size" placeholder="Quickmart" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Unit Price:</label>
            <input type="text" id="size" name="size" placeholder="1" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Sub-Total:</label>
            <input type="text" id="size" name="size" placeholder="Ksh10,000" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Taxes:</label>
            <input type="text" id="size" name="size" placeholder="VAT 16% Inclusive" required disabled>
          </div>

          <div class="col-md-6">
            <label for="size">Total:</label>
            <input type="text" id="size" name="size" placeholder="Ksh 12,000" required disabled>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- end -->

<!-- End view announcement -->
<!-- end overlay card. -->
<!--begin::Third Party Plugin(OverlayScrollbars)-->

<!-- Overlay scripts -->
 <!-- View announcements script -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


 <script>
  function addRow() {
      const table = document.querySelector(".items-table tbody");
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>
                                            <select name="payment_method" required>
                                              <option value="" disabled selected>Select Option</option>
                                              <option value="credit_card">Rent</option>
                                              <option value="paypal">Water Bill</option>
                                              <option value="bank_transfer">Garbage</option>
                                            </select>
                                          </td>
                                          <td><textarea name="Description" placeholder="Description" rows="1" required></textarea></td>
                                          <td><input type="number" class="form-control quantity" placeholder="1"></td>
                                          <td><input type="number" class="form-control unit-price" placeholder="123"></td>
                                          <td>
                                            <select class="form-select vat-option">
                                              <option value="" disabled selected>Select Option</option>
                                              <option value="inclusive">VAT 16% Inclusive</option>
                                              <option value="exclusive">VAT 16% Exclusive</option>
                                              <option value="zero">Zero Rated</option>
                                              <option value="exempted">Exempted</option>
                                            </select>
                                          </td>
                                          <td>
                                            <input type="text" class="form-control total" placeholder="0" readonly>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
                                              <i class="fa fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                          </td>

      `;
      table.appendChild(newRow);
  }
  function deleteRow(btn) {
      btn.closest("tr").remove();
  }
      function printInvoice() {
          window.print();
      }
      function downloadPDF() {
          const element = document.querySelector(".invoice-container");
          html2pdf().from(element).save("invoice.pdf");
      }
</script>


 <script>
  function enableEditing() {
    const fields = document.querySelectorAll('#unit_number, #size, #floor_number, #description');
    fields.forEach(field => field.disabled = false);
  }

 </script>



 <script>
  // Function to open the complaint popup
  function openexpensePopup() {
    document.getElementById("expensePopup").style.display = "flex";
  }


  // Function to close the complaint popup
  function closeexpensePopup() {
    document.getElementById("expensePopup").style.display = "none";
  }
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    function formatNumber(num) {
      return num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function calculateRow(row) {
      const unitInput = row.querySelector(".unit-price");
      const quantityInput = row.querySelector(".quantity");
      const vatSelect = row.querySelector(".vat-option");
      const totalInput = row.querySelector(".total");

      const unitPrice = parseFloat(unitInput?.value) || 0;
      const quantity = parseFloat(quantityInput?.value) || 0;
      let subtotal = unitPrice * quantity;

      let vatAmount = 0;
      let total = subtotal;
      const vatType = vatSelect?.value;

      if (vatType === "inclusive") {
        subtotal = subtotal / 1.16;
        vatAmount = total - subtotal;
      } else if (vatType === "exclusive") {
        vatAmount = subtotal * 0.16;
        total += vatAmount;
      } else if (vatType === "zero" || vatType === "exempted") {
        vatAmount = 0;
        total = subtotal;
      }

      totalInput.value = formatNumber(total);
      return { subtotal, vatAmount, total, vatType };
    }

    function updateTotalAmount() {
      let subtotalSum = 0, taxSum = 0, grandTotal = 0;
      let vat16Used = false, vat0Used = false, exemptedUsed = false;

      document.querySelectorAll(".items-table tbody tr").forEach(row => {
        if (row.querySelector(".unit-price")) {
          const { subtotal, vatAmount, total, vatType } = calculateRow(row);
          subtotalSum += subtotal;
          taxSum += vatAmount;
          grandTotal += total;

          if (vatType === "inclusive" || vatType === "exclusive") vat16Used = true;
          else if (vatType === "zero") vat0Used = true;
          else if (vatType === "exempted") exemptedUsed = true;
        }
      });

      createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, vat16Used, vat0Used, exemptedUsed });
    }

    function createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, vat16Used, vat0Used, exemptedUsed }) {
      let summaryTable = document.querySelector(".summary-table");

      if (!summaryTable) {
        summaryTable = document.createElement("table");
        summaryTable.className = "summary-table table table-bordered";
        summaryTable.style = "width: 20%; float: right; font-size: 0.8rem; margin-top: 10px;";
        summaryTable.innerHTML = `<tbody></tbody>`;
        document.querySelector(".items-table").after(summaryTable);
      }

      const tbody = summaryTable.querySelector("tbody");
      tbody.innerHTML = `
        <tr>
          <th style="width: 50%; padding: 5px;">Sub-total</th>
          <td><input type="text" class="form-control" value="${formatNumber(subtotalSum)}" readonly></td>
        </tr>
        ${vat16Used ? `
        <tr>
          <th>VAT 16%</th>
          <td><input type="text" class="form-control" value="${formatNumber(taxSum)}" readonly></td>
        </tr>` : ''}
        ${vat0Used ? `
        <tr>
          <th>VAT 0%</th>
          <td><input type="text" class="form-control" value="0.00" readonly></td>
        </tr>` : ''}
        ${exemptedUsed ? `
        <tr>
          <th>Exempted</th>
          <td><input type="text" class="form-control" value="0.00" readonly></td>
        </tr>` : ''}
        <tr>
          <th>Total</th>
          <td><input type="text" class="form-control" value="${formatNumber(grandTotal)}" readonly></td>
        </tr>
      `;
    }

    function attachEvents(row) {
      ["input", "change"].forEach(evt => {
        row.querySelectorAll(".unit-price, .quantity, .vat-option").forEach(el =>
          el.addEventListener(evt, updateTotalAmount)
        );
      });
    }

    window.addRow = function () {
      const table = document.querySelector(".items-table tbody");
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>
          <select name="payment_method" required>
            <option value="" disabled selected>Select Option</option>
            <option value="credit_card">Rent</option>
            <option value="paypal">Water Bill</option>
            <option value="bank_transfer">Garbage</option>
          </select>
        </td>
        <td><textarea name="Description" placeholder="Description" rows="1" required></textarea></td>
        <td><input type="number" class="form-control quantity" placeholder="1"></td>
        <td><input type="number" class="form-control unit-price" placeholder="123"></td>
        <td>
          <select class="form-select vat-option">
            <option value="" disabled selected>Select Option</option>
            <option value="inclusive">VAT 16% Inclusive</option>
            <option value="exclusive">VAT 16% Exclusive</option>
            <option value="zero">Zero Rated</option>
            <option value="exempted">Exempted</option>
          </select>
        </td>
        <td>
          <input type="text" class="form-control total" placeholder="0" readonly>
          <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
            <i class="fa fa-trash" style="font-size: 12px;"></i>
          </button>
        </td>
      `;
      table.appendChild(newRow);
      attachEvents(newRow);
    };

    window.deleteRow = function (btn) {
      btn.closest("tr").remove();
      updateTotalAmount();
    };

    document.querySelectorAll(".items-table tbody tr").forEach(attachEvents);
    updateTotalAmount();
  });
  </script>


 <script>
  const   more_announcement = document.getElementById('more_announcement_btn');
  const   view_announcement = document.getElementById('view_announcement');
  const   close_overlay = document.getElementById("close-overlay-btn");

  more_announcement.addEventListener('click', ()=>{

     view_announcement.style.display= "flex";
     document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
     const now = new Date();
            const formattedTime = now.toLocaleString(); // Format the date and time
            timestamp.textContent = `Sent on: ${formattedTime}`;
  });

     close_overlay.addEventListener('click', ()=>{

     view_announcement.style.display= "none";
     document.querySelector('.app-wrapper').style.opacity = '1';


     });
 </script>

 <!-- End view announcement script -->

 <script>
  // Function to toggle the visibility of the overlay
  function toggleOverlay() {
    var overlay = document.getElementById('overlay');
    // If overlay is hidden, show it
    if (overlay.style.display === "none" || overlay.style.display === "") {
      overlay.style.display = "flex";
    } else {
      overlay.style.display = "none";
    }
  }
</script>


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



<!-- more options -->
<script>

  // JavaScript to handle hover and hide functionality
  const  more= document.getElementById("more");
  const more_icon = document.getElementById("more_icon");
  const more_options = document.getElementById("more_options");

  // Show panel when hovering over the accordion
  more_icon.addEventListener("mouseenter", () => {
    more_options.style.display = "block";
  });

  // Hide panel when moving out of both accordion and panel
   more.addEventListener("mouseleave", () => {
    more_options.style.display = "none";
});


</script>
    <!-- Begin script for datatable -->
    <script>

            document.addEventListener("DOMContentLoaded", function() {
            let table = $('#maintenanc').DataTable({
                lengthChange: false, // Removes "Show [X] entries"
                dom: 't<"bottom"p>', // Removes default search bar & keeps only table + pagination
            });

            // Link custom search box to DataTables search
            $('#searchInput').on('keyup', function () {
                table.search(this.value).draw();
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



<!--
  Add expense scripts.

-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const addRowButton = document.getElementById("addRow");
        const tableBody = document.querySelector("tbody");

        function createNewRow() {
            const newRow = document.createElement("tr");
            newRow.innerHTML = `
               <td>
                                                        <select class="form-select">
                                                            <option selected disabled>Repair and Mantainance</option>
                                                            <option>Salaries</option>
                                                            <option>Legal Work</option>
                                                            <option>Internet</option>
                                                        </select>
                                                    </td>
                                                    <td><textarea class="form-control" rows="1" placeholder="Enter details"></textarea></td>
                                                    <td><input type="number" class="form-control unit-price" placeholder="123"></td>
                                                    <td><input type="number" class="form-control quantity" placeholder="1"></td>
                                                    <td><input type="number" class="form-control subtotal" placeholder="0" readonly></td>
                                                    <td>
                                                      <select class="form-select vat-option">
                                                        <option value="" disabled selected>Select Option</option>
                                                        <option value="inclusive">VAT 16% Inclusive</option>
                                                        <option value="exclusive">VAT 16% Exclusive</option>
                                                        <option value="zero">Zero Rated</option>
                                                        <option value="exempt">Exempted</option>
                                                      </select>
                                                    </td>
                                                    <td><input type="number" class="form-control vat-amount" placeholder="0" readonly></td>
                                                    <td><input type="number" class="form-control total" placeholder="0" readonly></td>
                                                    <td><button type="button" class="btn btn-danger remove-row">Delete</button></td>
            `;

            newRow.querySelector(".remove-row").addEventListener("click", function () {
                newRow.remove();
                checkIfTableEmpty();
            });

            return newRow;
        }

        function checkIfTableEmpty() {
            if (tableBody.children.length === 0) {
                tableBody.appendChild(createNewRow()); // Add default row if empty
            }
        }

        addRowButton.addEventListener("click", function () {
            tableBody.appendChild(createNewRow());
        });

        // Initialize the first row in case user removes all
        checkIfTableEmpty();
    });
</script> -->



<script>
  $(document).ready(function() {
      $('#repaireExpenses').DataTable({
          "lengthChange": false,
          "dom": 'Bfrtip',
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      });
  });
</script>
    <!-- End script for data_table -->

<!--Begin sidebar script -->
<!-- <script>
  fetch('../../bars/sidebar.html')  // Fetch the file
      .then(response => response.text()) // Convert it to text
      .then(data => {
          document.getElementById('sidebar').innerHTML = data; // Insert it
      })
      .catch(error => console.error('Error loading the file:', error)); // Handle errors
</script> -->
<!-- end sidebar script -->


<!--Begin sidebar script -->
<script>
  fetch('../chatbot/index.html')  // Fetch the file
      .then(response => response.text()) // Convert it to text
      .then(data => {
          document.getElementById('index').innerHTML = data; // Insert it
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
    <script src="../../../../dist/js/adminlte.js"></script>

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
