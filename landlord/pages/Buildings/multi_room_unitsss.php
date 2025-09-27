<!DOCTYPE html>
<html lang="en">
    <?php include_once 'includes/head.php';?>

    <body class="hold-transition sidebar-mini">
        <!-- Site wrapper -->
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
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <b>Overview</b>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                        <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-home"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Multi-Room Units</span>
                                            <span class="info-box-number">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                        <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Occupied Units</span>
                                            <span class="info-box-number">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                        <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-home"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Vacant Units</span>
                                            <span class="info-box-number">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    include_once 'processes/encrypt_decrypt_function.php';
                    if(isset($_POST['submit_bed_sitter_btn'])) {
                        try{
                                // Insert unit data 
                            $stmt = $conn->prepare("
                                INSERT INTO multi_rooms (unit_number, purpose, building_link, location, water_meter, monthly_rent, occupancy_status)
                                VALUES (:unit_number, :purpose, :building_link, :location, :water_meter, :monthly_rent, :occupancy_status)");
                            $stmt->execute([
                                ':unit_number'      => $_POST['unit_number'],
                                ':purpose'          => $_POST['purpose'],
                                ':building_link'    => $_POST['building_link'],
                                ':location'         => $_POST['location'],
                                ':water_meter'     => (string) $_POST['water_meter'], // decimals handled as strings
                                ':monthly_rent' => (string) $_POST['monthly_rent'],
                                ':occupancy_status' => $_POST['occupancy_status'],
                                ]);

                            $unitId = $conn->lastInsertId();

                                // Insert recurring expenses if available
                            if (!empty($_POST['bill'])) {
                                $stmtExp = $conn->prepare("
                                    INSERT INTO multi_roombills (unit_id, bill, qty, unit_price)
                                    VALUES (:unit_id, :bill, :qty, :unit_price)
                                    ");

                                foreach ($_POST['bill'] as $i => $bill) {
                                    if (!empty($bill)) {
                                        $stmtExp->execute([
                                            ':unit_id'    => $unitId,
                                            ':bill'       => $bill,
                                            ':qty'        => (int) $_POST['qty'][$i],
                                            ':unit_price' => (string) $_POST['unit_price'][$i],
                                        ]);
                                    }
                                }
                            }

                            echo '<div id="countdown" class="alert alert-success" role="alert"></div>
                            <script>
                            var timeleft = 10;
                            var downloadTimer = setInterval(function(){
                              if(timeleft <= 0){
                                clearInterval(downloadTimer);
                                window.location.href=window.location.href;
                                } else {
                                    document.getElementById("countdown").innerHTML = "Bed Seater Unit Information Submitted Successfully! Redirecting in " + timeleft + " seconds remaining";
                                }
                                timeleft -= 1;
                                }, 1000);
                                </script>';
                            } catch(PDOException $e){
                                echo "❌ Database error: " . $e->getMessage();
                            }
                        }
                        ?>
                    <!-- Container Box -->
                    <div class="card shadow">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <b>All Multi Room Units</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button class="btn btn-sm" style="border: 1px solid #00192D; color:#00192D; font-weight:bold;" data-toggle="modal" data-target="#addUnitModal"><i class="fas fa-home"></i> Add Unit</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable">
                                            <thead>
                                                <th>Unit No</th>
                                                <th>Building</th>
                                                <th>Purpose</th>
                                                <th>Monthly Rent</th>
                                                <th>Water Meter</th>
                                                <th>Occupancy Status</th>
                                                <th>Added On</th>
                                                <th>Options</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    try{
                                                        $select = "SELECT * FROM multi_rooms";
                                                        $stmt = $conn->prepare($select);
                                                        $stmt->execute();
                                                        while($row = $stmt->fetch()){
                                                            $id = encryptor('encrypt', $row['id']);
                                                            $unit_number = $row['unit_number'];
                                                            $building_link = $row['building_link'];
                                                            $purpose = $row['purpose'];
                                                            $location = $row['location'];
                                                            $monthly_rent = $row['monthly_rent'];
                                                            $water_meter = $row['water_meter'];
                                                            $occupancy_status = $row['occupancy_status'];
                                                            $created_at = $row['created_at'];
                                                        ?>
                                                            <tr>
                                                    <td><i class="bi bi-house-door"></i> <?= htmlspecialchars($unit_number)?></td>
                                                    <td><i class="bi bi-building"></i> <?= htmlspecialchars($building_link)?></td>
                                                    <td>
                                                        <?php
                                                            if(htmlspecialchars($purpose) == 'Business') {
                                                                echo '<i class="bi bi-shop"></i> ' .htmlspecialchars($purpose);
                                                            } else if (htmlspecialchars($purpose) == 'Office') {
                                                                echo '<i class="bi bi-briefcase"></i> ' .htmlspecialchars($purpose);
                                                            } else if (htmlspecialchars($purpose) == 'Residential') {
                                                                echo '<i class="bi bi-file-person"></i> ' .htmlspecialchars($purpose);
                                                            } else if (htmlspecialchars($purpose) == 'Store') {
                                                                echo '<i class="bi bi-house-gear"></i> ' .htmlspecialchars($purpose);
                                                            }   
                                                        ?>
                                                    </td>
                                                    <td><?= htmlspecialchars('Kshs.'. $monthly_rent)?></td>
                                                    <td><?= htmlspecialchars($water_meter)?></td>
                                                    <td>
                                                        <?php
                                                            if(htmlspecialchars($occupancy_status) == 'Occupied') {
                                                                echo '<button class="btn btn-sm" style="border: 1px solid #2C9E4B; color:#2C9E4B;"><i class="fa fa-user"></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                            } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                echo '<button class="btn btn-sm" style="border: 1px solid #cc0001; color:#cc0001;"><i class="bi bi-house-exclamation"></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                            } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                echo '<button class="btn btn-sm" style="border: 1px solid #F74B00; color:#F74B00;"><i class="fa fa-calendar" ;?=""></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><i class="bi bi-calendar"></i> <?= htmlspecialchars($created_at)?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                                <button type="button" class="btn btn-default btn-sm" style="border:1px solid rgb(0, 25, 45 ,.3);">Action</button>
                                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                </button>
                                                                <div class="dropdown-menu shadow" role="menu" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                                    <?php
                                                                        if(htmlspecialchars($occupancy_status) == 'Occupied') {
                                                                            ?>
                                                                                <a class="dropdown-item" href="bed_seater_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item" href="edit_bed_seater.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                            <?php
                                                                        } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                            ?>
                                                                                <a class="dropdown-item" href="bed_seater_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item" href="rent_single_unit.php"><i class="bi bi-wallet"></i> Rent It</a>
                                                                                <a class="dropdown-item" href="inspect_bed_sitter_unit.php?inspect=<?php echo $id;?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                                <a class="dropdown-item" href="edit_bed_seater.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                            <?php
                                                                        } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                            ?>
                                                                                <a class="dropdown-item" href="edit_bed_seater.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item" href="inspect_bed_sitter_unit.php?inspect=<?php echo $id;?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                                <a class="dropdown-item" href="bed_seater_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                    </td>
                                                </tr>
                                                    <?php
                                                        }
                                                    }catch(PDOException $e){
                                                        echo '<div class="alert alert-danger>
                                                            Selection Failed! "'.$e->getMessage().'"
                                                        </div>';
                                                    }
                                                ?>
                                            </tbody>                                            
                                        </table>
                            </div>
                        </div>
                    </div>
                    <!-- Container Box -->
                    <!-- Add  Bedsitter Unit Accordion -->
                    <div class="modal fade" id="addUnitModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#00192D; color:#fff;">
                                    <b class="modal-title">Add Unit</b>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="card shadow" id="firstSection" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                                <b>Unit Identification</b>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Unit Number</label>
                                                            <input type="text" class="form-control" id="unit_number" placeholder="Unit Number e.g. CH-01" name="unit_number">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Purpose</label>
                                                            <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" name="purpose">
                                                                <option value="" selected hidden>-- Select Option --
                                                                </option>
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
                                                            <select name="building_link" id="building_link" class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                                <option value="" selected hidden>-- Select to Link --</option>
                                                                <?php
                                                                $show_buildings = "SELECT * FROM buildings";
                                                                $results_show_buildings = $conn->prepare($show_buildings);
                                                                $results_show_buildings->execute();
                                                                while($row = $results_show_buildings->fetch()) {
                                                                    $building_name = $row['building_name']; 
                                                                    ?>
                                                                    <option value="<?php echo $building_name ;?>"><?php echo $building_name ;?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
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
                                            <div class="card-header" style="background-color:#00192D; color:#fff;">More Information</div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Water Meter Number</label>
                                                    <input type="number" class="form-control" id="water_meter" placeholder="Water Meter Number" name="water_meter">
                                                </div>
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
                                                        <button type="button" class="btn btn-sm" style="border:1px solid #00192D; color:#00192D;" onclick="addRow()">+ Add Row</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-danger btn-sm back-btn" id="secondSectionBackBtn">Back</button>
                                                <button type="button" class="btn btn-sm next-btn" id="secondSectionNextBtn">Next</button>
                                            </div>
                                        </div>
                                        <div class="card shadow" id="thirdSection" style="border:1px solid rgb(0,25,45,.2); display:none;">
                                            <div class="card-header" style="background-color:#00192D; color:#fff;">More Information</div>
                                            <div class="card-body">
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
                                                <button type="button" class="btn btn-danger btn-sm back-btn" id="thirdSectionBackBtn">Back</button>
                                                <button type="submit" name="submit_bed_sitter_btn" class="btn btn-sm next-btn" id="">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Help Pop Up Form -->
                <?php include_once 'includes/lower_right_popup_form.php' ;?>
            </div>

            <!-- Footer -->
            <?php include_once 'includes/footer.php';?>

        </div>

        <!-- Required Scripts -->
        <?php include_once 'includes/required_scripts.php';?>
    </body>

</html>
