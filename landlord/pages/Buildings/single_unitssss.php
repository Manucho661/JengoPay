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

                        //Meter Readings Submission PHP Script
                        if(isset($_POST['submit_reading'])) {
                            $reading_date      = $_POST['reading_date'];
                            $unit_number       = $_POST['unit_number'];
                            $meter_type        = $_POST['meter_type'];
                            $previous_reading  = $_POST['previous_reading'];
                            $current_reading   = $_POST['current_reading'];
                            $consumption_units = $_POST['consumption_units'];
                            $consumption_cost  = $_POST['consumption_cost'];
                            $final_bill        = $_POST['final_bill'];
                            $building_id        = $_POST['building_id'];
                            $created_at        = date("Y-m-d H:i:s");

                            try{
                                //Step 1: Check for duplicate entry (unit_number + meter_type + reading_date). Avoid Double Submission of the Values
                                $check_reading = $conn->prepare("SELECT COUNT(*) FROM meter_readings WHERE unit_number = :unit_number AND meter_type = :meter_type AND reading_date = :reading_date");
                                $check_reading->execute([
                                    ':unit_number'  => $unit_number,
                                    ':meter_type'   => $meter_type,
                                    ':reading_date' => $reading_date
                                ]);
                                $reading_exists = $check_reading->fetchColumn();
                                if($reading_exists > 0) {
                                    //Duplicate found — show warning
                                    echo "<script>
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Duplicate Entry',
                                                text: 'A meter reading for this unit, meter type, and date already exists.',
                                                confirmButtonColor: '#00192D'
                                            });
                                        </script>";
                                } else {
                                    //if no duplicates then start the process of submitting the data
                                    $submit = $conn->prepare("INSERT INTO meter_readings (reading_date, unit_number, meter_type, previous_reading,current_reading, consumption_units, consumption_cost, final_bill, building_id, created_at) VALUES (:reading_date, :unit_number, :meter_type, :previous_reading, :current_reading, :consumption_units, :consumption_cost, :final_bill, :building_id, :created_at)");
                                    $submit->execute([
                                            ':reading_date'      => $reading_date,
                                            ':unit_number'       => $unit_number,
                                            ':meter_type'        => $meter_type,
                                            ':previous_reading'  => $previous_reading,
                                            ':current_reading'   => $current_reading,
                                            ':consumption_units' => $consumption_units,
                                            ':consumption_cost'  => $consumption_cost,
                                            ':final_bill'        => $final_bill,
                                            ':building_id'        => $building_id,
                                            ':created_at'        => $created_at
                                    ]);

                                    //Success Alert Message for successful submission of meter readings
                                    echo "<script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Saved!',
                                                text: 'Meter reading has been saved successfully.',
                                                confirmButtonColor: '#00192D'
                                            }).then(() => {
                                                window.location.href = 'all_meter_readings.php';
                                            });
                                        </script>";
                                }
                            }catch(PDOException $e) {
                                //Error Alert for Debugging
                                echo "<script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Failed to save meter reading. " . addslashes($e->getMessage()) . "',
                                            confirmButtonColor: '#00192D'
                                        });
                                    </script>";
                            }
                        }

                        //if change the occupancy status to Occuped
                        if (isset($_POST['update_occupied_status'])) {
                            try {
                                // Fetch current status of the unit
                                $check = $conn->prepare("SELECT occupancy_status FROM single_units WHERE id = :id");
                                $check->execute([
                                    ':id' => $_POST['id']
                                ]);
                                $current_status = $check->fetchColumn();

                                if ($current_status === $_POST['occupancy_status']) {
                                    // No change made
                                    echo "
                                        <script>
                                            Swal.fire({
                                                title: 'Warning!',
                                                text: 'Update failed. You did not change the status.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                window.history.back();
                                            });
                                        </script>";
                                } else {
                                    // Update with the new status
                                    $update = "UPDATE single_units SET occupancy_status = :occupancy_status WHERE id = :id";
                                    $stmt = $conn->prepare($update);
                                    $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                                    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                                    $stmt->execute();

                                    // Success message
                                    echo "
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: 'Occupancy status updated successfully!',
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

                        //Change the Occupancy Status of the Vacant Uit to Under Maintenance
                        if(isset($_POST['update_maintenance_status'])) {
                            try {
                                // Fetch current status of the unit
                                $check = $conn->prepare("SELECT occupancy_status FROM single_units WHERE id = :id");
                                $check->execute([
                                    ':id' => $_POST['id']
                                ]);
                                $current_status = $check->fetchColumn();

                                if ($current_status === $_POST['occupancy_status']) {
                                    // No change made
                                    echo "
                                        <script>
                                            Swal.fire({
                                                title: 'Warning!',
                                                text: 'Update failed. You did not change the status.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                window.history.back();
                                            });
                                        </script>";
                                } else {
                                    // Update with the new status
                                    $update = "UPDATE single_units SET occupancy_status = :occupancy_status WHERE id = :id";
                                    $stmt = $conn->prepare($update);
                                    $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                                    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                                    $stmt->execute();

                                    // Success message
                                    echo "
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: 'Occupancy status updated successfully!',
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

                        //Change the Status to Vacant if the Unit is Occupied
                        if(isset($_POST['update_vacant_status'])) {
                            try {
                                // Fetch current status of the unit
                                $check = $conn->prepare("SELECT occupancy_status FROM single_units WHERE id = :id");
                                $check->execute([
                                    ':id' => $_POST['id']
                                ]);
                                $current_status = $check->fetchColumn();

                                if ($current_status === $_POST['occupancy_status']) {
                                    // No change made
                                    echo "
                                        <script>
                                            Swal.fire({
                                                title: 'Warning!',
                                                text: 'Update failed. You did not change the status.',
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                window.history.back();
                                            });
                                        </script>";
                                } else {
                                    // Update with the new status
                                    $update = "UPDATE single_units SET occupancy_status = :occupancy_status WHERE id = :id";
                                    $stmt = $conn->prepare($update);
                                    $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                                    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                                    $stmt->execute();

                                    // Success message
                                    echo "
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: 'Occupancy status updated successfully!',
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
                        <div class="card">
                            <div class="card-header" style="background-color:#00192D; color: #fff;">
                                <b>Summary</b>
                                <button class="btn btn-sm" type="button" style="border:1px solid #fff; color: #fff; float: right;" data-toggle="modal" data-target="#addUnit"><i class="fa fa-plus"></i> Add Unit</button>
                            </div>
                            <div class="card-body">

                                <?php
                                    try {
                                        // Count Vacant
                                        $stmt = $conn->prepare("SELECT COUNT(*) FROM single_units WHERE occupancy_status = 'Vacant'");
                                        $stmt->execute();
                                        $vacant = $stmt->fetchColumn();

                                        // Count Occupied
                                        $stmt = $conn->prepare("SELECT COUNT(*) FROM single_units WHERE occupancy_status = 'Occupied'");
                                        $stmt->execute();
                                        $occupied = $stmt->fetchColumn();

                                        // Count Under Maintenance
                                        $stmt = $conn->prepare("SELECT COUNT(*) FROM single_units WHERE occupancy_status = 'Under Maintenance'");
                                        $stmt->execute();
                                        $maintenance = $stmt->fetchColumn();

                                    } catch (PDOException $e) {
                                        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                                    }
                                ?>
                                <div class="row">
                                    <!-- Vacant Units -->
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#cc0001; color:#fff;"><i class="bi bi-house-exclamation-fill"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Vacant Units</span>
                                                <span class="info-box-number"><?= $vacant; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#1B712F; color:#fff;"><i class="bi bi-house-lock-fill"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Occupied Units</span>
                                                <span class="info-box-number"><?= htmlspecialchars($occupied);?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-home"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Under Maintenance</span>
                                                <span class="info-box-number"><?= htmlspecialchars($maintenance) ;?></span>
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
                                                                        echo '<button class="btn btn-xs shadow" style="border:1px solid #2C9E4B; color:#2C9E4B;"><i class="fa fa-user"></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                                    } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                        echo '<button class="btn btn-xs shadow" style="border:1px solid #cc0001; color:#cc0001;"><i class="bi bi-house-exclamation"></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                                    } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                        echo '<button class="btn btn-xs shadow" style="border:1px solid #F74B00; color:#F74B00;"><i class="fa fa-calendar" ;?=""></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><i class="bi bi-calendar"></i> <?= htmlspecialchars($created_at)?></td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default btn-sm shadow" style="border:1px solid rgb(0, 25, 45 ,.3);">Action</button>
                                                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                                            <span class="sr-only">Toggle Dropdown</span>
                                                                        </button>
                                                                        <div class="dropdown-menu shadow" role="menu" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                                            <?php
                                                                            if(htmlspecialchars($occupancy_status) == 'Occupied') {
                                                                                ?>
                                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item btn" data-toggle="modal" data-target="#meterReadingModal<?= $id ;?>"><i class="bi bi-speedometer"></i> Meter Reading</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#markAsVacant<?php echo $id;?>"><i class="bi bi-house-exclamation"></i> Mark As Vacant</a>
                                                                                <?php
                                                                            } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                                ?>
                                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item" href="rent_single_unit.php?rent=<?php echo $id ;?>"><i class="bi bi-wallet"></i> Rent It</a>
                                                                                <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?php echo $id;?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#occupiedModal<?php echo $id;?>"><i class="bi bi-person-fill-check"></i> Mark as Occupied</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#underMaintenance<?php echo $id;?>"><i class="bi bi-house-gear"></i> Under Maintenance</a>
                                                                                <?php
                                                                            } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                                ?>
                                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?php echo $id;?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item btn" data-toggle="modal" data-target="#meterReadingModal<?= $id ;?>"><i class="bi bi-speedometer"></i> Meter Reading</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#occupiedModal<?php echo $id;?>"><i class="bi bi-person-fill-check"></i> Mark as Occupied</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#markAsVacant<?php echo $id;?>"><i class="bi bi-house-exclamation"></i> Mark As Vacant</a>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <!-- Meter Readings Modal -->
                                                            <div class="modal fade shadow" id="meterReadingModal<?= $id ;?>">
                                                                <div class="modal-dialog modal-md">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header" style="background-color:#00192D; color: #fff;">
                                                                            <b>Add Meter Reading for Unit <?= $unit_number;?></b>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <label>Reading Date</label>
                                                                                    <input type="date" class="form-control" name="reading_date" id="reading_date" required>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>Unit Number</label>
                                                                                            <input class="form-control unit_number" name="unit_number" value="<?= $unit_number ;?>" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>Meter Type</label>
                                                                                            <select class="form-control meter_type" name="meter_type" required>
                                                                                                <option value="" selected hidden>Meter Type</option>
                                                                                                <option value="Water">Water</option>
                                                                                                <option value="Electricity">Electricity</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> <hr>

                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>Previous Reading:</label>
                                                                                            <input type="number" name="previous_reading" placeholder="Previous Reading" required class="form-control previous_reading">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>Current Reading:</label>
                                                                                            <input type="number" name="current_reading" placeholder="Current Reading" required class="form-control current_reading">
                                                                                        </div>
                                                                                    </div>
                                                                                </div> <hr>

                                                                                <fieldset class="border p-1">
                                                                                    <legend class="w-auto" style="font-size: 18px; font-weight: bold; padding: 3px;">Calculations</legend>
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Units Consumed:</label>
                                                                                                <input class="form-control consumption_units" name="consumption_units" readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Cost Per Unit:</label>
                                                                                                <input class="form-control consumption_cost" type="text" name="consumption_cost">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>Bill</label>
                                                                                        <input class="form-control final_bill" name="final_bill" readonly type="text">
                                                                                    </div>
                                                                                    <input type="hidden" name="building_id" value="<?= $building_link;?>">
                                                                                    <input type="hidden" name="created_at">
                                                                                </fieldset>
                                                                            </div>
                                                                            <div class="modal-footer text-right">
                                                                                <button type="submit" name="submit_reading" class="btn btn-sm" style="border:1px solid #00192D;">
                                                                                    <i class="bi bi-send"></i> Submit
                                                                                </button>
                                                                            </div>
                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Mark as Occupied Modal -->
                                                            <div class="modal fade shadow" id="occupiedModal<?php echo $id;?>">
                                                                <div class="modal-dialog modal-sm">
                                                                  <div class="modal-content">
                                                                    <div class="modal-header" style="background-color:#00192D; color: #fff;">
                                                                      <p class="modal-title">Mark Unit <?= htmlspecialchars($row['unit_number']);?> as Occupied</p>
                                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                      </button>
                                                                    </div>
                                                                    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                                                        <div class="modal-body">
                                                                          <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id));?>">
                                                                          <div class="form-group">
                                                                              <label>Mark as Occuped</label>
                                                                              <select class="form-control" id="occupancy_status" name="occupancy_status">
                                                                                  <option value="<?= htmlspecialchars($occupancy_status);?>" selected hidden><?= htmlspecialchars($occupancy_status);?></option>
                                                                                  <option value="Occupied">Occupied</option>
                                                                              </select>
                                                                          </div>
                                                                        </div>
                                                                        <div class="modal-footer text-right">
                                                                          <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="update_occupied_status"><i class="bi bi-send"></i> Update</button>
                                                                        </div>
                                                                    </form>
                                                                  </div>
                                                                </div>
                                                              </div>

                                                              <!-- Mark as Vacant Modal -->
                                                              <div class="modal fade shadow" id="markAsVacant<?php echo $id;?>">
                                                                <div class="modal-dialog modal-sm">
                                                                  <div class="modal-content">
                                                                    <div class="modal-header" style="background-color:#00192D; color: #fff;">
                                                                      <b class="modal-title">Mark Unit <?= htmlspecialchars($row['unit_number']);?> as Vacant</b>
                                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                      </button>
                                                                    </div>
                                                                    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                                                        <div class="modal-body">
                                                                          <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id));?>">
                                                                          <div class="form-group">
                                                                              <label>Occupancy Status</label>
                                                                              <select type="text" class="form-control" id="occupancy_status" name="occupancy_status">
                                                                                  <option value="<?= htmlspecialchars($occupancy_status);?>" selected hidden><?= htmlspecialchars($occupancy_status);?></option>
                                                                                  <option value="Vacant">Vacant</option>
                                                                              </select>
                                                                          </div>
                                                                        </div>
                                                                        <div class="modal-footer text-right">
                                                                          <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="update_vacant_status"><i class="bi bi-send"></i> Update</button>
                                                                        </div>
                                                                    </form>
                                                                  </div>
                                                                </div>
                                                              </div>

                                                            <!-- Under Maintenance Modal -->
                                                           <div class="modal fade shadow" id="underMaintenance<?php echo $id;?>">
                                                                <div class="modal-dialog modal-sm">
                                                                  <div class="modal-content">
                                                                    <div class="modal-header" style="background-color:#00192D; color: #fff;">
                                                                      <p class="modal-title">Mark Unit <?= htmlspecialchars($row['unit_number']);?> as Under Maintenance</p>
                                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                      </button>
                                                                    </div>
                                                                    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                                                        <div class="modal-body">
                                                                          <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id));?>">
                                                                          <div class="form-group">
                                                                              <label>Mark as Under Maintenance</label>
                                                                              <select class="form-control" id="occupancy_status" name="occupancy_status">
                                                                                  <option value="<?= htmlspecialchars($occupancy_status);?>" selected hidden><?= htmlspecialchars($occupancy_status);?></option>
                                                                                  <option value="Under Maintenance">Under Maintenance</option>
                                                                              </select>
                                                                          </div>
                                                                        </div>
                                                                        <div class="modal-footer text-right">
                                                                          <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="update_maintenance_status"><i class="bi bi-send"></i> Update</button>
                                                                        </div>
                                                                    </form>
                                                                  </div>
                                                                </div>
                                                              </div>

                                                              <!-- Rent Single Unit Modal -->
                                                              <div class="modal fade shadow" id="rentUnit<?php echo $id;?>">
                                                                <div class="modal-dialog modal-lg">
                                                                  <div class="modal-content">
                                                                    <div class="modal-header" style="background-color:#00192D; color: #fff;">
                                                                      <p class="modal-title">Rent Out <?= $unit_number;?></p>
                                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                      </button>
                                                                    </div>
                                                                    <form action="" method="post" enctype="multipart/form-data" autocomplete="off"></form>
                                                                    <div class="modal-body">
                                                                      <p>Rent out this Unit&hellip;</p>
                                                                    </div>
                                                                    <div class="modal-footer text-right">
                                                                      <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;"><i class="bi bi-send"></i> Submit</button>
                                                                    </div>
                                                                    </form>
                                                                  </div>
                                                                </div>
                                                              </div>

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
<!-- Meter Readings JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="single_unit.js"></script>


</div>
</div>
</body>
</html>
