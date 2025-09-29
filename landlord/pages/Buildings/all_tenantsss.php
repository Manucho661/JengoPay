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
                    if(isset($_GET['rent']) && !empty($_GET['rent'])) {
                        $id = $_GET['rent'];
                        $id = encryptor('decrypt', $id);
                        try{
                            if(!empty($id)) {
                                $sql = "SELECT * FROM single_units WHERE id =:id";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute(array(':id' => $id));
                                while ($row = $stmt->fetch()) {
                                    $unit_number = $row['unit_number'];
                                    $location = $row['location'];
                                    $building_link = $row['building_link'];
                                    $purpose = $row['purpose'];
                                }
                            }
                        }catch(PDOException $e){
                            //if the execution fails
                        }
                    }
                    //Process Submission of the Tenant Information into the Database
                ?>
                <div class="card shadow-sm">
                    <div class="card-header">
                        <b>Overview</b>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                    </div>
                    <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-home"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Unit No</span>
                                                <span class="info-box-number"><?= htmlspecialchars($unit_number); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fa fa-table"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Unit Floor</span>
                                                <span class="info-box-number"><?= htmlspecialchars($location);?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-building"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Building</span>
                                                <span class="info-box-number"><?= htmlspecialchars($building_link) ;?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-hotel"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Rental Purpose</span>
                                                <span class="info-box-number"><?= htmlspecialchars($purpose) ;?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="callout callout-danger shadow" id="callOutSection">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" id="closeCallOut">&times;</button>
                            <p style="font-weight:bold;"><span style="background-color:#cc0001; color:#fff; padding:3px; border-radius:4px;">Add Tenant!</span> Enter All the Required Relevant Tenant Details in Order to Rent out this Unit.</p>
                        </div>
                        <?php
                            if (isset($_POST['submit'])) {
                                
                                
                                $tm = md5(time()); // Unique prefix for uploaded files

                                // Upload files
                                $id_upload_destination = "all_uploads/" . $tm . $_FILES['id_upload']['name'];
                                move_uploaded_file($_FILES["id_upload"]["tmp_name"], $id_upload_destination);

                                $tax_pin_copy_destination = "all_uploads/" . $tm . $_FILES['tax_pin_copy']['name'];
                                move_uploaded_file($_FILES["tax_pin_copy"]["tmp_name"], $tax_pin_copy_destination);

                                $rental_agreement_destination = "all_uploads/" . $tm . $_FILES['rental_agreement']['name'];
                                move_uploaded_file($_FILES["rental_agreement"]["tmp_name"], $rental_agreement_destination);

                                try {
                                    // Check for duplicates of tenant Information
                                    $no_duplicate = "SELECT * FROM tenants WHERE main_contact = '$_POST[main_contact]' AND email = '$_POST[email]' AND id_no = '$_POST[id_no]' AND pass_no = '$_POST[pass_no]'";
                                    $stmt = $conn->prepare($no_duplicate);
                                    $stmt->execute();

                                    //No Renting the Unit Twice with in the Same Building if the Tenant Status is Active
                                    $no_double_renting = "SELECT * FROM tenants WHERE account_no = '$_POST[account_no]' AND status = '$_POST[status]' AND building ='$_POST[building]'";
                                    $result = $conn->prepare($no_double_renting);
                                    $result->execute();

                                    if ($stmt->rowCount() > 0) {
                                        echo "
                                            <script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Submission Failed',
                                                    text: 'Some Tenant Information Already Exists in the Database! Please Provide Accurate Details',
                                                    width: '600px',
                                                    padding: '0.6em',
                                                    customClass: {
                                                        popup: 'compact-swal'
                                                    },
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = 'single_units.php';
                                                    }
                                                });
                                            </script>";
                                    } else if ($result->rowCount() > 0) {
                                        echo "
                                            <script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Submission Failed',
                                                    text: 'This Unit is Already Occuped by An Active Tenant. Double Renting Not Allowed!',
                                                    width: '600px',
                                                    padding: '0.6em',
                                                    customClass: {
                                                        popup: 'compact-swal'
                                                    },
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = 'single_units.php';
                                                    }
                                                });
                                            </script>";
                                    } else {
                                        // Insert tenant
                                        $stmt = $conn->prepare("INSERT INTO tenants 
                                            (first_name, middle_name, last_name, main_contact, alt_contact, email, idMode, id_no, pass_no, leasing_period, leasing_start_date, leasing_end_date, move_in_date, move_out_date, account_no, id_upload, tax_pin_copy, rental_agreement, income, job_title, job_location, casual_job, business_name, business_location, status, building) 
                                            VALUES 
                                            (:first_name, :middle_name, :last_name, :main_contact, :alt_contact, :email, :idMode, :id_no, :pass_no, :leasing_period, :leasing_start_date, :leasing_end_date, :move_in_date, :move_out_date, :account_no, :id_upload, :tax_pin_copy, :rental_agreement, :income, :job_title, :job_location, :casual_job, :business_name, :business_location, :status, :building)                                 ");

                                        $stmt->execute([
                                            ':first_name'        => $_POST['first_name'],
                                            ':middle_name'       => $_POST['middle_name'],
                                            ':last_name'         => $_POST['last_name'],
                                            ':main_contact'      => (string) $_POST['main_contact'],
                                            ':alt_contact'       => (string) $_POST['alt_contact'],
                                            ':email'             => $_POST['email'],
                                            ':idMode'            => $_POST['idMode'],
                                            ':id_no'             => (string) $_POST['id_no'],
                                            ':pass_no'           => $_POST['pass_no'],
                                            ':leasing_period'    => (string) $_POST['leasing_period'],
                                            ':leasing_start_date'=> $_POST['leasing_start_date'],
                                            ':leasing_end_date'  => $_POST['leasing_end_date'],
                                            ':move_in_date'      => $_POST['move_in_date'],
                                            ':move_out_date'     => $_POST['move_out_date'],
                                            ':account_no'        => $_POST['account_no'],
                                            ':id_upload'         => $id_upload_destination,
                                            ':tax_pin_copy'      => $tax_pin_copy_destination,
                                            ':rental_agreement'  => $rental_agreement_destination,
                                            ':income'            => $_POST['income'],
                                            ':job_title'         => $_POST['job_title'],
                                            ':job_location'      => $_POST['job_location'],
                                            ':casual_job'        => $_POST['casual_job'],
                                            ':business_name'     => $_POST['business_name'],
                                            ':business_location' => $_POST['business_location'],
                                            ':status'            => $_POST['status'],
                                            ':building'          => $_POST['building']
                                        ]);

                                        $tenant_id = $conn->lastInsertId();

                                        // Insert deposits
                                        if (!empty($_POST['deposit_for']) && is_array($_POST['deposit_for'])) {
                                            $depositFor   = $_POST['deposit_for'];
                                            $requiredPay  = $_POST['required_pay'] ?? [];
                                            $amountPaid   = $_POST['amount_paid'] ?? [];

                                            $sqlDeposit = "INSERT INTO tenant_deposits 
                                            (tenant_id, deposit_for, required_pay, amount_paid, balance, subtotal) 
                                            VALUES (:tenant_id, :deposit_for, :required_pay, :amount_paid, :balance, :subtotal)";
                                            $stmtDep = $conn->prepare($sqlDeposit);

                                            for ($i = 0; $i < count($depositFor); $i++) {
                                                $for = trim($depositFor[$i]);
                                                if ($for === '') continue;

                                                $req  = isset($requiredPay[$i]) ? floatval($requiredPay[$i]) : 0.0;
                                                $paid = isset($amountPaid[$i]) ? floatval($amountPaid[$i]) : 0.0;
                                                $bal  = max($req - $paid, 0);

                                                $stmtDep->execute([
                                                    ':tenant_id'    => $tenant_id,
                                                    ':deposit_for'  => $for,
                                                    ':required_pay' => number_format($req, 2, '.', ''),
                                                    ':amount_paid'  => number_format($paid, 2, '.', ''),
                                                    ':balance'      => number_format($bal, 2, '.', ''),
                                                    ':subtotal'     => number_format($paid, 2, '.', '')
                                                ]);
                                            }
                                        }

                                        // Success message
                                        echo "
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: 'Tenant Information Saved Successfully!',
                                                width: '600px',
                                                padding: '0.6em',
                                                customClass: {
                                                    popup: 'compact-swal'
                                                },
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = 'all_tenants.php';
                                                }
                                            });
                                        </script>";
                                    }
                                } catch (PDOException $e) {
                                    echo "
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Database Error',
                                            text: '".addslashes($e->getMessage())."',
                                            confirmButtonText: 'Close'
                                        });
                                    </script>";
                                }
                            }
                            ?>


                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                            <!-- CSRF Protection -->
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

                            <!-- Personal Information -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Personal Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" id="first_name" name="first_name" required class="form-control" placeholder="First Name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="middle_name">Middle Name</label>
                                                <input type="text" id="middle_name" name="middle_name" required class="form-control" placeholder="Middle Name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" required class="form-control" placeholder="Last Name">
                                            </div>
                                        </div>
                                    </div> <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="main_contact">Main Contact</label> 
                                                <sup class="p-1" style="background-color:#00192D; color: #fff;">(Active WhatsApp No.)</sup>
                                                <input type="tel" id="main_contact" name="main_contact" pattern="^[0-9]{10}$" required class="form-control" placeholder="10 digit number">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="alt_contact">Alternative Contact</label>
                                                <input type="tel" id="alt_contact" name="alt_contact" pattern="^[0-9]{10}$" class="form-control" placeholder="10 digit number">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email" required class="form-control" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Identification Mode</label>   
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="radio" id="idNational" name="idMode" value="national" required> 
                                                        <label for="idNational">National ID</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="radio" id="idPassport" name="idMode" value="passport"> 
                                                        <label for="idPassport">Passport</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- National ID Section -->
                                            <div id="nationalIdSection" class="popup" style="display:none;">
                                                <label for="nationalId">National ID Number:</label>
                                                <input type="text" id="nationalId" class="form-control" placeholder="ID Number" name="id_no" pattern="[0-9]{6,10}">
                                                <div id="nationalIdError" class="error text-danger small"></div><hr>
                                                <button type="button" onclick="closeId();" class="btn btn-sm btn-outline-dark">OK</button>
                                            </div>

                                            <!-- Passport Section -->
                                            <div id="passportPopup" class="popup" style="display:none;">
                                                <label for="passportNumber">Enter Passport Number:</label>
                                                <input type="text" id="passportNumber" class="form-control" placeholder="Passport Number" name="pass_no" pattern="[A-Z0-9]{5,15}">
                                                <div id="passportError" class="error text-danger small"></div>
                                                <button type="button" onclick="closePassport();" class="btn btn-sm mt-1 btn-outline-danger">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Deposits Information -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Security Deposits</div>
                                <div class="card-body">
                                    <table id="paymentTable" class="table">
                                        <thead>
                                            <tr>
                                                <th>Deposit For</th>
                                                <th>Required Pay</th>
                                                <th>Amount Paid</th>
                                                <th>Balance</th>
                                                <th>Sub Total</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td><b>Totals</b></td>
                                                <td id="totalRequired">0</td>
                                                <td id="totalPaid">0</td>
                                                <td id="totalBalance">0</td>
                                                <td id="totalSub">0</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <button type="button" onclick="addRow()" class="btn btn-sm" style="background-color:#00192D; color: #fff;"><i class="bi bi-plus"></i> Add More</button>
                                </div>
                            </div>

                            <!-- Leasing Information -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Leasing Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="leasingPeriod">Leasing Period (In Months)</label>
                                            <input type="number" id="leasingPeriod" required class="form-control" name="leasing_period">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="leasingStart">Leasing Starts On</label>
                                            <input type="date" id="leasingStart" required class="form-control" name="leasing_start_date">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="leasingEnd">Leasing Ends On</label>
                                            <input type="date" id="leasingEnd" readonly class="form-control" name="leasing_end_date">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-8">
                                            <label for="moveIn">Move In Date</label>
                                            <input type="date" id="moveIn" required class="form-control" name="move_in_date">
                                            <label for="moveOut">Move Out Date</label>
                                            <input type="date" id="moveOut" readonly class="form-control" name="move_out_date">
                                            <label for="account_no">Unit Number</label>
                                            <input type="text" id="account_no" name="account_no" required class="form-control" value="<?= htmlspecialchars($unit_number); ?>" readonly>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Uploads -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Uploads Information</div>
                                <div class="card-body">
                                    <label for="id_upload">Identification Upload</label>
                                    <input type="file" id="id_upload" required name="id_upload" class="form-control" accept=".jpg,.jpeg,.png,.pdf"> <hr>
                                    <label for="tax_pin_copy">TAX PIN Upload</label>
                                    <input type="file" id="tax_pin_copy" name="tax_pin_copy" required class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    <label for="rental_agreement">Rental Agreement Upload</label>
                                    <input type="file" id="rental_agreement" required name="rental_agreement" class="form-control" accept=".pdf">
                                </div>
                            </div>

                            <!-- Source of Income -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Source of Income</div>
                                <div class="card-body text-center">
                                    <label>Main Source of Income</label>
                                    <div class="row">
                                        <div class="col-md-4"><input type="radio" id="incomeFormal" name="income" value="formal"> <label for="incomeFormal">Formal Employment</label></div>
                                        <div class="col-md-4"><input type="radio" id="incomeCasual" name="income" value="casual"> <label for="incomeCasual">Casual Employment</label></div>
                                        <div class="col-md-4"><input type="radio" id="incomeBusiness" name="income" value="business"> <label for="incomeBusiness">Business</label></div>
                                    </div>
                                    <!-- Formal -->
                                    <div id="formalPopup" class="popup" style="display:none;">
                                        <p>Specify Job Title & Location:</p>
                                        <input type="text" id="formalWork" class="form-control" name="job_title" placeholder="Job Title">
                                        <input type="text" id="formalWorkLocation" class="form-control" name="job_location" placeholder="Job Location">
                                        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
                                    </div>
                                    <!-- Casual -->
                                    <div id="casualPopup" class="popup" style="display:none;">
                                        <p>Please Specify:</p>
                                        <input type="text" id="casualWork" class="form-control" name="casual_job">
                                        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
                                    </div>
                                    <!-- Business -->
                                    <div id="businessPopup" class="popup" style="display:none;">
                                        <p>Business Name and Location:</p>
                                        <input type="text" id="businessName" class="form-control" name="business_name" placeholder="Business Name">
                                        <input type="text" id="businessLocation" class="form-control" name="business_location" placeholder="Location">
                                        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
                                    </div>
                                    <input type="hidden" name="status" value="Active">
                                    <input type="hidden" name="building" value="<?= htmlspecialchars($building_link) ;?>">
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="card shadow">
                                <div class="card-body text-right">
                                    <button type="submit" name="submit" class="btn btn-sm" style="background-color: #00192D; color: #fff;"><i class="bi bi-check2-all"></i> Submit</button>
                                </div>
                            </div>
                        </form>
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
// ==================== Deposits ====================
function addRow() {
  const tbody = document.querySelector("#paymentTable tbody");
  const row = document.createElement("tr");
  row.innerHTML = `
    <td><input type="text" class="form-control depositFor" name="deposit_for[]"></td>
    <td><input type="number" class="form-control requiredPay" name="required_pay[]" value="0" min="0"></td>
    <td><input type="number" class="form-control amountPaid" name="amount_paid[]" value="0" min="0"></td>
    <td class="balance"><input type="hidden" name="balance">0</td>
    <td class="subTotal"><input type="hidden" name="subtotal"0</td>
    <td><button type="button" class="btn btn-sm removeRow" style="background-color:#cc0001; color:#fff;"><i class="bi bi-trash"></i> Remove</button></td>
  `;
  tbody.appendChild(row);

  // Input listeners
  row.querySelectorAll(".requiredPay, .amountPaid").forEach(input => {
    input.addEventListener("input", () => {
      if (input.value < 0) input.value = 0;
      updateTableTotals();
    });
  });

  row.querySelector(".removeRow").addEventListener("click", () => {
    row.remove();
    updateTableTotals();
  });
  updateTableTotals();
}

