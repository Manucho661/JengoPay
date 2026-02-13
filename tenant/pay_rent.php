<!DOCTYPE html>
<html lang="en">
    <?php include_once 'includes/head.php';?>

    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <!-- Navbar -->
            <?php include_once 'includes/nav_bar.php';?>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <?php include_once 'includes/side_menus.php';?>
            <!-- Main Sidebar Container -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <?php include_once 'includes/dashboard_bradcrumbs.php';?>
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="card shadow">
                            <div class="card-header">
                                <b>Pay Rent</b>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Invoice To</label>
                                                        <input class="form-control" name="invoice_to" id="invoice_to" readonly value="Paul Pashan">
                                                    </div>
                                                </div>
                                            </div> <hr>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Invoice No.</label>
                                                        <input class="form-control" name="invoice_no" id="invoice_no" value="<?php echo rand(0, 1000000);?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Invoice To</label>
                                                        <input class="form-control" name="invoice_date" id="invoice_date" readonly value="<?php echo date('d, M Y');?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Invoice Status</label>
                                                        <input class="form-control" name="invoice_status" id="invoice_status" value="Pending" readonly>
                                                    </div>
                                                </div>
                                            </div><hr>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-2"><b>Bill</b></div>
                                                    <div class="col-md-1"><b>Qty</b></div>
                                                    <div class="col-md-2"><b>Amount</b></div>
                                                    <div class="col-md-2"><b>Sub Total</b></div>
                                                    <div class="col-md-2"><b>Tax</b></div>
                                                    <div class="col-md-2"><b>Total</b></div>
                                                    <div class="col-md-1"><b>Options</b></div>
                                                </div> <hr>
                                                
                                                <!-- Rent Recurring Expense -->
                                                <div class="row mt-2 mb-2" id="rentSection">
                                                    <div class="col-md-2">
                                                        <select name="rent_bill" id="rent_bill" class="form-control">
                                                            <option value="" selected hidden>Select</option>
                                                            <option value="Rent">Rent</option>
                                                            <option value="Water" hidden>Water</option>
                                                            <option value="Garbage" hidden>Garbage</option>
                                                            <option value="Internet" hidden>Internet</option>
                                                            <option value="Security" hidden>Security</option>
                                                            <option value="Electricity" hidden>Electricity</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="text" class="form-control" id="rent" name="rent" placeholder="Qty">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="rent_value" name="rent_value" placeholder="Amount">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="rent_subtotal" name="rent_subtotal" placeholder="Sub Total" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input class="form-control" name="rent_tax" id="rent_tax" readonly placeholder="Tax">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="rent_totals" name="rent_totals" placeholder="Total" readonly>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-xs shadow" type="button" id="addWaterBtn" style="border: 2px solid #24953E; color:#24953E"><i class="fa fa-plus-square"></i> </button>
                                                    </div>
                                                </div>

                                                <!-- Water Recurring Expense -->
                                                <div class="row mt-2 mb-2" id="waterSection" style="display:none;">
                                                    <div class="col-md-2">
                                                        <select name="water_bill" id="water_bill" class="form-control">
                                                            <option value="" selected hidden>Select</option>
                                                            <option value="Water">Water</option>
                                                            <option value="Garbage" hidden>Garbage</option>
                                                            <option value="Internet" hidden>Internet</option>
                                                            <option value="Security" hidden>Security</option>
                                                            <option value="Electricity" hidden>Electricity</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="text" class="form-control" id="water" name="water" placeholder="Qty">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="water_val" name="water_val" placeholder="Unit Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="water_subtotal" name="water_subtotal" placeholder="Sub Total" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input class="form-control" name="water_tax" id="water_tax" placeholder="Tax" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="water_totals" name="water_totals" placeholder="Total" readonly>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-xs shadow" type="button" id="addGarbageBtn" style="border: 2px solid #24953E; color:#24953E"><i class="fa fa-plus-square"></i> </button>
                                                        <button class="btn btn-xs shadow" type="button" id="removeWaterBtn" style="border: 2px solid #cc0001; color:#cc0001;"><i class="fa fa-trash"></i> </button>
                                                    </div>
                                                </div>

                                                <!-- Garbage Recurring Expense -->
                                                <div class="row mt-2 mb-2" id="garbageSection" style="display:none;">
                                                    <div class="col-md-2">
                                                        <select name="garbage_bill" id="garbage_bill" class="form-control">
                                                            <option value="" selected hidden>Select</option>
                                                            <option value="Water" hidden>Water</option>
                                                            <option value="Garbage">Garbage</option>
                                                            <option value="Internet" hidden>Internet</option>
                                                            <option value="Security" hidden>Security</option>
                                                            <option value="Electricity" hidden>Electricity</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="text" class="form-control" id="garbage" name="garbage" placeholder="Qty">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="garbage_val" name="garbage_val" placeholder="Unit Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="garbage_subtotal" name="garbage_subtotal" placeholder="Sub Total" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="garbage_tax" name="garbage_tax" placeholder="Tax" readonly >
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="garbage_totals" name="garbage_totals" placeholder="Total" readonly>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-xs shadow" type="button" id="addInternetBtn" style="border: 2px solid #24953E; color:#24953E"><i class="fa fa-plus-square"></i> </button>
                                                        <button class='btn btn-xs shadow' id="removeGarbageBtn" style='border:2px solid #cc0001; color:#cc0001;' type='button'><i class='fa fa-trash'></i></button>
                                                    </div>
                                                </div>

                                                <!-- Internet Recurring Expense -->
                                                <div class="row mt-2 mb-2" id="internetSection" style="display:none;">
                                                    <div class="col-md-2">
                                                        <select name="internet_bill" id="internet_bill" class="form-control">
                                                            <option value="" selected hidden>Select</option>
                                                            <option value="Water" hidden>Water</option>
                                                            <option value="Garbage" hidden>Garbage</option>
                                                            <option value="Internet">Internet</option>
                                                            <option value="Security" hidden>Security</option>
                                                            <option value="Electricity" hidden>Electricity</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="text" class="form-control" id="internet" name="internet" placeholder="Qty">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="internet_val" name="internet_val" placeholder="Unit Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="internet_subtotal" name="internet_subtotal" placeholder="Sub Total" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="internet_tax" name="internet_tax" placeholder="Tax" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="internet_totals" name="internet_totals" placeholder="Total" readonly>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-xs" type="button" id="addSecurityBtn" style="border: 2px solid #24953E; color:#24953E"><i class="fa fa-plus-square"></i> </button>
                                                        <button class='btn btn-xs' id="removeInternetBtn" style='border:2px solid #cc0001; color:#cc0001;' type='button'><i class='fa fa-trash'></i></button>
                                                    </div>
                                                </div>

                                                <div class="row mt-2 mb-2" id="securitySection" style="display:none;">
                                                    <div class="col-md-2">
                                                        <select name="security_bill" id="security_bill" class="form-control">
                                                            <option value="" selected hidden>Select</option>
                                                            <option value="Water" hidden>Water</option>
                                                            <option value="Garbage" hidden>Garbage</option>
                                                            <option value="Internet" hidden>Internet</option>
                                                            <option value="Security">Security</option>
                                                            <option value="Electricity" hidden>Electricity</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="text" class="form-control" id="security" name="security" placeholder="Qty">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="security_amt" name="security_amt" placeholder="Unit Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="security_subtotals" name="security_subtotals" placeholder="Unit Price" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="security_taxation" name="security_taxation" placeholder="Tax" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="security_total" name="security_total" placeholder="Total" readonly>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-xs" type="button" id="addElectricityBtn" style="border: 2px solid #24953E; color:#24953E"><i class="fa fa-plus-square"></i> </button>
                                                        <button class='btn btn-xs' id="removeSecurityBtn" style='border:2px solid #cc0001; color:#cc0001;' type='button'><i class='fa fa-trash'></i></button>
                                                    </div>
                                                </div>

                                                <div class="row mt-2 mb-2" id="electricitySection" style="display:none;">
                                                    <div class="col-md-2">
                                                        <select name="electricity_bill" id="electricity_bill" class="form-control">
                                                            <option value="" selected hidden>Select</option>
                                                            <option value="Water" hidden>Water</option>
                                                            <option value="Garbage" hidden>Garbage</option>
                                                            <option value="Internet" hidden>Internet</option>
                                                            <option value="Security" hidden>Security</option>
                                                            <option value="Electricity">Electricity</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="text" class="form-control" id="electricity" name="electricity" placeholder="Qty">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="electricity_val" name="electricity_val" placeholder="Unit Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="electricity_subtotal" name="electricity_subtotal" placeholder="Sub Total" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="electricity_tax" name="electricity_tax" placeholder="Tax" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="electricity_totals" name="electricity_totals" placeholder="Total" readonly>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-xs" type="button" id="addPenaltyBtn" style="border: 2px solid #24953E; color:#24953E"><i class="fa fa-plus-square"></i> </button>
                                                        <button class='btn btn-xs' id="removeElectricityBtn" style='border:2px solid #cc0001; color:#cc0001;' type='button'><i class='fa fa-trash'></i></button>
                                                    </div>                                                                
                                                </div>

                                                <div class="row mt-2 mb-2" id="penaltySection" style="display:none;">
                                                    <div class="col-md-2">
                                                        <select name="penalty_bill" id="penalty_bill" class="form-control">
                                                            <option value="" selected hidden>Select</option>
                                                            <option value="Water" hidden>Water</option>
                                                            <option value="Garbage" hidden>Garbage</option>
                                                            <option value="Internet" hidden>Internet</option>
                                                            <option value="Security" hidden>Security</option>
                                                            <option value="Electricity" hidden>Electricity</option>
                                                            <option value="Penalty">Penalty</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="text" class="form-control" id="penalty" name="penalty_qty" placeholder="Qty">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="penalty_val" name="penalty_val" placeholder="Unit Price">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="penalty_subtotals" name="penalty_subtotals" placeholder="Sub Total" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="penalty_taxation" name="penalty_taxation" placeholder="Tax" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="penalty_totals" name="penalty_totals" placeholder="Total" readonly>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class='btn btn-xs' id="removePenaltyBtn" style='border:2px solid #cc0001; color:#cc0001;' type='button'><i class='fa fa-trash'></i></button>
                                                    </div>                                                                
                                                </div>
                                                <hr>
                                                <div class="row mt-2 mb-2">
                                                    <div class="col-md-2">
                                                        <label>Totals</label>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input class="form-control" name="total_qtys" id="total_qtys" placeholder="Qty" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input class="form-control" name="total_amts" id="total_amts" placeholder="Amount" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="total_subtotals" name="total_subtotals" readonly placeholder="Total Sub Totals">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="total_taxation" name="total_taxation" readonly placeholder="Taxes">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" id="total_posttax" name="total_posttax" readonly placeholder="Totals">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer text-right">
                                            <button class="btn btn-sm" type="submit" style="background-color: #00192D; color: #fff;"><i class="fa fa-check"></i> Create</button>
                                        </div>
                                    </form>
                        </div>
                    </div>
                </section>
                <!-- /.content -->

                <!-- Help Pop Up Form -->
                <?php include_once 'includes/lower_right_popup_form.php' ;?>
            </div>
            <!-- /.content-wrapper -->

            <!-- Footer -->
            <?php include_once 'includes/footer.php';?>

        </div>
        <!-- ./wrapper -->
        <!-- Required Scripts -->
        <?php include_once 'includes/required_scripts.php';?>


        <script>
            // Function to view the invoice in the modal
            function viewInvoice() {
                document.getElementById('viewInvoiceNumber').textContent = document.querySelector('[name="invoice_number"]').value;
                document.getElementById('viewInvoiceDate').textContent = document.querySelector('[name="invoice_date"]').value;
                document.getElementById('viewCustomerName').textContent = document.querySelector('[name="tenant_name"]').value;
                document.getElementById('viewCustomerAddress').textContent = document.querySelector('[name="customer_address"]').value;
                document.getElementById('viewCustomerEmail').textContent = document.querySelector('[name="customer_email"]').value;
                document.getElementById('viewPaymentMethod').textContent = document.querySelector('[name="payment_method"]').value;
                document.getElementById('viewShippingOption').textContent = document.querySelector('[name="shipping_option"]').value;
            }
            // Function to delete a row
            function deleteRow(button) {
                // Find the row to delete
                var row = button.parentElement.parentElement;
                row.remove();
                updateTotalAmount();
            }

            // Function to update the total amount of an item when quantity or price is changed
            function updateTotal(input) {
                var row = input.parentElement.parentElement;
                var quantity = row.querySelector('[name="item_quantity[]"]').value;
                var price = row.querySelector('[name="item_price[]"]').value;
                var totalCell = row.querySelector('[name="item_total[]"]');
                totalCell.value = (quantity * price).toFixed(2);

                updateTotalAmount();
            }

            // Function to calculate the total invoice amount
            function updateTotalAmount() {
                var totalAmount = 0;
                var rows = document.querySelectorAll('.items-table tbody tr');
                rows.forEach(function(row) {
                    var total = parseFloat(row.querySelector('[name="item_total[]"]').value) || 0;
                    totalAmount += total;
                });
                document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
            }
        </script>

        <!--end::App Wrapper-->
        <!--begin::Script-->
        <!--begin::Third Party Plugin(OverlayScrollbars)-->
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


        <!-- Required Scripts -->
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
                                                 } else if (vatType === "zero") {
                                                 vatAmount = 0; // VAT 0% for Zero Rated
                                                 total = subtotal; // No tax added for Zero Rated
                                                 } else if (vatType === "exempted") {
                                                 vatAmount = 0; // No VAT for Exempted
                                                 total = subtotal; // No tax added for Exempted
                                                 }

                                                 totalInput.value = formatNumber(total);
                                                 return { subtotal, vatAmount, total, vatType };
                                                 }

                                                 function updateTotalAmount() {
                                                 let subtotalSum = 0, taxSum = 0, grandTotal = 0, exemptedSum = 0, zeroVatSum = 0;
                                                 let vat16Used = false, vat0Used = false, exemptedUsed = false;

                                                 document.querySelectorAll(".items-table tbody tr").forEach(row => {
                                                 if (row.querySelector(".unit-price")) {
                                                 const { subtotal, vatAmount, total, vatType } = calculateRow(row);
                                                 subtotalSum += subtotal;
                                                 taxSum += vatAmount;
                                                 grandTotal += total;

                                                 if (vatType === "inclusive" || vatType === "exclusive") {
                                                 vat16Used = true;
                                                 } else if (vatType === "zero") {
                                                 zeroVatSum += 0; // Zero Rated has zero VAT
                                                 vat0Used = true;
                                                 } else if (vatType === "exempted") {
                                                 exemptedSum += 0; // Exempted has zero VAT
                                                 exemptedUsed = true;
                                                 }
                                                 }
                                                 });

                                                 createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, zeroVatSum, exemptedSum, vat16Used, vat0Used, exemptedUsed });
                                                 }

                                                 function createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, zeroVatSum, exemptedSum, vat16Used, vat0Used, exemptedUsed }) {
                                                 let summaryTable = document.querySelector(".summary-table");

                                                 if (!summaryTable) {
                                                 summaryTable = document.createElement("table");
                                                 summaryTable.className = "summary-table table table-bordered";
                                                 summaryTable.style = "width: 20%; float: right; font-size: 0.8rem; margin-top: 10px;";
                                                 summaryTable.innerHTML = `<tbody></tbody>`;
                                                 document.querySelector(".items-table").after(summaryTable);
                                                 }

                                                 const tbody = summaryTable.querySelector("tbody");
                                                 tbody.innerHTML = `<tr>
<th style="width: 50%; padding: 5px; text-align: left;">Sub-total</th><td><input type="text" class="form-control" value="${formatNumber(subtotalSum)}" readonly style="padding: 5px;"></td>
            </tr>
                                                 ${vat16Used ? `
<tr>
<th style="width: 50%; padding: 5px; text-align: left;">VAT 16%</th>
<td><input type="text" class="form-control" value="${formatNumber(taxSum)}" readonly style="padding: 5px;"></td>
            </tr>` : ''}
                ${vat0Used ? `
<tr>
<th style="width: 50%; padding: 5px; text-align: left;">VAT 0%</th>
<td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
            </tr>` : ''}
                ${exemptedUsed ? `
<tr>
<th style="width: 50%; padding: 5px; text-align: left;">Exempted</th>
<td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
            </tr>` : ''}
                <tr>
                    <th style="width: 50%; padding: 5px; text-align: left;">Total</th>
                <td><input type="text" class="form-control" value="${formatNumber(grandTotal)}" readonly style="padding: 5px;"></td>
            </tr>
                `;
}

