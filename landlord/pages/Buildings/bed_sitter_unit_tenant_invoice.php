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
          <?php
            include_once 'processes/encrypt_decrypt_function.php';
            //Fetch Tenant Information from the Database
            if(isset($_GET['invoice']) && !empty($_GET['invoice'])) {
              $id = $_GET['invoice'];
              $id = encryptor('decrypt', $id);
              try {
                //1. Fetch Tenant Details from the Tenant Database
                $tenant = $pdo->prepare("SELECT * FROM bedsitter_units WHERE id = ? ");
                $tenant->execute([$id]);
                $tenant_info = $tenant->fetch(PDO::FETCH_ASSOC);
                if(!$tenant_info) {
                  echo 
                  "<script>
                      Swal.fire({
                        icon: 'warning',
                        title: 'No Data',
                        text: 'No Active Tenant Data Found in the Database.'
                      });
                  </script>";
                }
                $monthly_rent = $tenant_info['monthly_rent'] ?? 0;
                $final_bill = $tenant_info['final_bill'];
              } catch (Exception) {

              }
            }
          ?>
          <div class="card shadow">
            <div class="card-header" style="background-color: #00192D; color: #fff;">
              <b>Create Invoice for <?= htmlspecialchars($tenant_info['tfirst_name'].' '.($tenant_info['tmiddle_name']).' '.($tenant_info['tlast_name']));?></b>
            </div>
            <form id="invoiceForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" enctype="multipart/form-data" autocomplete="off">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Invoice Number:</label>
                      <input type="text" id="invoiceNumber" name="invoice_no" required class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Invoice To:</label>
                      <input type="text" name="receiver" required class="form-control" value="<?= $tenant_info['tfirst_name'].' '.$tenant_info['tmiddle_name'].' '.$tenant_info['tlast_name'];?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Main Contact</label>
                        <input class="form-control" value="<?= $tenant_info['tmain_contact'];?>" readonly name="main_contact">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Alternative Contact</label>
                      <input class="form-control" value="<?= $tenant_info['talt_contact'];?>" readonly name="alt_contact">
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Email</label>
                        <input class="form-control" value="<?= htmlspecialchars($tenant_info['temail']); ?>" readonly name="email">
                    </div>
                    <div class="col-md-4">
                        <label>Invoice Date</label>
                        <input type="date" id="invoiceDate" name="invoice_date" required class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Date Due</label>
                        <input type="date" id="dateDue" name="due_date" required class="form-control" readonly>
                    </div>
                </div>

                <hr>
                <input type="hidden" name="paymentStatus" value="Pending">
                <input type="hidden" name="monthly_rent" id="monthlyRent" value="<?= htmlspecialchars($monthly_rent); ?>">
                <input type="hidden" name="final_bill" id="finalBill" value="<?= htmlspecialchars($final_bill); ?>">
                <table id="invoiceTable" class="table table-bordered table-striped shadow">
                  <thead class="table-dark">
                    <tr>
                      <th>Paid For</th>
                      <th>Description</th>
                      <th>Unit Price</th>
                      <th>Quantity</th>
                      <th>Taxation</th>
                      <th>Tax Amount</th>
                      <th>Total Price</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="invoiceBody"></tbody>
                    <tfoot>
                        <tr><td colspan="5" class="text-end">Subtotal:</td><td id="subtotal">0.00</td></tr>
                        <tr><td colspan="5" class="text-end">Total Tax:</td><td id="totalTax">0.00</td></tr>
                        <tr><td colspan="5" class="text-end"><strong>Final Total:</strong></td><td id="finalTotal">0.00</td></tr>
                    </tfoot>
                </table> <hr>
                <button type="button" onclick="addRow()" class="btn btn-sm shadow text-white" style="background-color:#00192D;">+ Add Row</button><hr>
                <div class="row mb-3 shadow">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Attachment</label>
                            <input type="file" name="attachment" accept=".pdf,.jpg,.png,.docx" class="form-control">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="invoice_items" id="invoiceItems">
                <input type="hidden" name="subtotalValue" id="subtotalValue">
                <input type="hidden" name="totalTaxValue" id="totalTaxValue">
                <input type="hidden" name="finalTotalValue" id="finalTotalValue">
              </div>
              <div class="card-footer text-right">
                <button type="submit" onclick="return prepareInvoiceData()" name="submit" class="btn btn-sm shadow" style="border:1px solid #00192D; color:#00192D;"><i class="fa fa-check"></i> Submit</button>
              </div>
            </form>
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
     <?php include_once 'includes/required_scripts.php';?>
     <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


