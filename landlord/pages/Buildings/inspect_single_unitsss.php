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
                            if(isset($_GET['inspect']) && !empty($_GET['inspect'])) {
                                $id = $_GET['inspect'];
                                $id = encryptor('decrypt', $id);
                                try{
                                    if(!empty($id)){
                                        $select = "SELECT * FROM single_units WHERE id =:id";
                                        $stmt = $conn->prepare($select);
                                        $stmt->execute(array(':id' => $id));

                                        while($row = $stmt->fetch()){
                                            $unit_number = $row['unit_number'];
                                            $purpose = $row['purpose'];
                                            $building_link = $row['building_link'];
                                            $location = $row['location'];
                                            $monthly_rent = $row['monthly_rent'];
                                            $occupancy_status = $row['occupancy_status'];
                                            $created_at = $row['created_at'];
                                        }
                                    }
                                } catch(PDOException $e){
                                    //If the Query Fails to Execute
                                }
                            }

                            // Helper function
                            function clean($data) {
                                return htmlspecialchars(trim($data));
                            }
                            if($_SERVER["REQUEST_METHOD"] === "POST") {
                                $tm = md5(time()); //disable overwriting image names to have unique uploaded image names

                                $floor_photo_proof_name = $_FILES['floor_photo']['name']; //Image Name
                                $floor_photo_proof_destination = "./all_uploads/".$floor_photo_proof_name; //uploading an image
                                $floor_photo_proof_destination = "all_uploads/".$tm.$floor_photo_proof_name; //storing an encrypted File Name in the table
                                move_uploaded_file($_FILES["floor_photo"]["tmp_name"], $floor_photo_proof_destination); //Move Uploaded File

                                $window_photo_name = $_FILES['window_photo']['name']; //Image Name
                                $window_photo_destination = "./all_uploads/".$window_photo_name; //uploading an image
                                $window_photo_destination = "all_uploads/".$tm.$window_photo_name; //storing an encrypted File Name in the table
                                move_uploaded_file($_FILES["window_photo"]["tmp_name"], $window_photo_destination); //Move Uploaded File

                                $door_badphoto_name = $_FILES['door_badphoto']['name']; //Image Name
                                $door_badphoto_destination = "./all_uploads/".$door_badphoto_name; //uploading an image
                                $door_badphoto_destination = "all_uploads/".$tm.$door_badphoto_name; //storing an encrypted File Name in the table
                                move_uploaded_file($_FILES["door_badphoto"]["tmp_name"], $door_badphoto_destination); //Move Uploaded File
                                
                                $faulty_wall_photo_name = $_FILES['faulty_wall_photo']['name']; //Image Name
                                $faulty_wall_photo_destination = "./all_uploads/".$faulty_wall_photo_name; //uploading an image
                                $faulty_wall_photo_destination = "all_uploads/".$tm.$faulty_wall_photo_name; //storing an encrypted File Name in the table
                                move_uploaded_file($_FILES["faulty_wall_photo"]["tmp_name"], $faulty_wall_photo_destination); //Move Uploaded File

                                $bulb_holder_photo_name = $_FILES['bulb_holder_photo']['name']; //Image Name
                                $bulb_holder_photo_destination = "./all_uploads/".$bulb_holder_photo_name; //uploading an image
                                $bulb_holder_photo_destination = "all_uploads/".$tm.$bulb_holder_photo_name; //storing an encrypted File Name in the table
                                move_uploaded_file($_FILES["bulb_holder_photo"]["tmp_name"], $bulb_holder_photo_destination); //Move Uploaded File

                                $fault_socket_photo_name = $_FILES['fault_socket_photo']['name']; //Image Name
                                $fault_socket_photo_destination = "./all_uploads/".$fault_socket_photo_name; //uploading an image
                                $fault_socket_photo_destination = "all_uploads/".$tm.$fault_socket_photo_name; //storing an encrypted File Name in the table
                                move_uploaded_file($_FILES["fault_socket_photo"]["tmp_name"], $fault_socket_photo_destination); //Move Uploaded File

                                try{
                                    //Collect Inputs
                                    $unit_number = clean($_POST['unit_number']);
                                    $purpose = clean($_POST['purpose']);
                                    $building_link = clean($_POST['building_link']);
                                    $location = clean($_POST['location']);

                                    $floor_condition = clean($_POST['floor_condition']);
                                    $floor_state = clean($_POST['floor_state']);

                                    $window_condition = clean($_POST['window_condition']);
                                    $window_state = clean($_POST['window_state']);

                                    $door_condition = clean($_POST['door_condition']);
                                    $door_baddesc = clean($_POST['door_baddesc']);

                                    $wall_condition = clean($_POST['wall_condition']);
                                    $wall_faulty = clean($_POST['wall_faulty']);

                                    $bulb_holder_condition = clean($_POST['bulb_holder_condition']);
                                    $bulb_holder_desc = clean($_POST['bulb_holder_desc']);

                                    $socket_condition = clean($_POST['socket_condition']);
                                    $fault_socket_description = clean($_POST['fault_socket_description']);
                                    
                                    $last_inspection = clean($_POST['last_inspection']);

                                    $sql = "INSERT INTO single_inspection (unit_number, purpose, building_link, location, floor_condition, floor_state, floor_photo, window_condition, window_state, window_photo, door_condition, door_baddesc, door_badphoto, wall_condition, wall_faulty, faulty_wall_photo, bulb_holder_condition, bulb_holder_desc, bulb_holder_photo, socket_condition, fault_socket_description, fault_socket_photo, last_inspection) VALUES (:unit_number, :purpose, :building_link, :location, :floor_condition, :floor_state, :floor_photo, :window_condition, :window_state, :window_photo, :door_condition, :door_baddesc, :door_badphoto, :wall_condition, :wall_faulty, :faulty_wall_photo, :bulb_holder_condition, :bulb_holder_desc, :bulb_holder_photo, :socket_condition, :fault_socket_description, :fault_socket_photo, :last_inspection)";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute([
                                        ':unit_number' => $unit_number,
                                        ':purpose' => $purpose,
                                        ':building_link' => $building_link,
                                        ':location' => $location,
                                        ':floor_condition' => $floor_condition,
                                        ':floor_state' => $floor_state,
                                        ':floor_photo' => $floor_photo_proof_destination,
                                        ':window_condition' => $window_condition,
                                        ':window_state' => $window_state,
                                        ':window_photo' => $window_photo_destination,
                                        ':door_condition' => $door_condition,
                                        ':door_baddesc' => $door_baddesc,
                                        ':door_badphoto' => $door_badphoto_destination,
                                        ':wall_condition' => $wall_condition,
                                        ':wall_faulty' => $wall_faulty,
                                        ':faulty_wall_photo' => $faulty_wall_photo_destination,
                                        ':bulb_holder_condition' => $bulb_holder_condition,
                                        ':bulb_holder_desc' => $bulb_holder_desc,
                                        ':bulb_holder_photo' => $bulb_holder_photo_destination,
                                        ':socket_condition' => $socket_condition,
                                        ':fault_socket_description' => $fault_socket_description,
                                        ':fault_socket_photo' => $fault_socket_photo_destination,
                                        ':last_inspection' => $last_inspection
                                    ]);

                                    echo '<div id="countdown" class="alert alert-success" role="alert"></div>
                                    <script>
                                        var timeleft = 10;
                                        var downloadTimer = setInterval(function(){
                                          if(timeleft <= 0){
                                            clearInterval(downloadTimer);
                                            window.location.href="inspections.php";
                                          } else {
                                            document.getElementById("countdown").innerHTML = "Inspection Information Submitted Succeessfully! Redirecting in " + timeleft + " seconds remaining";
                                          }
                                          timeleft -= 1;
                                        }, 1000);
                                    </script>';

                                }catch(PDOException $e){
                                    echo '<div class="alert alert-danger">
                                        Data Submission Failed! '.$e->getMessage().'
                                    </div>';
                                }
                            }
                        ?>
                        <div class="card">
                            <div class="card-header" style="background-color:#00192D; color:#FFC107"><b>Perform Inspection</b></div>
                            <div class="card-body">
                                <div class="card shadow" style="border:1px solid rbg(0,25,45,.2)">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><i class="bi bi-house"></i> Unit No: <?= htmlspecialchars($unit_number);?></label>
                                            </div>
                                            <div class="col-md-6">
                                                <label><i class="bi bi-building"></i> Building: <?= htmlspecialchars($building_link) ;?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><i class="bi bi-table"></i> Floor Location: <?= htmlspecialchars($location) ;?></label>
                                            </div>
                                            <div class="col-md-6">
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
                                            </div>
                                        </div>
                                    </div>
                                </div> <hr>
                                <div class="card shadow">
                                    <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-cogs"></i> <b>Inspect this Unit</b></div>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="unit_number" value="<?= htmlspecialchars($unit_number) ;?>">
                                        <input type="hidden" name="purpose" value="<?= htmlspecialchars($purpose) ;?>">
                                        <input type="hidden" name="building_link" value="<?= htmlspecialchars($building_link) ;?>">
                                        <input type="hidden" name="location" value="<?= htmlspecialchars($location) ;?>">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-home"></i> <b>Floor Condition</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="floor_condition" id="floorRepair" value="Needs Repair">
                                                                            <label for="floorRepair"> Needs Repair</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="floor_condition" id="floorGood" value="Good">
                                                                            <label for="floorGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" id="floorBadDescription" style="display:none;">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Describe the Repair Required</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the State</label>
                                                                        <textarea name="floor_state" id="" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="floor_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-table"></i> <b>Window(s) Condition</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="window_condition" id="windowNeedsRepair" value="Needs Repair">
                                                                            <label for="windowNeedsRepair"> Needs Repair</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="window_condition" id="windowGood" value="Good">
                                                                            <label for="windowGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" id="windowBadDescription" style="display:none;">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Describe the Repair Required</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the State</label>
                                                                        <textarea name="window_state" id="" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="window_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-building"></i> <b>Doors Condition</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="door_condition" id="doorGood" value="Good">
                                                                            <label for="doorGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="door_condition" id="doorBad" value="Good">
                                                                            <label for="doorBad"> Needs Repair</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow mt-2" id="doorBadCard" style="display:none;">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107"><b>Provide More Informations</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label for="">Describe the Damage</label>
                                                                        <textarea name="door_baddesc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Attach Photo</label>
                                                                        <input type="file" class="form-control" name="door_badphoto">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-bank"></i><b> Wall Condition</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="wall_condition" id="wallNeedRepair" value="Needs Repair">
                                                                            <label for="wallNeedRepair"> Needs Repair</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="wall_condition" id="wallGood" value="Good">
                                                                            <label for="wallGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow mt-2" id="wallNeedsRepairCard" style="display:none;">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label for="">Describe the Repair Needed</label>
                                                                        <textarea name="wall_faulty" id="" cols="30" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_wall_photo" id="faulty_wall_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-bell"></i><b> Bulb Holder(s)</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="bulb_holder_condition" id="bulbHolderGood" value="Good">
                                                                            <label for="bulbHolderGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-3" style="order: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="bulb_holder_condition" id="bulbHolderNeedsRepair" value="Needs Repair">
                                                                            <label for="bulbHolderNeedsRepair"> Needs Repair</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" id="bulbHolderCard" style="display:none;">
                                                                <div class="card-header" style="background-color:#00192D; color: #FFC107;"><b>Describe the Repair Needed</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label for="">Describe the Fault</label>
                                                                        <textarea name="bulb_holder_desc" id="bulb_holder_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Attach Photo</label>
                                                                        <input type="file" name="bulb_holder_photo" id="bulb_holder_photo" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-plug"></i> <b>Sockets</b></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6 text-center">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="socket_condition" id="socketGood" value="Good">
                                                                            <label for="socketGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 text-center">
                                                                    <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                                        <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline">
                                                                            <input type="radio" name="socket_condition" id="socketNeedsRepair" value="Needs Repair">
                                                                            <label for="socketNeedsRepair"> Needs Repair</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" id="socketFaultyCard" style="border:1px solid rgb(0,25,45,.2); display:none;">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Describe the Fault</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="fault_socket_description" id="fault_socket_description" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photos</label>
                                                                        <input type="file" name="fault_socket_photo" id="fault_socket_photo" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="last_inspection" value="<?php echo date('d, M Y H:i:s') ;?>">
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-sm next-btn" name="submit">Submit</button>
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
        <script>
            //Inspect Single Room and Display FLoor Condition if it is bad
            document.getElementById('floorRepair').addEventListener('change', function(){
                document.getElementById('floorBadDescription').style.display='block';
            });
            //if the floor condition is good no need for description
            document.getElementById('floorGood').addEventListener('change',function(){
                document.getElementById('floorBadDescription').style.display='none';
            });
            //Inspect Single Room if for the Faulty Window
            document.getElementById('windowNeedsRepair').addEventListener('change', function(){
                document.getElementById('windowBadDescription').style.display='block';
            });
            document.getElementById('windowGood').addEventListener('change', function(){
                document.getElementById('windowBadDescription').style.display='none';
            });
            //Inspect Single Room for the Door Condition
            document.getElementById('doorBad').addEventListener('click', function(){
                document.getElementById('doorBadCard').style.display='block';
                doorGood
            });
            //Inspect Single Room for the Door Condition
            document.getElementById('doorGood').addEventListener('click', function(){
                document.getElementById('doorBadCard').style.display='none';
            });
            //Inspect Single Unit for the Wall Condition
            document.getElementById('wallNeedRepair').addEventListener('click', function(){
                document.getElementById('wallNeedsRepairCard').style.display='block';
            });
            document.getElementById('wallGood').addEventListener('click', function(){
                document.getElementById('wallNeedsRepairCard').style.display='none';
            });
            //BUlb Holder Event Listener
            document.getElementById('bulbHolderNeedsRepair').addEventListener('click', function(){
                document.getElementById('bulbHolderCard').style.display='block';
            });
            document.getElementById('bulbHolderGood').addEventListener('click', function(){
                document.getElementById('bulbHolderCard').style.display='none';
            });
            document.getElementById('socketNeedsRepair').addEventListener('click',function(){
                document.getElementById('socketFaultyCard').style.display='block';
            });
            document.getElementById('socketGood').addEventListener('click',function(){
                document.getElementById('socketFaultyCard').style.display='none';
            });
        </script>
    </body>

</html>
