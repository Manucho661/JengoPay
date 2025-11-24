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


    <style>
    /*========================== Add Items in the Invoice ====================================*/
.offcanvas-right {
  position: fixed;
  top: 0;
  right: 0;
  width: 400px; /* Adjust width as needed */
  height: 100%;
  background-color: #fff;
  box-shadow: -5px 0 15px rgba(0,0,0,0.1);
  transform: translateX(100%);
  transition: transform 0.3s ease-in-out;
  z-index: 1050; /* Above Bootstrap modals */
  padding: 20px;
  overflow-y: auto; /* Enable scrolling for long forms */
}
.offcanvas-right.show {
  transform: translateX(0);
}
.offcanvas-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  z-index: 1040;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
  pointer-events: none; /* Allows clicks through when not visible */
}
.offcanvas-backdrop.show {
  opacity: 1;
  pointer-events: auto; /* Blocks clicks when visible */
}
#closeAddItem{
  background-color: #cc0001;
  color: #fff;
  border: 0;
  border-radius: 3px;
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
              
<div class="card shadow">
            <div class="card-header" style="background-color: #00192D; color: #fff;">
              <b>Create Invoice for <?= htmlspecialchars($tenant_info['tfirst_name'].' '.($tenant_info['tmiddle_name']).' '.($tenant_info['tlast_name']));?></b>
            </div>
            <form id="invoiceForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" enctype="multipart/form-data" autocomplete="off">
                <div class="card-body">
                    <!-- Tenant Info Section -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Invoice Number:</label>
                                <input type="text" id="invoiceNumber" name="invoice_no" required class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Invoice To:</label>
                                <input type="text" name="receiver" required class="form-control" value="<?= htmlspecialchars($tenant_info['tfirst_name'].' '.$tenant_info['tmiddle_name'].' '.$tenant_info['tlast_name']);?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Main Contact</label>
                                <input class="form-control" value="<?= htmlspecialchars($tenant_info['tmain_contact']);?>" readonly name="main_contact">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Alternative Contact</label>
                                <input class="form-control" value="<?= htmlspecialchars($tenant_info['talt_contact']);?>" readonly name="alt_contact">
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

                    <!-- Invoice Items Table -->
                    <h5 class="mb-3">Invoice Items</h5>
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
                            <tr><td colspan="6" class="text-end">Subtotal:</td><td id="subtotal" class="text-end">0.00</td><td></td></tr>
                            <tr><td colspan="6" class="text-end">Total Tax:</td><td id="totalTax" class="text-end">0.00</td><td></td></tr>
                            <tr><td colspan="6" class="text-end"><strong>Final Total:</strong></td><td id="finalTotal" class="text-end">0.00</td><td></td></tr>
                        </tfoot>
                    </table>

                    <hr>
                    <!-- Changed addRow() to open the drawer -->
                    <button type="button" onclick="openAddItemDrawer()" class="btn btn-sm shadow text-white" style="background-color:#00192D;">
                        <i class="fa fa-plus"></i> Add Item
                    </button>
                    <hr>

                    <!-- Notes & Attachment Section -->
                    <div class="row mb-3 shadow p-3 rounded">
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
                    <button type="submit" onclick="return prepareInvoiceData()" name="submit" class="btn btn-sm shadow" style="border:1px solid #00192D; color:#00192D;"><i class="fa fa-check"></i> Submit Invoice</button>
                </div>
            </form>
          </div>
        </div>
        <!-- Offcanvas (Side Panel) for Add Item -->
        <div class="offcanvas-right shadow" id="addItemDrawer">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Add New Invoice Item</h5>
            <button type="button" class="btn-close shadow" aria-label="Close" onclick="closeAddItemDrawer()" id="closeAddItem"><i class="fa fa-close"></i></button>
        </div>
            <form id="addItemForm">
                <div class="mb-3">
                    <label for="drawerItemName" class="form-label">Paid For <span class="text-danger">*</span></label>
                    <select class="form-control" id="drawerItemName" onchange="checkDrawerOthersInput(this)" required>
                        <!-- Options will be populated by JS -->
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="drawerOtherInput" placeholder="Please specify">
                </div>
                <div class="mb-3">
                    <label for="drawerDescription" class="form-label">Description</label>
                    <input type="text" class="form-control" id="drawerDescription">
                </div>
                <div class="mb-3">
                    <label for="drawerUnitPrice" class="form-label">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="drawerUnitPrice" step="0.01" value="0" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="drawerQuantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="drawerQuantity" value="1" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="drawerTaxType" class="form-label">Taxation</label>
                    <select class="form-control" id="drawerTaxType">
                        <option value="VAT Inclusive">VAT 16% Inclusive</option>
                        <option value="VAT Exclusive">VAT 16% Exclusive</option>
                        <option value="Zero Rated">Zero Rated</option>
                        <option value="Exempted">Exempted</option>
                    </select>
                </div> <hr>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-sm shadow text-white" style="background-color:#00192D;" style="background-color:#00192D;"><i class="fa fa-plus"></i> Add Item</button>
                    <button type="button" class="btn btn-sm text-white shadow" onclick="closeAddItemDrawer()" style="background-color:#cc0001;"><i class="fa fa-close"></i> Cancel</button>
                </div>
            </form>
        </div>
        <div class="offcanvas-backdrop" id="addItemDrawerBackdrop" onclick="closeAddItemDrawer()"></div>
                                                
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