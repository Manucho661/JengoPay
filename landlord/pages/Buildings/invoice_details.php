<?php
 require_once "../db/connect.php";
//  include_once 'includes/lower_right_popup_form.php';
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <?php if (isset($successMessage)) echo "<div class='alert alert-success'>$successMessage</div>"; ?>
    <?php if (isset($errorMessage)) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE | Dashboard v2</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE | Dashboard v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta
        name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta
        name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <!-- LINKS -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
        <link rel="stylesheet" href="../../assets/main.css" />
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->

    <!-- jquery link-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="expenses.css">
    <!-- scripts for data_table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Pdf pluggin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!--Tailwind CSS  -->
    <style>
        .app-wrapper {
            background-color: rgba(128, 128, 128, 0.1);
        }

        .modal-backdrop.show {
            opacity: 0.4 !important;
            /* Adjust the value as needed */
        }

        .diagonal-paid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(0, 128, 0, 0.2);
            /* Light green with transparency */
            color: green;
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid green;
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }

        .diagonal-unpaid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(255, 0, 0, 0.2);
            /* Red with transparency for "UNPAID" */
            color: #ff4d4d;
            /* Softer red text color */
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid red;
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }

        .diagonal-partially-paid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(255, 165, 0, 0.2);
            /* Amber background with opacity */
            color: #ff9900;
            /* Amber or gold text */
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid #ff9900;
            /* Amber border */
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-dark" style="">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
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
            <div> <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?> </div> <!-- This is where the sidebar is inserted -->
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main mt-4">
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
        <div class="container-fluid">
          <div class="card shadow">
            <div class="card-header" style="background-color: #00192D; color: #fff;">
              <b>Invoice Details for</b>
            </div>
            <div class="card-body">
              <?php
                include_once 'processes/encrypt_decrypt_function.php';
                if(isset($_GET['details']) && !empty($_GET['details'])) {
                  $id = $_GET['details'];
                  $id = encryptor('decrypt', $id);

                  try {
                    // --- Fetch Invoice ---
                    $sql = "SELECT * FROM single_units_invoice WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':id' => $id]);
                    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$invoice) {
                      die
                      ("<script>
                        Swal.fire('Not Found', 'Invoice not found.', 'error');
                      </script>");
                    }

                    // --- Fetch Items ---
                    $sql_items = "SELECT * FROM single_units_invoice_items WHERE invoice_id = :id ORDER BY id ASC";
                    $stmt_items = $pdo->prepare($sql_items);
                    $stmt_items->execute([':id' => $id]);
                    $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
                  } catch (Exception $e) {

                  }                  
                }
              ?>
              <div class="container mt-4">
                <div class="invoice-box">
                  <!-- HEADER -->
                  <div class="invoice-header d-flex justify-content-between">
                      <h3>Invoice #<?= htmlspecialchars($invoice['invoice_no']) ?></h3>
                      <strong>Date: <?= htmlspecialchars($invoice['invoice_date']) ?></strong>
                  </div>
                  <!-- RECEIVER + DATES -->
                  <div class="row mb-4">
                      <div class="col-md-6">
                          <h5>Bill To:</h5>
                          <p>
                              <strong><?= htmlspecialchars($invoice['receiver']) ?></strong><br>
                              <?= htmlspecialchars($invoice['email'] ?? '') ?><br>
                              <?= htmlspecialchars($invoice['main_contact'] ?? '') ?>
                          </p>
                      </div>
                      <div class="col-md-6 text-end">
                          <h5>Invoice Details</h5>
                          <p>
                              <strong>Invoice Date:</strong> <?= htmlspecialchars($invoice['invoice_date']) ?><br>
                              <strong>Due Date:</strong> <?= htmlspecialchars($invoice['due_date']) ?><br>
                              <strong>Status:</strong> 
                              <?php
                                  if($invoice['payment_status'] == 'Paid') {
                                ?>
                                  <button class="btn btn-xs shadow" style="background-color:#28A745; color: #fff;">
                                    <i class="fa fa-check"></i> <?= htmlspecialchars($invoice["payment_status"]) ?>
                                  </button>
                                <?php
                                  } else {
                                ?>
                                  <button class="btn btn-xs shadow" style="background-color:#cc0001; color: #fff;">
                                    <i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($invoice["payment_status"]) ?>
                                  </button>
                                <?php
                                }
                            ?>
                          </p>
                      </div>
                  </div>
                  <!-- TABLE -->
                  <table class="table table-bordered shadow">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Item</th>
                              <th>Description</th>
                              <th>Unit Price</th>
                              <th>Qty</th>
                              <th>Tax</th>
                              <th>Total</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php 
                          $n = 1;
                          foreach ($items as $item): ?>
                          <tr>
                              <td><?= $n++ ?></td>
                              <td><?= htmlspecialchars($item['item_name']) ?></td>
                              <td><?= htmlspecialchars($item['description'] ?? '') ?></td>
                              <td><?= number_format($item['unit_price'], 2) ?></td>
                              <td><?= htmlspecialchars($item['quantity']) ?></td>
                              <td><?= htmlspecialchars($item['tax_type']) ?> (<?= number_format($item['tax_amount'], 2) ?>)</td>
                              <td><strong><?= number_format($item['total_price'], 2) ?></strong></td>
                          </tr>
                          <?php endforeach; ?>
                      </tbody>
                  </table>
                  <!-- TOTALS -->
                  <div class="row mt-4">
                      <div class="col-md-6"></div>
                      <div class="col-md-6">
                          <table class="table">
                              <tr>
                                  <th>Subtotal</th>
                                  <td><?= number_format($invoice['subtotal'], 2) ?></td>
                              </tr>
                              <tr>
                                  <th>Total Tax</th>
                                  <td><?= number_format($invoice['total_tax'], 2) ?></td>
                              </tr>
                              <tr>
                                  <th>Final Total</th>
                                  <td><strong><?= number_format($invoice['final_total'], 2) ?></strong></td>
                              </tr>
                          </table>
                      </div>
                  </div>

                      <?php if(!empty($invoice['notes'])): ?>
                          <div class="mt-3">
                              <h5>Additional Notes:</h5>
                              <p><?= nl2br(htmlspecialchars($invoice['notes'])) ?></p>
                          </div>
                      <?php endif; ?>

                  </div>
              </div>
            </div>
          </div>
        </div>
      </section>

                                                
            <!-- /.content -->

            <!-- Help Pop Up Form -->
        
        </div>
        <!-- /.content-wrapper -->
        <!-- Footer -->
    </div>
    <!-- ./wrapper -->
    <!-- Required Scripts -->
    <?php include_once '../includes/required_scripts.php';?>
    <!-- Meter Readings JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 
            </div>
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
                <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;">JENGO PAY</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>

    </div>
    <!--end::App Wrapper-->

    <!-- plugin for pdf -->


    <!-- Main Js File -->
    <script src="../../js/adminlte.js"></script>
    <script src="js/main.js"></script>
    <!-- html2pdf depends on html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script type="module" src="./js/main.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!-- pdf download plugin -->


    <!-- Scripts -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!--end::Body-->

</html>