function attachEvents(row) {
const inputs = [".unit-price", ".quantity", ".vat-option"];
inputs.forEach(sel => {
const el = row.querySelector(sel);
if (el) {
el.addEventListener("input", updateTotalAmount);
el.addEventListener("change", updateTotalAmount);
}
});
}

// ==================================             Rental Payment DOM And Auto-Calculations =======================================

window.addRow = function () {
const table = document.querySelector(".items-table tbody");
const newRow = document.createElement("tr");
newRow.innerHTML = `
                    <td>
                    <select name="paid_for" required class="form-control">
                        <option value="" hidden selected>Item (Service)</option>
                <option value="Rent">Rent</option>
                <option value="Water Bill">Water Bill</option>
                <option value="Garbage">Garbage</option>
            </select>
            </td>
                <td>
                    <textarea name="description" placeholder="Description" rows="1" cols="40" required class="form-control"></textarea>
            </td>
                <td>
                        <input type="text" name="quantity" class="form-control quantity" placeholder="Quantity">
            </td>
                <td>
                            <input type="text" class="form-control unit-price" placeholder="Unit Price" name="unit_price" required>
            </td>
                <td>
                                <select class="form-control vat-option" name="vat_taxation">
                                    <option value="" hidden selected>Select Option</option>
                <option value="inclusive">VAT 16% Inclusive</option>
                <option value="exclusive">VAT 16% Exclusive</option>
                <option value="zero">Zero Rated</option><option value="exempted">Exempted</option>
            </select>
            </td>
                <td>
                    <input type="text" class="form-control total" placeholder="0.00" readonly placeholder="Totals">
            </td>
                <td>
                        <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete"><i class="fa fa-trash" style="font-size: 12px;"></i>
            </button>
            </td>
                `;
table.appendChild(newRow);
attachEvents(newRow);
};

