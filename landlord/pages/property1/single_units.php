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
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <?php include_once 'includes/dashboard_bradcrumbs.php';?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                    include_once 'processes/encrypt_decrypt_function.php';
                    if(isset($_POST['submit'])) {
                        try{
                                // Insert unit data
                            $stmt = $conn->prepare("
                                INSERT INTO single_units (unit_number, purpose, building_link, location, monthly_rent, occupancy_status)
                                VALUES (:unit_number, :purpose, :building_link, :location, :monthly_rent, :occupancy_status)
                                ");
                            $stmt->execute([
                                ':unit_number'      => $_POST['unit_number'],
                                ':purpose'          => $_POST['purpose'],
                                ':building_link'    => $_POST['building_link'],
                                ':location'         => $_POST['location'],
                                ':monthly_rent'     => (string) $_POST['monthly_rent'], // decimals handled as strings
                                ':occupancy_status' => $_POST['occupancy_status'],
                                ]);

                            $unitId = $conn->lastInsertId();

                                // Insert recurring expenses if available
                            if (!empty($_POST['bill'])) {
                                $stmtExp = $conn->prepare("
                                    INSERT INTO single_unit_bills (unit_id, bill, qty, unit_price)
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
                                    document.getElementById("countdown").innerHTML = "Single Unit Information Submitted Successfully! Redirecting in " + timeleft + " seconds remaining";
                                }
                                timeleft -= 1;
                                }, 1000);
                                </script>';
                            } catch(PDOException $e){
                                echo "❌ Database error: " . $e->getMessage();
                            }
                        }
                        ?>
                        <div class="card">
                            <div class="card-header" style="background-color:#00192D; color: #fff;">
                                <b>Summary</b>
                                <button class="btn btn-sm" type="button" style="border:1px solid #fff; color: #fff; float: right;" data-toggle="modal" data-target="#addUnit"><i class="fa fa-plus"></i> Add Unit</button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-home"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Single Units</span>
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
                                </div> <hr>
                                <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                    <div class="card-header" style="background-color: #00192D; color:#fff;">
                                        <b>All Single Units</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <?php
                                            try {
                                                // Fetch all units
                                                $stmt = $conn->query("SELECT * FROM single_units ORDER BY created_at DESC");
                                                $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            } catch (PDOException $e) {
                                                die("❌ Database error: " . $e->getMessage());
                                            }
                                            
                                        ?>
                                        <table class="table table-hover" id="dataTable">
                                            <thead>
                                                <th>Unit No</th>
                                                <th>Building</th>
                                                <th>Purpose</th>
                                                <th>Monthly Rent</th>
                                                <th>Occupancy Status</th>
                                                <th>Added On</th>
                                                <th>Options</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    try{
                                                        $select = "SELECT * FROM single_units";
                                                        $stmt = $conn->prepare($select);
                                                        $stmt->execute();
                                                        while($row = $stmt->fetch()){
                                                            $id = encryptor('encrypt', $row['id']);
                                                            $unit_number = $row['unit_number'];
                                                            $building_link = $row['building_link'];
                                                            $purpose = $row['purpose'];
                                                            $location = $row['location'];
                                                            $monthly_rent = $row['monthly_rent'];
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
                                                    <td>
                                                        <?php
                                                            if(htmlspecialchars($occupancy_status) == 'Occupied') {
                                                                echo '<button class="btn btn-xs" style="border:1px solid #2C9E4B; color:#2C9E4B;"><i class="fa fa-user"></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                            } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                echo '<button class="btn btn-xs" style="border:1px solid #cc0001; color:#cc0001;"><i class="bi bi-house-exclamation"></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                            } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                echo '<button class="btn btn-xs" style="border:1px solid #F74B00; color:#F74B00;"><i class="fa fa-calendar" ;?=""></i> '.htmlspecialchars($occupancy_status).'</button>';
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
                                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                            <?php
                                                                        } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                            ?>
                                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item" href="rent_single_unit.php?rent=<?php echo $id ;?>"><i class="bi bi-wallet"></i> Rent It</a>
                                                                                <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?php echo $id;?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item" href="mark_single_unit_as_occupied.php?edit=<?php echo $id;?>"><i class="bi bi-user"></i> Mark Occupied</a>
                                                                            <?php
                                                                        } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                            ?>
                                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?php echo $id;?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
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

                        </div>
                    </div>
                    <div class="modal fade shadow" id="addUnit">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <b class="modal-title">Add Unit</b>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                                        <button type="button" class="btn btn-sm" style="border:1px solid #00192D; color:#00192D;" onclick="addRow()">+ Add Row</button>
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
                                                <button class="btn btn-sm" name="submit" type="submit" style="background-color:#00192D; color:#fff;">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
</div>
</div>
</body>
</html>
