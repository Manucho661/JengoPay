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
            section class="content">
        <div class="container-fluid">
          <!-- if the Rent Single Unit Button is Clicked -->
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
            ?>
          <!-- Get Some Details About the Unit and make Cards for it -->
          <div class="card shadow-sm">
            <div class="card-header">
              <b>Overview</b>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                    class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                    <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i
                        class="fas fa-home"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Unit No</span>
                      <span class="info-box-number"><?= htmlspecialchars($unit_number); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                    <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i
                        class="fa fa-table"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Unit Floor</span>
                      <span class="info-box-number"><?= htmlspecialchars($location);?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                    <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i
                        class="fas fa-building"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Building</span>
                      <span class="info-box-number"><?= htmlspecialchars($building_link) ;?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                    <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i
                        class="fas fa-hotel"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Rental Purpose</span>
                      <span class="info-box-number"><?= htmlspecialchars($purpose) ;?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- A Simple Callout telling you to type important information -->
          <div class="callout callout-danger shadow" id="callOutSection">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"
              id="closeCallOut">&times;</button>
            <p style="font-weight:bold;"><span
                style="background-color:#cc0001; color:#fff; padding:3px; border-radius:4px;">Add Tenant!</span> Enter
              All the Required Relevant Tenant Details in Order to Rent out this
              Unit.</p>
          </div>
          <!-- Form Start -->
          <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
            <!-- Tenant Personal Information -->
            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
              <div class="card-header" style="background-color: #00192D; color:#fff;">Personal Information</div>
              <div class="card-body">
                <!-- Name Details -->
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="first_name">First Name</label>
                      <input type="text" id="first_name" name="first_name" required class="form-control"
                        placeholder="First Name">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="middle_name">Middle Name</label>
                      <input type="text" id="middle_name" name="middle_name" required class="form-control"
                        placeholder="Middle Name">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="last_name">Last Name</label>
                      <input type="text" id="last_name" name="last_name" required class="form-control"
                        placeholder="Last Name">
                    </div>
                  </div>
                </div>
                <!-- Contact Details -->
                <div class="row">
                  <div class="col-md-3">
                    <div class="for-group">
                      <label for="main_contact" class="form-label">Main Contact</label>
                      <input id="main_contact" type="tel" name="main_contact" class="form-control"
                        placeholder="Enter phone number" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="alt_contact" class="form-label">Alternative Contact</label>
                      <input id="alt_contact" type="tel" name="alt_contact" class="form-control"
                        placeholder="Alternative phone number">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <div class="input-group">
                        <input type="email" id="email" name="email" required class="form-control" placeholder="Email">
                      </div>
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
                  </div>
                </div>
                <!-- National ID Section -->
                <div id="nationalIdSection" class="popup" style="display:none;">
                  <label for="nationalId">National ID Number:</label>
                  <input type="text" id="nationalId" class="form-control" placeholder="ID Number" name="id_no"
                    pattern="[0-9]{6,10}">
                  <div id="nationalIdError" class="error text-danger small"></div>
                  <hr>
                  <button type="button" onclick="closeId();" class="btn btn-sm btn-outline-dark">OK</button>
                </div>

                <!-- Passport Section -->
                <div id="passportPopup" class="popup" style="display:none;">
                  <label for="passportNumber">Enter Passport Number:</label>
                  <input type="text" id="passportNumber" class="form-control" placeholder="Passport Number"
                    name="pass_no" pattern="[A-Z0-9]{5,15}">
                  <div id="passportError" class="error text-danger small"></div>
                  <button type="button" onclick="closePassport();"
                    class="btn btn-sm mt-1 btn-outline-danger">OK</button>
                </div>
              </div>

            </div>
            <!-- Security Deposits Information -->
            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
              <div class="card-header" style="background-color: #00192D; color:#fff;">Security Deposits</div>
              <div class="card-body">
                <table id="paymentTable" class="table table-bordered">
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
                <button type="button" onclick="addDepositRow()" class="btn btn-sm"
                  style="background-color:#00192D; color: #fff;"><i class="bi bi-plus"></i> Add More</button>
              </div>
            </div>
            <!-- Leasing Information -->
            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
              <div class="card-header" style="background-color: #00192D; color:#fff;">Leasing Information</div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="leasingPeriod">Leasing Period (In Months)</label>
                      <input type="number" id="leasingPeriod" required class="form-control" name="leasing_period">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="leasingStart">Leasing Starts On</label>
                      <input type="date" id="leasingStart" required class="form-control" name="leasing_start_date">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="leasingEnd">Leasing Ends On</label>
                      <input type="date" id="leasingEnd" readonly class="form-control" name="leasing_end_date">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="moveIn">Move In Date</label>
                      <input type="date" id="moveIn" required class="form-control" name="move_in_date">
                    </div>
                    <div class="form-group">
                      <label for="moveOut">Move Out Date</label>
                      <input type="date" id="moveOut" readonly class="form-control" name="move_out_date">
                    </div>
                    <div class="form-group">
                      <label for="account_no">Unit Number</label>
                      <input type="text" id="account_no" name="account_no" required class="form-control"
                        value="<?= htmlspecialchars($unit_number); ?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-2"></div>
                </div>
              </div>
            </div>
            <!-- Uploads Information -->
            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
              <div class="card-header" style="background-color: #00192D; color:#fff;">Uploads</div>
              <div class="card-body">
                <div class="form-group">
                  <label for="id_upload">Identification Upload</label>
                  <input type="file" id="id_upload" required name="id_upload" class="form-control"
                    accept=".jpg,.jpeg,.png,.pdf" onchange="previewIdUpload(this)">
                </div>
                <div id="idPreview" style="margin-top:10px; display:none;"></div>
                <div class="form-group">
                  <label for="tax_pin_copy">TAX PIN Upload</label>
                  <input type="file" id="tax_pin_copy" name="tax_pin_copy" required class="form-control"
                    accept=".jpg,.jpeg,.png,.pdf" onchange="previewTaxPinCopy(this)">
                </div>
                <div id="taxPinPreview" style="margin-top:10px; display:none;"></div>
                <div class="form-group">
                  <label for="rental_agreement">Rental Agreement Upload</label>
                  <input type="file" id="rental_agreement" required name="rental_agreement" class="form-control"
                    accept="application/pdf" onchange="previewPDF(this)">
                </div>
                <div id="pdfPreview" style="margin-top:10px; display:none;">
                  <iframe class="card shadow" id="pdfFrame"
                    style="width:100%; height:400px; border:1px solid #00192D;"></iframe>
                </div>
              </div>
            </div>
            <!-- Source of Income -->
            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
              <div class="card-header" style="background-color: #00192D; color:#fff;">Source of Income</div>
              <div class="card-body text-center">
                <label>Main Source of Income</label>
                <div class="row">
                  <div class="col-md-4">
                    <input type="radio" id="incomeFormal" name="income" value="formal"> <label for="incomeFormal">Formal
                      Employment</label>
                  </div>
                  <div class="col-md-4">
                    <input type="radio" id="incomeCasual" name="income" value="casual"> <label for="incomeCasual">Casual
                      Employment</label>
                  </div>
                  <div class="col-md-4">
                    <input type="radio" id="incomeBusiness" name="income" value="business"> <label
                      for="incomeBusiness">Business</label>
                  </div>
                </div>
                <!-- Formal -->
                <div id="formalPopup" class="popup" style="display:none;">
                  <p>Specify Job Title &amp; Location:</p>
                  <input type="text" id="formalWork" class="form-control" name="job_title" placeholder="Job Title">
                  <input type="text" id="formalWorkLocation" class="form-control" name="job_location"
                    placeholder="Job Location">
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
                  <input type="text" id="businessName" class="form-control" name="business_name"
                    placeholder="Business Name">
                  <input type="text" id="businessLocation" class="form-control" name="business_location"
                    placeholder="Location">
                  <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
                </div>
                <input type="hidden" name="status" value="Active">
                <input type="hidden" name="building" value="<?= htmlspecialchars($building_link) ;?>">
              </div>
              <div class="card-footer text-right">
                <button type="submit" name="submit" class="btn btn-sm" style="background-color: #00192D; color: #fff;">
                  <i class="bi bi-check2-all"></i> Submit
                </button>
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

        <?php
        if(isset($_POST['submit'])) {
          $tm = md5(time()); // Unique prefix for uploaded files

          //Files Uploads Handling
          function uploadFile($fileKey, $tm) {
            if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
              $name = basename($_FILES[$fileKey]['name']);
              $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $name); // sanitize
              $destination = "all_uploads/" . $tm . "_" . $safeName;
              move_uploaded_file($_FILES[$fileKey]['tmp_name'], $destination);
              return $destination;
            }
            return null;
          }
          $id_upload_destination = uploadFile('id_upload', $tm);
          $tax_pin_copy_destination = uploadFile('tax_pin_copy', $tm);
          $rental_agreement_destination = uploadFile('rental_agreement', $tm);

          //Collect Form Data
          $first_name = trim($_POST['first_name'] ?? null);
          $middle_name = trim($_POST['middle_name'] ?? null);
          $last_name = trim($_POST['last_name'] ?? null);
          $main_contact = trim($_POST['main_contact'] ?? null);
          $alt_contact = trim($_POST['alt_contact'] ?? null);
          $email = trim($_POST['email'] ?? null);
          $idMode = trim($_POST['idMode'] ?? null);
          $id_no = trim($_POST['id_no'] ?? null);
          $pass_no = trim($_POST['pass_no'] ?? null);
          $leasing_period = trim($_POST['leasing_period'] ?? null);
          $leasing_start_date = trim($_POST['leasing_start_date'] ?? null);
          $leasing_end_date = trim($_POST['leasing_end_date'] ?? null);
          $move_in_date = trim($_POST['move_in_date'] ?? null);
          $move_out_date = trim($_POST['move_out_date'] ?? null);
          $account_no = trim($_POST['account_no'] ?? null);
          $income = trim($_POST['income'] ?? null);
          $job_title = trim($_POST['job_title'] ?? null);
          $job_location = trim($_POST['job_location'] ?? null);
          $casual_job = trim($_POST['casual_job'] ?? null);
          $business_name = trim($_POST['business_name'] ?? null);
          $business_location = trim($_POST['business_location'] ?? null);
          $status = trim($_POST['status'] ?? null);
          $building = trim($_POST['building'] ?? null);

          try {
            //Check if the Unit No. is occupied by checking on the status of the tenant
            $checkUnitNoStatus = $conn->prepare("SELECT COUNT(*) FROM tenants WHERE account_no = :account_no AND status = :status");
            $checkUnitNoStatus->execute([
              ':account_no' => $account_no,
              ':status' => 'Active'
            ]);
            $noSavingActive = $checkUnitNoStatus->fetchColumn();

            //Check if the tenant information within the same building exists to avoid double registration
            $checkTenant = $conn->prepare("SELECT COUNT(*) FROM tenants WHERE account_no =:account_no AND main_contact =:main_contact AND alt_contact =:alt_contact AND email =:email AND building =:building");
            $checkTenant->execute([
              ':account_no'   => $account_no,
              ':main_contact' => $main_contact,
              ':alt_contact'  => $alt_contact,
              ':email'        => $email,
              ':building'     => $building
            ]);
            $noDuplicateTenant  = $checkTenant->fetchColumn();

            if($noSavingActive > 0) {
              //No Double Renting of the Unit if it is Occupied
              echo "<script>
                      Swal.fire({
                        icon: 'warning',
                        title: 'Occupied',
                        text: 'This Unit is Already Occupied. Double Renting Not Allowed.',
                        confirmButtonColor: '#00192D'
                      });
                    </script>";
            } else if ($noDuplicateTenant > 0) {
              // No Duplication of Tenant Data within the same building
              echo "<script>
                      Swal.fire({
                        icon: 'warning',
                        title: 'Duplicate',
                        text: 'Some Tenant information within the Same Building Alredy Registered. Please Provide Accurate Data',
                        confirmButtonColor: '#00192D'
                      });
                    </script>";
            } else {
              //Submit Data into the Database if all the Above Exceptions are Met
              $insert = $conn->prepare("INSERT INTO tenants (first_name, middle_name, last_name, main_contact, alt_contact, email, idMode, id_no, pass_no, leasing_period, leasing_start_date, leasing_end_date, move_in_date, move_out_date, account_no, id_upload, tax_pin_copy, rental_agreement, income, job_title, job_location, casual_job, business_name, business_location, status, building, added_on) VALUES (:first_name, :middle_name, :last_name, :main_contact, :alt_contact, :email, :idMode, :id_no, :pass_no, :leasing_period, :leasing_start_date, :leasing_end_date, :move_in_date, :move_out_date, :account_no, :id_upload, :tax_pin_copy, :rental_agreement, :income, :job_title, :job_location, :casual_job, :business_name, :business_location, :status, :building, NOW())");
              $insert->execute([
              ':first_name'         => $first_name,
              ':middle_name'        => $middle_name,
              ':last_name'          => $last_name,
              ':main_contact'       => $main_contact,
              ':alt_contact'        => $alt_contact,
              ':email'              => $email,
              ':idMode'             => $idMode,
              ':id_no'              => $id_no,
              ':pass_no'            => $pass_no,
              ':leasing_period'     => $leasing_period,
              ':leasing_start_date' => $leasing_start_date,
              ':leasing_end_date'   => $leasing_end_date,
              ':move_in_date'       => $move_in_date,
              ':move_out_date'      => $move_out_date,
              ':account_no'         => $account_no,
              ':id_upload'          => $id_upload_destination,
              ':tax_pin_copy'       => $tax_pin_copy_destination,
              ':rental_agreement'   => $rental_agreement_destination,
              ':income'             => $income,
              ':job_title'          => $job_title,
              ':job_location'       => $job_location,
              ':casual_job'         => $casual_job,
              ':business_name'      => $business_name,
              ':business_location'  => $business_location,
              ':status'             => $status,
              ':building'           => $building
            ]);

              $tenant_id = $conn->lastInsertId();

              if (!empty($_POST['deposit_for'])) {
              $stmtDep = $conn->prepare("INSERT INTO tenant_deposits (tenant_id, deposit_for, deposit_for_other, required_pay, amount_paid, balance, subtotal, created_at) VALUES (:tenant_id, :deposit_for, :deposit_for_other, :required_pay, :amount_paid, :balance, :subtotal, NOW())");
               foreach ($_POST['deposit_for'] as $i => $deposit_for) {
                $stmtDep->execute([
                  ':tenant_id' => $tenant_id,
                  ':deposit_for' => $deposit_for,
                  ':deposit_for_other' => $_POST['deposit_for_other'][$i] ?? null,
                  ':required_pay' => $_POST['required_pay'][$i] ?? 0,
                  ':amount_paid' => $_POST['amount_paid'][$i] ?? 0,
                  ':balance' => $_POST['balance'][$i] ?? 0,
                  ':subtotal' => $_POST['subtotal'][$i] ?? 0
                ]);
               }
            }
            echo "<script>
                    Swal.fire({
                    icon: 'success',
                    title: 'Tenant Saved Successfully!',
                    text: 'Tenant information and deposits were added successfully.',
                    confirmButtonColor: '#00192D'
                    }).then(() => {
                    window.location.href = 'all_tenants.php'; // redirect after confirmation
                    });
                  </script>";

            }
          } catch (Exception $e) {
            echo "<script>
                  Swal.fire({
                  icon: 'error',
                  title: 'Error Saving Tenant',
                  text: '" . addslashes($e->getMessage()) . "',
                  confirmButtonColor: '#00192D'
                  });
              </script>";
          }
        }
      ?>
  <script>
  // ==================== Security Deposits Table ====================
  // Handles dynamic addition/removal of deposit rows and updates totals in the table.
  function addDepositRow() {
    const tbody = document.querySelector("#paymentTable tbody");
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>
      <select class="form-control depositForSelect" name="deposit_for[]">
        <option value="" disabled selected>Select option</option>
        <option value="Rent">Rent</option>
        <option value="Water">Water</option>
        <option value="Internet">Internet</option>
        <option value="Garbage">Garbage</option>
        <option value="Security">Security</option>
        <option value="Management Fee">Management Fee</option>
        <option value="Wellfare">Wellfare</option>
        <option value="Others">Others</option>
      </select>
      <input type="text" class="form-control depositForOther mt-2"
             name="deposit_for_other[]" style="display:none;" placeholder="Please specify...">
    </td>
    <td><input type="number" class="form-control requiredPay" name="required_pay[]" value="0" min="0"></td>
    <td><input type="number" class="form-control amountPaid" name="amount_paid[]" value="0" min="0"></td>

    <!-- Balance column -->
    <td class="balance">
      <input type="hidden" name="balance[]" value="0">0
    </td>

    <!-- Subtotal column -->
    <td class="subTotal">
      <input type="hidden" name="subtotal[]" value="0">0
    </td>

    <td>
      <button type="button" class="btn btn-sm removeRow"
              style="background-color:#cc0001; color:#fff;">
        <i class="bi bi-trash"></i> Remove
      </button>
    </td>
  `;

    tbody.appendChild(row);

    // Show/hide "Other" input
    const select = row.querySelector('.depositForSelect');
    const otherInput = row.querySelector('.depositForOther');
    select.addEventListener('change', function() {
      if (this.value === 'Others') {
        otherInput.style.display = '';
        otherInput.required = true;
      } else {
        otherInput.style.display = 'none';
        otherInput.required = false;
      }
      updateDepositForOptions();
    });

    // Input listeners for totals
    row.querySelectorAll(".requiredPay, .amountPaid").forEach(input => {
      input.addEventListener("input", () => {
        if (input.value < 0) input.value = 0;
        updateTableTotals();
      });
    });

    // Remove row
    row.querySelector(".removeRow").addEventListener("click", () => {
      row.remove();
      updateTableTotals();
      updateDepositForOptions();
    });

    updateTableTotals();
    updateDepositForOptions();
  }


  // Ensures deposit type options are unique across all rows
  function updateDepositForOptions() {
    const selects = document.querySelectorAll('.depositForSelect');
    // Gather all selected values except 'Others'
    const selected = Array.from(selects).map(s => s.value).filter(v => v !== 'Others');
    selects.forEach(select => {
      const currentValue = select.value;
      Array.from(select.options).forEach(option => {
        if (option.value === 'Others') {
          option.style.display = '';
        } else if (option.value === currentValue) {
          option.style.display = '';
        } else if (selected.includes(option.value)) {
          option.style.display = 'none';
        } else {
          option.style.display = '';
        }
      });
    });
  }

  //Update Table Totals
  function updateTableTotals() {
    let totalRequired = 0,
      totalPaid = 0,
      totalBalance = 0,
      totalSub = 0;

    document.querySelectorAll("#paymentTable tbody tr").forEach(row => {
      const required = parseFloat(row.querySelector(".requiredPay").value) || 0;
      const paid = parseFloat(row.querySelector(".amountPaid").value) || 0;
      const balance = Math.max(required - paid, 0);
      const sub = paid;

      // Update balance cell
      row.querySelector(".balance input").value = balance;
      row.querySelector(".balance").lastChild.nodeValue = balance;

      // Update subtotal cell
      row.querySelector(".subTotal input").value = sub;
      row.querySelector(".subTotal").lastChild.nodeValue = sub;

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
  // ==================== Popups for ID and Income ====================
  // Shows/hides popup sections for ID mode and income type selection.
  document.querySelectorAll("input[name='idMode']").forEach(radio => {
    radio.addEventListener("change", function() {
      document.getElementById("nationalIdSection").style.display = this.value === "national" ?
        "block" : "none";
      document.getElementById("passportPopup").style.display = this.value === "passport" ?
        "block" : "none";
    });
  });

  document.querySelectorAll("input[name='income']").forEach(radio => {
    radio.addEventListener("change", function() {
      document.getElementById("formalPopup").style.display = this.value === "formal" ? "block" :
        "none";
      document.getElementById("casualPopup").style.display = this.value === "casual" ? "block" :
        "none";
      document.getElementById("businessPopup").style.display = this.value === "business" ?
        "block" : "none";
    });
  });

  function closePopup() {
    // Closes all popup sections for income type selection.
    document.querySelectorAll(".popup").forEach(p => p.style.display = "none");
  }

  function closeId() {
    // Validates and closes the National ID popup section.
    const idInput = document.getElementById('nationalId');
    if (!idInput.checkValidity()) {
      document.getElementById('nationalIdError').textContent = "Please enter a valid ID number.";
      return;
    }
    document.getElementById('nationalIdSection').style.display = 'none';
  }

  function closePassport() {
    // Validates and closes the Passport popup section.
    const passInput = document.getElementById('passportNumber');
    if (!passInput.checkValidity()) {
      document.getElementById('passportError').textContent = "Please enter a valid Passport number.";
      return;
    }
    document.getElementById('passportPopup').style.display = 'none';
  }

  // ==================== Leasing Dates ====================
  // ==================== Leasing Dates Calculation ====================
  // Automatically calculates lease end and move-out dates based on input values.
  document.getElementById("leasingPeriod").addEventListener("input", calculateEndDate);
  document.getElementById("leasingStart").addEventListener("change", calculateEndDate);
  document.getElementById("moveIn").addEventListener("change", calculateEndDate);

  function calculateEndDate() {
    // Calculates the leasing end date and move-out date based on leasing period and start/move-in dates.
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

  // ==================== Initialization ====================
  // Adds one deposit row on page load for user convenience.
  document.addEventListener("DOMContentLoaded", () => {
    addDepositRow(); // Start with one row
    updateDepositForOptions();
  });

  //Preview the Rental Agreement Document for Accuracy Purposes
  function previewPDF(input) {
    // ==================== PDF Preview ====================
    // Previews the uploaded rental agreement PDF in an iframe for accuracy.
    const file = input.files[0];
    if (file && file.type === "application/pdf") {
      const url = URL.createObjectURL(file);
      document.getElementById("pdfFrame").src = url;
      document.getElementById("pdfPreview").style.display = "block";
    } else {
      document.getElementById("pdfPreview").style.display = "none";
      document.getElementById("pdfFrame").src = "";
    }
  }

  // ==================== ID Upload Preview ====================
  function previewIdUpload(input) {
    const file = input.files[0];
    const preview = document.getElementById("idPreview");
    if (!file) {
      preview.style.display = "none";
      preview.innerHTML = "";
      return;
    }
    const ext = file.name.split('.').pop().toLowerCase();
    if (["jpg", "jpeg", "png"].includes(ext)) {
      const url = URL.createObjectURL(file);
      preview.innerHTML =
        `<img src="${url}" alt="ID Preview" style="max-width:100%; max-height:300px; border:1px solid #00192D;" class="rounded shadow">`;
      preview.style.display = "block";
    } else if (ext === "pdf") {
      const url = URL.createObjectURL(file);
      preview.innerHTML =
        `<iframe src="${url}" style="width:100%; height:300px; border:1px solid #00192D;" class="rounded shadow"></iframe>`;
      preview.style.display = "block";
    } else {
      preview.innerHTML = "<span class='text-danger'>Unsupported file type for preview.</span>";
      preview.style.display = "block";
    }
  }


  // ==================== TAX PIN Upload Preview ====================
  function previewTaxPinCopy(input) {
    const file = input.files[0];
    const preview = document.getElementById("taxPinPreview");
    if (!file) {
      preview.style.display = "none";
      preview.innerHTML = "";
      return;
    }
    const ext = file.name.split('.').pop().toLowerCase();
    if (["jpg", "jpeg", "png"].includes(ext)) {
      const url = URL.createObjectURL(file);
      preview.innerHTML =
        `<img src="${url}" alt="TAX PIN Preview" style="max-width:100%; max-height:300px; border:1px solid #00192D;" class="rounded shadow">`;
      preview.style.display = "block";
    } else if (ext === "pdf") {
      const url = URL.createObjectURL(file);
      preview.innerHTML =
        `<iframe src="${url}" style="width:100%; height:300px; border:1px solid #00192D;" class="rounded shadow"></iframe>`;
      preview.style.display = "block";
    } else {
      preview.innerHTML = "<span class='text-danger'>Unsupported file type for preview.</span>";
      preview.style.display = "block";
    }
  }
  </script>

    </body>

    </html>
