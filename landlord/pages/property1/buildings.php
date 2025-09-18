<!DOCTYPE html>
<html lang="en">
<?php
    include_once 'includes/head.php';
    //include_once 'processes/process_building.php';
    ?>

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
                <!-- Success Submission Modal -->
                <?php
                    include_once 'processes/encrypt_decrypt_function.php';

                    if(isset($_POST['submit_building'])) {
                        $tm = md5(time()); //disable overwriting image names to have unique uploaded image names
                        $ownership_proof_name = $_FILES['ownership_proof']['name']; //Image Name
                        $ownership_proof_destination = "./all_uploads/".$ownership_proof_name; //uploading an image
                        $ownership_proof_destination = "all_uploads/".$tm.$ownership_proof_name; //storing an encrypted File Name in the table
                        move_uploaded_file($_FILES["ownership_proof"]["tmp_name"], $ownership_proof_destination); //Move Uploaded File

                        $title_deed_name = $_FILES['title_deed']['name']; //Image Name
                        $title_deed_destination = "./all_uploads/".$title_deed_name; //uploading an image
                        $title_deed_destination = "all_uploads/".$tm.$title_deed_name; //storing an encrypted File Name in the table
                        move_uploaded_file($_FILES["title_deed"]["tmp_name"], $title_deed_destination); //Move Uploaded File

                        $legal_document_name = $_FILES['legal_document']['name']; //Image Name
                        $legal_document_destination = "./all_uploads/".$legal_document_name; //uploading an image
                        $legal_document_destination = "all_uploads/".$tm.$legal_document_name; //storing an encrypted File Name in the table
                        move_uploaded_file($_FILES["legal_document"]["tmp_name"], $legal_document_destination); //Move Uploaded File

                        $photo_one_name = $_FILES['photo_one']['name']; //Image Name
                        $photo_one_destination = "./all_uploads/".$photo_one_name; //uploading an image
                        $photo_one_destination = "all_uploads/".$tm.$photo_one_name; //storing an encrypted File Name in the table
                        move_uploaded_file($_FILES["photo_one"]["tmp_name"], $photo_one_destination); //Move Uploaded File

                        $photo_two_name = $_FILES['photo_two']['name']; //Image Name
                        $photo_two_destination = "./all_uploads/".$photo_two_name; //uploading an image
                        $photo_two_destination = "all_uploads/".$tm.$photo_two_name; //storing an encrypted File Name in the table
                        move_uploaded_file($_FILES["photo_two"]["tmp_name"], $photo_two_destination); //Move Uploaded File

                        $photo_three_name = $_FILES['photo_three']['name']; //Image Name
                        $photo_three_destination = "./all_uploads/".$photo_three_name; //uploading an image
                        $photo_three_destination = "all_uploads/".$tm.$photo_three_name; //storing an encrypted File Name in the table
                        move_uploaded_file($_FILES["photo_three"]["tmp_name"], $photo_three_destination); //Move Uploaded File

                        $photo_four_name = $_FILES['photo_four']['name']; //Image Name
                        $photo_four_destination = "./all_uploads/".$photo_four_name; //uploading an image
                        $photo_four_destination = "all_uploads/".$tm.$photo_four_name; //storing an encrypted File Name in the table
                        move_uploaded_file($_FILES["photo_four"]["tmp_name"], $photo_four_destination); //Move Uploaded File

                        //$iv = openssl_random_pseudo_bytes(16);

                        $building_name = $_REQUEST['building_name'];
                        $county = $_REQUEST['county'];
                        $constituency = $_REQUEST['constituency'];
                        $ward = $_REQUEST['ward'];
                        $structure_type = $_REQUEST['structure_type'];
                        $floors_no = $_REQUEST['floors_no'];
                        $no_of_units = $_REQUEST['no_of_units'];
                        $building_type = $_REQUEST['building_type'];
                        $tax_rate = $_REQUEST['tax_rate'];
                        $ownership_info = $_REQUEST['ownership_info'];
                        $first_name = $_REQUEST['first_name'];
                        $last_name = $_REQUEST['last_name'];
                        $id_number = $_REQUEST['id_number'];
                        $primary_contact = $_REQUEST['primary_contact'];
                        $other_contact = $_REQUEST['other_contact'];
                        $owner_email = $_REQUEST['owner_email'];
                        $postal_address = $_REQUEST['postal_address'];
                        $entity_name = $_REQUEST['entity_name'];
                        $entity_phone = $_REQUEST['entity_phone'];
                        $entity_phoneother = $_REQUEST['entity_phoneother'];
                        $entity_email = $_REQUEST['entity_email'];
                        $entity_rep = $_REQUEST['entity_rep'];
                        $rep_role = $_REQUEST['rep_role'];
                        $entity_postal = $_REQUEST['entity_postal'];
                        $utilities = $_REQUEST['utilities'];
                        $added_on = $_REQUEST['added_on'];
                        $confirm = $_REQUEST['confirm'];

                        try {
                            $submit = "INSERT INTO buildings (building_name, county, constituency, ward, structure_type, floors_no, no_of_units, building_type, tax_rate, ownership_info, first_name, last_name, id_number, primary_contact, other_contact, owner_email, postal_address, entity_name, entity_phone, entity_phoneother, entity_email, entity_rep, rep_role, entity_postal, ownership_proof, title_deed, legal_document, utilities, photo_one, photo_two, photo_three, photo_four, added_on, confirm) VALUES (:building_name, :county, :constituency, :ward, :structure_type, :floors_no, :no_of_units, :building_type, :tax_rate, :ownership_info, :first_name, :last_name, :id_number, :primary_contact, :other_contact, :owner_email, :postal_address, :entity_name, :entity_phone, :entity_phoneother, :entity_email, :entity_rep, :rep_role, :entity_postal, :ownership_proof, :title_deed, :legal_document, :utilities, :photo_one, :photo_two, :photo_three, :photo_four, :added_on, :confirm)";
                            $result = $conn->prepare($submit);
                            $result->bindParam(':building_name', $building_name, PDO::PARAM_STR);
                            $result->bindParam(':county', $county, PDO::PARAM_STR);
                            $result->bindParam(':constituency', $constituency, PDO::PARAM_STR);
                            $result->bindParam(':ward', $ward, PDO::PARAM_STR);
                            $result->bindParam(':structure_type', $structure_type, PDO::PARAM_STR);
                            $result->bindParam(':floors_no', $floors_no, PDO::PARAM_STR);
                            $result->bindParam(':no_of_units', $no_of_units, PDO::PARAM_STR);
                            $result->bindParam(':building_type', $building_type, PDO::PARAM_STR);
                            $result->bindParam(':tax_rate', $tax_rate, PDO::PARAM_STR);
                            $result->bindParam(':ownership_info', $ownership_info, PDO::PARAM_STR);
                            $result->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                            $result->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                            $result->bindParam(':id_number', $id_number, PDO::PARAM_STR);
                            $result->bindParam(':primary_contact', $primary_contact, PDO::PARAM_STR);
                            $result->bindParam(':other_contact', $other_contact, PDO::PARAM_STR);
                            $result->bindParam(':owner_email', $owner_email, PDO::PARAM_STR);
                            $result->bindParam(':postal_address', $postal_address, PDO::PARAM_STR);
                            $result->bindParam(':entity_name', $entity_name, PDO::PARAM_STR);
                            $result->bindParam(':entity_phone', $entity_phone, PDO::PARAM_STR);
                            $result->bindParam(':entity_phoneother', $entity_phoneother, PDO::PARAM_STR);
                            $result->bindParam(':entity_email', $entity_email, PDO::PARAM_STR);
                            $result->bindParam(':entity_rep', $entity_rep, PDO::PARAM_STR);
                            $result->bindParam(':rep_role', $rep_role, PDO::PARAM_STR);
                            $result->bindParam(':entity_postal', $entity_postal, PDO::PARAM_STR);
                            $result->bindParam(':ownership_proof', $ownership_proof_destination, PDO::PARAM_STR);
                            $result->bindParam(':title_deed', $title_deed_destination, PDO::PARAM_STR);
                            $result->bindParam(':legal_document', $legal_document_destination, PDO::PARAM_STR);
                            $result->bindParam(':utilities', $utilities, PDO::PARAM_STR);
                            $result->bindParam(':photo_one', $photo_one_destination, PDO::PARAM_STR);
                            $result->bindParam(':photo_two', $photo_two_destination, PDO::PARAM_STR);
                            $result->bindParam(':photo_three', $photo_three_destination, PDO::PARAM_STR);
                            $result->bindParam(':photo_four', $photo_four_destination, PDO::PARAM_STR);
                            $result->bindParam(':added_on', $added_on, PDO::PARAM_STR);
                            $result->bindParam(':confirm', $confirm, PDO::PARAM_STR);
                            $result->execute();

                            echo '<div id="countdown" class="alert alert-success" role="alert"></div>
                                    <script>
                                        var timeleft = 10;
                                        var downloadTimer = setInterval(function(){
                                          if(timeleft <= 0){
                                            clearInterval(downloadTimer);
                                            window.location.href=window.location.href;
                                          } else {
                                            document.getElementById("countdown").innerHTML = "Building Information Submitted Successfully! Redirecting in " + timeleft + " seconds remaining";
                                          }
                                          timeleft -= 1;
                                        }, 1000);
                                    </script>';

                        } catch(PDOException $e) {
                            echo '<div class="alert alert-danger text-center" id="dangerAlert" style="border:1px solid #8C0000; color:#8C0000; background-color:#FF9D9D; font-weight:bold; font-size:1.2rem;">Submission Failed. Contact the Developer About this Error Message. "'.$e->getMessage().'" </div>';
                        }
                    }
                    ?>
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
                        <?php
                            $count_buildings = "SELECT building_type, COUNT(*) AS total FROM buildings GROUP BY building_type";
                            $result = $conn->prepare($count_buildings);
                            $result->execute();
                            //Initialize the countings for all the buildings
                            $counts = [
                                'Residential' => 0,
                                'Commercial'   => 0,
                                'Industrial'   => 0,
                                'Mixed-Use'    => 0,
                                'Ware House'   => 0
                            ];
                            while($row = $result->fetch()) {
                                $counts[$row['building_type']] = $row['total'];
                            }

                            // Assign icons for each building type
                            $icons = [
                                'Residential' => 'bi-house-door-fill',
                                'Commercial'   => 'bi-shop',
                                'Industrial'   => 'bi-building-gear',
                                'Mixed-Use'    => 'bi-buildings',
                                'Ware House'   => 'bi-box-seam'
                            ];
                        ?>
                        <div class="row g-3">
                            <?php foreach ($counts as $type => $total): ?>
                            <div class="col-md-3">
                                <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                    <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="bi  <?php echo $icons[$type]; ?>"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $type; ?></span>
                                        <span class="info-box-number"><?php echo $total; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #00192D;">
                        <div class="row">
                            <div class="col-md-6 mt-2"><b class="text-white">Registered Buildings (1000)</b></div>
                            <div class="col-md-6 text-right mt-2">
                                <button class="btn btn-sm" style="border: 1px solid #fff; color:#fff; font-weight:bold;"
                                    data-toggle="modal" data-target="#addBuildingModal"><i class="fas fa-building"></i>
                                    Add Building</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataTable">
                                <thead>
                                    <th>Building</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>No. of Units</th>
                                    <th>Ownership Mode</th>
                                    <th>Reg. Date</th>
                                    <th>Options</th>
                                </thead>
                                <tbody>
                                    <?php
                                        $show_buildings = "SELECT * FROM buildings";
                                        $results_show_buildings = $conn->prepare($show_buildings);
                                        $results_show_buildings->execute();
                                        while($row = $results_show_buildings->fetch()) {
                                            $id = encryptor('encrypt', $row['id']);
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
                                            $added_on = $row['added_on'];
                                        ?>
                                    <tr>
                                        <td><i class="fas fa-building"></i> <?php echo $building_name ;?></td>
                                        <td>
                                            <?php
                                            if($structure_type == 'High Rise') {
                                                ?>
                                            <i class="fas fa-bars"></i> <?php echo $structure_type;?>
                                            <?php
                                            } else {
                                                ?>
                                            <i class="fas fa-home"></i> <?php echo $structure_type;?>
                                            <?php
                                            }
                                                ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($building_type == 'Residential'){
                                                ?>
                                            <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i
                                                    class="fas fa-hotel"></i> <?php echo $building_type;?></button>
                                            <?php
                                            } else if ($building_type == 'Commercial') {
                                                ?>
                                            <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i
                                                    class="fas fa-building"></i> <?php echo $building_type;?></button>
                                            <?php
                                            } else if ($building_type == 'Industrial') {
                                                ?>
                                            <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i
                                                    class="fas fa-industry"></i> <?php echo $building_type;?></button>
                                            <?php
                                            } else if ($building_type == 'Ware House') {
                                                ?>
                                            <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i
                                                    class="fas fa-bank"></i> <?php echo $building_type;?></button>
                                            <?php
                                            } else {
                                                ?>
                                            <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i
                                                    class="fas fa-home"></i> <?php echo $building_type;?></button>
                                            <?php
                                            }
                                                ?>
                                        </td>
                                        <td><i class="fas fa-home"></i> <?php echo $no_of_units;?></td>
                                        <td>
                                            <?php
                                            if($ownership_info == 'Individual') {
                                                ?>
                                            <i class="fa fa-user-circle" style="font-size:1.5rem;"></i>
                                            <?php echo $ownership_info ;?>
                                            <?php
                                            } else {
                                                ?>
                                            <i class="fa fa-users" style="font-size:1.5rem;"></i>
                                            <?php echo $ownership_info ;?>
                                            <?php
                                            }
                                                ?>
                                        </td>
                                        <td><i class="fa fa-calendar"></i> <?php echo $added_on;?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-sm"
                                                    style="border:1px solid rgb(0, 25, 45 ,.3);">Action</button>
                                                <button type="button"
                                                    class="btn btn-default dropdown-toggle dropdown-icon btn-sm"
                                                    data-toggle="dropdown" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu shadow" role="menu"
                                                    style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                    <!--<a class="dropdown-item" href="add_unit.php">Add Unit</a>
                                                        <div class="dropdown-divider"></div>-->
                                                    <a class="dropdown-item" href="edit_building_info.php?edit=<?php echo $id ;?>"><i class="bi bi-pen"></i> Edit</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item"
                                                        href="building_info.php?details=<?php echo $id ;?>"><i
                                                            class="bi bi-eye"></i> Details</a>
                                                </div>
                                            </div>
                                            <!-- Edit Building Modal -->
                                        </td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Register New Building Modal Popup -->
                <div class="modal fade" id="addBuildingModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color:#00192D; color:#fff;">
                                <b class="modal-title">Add New Building</b>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    style="color:#fff;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="POST"
                                    enctype="multipart/form-data">
                                    <!-- First Section -->
                                    <div class="card shadow" id="firstSection"
                                        style="border:1px solid rgb(0,25,45,.2);">
                                        <div class="card-header" style="background-color: #00192D; color:#fff;">
                                            <b>Building Identification</b>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Building Name</label> <sup
                                                            class="text-danger"><b>*</b></sup>
                                                        <input type="text" class="form-control" id="building_name"
                                                            name="building_name" placeholder="Building Name">
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 class="text-center" style="font-weight: bold;">Location Information</h5>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label>County</label>
                                                        <select class="form-control select2 select2-danger"
                                                            data-dropdown-css-class="select2-danger"
                                                            style="width: 100%;" id="county" name="county"
                                                            onchange="FetchConstituency(this.value)">
                                                            <option value="" hidden selected>-- Select County --
                                                            </option>
                                                            <?php
                                                                $select_county = "SELECT * FROM county ORDER BY id ASC";
                                                                $result = $conn->prepare($select_county);
                                                                $result->execute();
                                                                while($row = $result->fetch()) {
                                                                ?>
                                                            <option value="<?php echo $row['id'];?>">
                                                                <?php echo $row['name'];?></option>
                                                            <?php
                                                                }
                                                                ?>
                                                        </select>
                                                    </div>


                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Constituency</label>
                                                        <select class="form-control select2 select2-danger"
                                                            name="constituency" id="constituency"
                                                            data-dropdown-css-class="select2-danger"
                                                            style="width: 100%;" onchange="FetchWard(this.value)">
                                                            <option value="" selected hidden>-- Select Constituency --
                                                            </option>
                                                        </select>
                                                        <b class="errorMessages" id="constituencyError"></b>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Ward</label>
                                                        <select class="form-control select2 select2-danger"
                                                            data-dropdown-css-class="select2-danger"
                                                            style="width: 100%;" name="ward" id="ward">
                                                            <option value="" selected hidden>-- Choose Ward --</option>
                                                        </select>
                                                        <b class="errorMessages" id="wardError"></b>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Structural Type</label>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="radio" id="highRise"
                                                                        name="structure_type" data-toggle="modal"
                                                                        data-target="#specifyFloors" value="High Rise">
                                                                    <label for="highRise">High Rise</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="radio" id="lowStructure"
                                                                        name="structure_type" value="Low Structure">
                                                                    <label for="lowStructure">Low Structure</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade shadow" style="margin-top:150px;"
                                                        id="specifyFloors">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background-color:#00192D; color:#fff;">
                                                                    <b class="modal-title">Number of Floors</b>
                                                                    <button type="button" class="close"
                                                                        onclick="closeFloorsSpecify()"
                                                                        style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group" id="floorsNo">
                                                                        <input type="text" class="form-control"
                                                                            id="floors_no" name="floors_no"
                                                                            placeholder="Number of Floors">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer text-right">
                                                                    <button class="btn btn-sm"
                                                                        onclick="closeFloorsSpecify();" type="button"
                                                                        style="background-color:#cc0001;color:#fff;">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Number of Units</label>
                                                        <input type="text" class="form-control" id="no_of_units"
                                                            name="no_of_units" placeholder="Number of Units">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Building Type</label>
                                                    <select id="building_type" name="building_type"
                                                        class="form-control">
                                                        <option value="" selected hidden>--Select Building
                                                            Type--</option>
                                                        <option value="Residential">Residential</option>
                                                        <option value="Commercial">Commercial</option>
                                                        <option value="Industrial">Industrial</option>
                                                        <option value="Mixed-Use">Mixed-Use</option>
                                                        <option value="Mixed-Use">Ware House</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label>Tax Rate(%)</label>
                                                <input type="text" class="form-control" name="tax_rate" id="tax_rate"
                                                    placeholder="Tax Rate">
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-sm next-btn"
                                                id="firstSectionNexttBtn">Next</button>
                                        </div>
                                    </div>

                                    <!-- Second Section -->
                                    <div class="card shadow" id="secondSection"
                                        style="border:1px solid rgb(0,25,45,.2); display:none;">
                                        <div class="card-header" style="background-color:rgb(0,25,45); color:#fff;">
                                            Ownership Information</div>
                                        <div class="card-body">
                                            <div class="form-group text-center">
                                                <label>Ownership Mode</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="radio" name="ownership_info" id="ownership_type"
                                                            data-toggle="modal" data-target="#individual-owner"
                                                            value="Individual"> Individual
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="radio" name="ownership_info" id="ownership_type"
                                                            data-toggle="modal" data-target="#entity-owner"
                                                            value="Entity"> Entity
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Individual Ownership Information Modal -->
                                            <div class="modal fade mt-2 shadow p-2" id="individual-owner">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <b>Enter Individual's Information</b>
                                                            <button type="button" class="close"
                                                                onclick="closeIndividualOwnerInfo()" aria-label="Close"
                                                                style="color:#000;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>First Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="firstName" placeholder="First Name"
                                                                            name="first_name">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Last Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="lastName" placeholder="Last Name"
                                                                            name="last_name">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Identification Number</label>
                                                                <input type="text" class="form-control" id="id_number"
                                                                    name="id_number">
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Primary Contact</label>
                                                                        <input type="text" class="form-control"
                                                                            id="primary_contact" name="primary_contact"
                                                                            placeholder="Phone Number">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Other Contact</label>
                                                                        <input type="text" class="form-control"
                                                                            id="other_contact" name="other_contact"
                                                                            placeholder="Other Number">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input type="text" class="form-control" id="ownerEmail"
                                                                    placeholder="Email" name="owner_email">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Postal Address </label><sup>(Optional)</sup>
                                                                <input type="text" class="form-control"
                                                                    id="postal_address" name="postal_address"
                                                                    placeholder="Postal Address">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer text-right">
                                                            <button type="button" class="btn btn-sm"
                                                                onclick="closeIndividualOwnerInfo()"
                                                                style="background-color:#cc0001; color: #fff;">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Entity Ownership Modal -->
                                            <div class="modal fade" id="entity-owner">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <b>Enter Entity's Information</b>
                                                            <button type="button" class="close"
                                                                onclick="closeEntityOwnership()" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Entity Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="entityName" name="entity_name"
                                                                            placeholder="Entity Name">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Primary Contact</label>
                                                                        <input type="text" class="form-control"
                                                                            id="entity_phone" name="entity_phone"
                                                                            placeholder="Primary Contact">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Other Contact</label>
                                                                        <input type="text" class="form-control"
                                                                            id="entity_phoneother"
                                                                            name="entity_phoneother"
                                                                            placeholder="Other Contact">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Official Email</label>
                                                                <input type="text" class="form-control" id="entityEmail"
                                                                    placeholder="Entity Email" name="entity_email">
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Entity Representative</label>
                                                                        <input type="text" class="form-control"
                                                                            id="entityRepresentative"
                                                                            placeholder="Entity Representative"
                                                                            name="entity_rep">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Role</label>
                                                                        <select id="entityRepRole" class="form-control"
                                                                            name="rep_role">
                                                                            <option value="" selected hidden> --Select
                                                                                Role --</option>
                                                                            <option value="CEO">CEO</option>
                                                                            <option value="Treasury">Treasury</option>
                                                                            <option value="Board Member">BoardMember
                                                                            </option>
                                                                            <option value="Signatory">Signatory</option>
                                                                            <option value="Founder">Founder</option>
                                                                            <option value="Co-Founder">Co-Founder
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Postal Address</label> <sup>Optional</sup>
                                                                <input class="form-control" name="entity_postal"
                                                                    id="postal_co" placeholder="Postal Address">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer text-right">
                                                            <button type="button" class="btn btn-sm"
                                                                style="background-color:#cc0001; color: #fff;"
                                                                onclick="closeEntityOwnership()">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Proof of Ownership</label>
                                                <input type="file" class="form-control" id="ownership_proof"
                                                    name="ownership_proof">
                                            </div>
                                            <div class="form-group">
                                                <label>Title Deed Copy</label>
                                                <input type="file" class="form-control" id="title_deed"
                                                    name="title_deed">
                                            </div>
                                            <div class="form-group">
                                                <label>Other Legal Document</label>
                                                <input type="file" class="form-control" id="legal_document"
                                                    name="legal_document">
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn"
                                                id="secondSectionBackBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn"
                                                id="secondSectionNextBtn">Next</button>
                                        </div>
                                    </div>

                                    <div class="card" id="thirdSection"
                                        style="border:1px solid rgb(0,25,45,.2); display:none;">
                                        <div class="card-header" style="background-color: #00192D; color:#fff;">
                                            <b>Utilities and Infrastructure</b></div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>List all the Utilities and Infrastructure</label>
                                                <textarea id="summernote" name="utilities">

                                                    </textarea>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn"
                                                id="thirdSectionBackBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn"
                                                id="thirdSectionNextBtn">Next</button>
                                        </div>
                                    </div>
                                    <div class="card" id="fourthSection"
                                        style="border:1px solid rgb(0,25,45,.2); display:none;">
                                        <div class="card-header" style="background-color: #00192D; color:#fff;">
                                            <b>Photos</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Photo 1</label>
                                                        <input type="file" class="form-control" name="photo_one"
                                                            id="photo_one">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Photo 2</label>
                                                        <input type="file" class="form-control" name="photo_two"
                                                            id="photo_two">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Photo 3</label>
                                                        <input type="file" class="form-control" name="photo_three"
                                                            id="photo_three">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Photo 4</label>
                                                        <input type="file" class="form-control" name="photo_four"
                                                            id="photo_four">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="added_on" class="form-control"
                                                value="<?php echo date('Y, M d H:i:s');?>">
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn"
                                                id="fourthSectionBackBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn"
                                                id="fourthSectionNextBtn">Next</button>
                                        </div>
                                    </div>
                                    <div class="card" id="fifthSection"
                                        style="border:1px solid rgb(0,25,45,.2); display:none;">
                                        <div class="card-header" style="background-color: #00192D; color:#fff;">
                                            <b>Confirmation</b></div>
                                        <div class="card-body text-center">
                                            <input type="checkbox" required name="confirm" value="Confirmation"> I here
                                            by confirm that all the information filled in this form is accurare. I
                                            therefore issue my consent to Biccount Technologies to go ahead and register
                                            my rental property for further property management services that I will be
                                            receiving.
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn"
                                                id="fifthSectionBackBtn">Back</button>
                                            <button type="submit" name="submit_building" class="btn btn-sm next-btn"
                                                id="fifthSectionSubmitBtn" name="submit_building_btn">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Edit Process -->

            <!-- Help Pop Up Form -->
            <?php include_once 'includes/lower_right_popup_form.php' ;?>
        </div>

        <!-- Footer -->
        <?php include_once 'includes/footer.php';?>

    </div>
    <?php include_once 'includes/required_scripts.php';?>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- CodeMirror -->
    <script src="plugins/codemirror/codemirror.js"></script>
    <script src="plugins/codemirror/mode/css/css.js"></script>
    <script src="plugins/codemirror/mode/xml/xml.js"></script>
    <script src="plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- Page specific script -->

    <script>
    $(document).ready(function() {
        $("#firstSectionNexttBtn").click(function() {
            $("#secondSection").show();
            $("#firstSection").hide();
        });

        $("#secondSectionBackBtn").click(function() {
            $("#secondSection").hide();
            $("#firstSection").show();
        });

        $("#secondSectionNextBtn").click(function() {
            $("#secondSection").hide();
            $("#thirdSection").show();
        });

        $("#thirdSectionNextBtn").click(function() {
            $("#fourthSection").show();
            $("#thirdSection").hide();
        });

        $("#thirdSectionBackBtn").click(function() {
            $("#thirdSection").hide();
            $("#secondSection").show();
        });

        $("#fourthSectionNextBtn").click(function() {
            $("#fourthSection").hide();
            $("#fifthSection").show();
        });

        $("#fourthSectionBackBtn").click(function() {
            $("#fourthSection").hide();
            $("#thirdSection").show();
        });

        $("#fifthSectionBackBtn").click(function() {
            $("#fourthSection").show();
            $("#fifthSection").hide();
        });
    });
    </script>
    <!-- Constituency Fetching basing on the Selected COunty -->
    <script type="text/javascript">
    function FetchConstituency(name) {
        alert('You have Chosen County Number ' + name);
        $('#constituency').html('');
        $('#ward').html('<option>Select Ward</option>');
        $.ajax({
            type: 'POST',
            url: 'processes/ajax_process.php',
            data: {
                county_id: name
            },
            success: function(data) {
                $('#constituency').html(data);
            }
        });
    }

    function FetchWard(name) {
        $('#ward').html('');
        $.ajax({
            type: 'POST',
            url: 'processes/ajax_process.php',
            data: {
                constituency_id: name
            },
            success: function(data) {
                $('#ward').html(data);
            }
        });
    }
    </script>
    <!-- SPecify the Number of Foors -->
    <script>
    var specifyFloorsSection = document.getElementById('specifyFloors');
    var addBuildingModalSection = document.getElementById('addBuildingModal');

    function closeFloorsSpecify() {
        specifyFloorsSection.style.display = 'none';
    }
    </script>

    <script>
    $(function() {
        // Summernote
        $('#summernote').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });
    })
    </script>
</body>
</html>