function updateTableTotals() {
  let totalRequired = 0, totalPaid = 0, totalBalance = 0, totalSub = 0;
  document.querySelectorAll("#paymentTable tbody tr").forEach(row => {
    const required = parseFloat(row.querySelector(".requiredPay").value) || 0;
    const paid = parseFloat(row.querySelector(".amountPaid").value) || 0;
    const balance = Math.max(required - paid, 0);
    const sub = paid;

    row.querySelector(".balance").textContent = balance;
    row.querySelector(".subTotal").textContent = sub;

    totalRequired += required;
    totalPaid += paid;
    totalBalance += balance;
    totalSub += sub;
  });

  document.getElementById("totalRequired").textContent = totalRequired;
  document.getElementById("totalPaid").textContent = totalPaid;
  document.getElementById("totalBalance").textContent = totalBalance;
  document.getElementById("totalSub").textContent = totalSub;
}

// ==================== Popups ====================
document.querySelectorAll("input[name='idMode']").forEach(radio => {
  radio.addEventListener("change", function() {
    document.getElementById("nationalIdSection").style.display = this.value === "national" ? "block" : "none";
    document.getElementById("passportPopup").style.display = this.value === "passport" ? "block" : "none";
  });
});

document.querySelectorAll("input[name='income']").forEach(radio => {
  radio.addEventListener("change", function() {
    document.getElementById("formalPopup").style.display = this.value === "formal" ? "block" : "none";
    document.getElementById("casualPopup").style.display = this.value === "casual" ? "block" : "none";
    document.getElementById("businessPopup").style.display = this.value === "business" ? "block" : "none";
  });
});

