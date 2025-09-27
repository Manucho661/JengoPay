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
                    <div class="row">
                        <?php
                            include_once 'processes/encrypt_decrypt_function.php';
                            if(isset($_GET['add_single_unit']) && !empty($_GET['add_single_unit'])) {
                                $id = $_GET['add_single_unit'];
                                $id = encryptor('decrypt', $id);
                            try{
                                if(!empty($id)) {
                                    $select = "SELECT * FROM buildings WHERE id =:id";
                                    $stmt = $conn->prepare($select);
                                    $stmt->execute(array(':id' => $id));

                                    while ($row = $stmt->fetch()) {
                                      $building_name = $row['building_name'];
                                      $county = $row['county'];
                                      $constituency = $row['constituency'];
                                      $ward = $row['ward'];
                                      $structure_type = $row['structure_type'];
                                      $floors_no = $row['floors_no'];
                                      $no_of_units = $row['no_of_units'];
                                      $building_type = $row['building_type'];
                                      $tax_rate = $row['tax_rate'];
                                      $ownership_info = $row['ownership_info'];
                                      $first_name = $row['first_name'];
                                      $last_name = $row['last_name'];
                                      $id_number = $row['id_number'];
                                      $primary_contact = $row['primary_contact'];
                                      $other_contact = $row['other_contact'];
                                      $owner_email = $row['owner_email'];
                                      $postal_address = $row['postal_address'];
                                      $entity_name = $row['entity_name'];
                                      $entity_phone = $row['entity_phone'];
                                      $entity_phoneother = $row['entity_phoneother'];
                                      $entity_email = $row['entity_email'];
                                      $entity_rep = $row['entity_rep'];
                                      $rep_role = $row['rep_role'];
                                      $entity_postal = $row['entity_postal'];
                                      $ownership_proof = $row['ownership_proof'];
                                      $title_deed = $row['title_deed'];
                                      $legal_document = $row['legal_document'];
                                      $utilities = $row['utilities'];
                                      $photo_one = $row['photo_one'];
                                      $photo_two = $row['photo_two'];
                                      $photo_three = $row['photo_three'];
                                      $photo_four = $row['photo_four'];
                                      $added_on = $row['added_on'];
                                      $ownership_proof = $row['ownership_proof'];
                                      $title_deed = $row['title_deed'];
                                      $legal_document = $row['legal_document'];
                                      $photo_one = $row['photo_one'];
                                      $photo_two = $row['photo_two'];
                                      $photo_three = $row['photo_three'];
                                      $photo_four = $row['photo_four'];
                                  }
                              } else {
                                echo "<script>
                                Swal.fire({
                                  icon: 'error',
                                  title: 'No Information!',
                                  text: 'No Building Information could be Extracted from the Database',
                                  confirmButtonColor: '#cc0001'
                                  });
                                  </script>";
                              }
                            }catch(PDOException $e){
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to Load Building Information. " . addslashes($e->getMessage()) . "',
                                    confirmButtonColor: '#cc0001'
                                    });
                                    </script>";
                                }
                            }

                            //if the Submit button is clicked
                            if(isset($_POST['submit_unit'])) {
                                //Check for duplicate unit_number + building_link and avoid double entry of information
                                try{
                                    $check = $conn->prepare("SELECT COUNT(*) FROM single_units WHERE unit_number = :unit_number AND building_link = :building_link");
                                    $check->execute([
                                        ':unit_number'   => $_POST['unit_number'],
                                        ':building_link' => $_POST['building_link']
                                    ]);

                                    if ($check->fetchColumn() > 0) {
                                        // Check if Duplicate found
                                        echo "
                                        <script>
                                            Swal.fire({
                                                title: 'Warning!',
                                                text: 'No double submission of data: this unit already exists in the Database.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                window.history.back();
                                            });
                                        </script>";
                                        exit;
                                    }
                                    // Start transaction
                                    $conn->beginTransaction();

                                    //Insert into single_units
                                    $stmt = $conn->prepare("INSERT INTO single_units (structure_type, first_name, last_name, owner_email, entity_name, entity_phone, entity_phoneother, entity_email, unit_number, purpose, building_link, location, monthly_rent, occupancy_status, created_at) VALUES (:structure_type, :first_name, :last_name, :owner_email, :entity_name, :entity_phone, :entity_phoneother, :entity_email, :unit_number, :purpose, :building_link, :location, :monthly_rent, :occupancy_status, NOW())");

                                    $stmt->execute([
                                        ':structure_type' => $_POST['structure_type'],
                                        ':first_name' => $_POST['first_name'],
                                        ':last_name' => $_POST['last_name'],
                                        ':owner_email' => $_POST['owner_email'],
                                        ':entity_name' => $_POST['entity_name'],
                                        ':entity_phone' => $_POST['entity_phone'],
                                        ':entity_phoneother' => $_POST['entity_phoneother'],
                                        ':entity_email' => $_POST['entity_email'],
                                        ':unit_number' => $_POST['unit_number'],
                                        ':purpose' => $_POST['purpose'],
                                        ':building_link' => $_POST['building_link'],
                                        ':location' => $_POST['location'],
                                        ':monthly_rent' => $_POST['monthly_rent'],
                                        ':occupancy_status' => $_POST['occupancy_status']
                                    ]);

                                    // Get inserted unit_id from single units. This will be used to initiate recurring bills on the foreign key unit_id
                                        $unit_id = $conn->lastInsertId();

                                    //Insert the Bills of the Unit into single_unit_bills
                                    if (!empty($_POST['bill'])) {
                                        $stmtBill = $conn->prepare("INSERT INTO single_unit_bills (unit_id, bill, qty, unit_price, subtotal, created_at) VALUES (:unit_id, :bill, :qty, :unit_price, :subtotal, NOW())");

                                        foreach ($_POST['bill'] as $i => $billName) {
                                            if ($billName != "") {
                                                $qty       = $_POST['qty'][$i] ?? 0;
                                                $unitPrice = $_POST['unit_price'][$i] ?? 0;
                                                $subtotal  = $qty * $unitPrice;

                                                $stmtBill->execute([
                                                    ':unit_id'    => $unit_id,
                                                    ':bill'       => $billName,   // ✅ match placeholder
                                                    ':qty'        => $qty,
                                                    ':unit_price' => $unitPrice,
                                                    ':subtotal'   => $subtotal
                                                ]);
                                            }
                                        }
                                    }
                                    $conn->commit();

                                    //SweetAlert success and redirect
                                    echo "
                                    <script>
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Unit information and bills saved successfully.',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.location.href = 'single_units.php';
                                        });
                                    </script>";
                                    exit;
                                } catch (PDOException $e) {
                                    $conn->rollBack();
                                    // SweetAlert error
                                    echo "
                                    <script>
                                        Swal.fire({
                                            title: 'Error!',
                                            text: '". addslashes($e->getMessage()) ."',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    </script>";
                                    exit;
                                }
                            }
                    
                        ?>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box shadow">
                                        <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="bi bi-building"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Building</span>
                                            <span class="info-box-number"><?= htmlspecialchars($building_name) ;?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box shadow">
                                        <span class="info-box-icon" style="background-color:#00192D; color: #fff;"><i class="bi bi-houses"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Structure Type</span>
                                            <span class="info-box-number"><?= htmlspecialchars($structure_type) ;?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box shadow">
                                        <span class="info-box-icon" style="background-color:#00192D; color: #fff;"><i class="bi bi-house-exclamation"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Building Type</span>
                                            <span class="info-box-number"><?= htmlspecialchars($building_type) ;?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box shadow">
                                        <span class="info-box-icon" style="background-color:#00192D; color: #fff;"><i class="bi bi-table"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Number of Units</span>
                                            <span class="info-box-number"><?= htmlspecialchars($no_of_units) ;?></span>
                                        </div>
                                    </div>
                                </div>
                            </div> <hr>
                            <div class="card shadow">
                                <div class="card-header" style="background-color: #00192D; color:#fff;">
                                    <p>Add Unit (<?= htmlspecialchars($building_name);?>)</p>
                                </div>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="structure_type" value="<?= htmlspecialchars($structure_type);?>">
                                    <input type="hidden" name="first_name" value="<?= htmlspecialchars($first_name);?>">
                                    <input type="hidden" name="last_name" value="<?= htmlspecialchars($last_name);?>">
                                    <input type="hidden" name="owner_email" value="<?= htmlspecialchars($owner_email);?>">
                                    <input type="hidden" name="entity_name" value="<?= htmlspecialchars($entity_name);?>">
                                    <input type="hidden" name="entity_phone" value="<?= htmlspecialchars($entity_phone);?>">
                                    <input type="hidden" name="entity_phoneother" value="<?= htmlspecialchars($entity_phoneother);?>">
                                    <input type="hidden" name="entity_email" value="<?= htmlspecialchars($entity_email);?>">
                                    <div class="card-body">
                                        <div class="card shadow" id="firstSection" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                                <b>Unit Identification</b>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Unit Number</label>
                                                            <input type="text" name="unit_number" required class="form-control" id="unit_number" placeholder="Unit Number">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Purpose</label>
                                                            <select name="purpose" id="purpose" class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                                <option value="" selected hidden>-- Select Option -- </option>
                                                                <option value="Office">Office</option>
                                                                <option value="Residential">Residential</option>
                                                                <option value="Business">Business</option>
                                                                <option value="Store">Store</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Link to the Building</label>
                                                            <input type="text" name="building_link" class="form-control" value="<?= htmlspecialchars($building_name);?>" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Location with in the Building</label>
                                                            <input name="location" type="text" class="form-control" id="location" placeholder="Location e.g.Second Floor">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-sm next-btn" id="firstSectionNexttBtn">Next</button>
                                            </div>
                                        </div>

                                        <div class="card shadow" id="secondSection" style="border:1px solid rgb(0,25,45,.2); display:none;">
                                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                                <b>Financials and Other Information</b>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Monthly Rent</label>
                                                    <input type="number" class="form-control" id="monthly_rent" name="monthly_rent" placeholder="Monthly Rent">
                                                </div>
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">Recurring Bills</div>
                                                    <div class="card-body">
                                                        <table id="expensesTable" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Bill</th>
                                                                    <th>Qty</th>
                                                                    <th>Unit Price</th>
                                                                    <th>Subtotal</th>
                                                                    <th>Options</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Rows will be added here dynamically -->
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td>Total</td>
                                                                    <td id="totalQty">0</td>
                                                                    <td id="totalUnitPrice">0.00</td>
                                                                    <td id="totalSubtotal">0.00</td>
                                                                    <td></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <button type="button" class="btn btn-sm shadow" style="border:1px solid #00192D; color:#00192D;" onclick="addRow()">+ Add Row</button>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Occupancy Status</label>
                                                    <select name="occupancy_status" id="occupancy_status" required class="form-control">
                                                        <option value="" selected hidden>-- Select Status --</option>
                                                        <option value="Occupied">Occupied</option>
                                                        <option value="Vacant">Vacant</option>
                                                        <option value="Under Maintenance">Under Maintenance</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button class="btn btn-sm" id="secondSectionBackBtn" type="button" style="background-color:#00192D; color:#fff;">Go Back</button>
                                                <button class="btn btn-sm" type="submit" name="submit_unit" style="background-color:#00192D; color: #fff;"><i class="bi bi-send"></i> Submit</button
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>

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
</body>

</html>
