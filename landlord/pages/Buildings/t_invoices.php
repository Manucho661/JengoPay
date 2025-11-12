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
                                                $tenant = $pdo->prepare("SELECT * FROM single_units WHERE id = ? ");
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

                                                if (!$pdo->inTransaction()) {
                                                    $pdo->beginTransaction();
                                                }

                                                // ===================== INSERT INTO singleunit_invoices =====================
                                                $stmt = $pdo->prepare("INSERT INTO singleunit_invoices (invoice_no, receiver, main_contact, alt_contact, email,invoice_date, due_date, notes, payment_status, subtotal, total_tax, final_total) VALUES (:invoice_no, :receiver, :main_contact, :alt_contact, :email, :invoice_date, :due_date, :notes, :payment_status, :subtotal, :total_tax, :final_total)");

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
                                                $invoiceId = $pdo->lastInsertId();

                                                // ===================== INSERT INTO singleinvoice_items =====================
                                                $items = json_decode($_POST['invoice_items'], true);

                                                if (!empty($items)) {
                                                    $stmtItem = $pdo->prepare("INSERT INTO singleinvoice_items (invoice_id, item_name, description, unit_price, quantity, tax_type, tax_amount, total_price) VALUES (:invoice_id, :item_name, :description, :unit_price, :quantity, :tax_type, :tax_amount, :total_price)");

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
                                                if ($pdo->inTransaction()) {
                                                    $pdo->commit();
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
                                                if ($pdo->inTransaction()) {
                                                    $pdo->rollBack();
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