function closePopup() {
  document.querySelectorAll(".popup").forEach(p => p.style.display = "none");
}

function closeId(){
  const idInput = document.getElementById('nationalId');
  if (!idInput.checkValidity()) {
    document.getElementById('nationalIdError').textContent = "Please enter a valid ID number.";
    return;
  }
  document.getElementById('nationalIdSection').style.display = 'none';
}

function closePassport(){
  const passInput = document.getElementById('passportNumber');
  if (!passInput.checkValidity()) {
    document.getElementById('passportError').textContent = "Please enter a valid Passport number.";
    return;
  }
  document.getElementById('passportPopup').style.display = 'none';
}

// ==================== Leasing Dates ====================
document.getElementById("leasingPeriod").addEventListener("input", calculateEndDate);
document.getElementById("leasingStart").addEventListener("change", calculateEndDate);
document.getElementById("moveIn").addEventListener("change", calculateEndDate);

function calculateEndDate() {
  const months = parseInt(document.getElementById("leasingPeriod").value) || 0;
  const startDate = new Date(document.getElementById("leasingStart").value);
  const moveInDate = new Date(document.getElementById("moveIn").value);

  if (months > 0 && !isNaN(startDate)) {
    const endDate = new Date(startDate);
    endDate.setMonth(endDate.getMonth() + months);
    const iso = endDate.toISOString().split("T")[0];
    document.getElementById("leasingEnd").value = iso;
    document.getElementById("moveOut").value = iso;
  }

  // Sync move-out with move-in + months if move-in is set
  if (months > 0 && !isNaN(moveInDate)) {
    const moveOut = new Date(moveInDate);
    moveOut.setMonth(moveOut.getMonth() + months);
    document.getElementById("moveOut").value = moveOut.toISOString().split("T")[0];
  }
}

// ==================== Init ====================
document.addEventListener("DOMContentLoaded", () => {
  addRow(); // Start with one row
});
</script>

    </body>

    </html>