if (isset($_POST['submit'])) {
    try {
        // -------------------------------
        // GET FORM DATA
        // -------------------------------
        $invoice_no     = $_POST['invoice_no'] ?? null;
        $receiver       = $_POST['receiver'] ?? null;
        $main_contact   = $_POST['main_contact'] ?? null;
        $alt_contact    = $_POST['alt_contact'] ?? null;
        $email          = $_POST['email'] ?? null;
        $invoice_date   = $_POST['invoice_date'] ?? null;
        $due_date       = $_POST['due_date'] ?? null;
        $paymentStatus  = $_POST['paymentStatus'] ?? 'Pending';
        $notes          = $_POST['notes'] ?? null;
        $subtotal       = $_POST['subtotalValue'] ?? 0;
        $total_tax      = $_POST['totalTaxValue'] ?? 0;
        $final_total    = $_POST['finalTotalValue'] ?? 0;

        if (!$invoice_no || !$invoice_date) {
            throw new Exception("Invoice Number and Date are required.");
        }

        // -------------------------------
        // DUPLICATE CHECK BY MONTH
        // -------------------------------
        $invoiceMonth = date("Y-m", strtotime($invoice_date));
        $stmt = $pdo->prepare("
            SELECT id FROM bedsitter_units_invoice
            WHERE DATE_FORMAT(invoice_date, '%Y-%m') = ?
            LIMIT 1
        ");
        $stmt->execute([$invoiceMonth]);

        if ($stmt->rowCount() > 0) {
            throw new Exception("An invoice has already been submitted this month.");
        }

        // -------------------------------
        // ATTACHMENT UPLOAD
        // -------------------------------
        $attachment_name = null;
        if (!empty($_FILES['attachment']['name'])) {
            $uploadDir = "uploads/invoices/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $attachment_name = time() . "_" . basename($_FILES['attachment']['name']);
            $targetPath = $uploadDir . $attachment_name;

            if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
                throw new Exception("Attachment upload failed.");
            }
        }

        // -------------------------------
        // INSERT INTO bedsitter_units_invoice
        // -------------------------------
        $stmtInvoice = $pdo->prepare("
            INSERT INTO bedsitter_units_invoice
            (invoice_no, receiver, main_contact, alt_contact, email, invoice_date, due_date,
             payment_status, notes, attachment, subtotal, total_tax, final_total)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtInvoice->execute([
            $invoice_no, $receiver, $main_contact, $alt_contact, $email,
            $invoice_date, $due_date, $paymentStatus, $notes, $attachment_name,
            $subtotal, $total_tax, $final_total
        ]);

        $invoice_id = $pdo->lastInsertId();

        // -------------------------------
        // INSERT INVOICE ITEMS
        // -------------------------------
        $items = json_decode($_POST['invoice_items'], true);
        if ($items === null || !is_array($items)) {
            throw new Exception("Invalid invoice items data.");
        }

        $stmtItem = $pdo->prepare("
            INSERT INTO bedsitter_units_invoice_items
            (invoice_id, item_name, description, unit_price, quantity, taxation, tax_amount, total_price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($items as $item) {
            $stmtItem->execute([
                $invoice_id,
                $item['item_name'],
                $item['description'],
                $item['unit_price'],
                $item['quantity'],
                $item['tax_type'],
                $item['tax_amount'],
                $item['total_price']
            ]);
        }

        // -------------------------------
        // SUCCESS
        // -------------------------------
        echo "<script>
            Swal.fire('Success', 'Invoice saved successfully!', 'success')
                .then(() => { window.location.href='invoice_list.php'; });
        </script>";

    } catch (Exception $e) {
        // -------------------------------
        // ERROR HANDLING
        // -------------------------------
        $error = $e->getMessage();
        echo "<script>Swal.fire('Error', '{$error}', 'error');</script>";
    }
}
?>

</body>
<!--end::Body-->

</html>