window.deleteRow = function (btn) {
alert('Removing this will lead to the Change in the Final Tallying. If you\'d like to continue, Click OK');
btn.closest("tr").remove();
updateTotalAmount();
};

document.querySelectorAll(".items-table tbody tr").forEach(attachEvents);
updateTotalAmount();
});
        </script>


        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!--end::OverlayScrollbars Configure-->
        <!-- OPTIONAL SCRIPTS -->
        <!-- apexcharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
                integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
                crossorigin="anonymous"
                ></script>

        <script>
            $(document).ready(function() {
                $('#repaireExpenses').DataTable({
                    "lengthChange": false,
                    "dom": 'Bfrtip',
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const checkboxes = document.querySelectorAll("#columnFilter input[type='checkbox']");
                const menuButton = document.getElementById("menuButton");
                const columnFilter = document.getElementById("columnFilter");

                // Toggle menu visibility when clicking the three dots
                menuButton.addEventListener("click", function(event) {
                    columnFilter.classList.toggle("hidden");
                    columnFilter.style.display = columnFilter.classList.contains("hidden") ? "none" : "block";

                    // Prevent closing immediately when clicking inside
                    event.stopPropagation();
                });

                // Hide menu when clicking outside
                document.addEventListener("click", function(event) {
                    if (!menuButton.contains(event.target) && !columnFilter.contains(event.target)) {
                        columnFilter.classList.add("hidden");
                        columnFilter.style.display = "none";
                    }
                });

                // Column filtering logic
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener("change", function() {
                        let columnClass = `.col-${this.dataset.column}`;
                        let elements = document.querySelectorAll(columnClass);

                        elements.forEach(el => {
                            el.style.display = this.checked ? "" : "none";
                        });
                    });
                });
            });

        </script>

        <script>
    $(document).ready(function() {

    //Get Values on key up
    $("#rent, #rent_value, #water, #water_val, #garbage, #garbage_val, #internet, #internet_val, #security, #security_amt, #electricity, #electricity_val, #penalty, #penalty_val").keyup(function(){

    //Assign the values for Auto Calculations to 0
    var rent_subtotal = 0;
    var rent_tax = 0;
    var rent_totals = 0;

    var water_subtotal = 0;
    var water_tax = 0;
    var water_totals = 0;

    var garbage_subtotal = 0;
    var garbage_tax = 0;
    var garbage_totals = 0;

    var internet_subtotal = 0;
    var internet_tax = 0;
    var internet_totals = 0;

    var security_subtotals = 0;
    var security_taxation = 0;
    var security_total = 0;

    var electricity_subtotal = 0
    var electricity_tax = 0
    var electricity_totals = 0

    var penalty_subtotals = 0;
    var penalty_taxation = 0;
    var penalty_totals = 0;

    var total_qtys = 0;
    var total_amts = 0;
    var total_subtotals = 0;
    var total_taxation = 0;
    var total_posttax = 0;

    //Convert input fields into Numbers for Calculation Purposes
    var rentalQty = Number($("#rent").val());
    var rentalValue = Number($("#rent_value").val());

    var waterQty = Number($("#water").val());
    var waterValue = Number($("#water_val").val());

    var garbageQty = Number($("#garbage").val());
    var garbageValue = Number($("#garbage_val").val());

    var internetQty = Number($("#internet").val());
    var internetValue = Number($("#internet_val").val());

    var securityQty = Number($("#security").val());
    var securityValue = Number($("#security_amt").val());

    var electricityQty = Number($("#electricity").val());
    var electricityValue = Number($("#electricity_val").val());

    var penaltyQty = Number($("#penalty").val());
    var penaltyValue = Number($("#penalty_val").val());

    //Calcuate Rent Sub Total
    var rent_subtotal = rentalQty * rentalValue;
    $("#rent_subtotal").val(rent_subtotal);

    //Calculate Rental Tax which is at 7.5%
    var rent_tax = rent_subtotal * (7.5 / 100);
    $("#rent_tax").val(rent_tax);

    //Calculate Rent Final Total
    var rent_totals = rent_subtotal - rent_tax;
    $("#rent_totals").val(rent_totals);

    //Calculate Water Sub Total
    var water_subtotal = waterQty * waterValue;
    $("#water_subtotal").val(water_subtotal);

    //Calculate Water Tax
    var water_tax = water_subtotal * 0;
    $("#water_tax").val(water_tax);

    //Calculate Water Totals
    var water_totals = water_subtotal - water_tax;
    $("#water_totals").val(water_totals);

    //Calculate Garbage Subtotal
    var garbage_subtotal = garbageQty * garbageValue;
    $("#garbage_subtotal").val(garbage_subtotal);

    //Calculate Garbage Tax
    var garbage_tax = garbage_subtotal * 0;
    $("#garbage_tax").val(garbage_tax);

    //Calculate Garbage Totals
    var garbage_totals = garbage_subtotal - garbage_tax;
    $("#garbage_totals").val(garbage_totals);

    //Calculate Internet Totals
    var internet_subtotal = internetQty * internetValue;
    $("#internet_subtotal").val(internet_subtotal);

    //Calculate Internet Tax
    var internet_tax = internet_subtotal * (16 / 100);
    $("#internet_tax").val(internet_tax);

    //Calculate Internet Totals
    var internet_totals = internet_subtotal - internet_tax;
    $("#internet_totals").val(internet_totals);

    //Calculate Security Sub Total
    var security_subtotals = securityQty * securityValue;
    $("#security_subtotals").val(security_subtotals);

    //Calculate Security Tax
    var security_taxation = security_subtotals * 0;
    $("#security_taxation").val(security_taxation);

    //Calculate Security Totals after Tax
    var security_total = security_subtotals - security_taxation;
    $("#security_total").val(security_total);

    //Calculate Electricity Sub Total
    var electricity_subtotal = electricityQty * electricityValue;
    $("#electricity_subtotal").val(electricity_subtotal);

    //Calculate Electricity Tax
    var electricity_tax = electricity_subtotal * (16 / 100);
    $("#electricity_tax").val(electricity_tax);

    //Calculate Electricity Totals
    var electricity_totals = electricity_subtotal - electricity_tax;
    $("#electricity_totals").val(electricity_totals);

    //Calculate Penalty Sub Total
    var penalty_subtotals = penaltyQty * penaltyValue;
    $("#penalty_subtotals").val(penalty_subtotals);
    
    //Calculate Penalty Taxation
    var penalty_taxation = penalty_subtotals * 0;
    $("#penalty_taxation").val(penalty_taxation);

    //Calculate Penalty Totals
    var penalty_totals = penalty_subtotals - penalty_taxation;
    $("#penalty_totals").val(penalty_totals);
    
    //Calculate the Totals for all the Quantities 
    var total_qtys = rentalQty + waterQty + garbageQty + internetQty + securityQty + electricityQty + penaltyQty;
    $("#total_qtys").val(total_qtys);

    //Calculate Total Amounts
    var total_amts = rentalValue + waterValue + garbageValue + internetValue + securityValue + electricityValue + penaltyValue;
    $("#total_amts").val(total_amts);

    //Calculate Total Sub Totals
    var total_subtotals  = rent_subtotal + water_subtotal + garbage_subtotal + internet_subtotal + security_subtotals + electricity_subtotal + penalty_subtotals;
    $("#total_subtotals").val(total_subtotals);

    //Calculate Total Taxations
    var total_taxation = rent_tax + water_tax + garbage_tax + internet_tax + security_taxation + electricity_tax + penalty_taxation;
    $("#total_taxation").val(total_taxation);

    //Calaulate Total for all Totals after Taxation
    var total_posttax = rent_totals + water_totals + garbage_totals + internet_totals + security_total + electricity_totals + penalty_totals;
    $("#total_posttax").val(total_posttax);
});

    //Add Button to Display Water Water Expense
    $("#addWaterBtn").click(function(e){
        e.preventDefault();
        $("#waterSection").show();
        $("#addWaterBtn").hide();
    });

    //Add Button to Display Garbage Expense
    $("#addGarbageBtn").click(function(e){
        e.preventDefault();
        $("#garbageSection").show();
        $("#addGarbageBtn").hide();
    });

    //Add Button to Display Internet Expense
    $("#addInternetBtn").click(function(e){
        e.preventDefault();
        $("#internetSection").show();
        $("#addInternetBtn").hide();
    });

    //Add Button to Display Security Expense
    $("#addSecurityBtn").click(function(e){
        e.preventDefault();
        $("#securitySection").show();
        $("#addSecurityBtn").hide();
    });

    //Add Button to Display Electricity Expense
    $("#addElectricityBtn").click(function(e){
        e.preventDefault();
        $("#electricitySection").show();
        $("#addElectricityBtn").hide();
    });

        //Add Button to Display Penalty Expense
        $("#addPenaltyBtn").click(function(e){
            e.preventDefault();
            $("#penaltySection").show();
            $("#addPenaltyBtn").hide();
        });

        //===================================== Remove Buttons for Creating Invoice ========================================

        //Remove Water Section
        $("#removeWaterBtn").click(function(e){
            e.preventDefault();
            if(confirm('This Added Deposit will be Removed from the List. Remember that If you had typed in the Required Pay and Amount Paid Figure, the Values in the Totals will not be Affected. For you to Change this, Just change the Typed Figures') == false){
                $("#waterSection").show();
            } else{
                $("#waterSection").hide();
            }
        });

        //Remove Garbage Section Button
        $("#removeGarbageBtn").click(function(e){
            e.preventDefault();
            if(confirm('This Added Deposit will be Removed from the List. Remember that If you had typed in the Required Pay and Amount Paid Figure, the Values in the Totals will not be Affected. For you to Change this, Just change the Typed Figures') == false){
                $("#garbageSection").show();
            } else{
                $("#garbageSection").hide();
            }
        });

        //Remove Internet Section Button
        $("#removeInternetBtn").click(function(e){
            e.preventDefault();
            if(confirm('This Added Deposit will be Removed from the List. Remember that If you had typed in the Required Pay and Amount Paid Figure, the Values in the Totals will not be Affected. For you to Change this, Just change the Typed Figures') == false){
                $("#internetSection").show();
            } else{
                $("#internetSection").hide();
            }
        })

        $("#removeSecurityBtn").click(function(e){
            e.preventDefault();
            if(confirm('This Added Deposit will be Removed from the List. Remember that If you had typed in the Required Pay and Amount Paid Figure, the Values in the Totals will not be Affected. For you to Change this, Just change the Typed Figures') == false){
                $("#securitySection").show();
            } else{
                $("#securitySection").hide();
            }
        });

        //Remove Electricity Deposit Section
        $("#removeElectricityBtn").click(function(e){
            e.preventDefault();
            if(confirm('This Added Deposit will be Removed from the List. Remember that If you had typed in the Required Pay and Amount Paid Figure, the Values in the Totals will not be Affected. For you to Change this, Just change the Typed Figures') == false){
                $("#electricitySection").show();
            } else{
                $("#electricitySection").hide();
            }
        });

        //Remove Penalty Section Button
        $("#removePenaltyBtn").click(function(e){e.preventDefault();
            if(confirm('This Added Deposit will be Removed from the List. Remember that If you had typed in the Required Pay and Amount Paid Figure, the Values in the Totals will not be Affected. For you to Change this, Just change the Typed Figures') == false){
                $("#penaltySection").show();
            } else{
                $("#penaltySection").hide();
            }
        });

                });
            </script>
    </body>

</html>
