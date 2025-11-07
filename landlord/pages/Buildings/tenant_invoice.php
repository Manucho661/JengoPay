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
                                    <?php
                                        include_once 'processes/encrypt_decrypt_function.php';
                                        if(isset($_GET['invoice']) && !empty($_GET['invoice'])) {
                                            $id = $_GET['invoice'];
                                            $id = encryptor('decrypt', $id);
                                            
                                            try{
                                                //1. Fetch Tenant Details from the Tenant Database
                                                $tenant = $conn->prepare("SELECT * FROM single_units WHERE id = ? ");
                                                $tenant->execute([$id]);
                                                $tenant_info = $tenant->fetch(PDO::FETCH_ASSOC);
                                                if(!$tenant_info) {
                                                    echo "<script>
                                                            Swal.fire({
                                                                icon: 'warning',
                                                                title: 'No Data',
                                                                text: 'No Data found in the database.'
                                                            });
                                                        </script>";
                                                }
                                            }catch(PDOException $e){
                                                //If the Query fails to execute
                                            }

                                        }
                                        //Submit Invoice Process
                                        if(isset($_POST['submit'])) {
                                            $tm = md5(time()); // Unique prefix for uploaded files
                                            $attachment_name = $_FILES['attachment']['name']; //Image Name
                                            $attachment_destination = "./all_uploads/".$attachment_name; //uploading an image
                                            $attachment_destination = "all_uploads/".$tm.$attachment_name; //storing an encrypted File Name in the table
                                            move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachment_destination); //Move Uploaded File

                                            $invoice_no      = $_POST['invoice_no'];
                                            $receiver       = $_POST['receiver'];
                                            $main_contact   = $_POST['main_contact'];
                                            $alt_contact    = $_POST['alt_contact'];
                                            $email          = $_POST['email'];
                                            $invoice_date   = $_POST['invoice_date'];
                                            $due_date       = $_POST['due_date'];
                                            $subtotal       = $_POST['subtotalValue'];
                                            $total_tax      = $_POST['totalTaxValue'];
                                            $final_total    = $_POST['finalTotalValue'];
                                            $notes          = $_POST['notes'];
                                            $payment_status = $_POST['paymentStatus'] ?? 'Pending';
                                            $invoice_items  = json_decode($_POST['invoice_items'], true);


                                            try{

                                                if (!$conn->inTransaction()) {
                                                    $conn->beginTransaction();
                                                }

                                                // ===================== INSERT INTO singleunit_invoices =====================
                                                $stmt = $conn->prepare("INSERT INTO singleunit_invoices (invoice_no, receiver, main_contact, alt_contact, email,invoice_date, due_date, notes, payment_status, subtotal, total_tax, final_total) VALUES (:invoice_no, :receiver, :main_contact, :alt_contact, :email, :invoice_date, :due_date, :notes, :payment_status, :subtotal, :total_tax, :final_total)");

                                                $stmt->execute([
                                                    ':invoice_no'     => $_POST['invoice_no'],
                                                    ':receiver'       => $_POST['receiver'],
                                                    ':main_contact'   => $_POST['main_contact'],
                                                    ':alt_contact'    => $_POST['alt_contact'],
                                                    ':email'          => $_POST['email'],
                                                    ':invoice_date'   => $_POST['invoice_date'],
                                                    ':due_date'       => $_POST['due_date'],
                                                    ':notes'          => $_POST['notes'],
                                                    ':payment_status' => $_POST['paymentStatus'],
                                                    ':subtotal'       => $_POST['subtotalValue'],
                                                    ':total_tax'      => $_POST['totalTaxValue'],
                                                    ':final_total'    => $_POST['finalTotalValue']
                                                ]);

                                                // ✅ Get inserted invoice ID from invoices table
                                                $invoiceId = $conn->lastInsertId();

                                                // ===================== INSERT INTO singleinvoice_items =====================
                                                $items = json_decode($_POST['invoice_items'], true);

                                                if (!empty($items)) {
                                                    $stmtItem = $conn->prepare("INSERT INTO singleinvoice_items (invoice_id, item_name, description, unit_price, quantity, tax_type, tax_amount, total_price) VALUES (:invoice_id, :item_name, :description, :unit_price, :quantity, :tax_type, :tax_amount, :total_price)");

                                                    foreach ($items as $item) {
                                                            $stmtItem->execute([
                                                            ':invoice_id'  => $invoiceId,
                                                            ':item_name'   => $item['item_name'],
                                                            ':description' => $item['description'],
                                                            ':unit_price'  => $item['unit_price'],
                                                            ':quantity'    => $item['quantity'],
                                                            ':tax_type'    => $item['tax_type'],
                                                            ':tax_amount'  => $item['tax_amount'],
                                                            ':total_price' => $item['total_price']
                                                        ]);
                                                    }
                                                }

                                                //  Commit once the transaction once
                                                if ($conn->inTransaction()) {
                                                    $conn->commit();
                                                }

                                                ?>
                                                    <script>
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Invoice Saved!',
                                                            text: 'The Tenant invoice and its items have been saved successfully.',
                                                            confirmButtonColor: '#00192D'
                                                        }).then(() => {
                                                            // redirect after success
                                                            window.location.href = "all_tenant_invoices.php";
                                                        });
                                                    </script>

                                                <?php

                                            } catch(Exception $e) {
                                                if ($conn->inTransaction()) {
                                                    $conn->rollBack();
                                                }
                                                ?>
                                                <script>
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Failed to Save Invoice',
                                                        text: '<?= addslashes($e->getMessage()); ?>',
                                                        confirmButtonColor: '#cc0001'
                                                    }).then(() => {
                                                        window.history.back(); // go back to form
                                                    });
                                                </script>
                                                <?php
                                            }
                                        }
                                    ?>
                                    <div class="card shadow">
                                        <div class="card-header" style="background-color:#00192D; color:#fff;"><b>Create Invoice</b></div>
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
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input class="form-control" value="<?= $tenant_info['temail'] ;?>" readonly name="email">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Invoice Date:</label>
                                                            <input type="date" id="invoiceDate" name="invoice_date" required class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Date Due:</label>
                                                            <input type="date" id="dateDue" name="due_date" required class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div><hr>
                                                <!-- Hidden Payment Status -->
                                                <input type="hidden" name="paymentStatus" value="Pending">
                                                <!-- Invoice Table -->
                                                <table id="invoiceTable" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Item Name</th>
                                                            <th>Description</th>
                                                            <th>Unit Price</th>
                                                            <th>Quantity</th>
                                                            <th>Tax Type</th>
                                                            <th>Tax Amount</th>
                                                            <th>Total Price</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="invoiceBody">
                                                        <!-- Rows inserted by JS -->
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" class="text-end">Subtotal:</td>
                                                            <td id="subtotal" class="amount">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-end">Total Tax:</td>
                                                            <td id="totalTax" class="amount">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-end"><strong>Final Total:</strong></td>
                                                            <td id="finalTotal" class="amount">0.00</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-12 text-left">
                                                        <button type="button" onclick="addRow()" class="btn btn-sm shadow" style="background-color:#00192D; color:#fff;">+ Add Row</button>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <!-- Notes Section -->
                                                        <label>Notes:</label>
                                                        <textarea name="notes" rows="4" class="form-control"></textarea>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <!-- Attachment -->
                                                        <label>Attachment:</label>
                                                        <input type="file" name="attachment" accept=".pdf,.jpg,.png,.docx" class="form-control">
                                                    </div>
                                                </div>
                                                <!-- Hidden invoice items JSON -->
                                                <input type="hidden" name="invoice_items" id="invoiceItems">
                                                <!-- Hidden totals -->
                                                <input type="hidden" name="subtotalValue" id="subtotalValue">
                                                <input type="hidden" name="totalTaxValue" id="totalTaxValue">
                                                <input type="hidden" name="finalTotalValue" id="finalTotalValue">
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="submit" onclick="return prepareInvoiceData()" name="submit" class="btn btn-sm" style="background-color:#00192D; color:#fff;"><i class="bi bi-check"></i> Submit</button>
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
        <!-- Invoice Scripts -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Format currency
