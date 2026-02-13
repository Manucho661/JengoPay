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
                  <div class="row">
                    <div class="col-md-6">
                      <b>All Rental Payments</b>
                    </div>
                    <div class="col-md-6 text-right">
                      <button class="btn btn-sm" type="button" data-toggle="modal" data-target="#payRentModal" style="background-color: #00192D; color:#fff;"><i class="fa fa-plus-square"></i> Make Payment</button>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <table class="table table-hover" id="dataTable">
                    <thead>
                      <th>Date</th>
                      <th>Unit</th>
                      <th>Required Pay</th>
                      <th>Amount Paid</th>
                      <th>Bal.</th>
                      <th>Status</th>
                      <th>Action</th>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php echo date ('d, M, Y') ;?></td>
                        <td>CH-201</td>
                        <td>Kshs. 170,000.00</td>
                        <td>Kshs. 170,000.00</td>
                        <td>Kshs. 00.00</td>
                        <td><button class="btn btn-sm" style="color:#24953E; border:1px solid #24953E;"><i class="fa fa-check"></i> Cleared</button></td>
                        <td>
                          <a href="#"><button class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;"> <i class="fa fa-file"></i> Receipt</button></a>
                        </td>
                      </tr>
                      <tr>
                        <td><?php echo date ('d, M, Y') ;?></td>
                        <td>CH-201</td>
                        <td>Kshs. 170,000.00</td>
                        <td>Kshs. 100,000.00</td>
                        <td>Kshs.70.000.00</td>
                        <td>
                          <button class="btn btn-sm" style="color:#cc0001; border:1px solid #cc0001;"><i class="fa fa-exclamation"></i> Pending</button>
                        </td>
                        <td>
                          <a href="#"><button class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;"> <i class="fa fa-file"></i> Receipt</button>
                          <a href="#"><button class="btn btn-sm" style="border:1px solid #305662; color: #305662;"> <i class="fa fa-check"></i> Clear</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- Make Rental Payment Modal -->
              <div class="modal fade" id="payRentModal">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <p class="modal-title">Pay Rent</p>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      
                      <form action="" method="post" autocomplete="off">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Payment No.</label>
                            <input type="text" class="form-control" name="payment_number" id="payment_number" readonly value="<?php echo '202/' .date('Y');?>">
                          </div>
                          <div class="form-group">
                            <label>Today's Date</label>
                            <input class="form-control" name="pay_date" id="pay_date" readonly value="<?php echo date('d, M, Y') ;?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Building</label>
                            <input class="form-control" name="building_name" id="building_name" readonly value="Angela Apartments">
                          </div>
                          <div class="form-group">
                            <label>House No.</label>
                            <input class="form-control" name="house_no" id="house_no" readonly value="CH-210">
                          </div>
                        </div>
                      </div> <hr>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Rent For</label>
                            <input class="form-control" name="rent_for" id="rent_for" readonly value="<?php echo date('M, Y') ;?>">
                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="form-group">
                            <label>Your Name</label>
                            <input class="form-control" name="payee_name" id="payee_name" readonly value="Paul Pashan">
                          </div>
                        </div>
                      </div> <hr>
                      <table class="table items-table">
                        <thead>
                          <tr>
                            <th>Item (Service)</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Taxes</th>
                            <th>Total</th>
                            <th>Remove</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
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
                                <option value="" disabled selected>Select Option</option>
                                <option value="inclusive">VAT 16% Inclusive</option>
                                <option value="exclusive">VAT 16% Exclusive</option>
                                <option value="zero">Zero Rated</option>
                                <option value="exempted">Exempted</option>
                              </select>
                            </td>
                            <td>
                              <input type="text" class="form-control total" placeholder="item_totals" readonly placeholder="Totals">
                            </td>
                            <td>
                              <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete" style="background-color: #cc0001; color:#fff;">
                                <i class="fa fa-trash" style="font-size: 12px;"></i>
                              </button>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="7">
                              <button type="button" class="btn btn-sm add-btn" onclick="addRow()" style="background-color: #00192D; color:#fff;">
                                <i class="fa fa-plus"></i> Add More
                            </button>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <div class="card-footer">
                      <div class="row">
                        <div class="col-md-6 text-left">
                          <button type="button" class="btn btn-sm" data-dismiss="modal" style="background-color:#cc0001; color:#fff;"><i class="fa fa-times"></i> Close</button>
                        </div>
                        <div class="col-md-6 text-right">
                          <button class="btn btn-sm" type="button" style="background-color: #00192D; color:#fff;"><i class="fa fa-check"></i> Submit</button>
                        </div>
                      </div>
                    </div>
                  </form>                  
                    </div>
                    <div class="modal-footer justify-content-between">
                    </div>
                  </div>
                </div>
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
                  <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete" style="background-color: #cc0001; color:#fff;"><i class="fa fa-trash" style="font-size: 12px;"></i>
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
    </body>

    </html>