function formatCurrency(num) {
    return num.toLocaleString("en-KE", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Generate invoice number
function generateInvoiceNumber() {
    const today = new Date();
    const datePart = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}-${String(today.getDate()).padStart(2, "0")}`;
    let storedData = JSON.parse(localStorage.getItem("invoiceCounter")) || {};
    let counter = 1;
    if (storedData.date === datePart) counter = storedData.counter + 1;
    storedData = { date: datePart, counter: counter };
    localStorage.setItem("invoiceCounter", JSON.stringify(storedData));
    return `INV-${datePart}-${String(counter).padStart(3, "0")}`;
}

// Add row
function addRow() {
    const tbody = document.getElementById("invoiceBody");

    // Validate last row before adding
    const rows = tbody.querySelectorAll("tr");
    if (rows.length > 0) {
        const lastRow = rows[rows.length - 1];
        if (!validateRow(lastRow)) {
            Swal.fire({
                icon: "warning",
                title: "Incomplete Row",
                text: "Can't add a new row without filling the previous one.",
                confirmButtonColor: "#00192D"
            });
            return;
        }
    }

    const row = document.createElement("tr");
    row.innerHTML = `
        <td>
            <select class="form-control itemName" required
                onchange="updateItemOptions(); checkOthersInput(this); validateField(this); validateAllRows();">
                ${buildItemOptions()}
            </select>
            <input type="text" class="form-control mt-1 otherInput d-none" 
                placeholder="Please specify" oninput="validateField(this); validateAllRows();" />
        </td>
        <td><input type="text" class="form-control description"></td>
        <td><input type="number" class="form-control unitPrice" step="0.01" value="0" min="0"
            oninput="updateTotals(); validateField(this); validateAllRows();"></td>
        <td><input type="number" class="form-control quantity" value="1" min="1"
            oninput="updateTotals(); validateField(this); validateAllRows();"></td>
        <td>
            <select class="form-control taxType" onchange="updateTotals();">
                <option value="VAT Inclusive">VAT 16% Inclusive</option>
                <option value="VAT Exclusive">VAT 16% Exclusive</option>
                <option value="Zero Rated">Zero Rated</option>
                <option value="Exempted">Exempted</option>
            </select>
        </td>
        <td class="taxAmount amount">0.00</td>
        <td class="totalPrice amount">0.00</td>
        <td>
            <button type="button" onclick="this.closest('tr').remove(); updateTotals(); updateItemOptions(); validateAllRows();" 
                class="btn btn-sm shadow" style="background-color:#cc0001; color:#fff;">Remove</button>
        </td>`;
    tbody.appendChild(row);

    updateTotals();
    updateItemOptions();
    validateAllRows();
}

// Build options dynamically based on selected items
function buildItemOptions(currentValue = "") {
    const allItems = ["Rent","Water","Internet","Electricity","Garbage",
        "Penalty","Parking","Maintenance","Security","Welfare","Fumigation","Others"];

    const selected = Array.from(document.querySelectorAll(".itemName"))
        .map(s => s.value)
        .filter(v => v && v !== "Others");

    let options = `<option value="">-- Select Item --</option>`;
    allItems.forEach(item => {
        if (item === currentValue || item === "Others" || !selected.includes(item)) {
            options += `<option ${item === currentValue ? "selected" : ""}>${item}</option>`;
        }
    });
    return options;
}

// Refresh options on all selects
function updateItemOptions() {
    document.querySelectorAll(".itemName").forEach(select => {
        const currentValue = select.value;
        select.innerHTML = buildItemOptions(currentValue);
        select.value = currentValue; // keep selection
    });
}

// Validate row
function validateRow(row) {
    let valid = true;
    const itemName = row.querySelector(".itemName");
    const unitPrice = row.querySelector(".unitPrice");
    const quantity = row.querySelector(".quantity");
    const otherInput = row.querySelector(".otherInput");

    if (itemName.value.trim() === "") {
        itemName.classList.add("border-danger");
        valid = false;
    }
    if (itemName.value === "Others" && otherInput.value.trim() === "") {
        otherInput.classList.add("border-danger");
        valid = false;
    }
    if (unitPrice.value.trim() === "" || unitPrice.value <= 0) {
        unitPrice.classList.add("border-danger");
        valid = false;
    }
    if (quantity.value.trim() === "" || quantity.value <= 0) {
        quantity.classList.add("border-danger");
        valid = false;
    }
    return valid;
}

// Remove red border once fixed
function validateField(input) {
    if (input.value.trim() !== "" && !(input.type === "number" && input.value <= 0)) {
        input.classList.remove("border-danger");
    }
}

// Others input
function checkOthersInput(select) {
    const otherInput = select.parentElement.querySelector(".otherInput");
    if (select.value === "Others") {
        otherInput.classList.remove("d-none");
        otherInput.required = true;
    } else {
        otherInput.classList.add("d-none");
        otherInput.required = false;
        otherInput.value = "";
        otherInput.classList.remove("border-danger");
    }
}

// Update totals
function updateTotals() {
    let subtotal = 0, totalTax = 0, finalTotal = 0;
    document.querySelectorAll("#invoiceBody tr").forEach(row => {
        const unitPrice = parseFloat(row.querySelector(".unitPrice").value) || 0;
        const quantity = parseInt(row.querySelector(".quantity").value) || 0;
        const taxType = row.querySelector(".taxType").value;
        let taxAmount = 0, totalPrice = 0;

        if (taxType === "VAT Inclusive") {
            let priceWithoutTax = unitPrice / 1.16;
            taxAmount = (unitPrice - priceWithoutTax) * quantity;
            totalPrice = unitPrice * quantity;
        } else if (taxType === "VAT Exclusive") {
            taxAmount = unitPrice * 0.16 * quantity;
            totalPrice = (unitPrice * quantity) + taxAmount;
        } else {
            taxAmount = 0;
            totalPrice = unitPrice * quantity;
        }

        subtotal += unitPrice * quantity;
        totalTax += taxAmount;
        finalTotal += totalPrice;

        row.querySelector(".taxAmount").textContent = formatCurrency(taxAmount);
        row.querySelector(".totalPrice").textContent = formatCurrency(totalPrice);
    });

    document.getElementById("subtotal").textContent = formatCurrency(subtotal);
    document.getElementById("totalTax").textContent = formatCurrency(totalTax);
    document.getElementById("finalTotal").textContent = formatCurrency(finalTotal);

    document.getElementById("subtotalValue").value = subtotal.toFixed(2);
    document.getElementById("totalTaxValue").value = totalTax.toFixed(2);
    document.getElementById("finalTotalValue").value = finalTotal.toFixed(2);
}

// Prepare invoice
function prepareInvoiceData() {
    const rows = document.querySelectorAll("#invoiceBody tr");
    if (rows.length === 0) {
        Swal.fire("Error", "Please add at least one invoice item before saving.", "error");
        return false;
    }

    for (let row of rows) {
        if (!validateRow(row)) {
            Swal.fire("Error", "Please fill all required fields correctly before submitting.", "error");
            row.scrollIntoView({ behavior: "smooth", block: "center" });
            return false;
        }
    }

    const items = [];
    rows.forEach(row => {
        let itemName = row.querySelector(".itemName").value;
        const otherInput = row.querySelector(".otherInput").value.trim();
        if (itemName === "Others" && otherInput) {
            itemName = `Others - ${otherInput}`;
        }

        items.push({
            item_name: itemName,
            description: row.querySelector(".description").value,
            unit_price: parseFloat(row.querySelector(".unitPrice").value) || 0,
            quantity: parseInt(row.querySelector(".quantity").value) || 0,
            tax_type: row.querySelector(".taxType").value,
            tax_amount: parseFloat(row.querySelector(".taxAmount").textContent.replace(/,/g, "")) || 0,
            total_price: parseFloat(row.querySelector(".totalPrice").textContent.replace(/,/g, "")) || 0
        });
    });

    document.getElementById("invoiceItems").value = JSON.stringify(items);
    return true;
}

// Init
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("invoiceNumber").value = generateInvoiceNumber();
    const invoiceDateInput = document.getElementById("invoiceDate");
    const dateDueInput = document.getElementById("dateDue");
    const today = new Date().toISOString().split("T")[0];
    invoiceDateInput.setAttribute("min", today);

    invoiceDateInput.addEventListener("change", function () {
        const invoiceDate = new Date(this.value);
        if (isNaN(invoiceDate)) return;
        const dueDate = new Date(invoiceDate);
        dueDate.setDate(dueDate.getDate() + 2);
        dateDueInput.value = dueDate.toISOString().split("T")[0];
    });

    addRow();
});
</script>

<style>
/* Disabled button style */
.btn-disabled {
    opacity: 0.6;
    cursor: not-allowed !important;
}
</style>


<style>
/* Greyed-out button style */
.btn-disabled {
  opacity: 0.6;
  cursor: not-allowed !important;
}
</style>

</body>

</html>