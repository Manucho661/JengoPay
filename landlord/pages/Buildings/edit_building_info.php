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
      <?php
      include_once 'processes/encrypt_decrypt_function.php';
      //decrypt the id so as the contents can be viewed normally
      if(isset($_GET['edit']) && !empty($_GET['edit'])) {
        $id = $_GET['edit'];
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
              $added_on = $row['added_on'];
              $ownership_proof = $row['ownership_proof'];
              $title_deed = $row['title_deed'];
              $legal_document = $row['legal_document'];
              $photo_one = $row['photo_one'];
              $photo_two = $row['photo_two'];
              $photo_three = $row['photo_three'];
              $photo_four = $row['photo_four'];
            }
          }
        }catch(PDOException $e){
          //if the query fails to execute
        }
      }
      if(isset($_POST['update'])) {
        //$tm = md5(time());
        $old_image_one = $_POST['old_image_one'];
        $old_image_two = $_POST['old_image_two'];
        $old_image_three = $_POST['old_image_three'];
        $photo_one_old = $_POST['photo_one_old'];
        $photo_two_old = $_POST['photo_two_old'];
        $photo_three_old = $_POST['photo_three_old'];
        $photo_four_old = $_POST['photo_four_old'];

        if(isset($_FILES['ownership_proof']['name']) && ($_FILES['ownership_proof']['name'] != "")){
          $new_ownership_proof = '.all_uploads/'.$_FILES['ownership_proof']['name'];
          unlink($old_image_one);
          move_uploaded_file($_FILES['ownership_proof']['tmp_name'], $new_ownership_proof);
        } else {
          $new_ownership_proof = $old_image_one;
        }

        if(isset($_FILES['title_deed']['name']) && ($_FILES['title_deed']['name'] != "")){
          $new_title_deed = '.all_uploads/'.$_FILES['title_deed']['name'];
          unlink($old_image_two);
          move_uploaded_file($_FILES['title_deed']['tmp_name'], $new_title_deed);
        } else {
          $new_title_deed = $old_image_two;
        }

        if(isset($_FILES['legal_document']['name']) && ($_FILES['legal_document']['name'] != "")){
          $new_legal_document = '.all_uploads/'.$_FILES['legal_document']['name'];
          unlink($old_image_three);
          move_uploaded_file($_FILES['legal_document']['tmp_name'], $new_legal_document);
        } else {
          $new_legal_document = $old_image_three;
        }

        if(isset($_FILES['photo_one']['name']) && ($_FILES['photo_one']['name'] != "")){
          $new_photo_one = 'teacheruploads/'.$_FILES['photo_one']['name'];
          unlink($photo_one_old);
          move_uploaded_file($_FILES['photo_one']['tmp_name'], $new_photo_one);
        } else {
          $new_photo_one = $photo_one_old;
        }

        if(isset($_FILES['photo_two']['name']) && ($_FILES['photo_two']['name'] != "")){
          $new_photo_two = 'teacheruploads/'.$_FILES['photo_two']['name'];
          unlink($photo_two_old);
          move_uploaded_file($_FILES['photo_two']['tmp_name'], $new_photo_two);
        } else {
          $new_photo_two = $photo_two_old;
        }

        if(isset($_FILES['photo_three']['name']) && ($_FILES['photo_three']['name'] != "")){
          $new_photo_three = 'teacheruploads/'.$_FILES['photo_three']['name'];
          unlink($photo_three_old);
          move_uploaded_file($_FILES['photo_three']['tmp_name'], $new_photo_three);
        } else {
          $new_photo_three = $photo_three_old;
        }


        if(isset($_FILES['photo_four']['name']) && ($_FILES['photo_four']['name'] != "")){
          $new_photo_four = 'teacheruploads/'.$_FILES['photo_four']['name'];
          unlink($photo_four_old);
          move_uploaded_file($_FILES['photo_four']['tmp_name'], $new_photo_four);
        } else {
          $new_photo_four = $photo_four_old;
        }

        $added_on = date('Y, M d H:i:s');


        try{
            $no_update = "SELECT * FROM buildings WHERE building_name = '$_POST[building_name]' AND county = '$_POST[county]' AND constituency = '$_POST[constituency]' AND ward = '$_POST[ward]' AND structure_type = '$_POST[structure_type]' AND floors_no = '$_POST[floors_no]' AND no_of_units = '$_POST[no_of_units]' AND building_type = '$_POST[building_type]' AND tax_rate = '$_POST[tax_rate]' AND ownership_info = '$_POST[ownership_info]' AND first_name = '$_POST[first_name]' AND last_name = '$_POST[last_name]' AND id_number = '$_POST[id_number]' AND primary_contact = '$_POST[primary_contact]' AND other_contact = '$_POST[other_contact]' AND owner_email = '$_POST[owner_email]' AND postal_address = '$_POST[postal_address]' AND entity_name = '$_POST[entity_name]' AND entity_phone = '$_POST[entity_phone]' AND entity_phoneother = '$_POST[entity_phoneother]' AND entity_email = '$_POST[entity_email]' AND entity_rep = '$_POST[entity_rep]' AND rep_role = '$_POST[rep_role]' AND entity_postal = '$_POST[entity_postal]' AND utilities = '$_POST[utilities]'";
            $result = $conn->prepare($no_update);
            $result->execute();
            if($result->rowCount() > 0) {
              ?>
                <script>
                  alert('Update Failed! You Haven\'t Changed Anything in the Form Fields');
                  window.location.href = "buildings.php";
                </script>
              <?php
            } else {
              $update_building = "UPDATE buildings SET building_name =:building_name, county =:county, constituency =:constituency, ward =:ward, structure_type =:structure_type, floors_no =:floors_no, no_of_units =:no_of_units, building_type =:building_type, tax_rate =:tax_rate, ownership_info =:ownership_info, first_name =:first_name, last_name =:last_name, id_number =:id_number, primary_contact =:primary_contact, other_contact =:other_contact, owner_email =:owner_email, postal_address =:postal_address, entity_name =:entity_name, entity_phone =:entity_phone, entity_phoneother =:entity_phoneother, entity_email =:entity_email, entity_rep =:entity_rep, rep_role =:rep_role, entity_postal =:entity_postal, ownership_proof =:ownership_proof, title_deed =:title_deed, legal_document =:legal_document, utilities =:utilities, photo_one =:photo_one, photo_two =:photo_two, photo_three =:photo_three, photo_four =:photo_four, added_on =:added_on, confirm =:confirm WHERE id =:id";
              $stmt = $conn->prepare($update_building);
              $stmt->bindParam(':building_name', $_REQUEST['building_name'], PDO::PARAM_STR);
              $stmt->bindParam(':county', $_REQUEST['$county'], PDO::PARAM_STR);
              $stmt->bindParam(':constituency', $_REQUEST['constituency'], PDO::PARAM_STR);
              $stmt->bindParam(':ward', $_REQUEST['ward'], PDO::PARAM_STR);
              $stmt->bindParam(':structure_type', $_REQUEST['structure_type'], PDO::PARAM_STR);
              $stmt->bindParam(':floors_no', $_REQUEST['floors_no'], PDO::PARAM_STR);
              $stmt->bindParam(':no_of_units', $_REQUEST['no_of_units'], PDO::PARAM_STR);
              $stmt->bindParam(':building_type', $_REQUEST['building_type'], PDO::PARAM_STR);
              $stmt->bindParam(':tax_rate', $_REQUEST['tax_rate'], PDO::PARAM_STR);
              $stmt->bindParam(':ownership_info', $_REQUEST['ownership_info'], PDO::PARAM_STR);
              $stmt->bindParam(':first_name', $_REQUEST['first_name'], PDO::PARAM_STR);
              $stmt->bindParam(':last_name', $_REQUEST['last_name'], PDO::PARAM_STR);
              $stmt->bindParam(':id_number', $_REQUEST['id_number'], PDO::PARAM_STR);
              $stmt->bindParam(':primary_contact', $_REQUEST['primary_contact'], PDO::PARAM_STR);
              $stmt->bindParam(':other_contact', $_REQUEST['other_contact'], PDO::PARAM_STR);
              $stmt->bindParam(':owner_email', $_REQUEST['owner_email'], PDO::PARAM_STR);
              $stmt->bindParam(':postal_address', $_REQUEST['postal_address'], PDO::PARAM_STR);
              $stmt->bindParam(':entity_name', $_REQUEST['entity_name'], PDO::PARAM_STR);
              $stmt->bindParam(':entity_phone', $_REQUEST['entity_phone'], PDO::PARAM_STR);
              $stmt->bindParam(':entity_phoneother', $_REQUEST['entity_phoneother'], PDO::PARAM_STR);
              $stmt->bindParam(':entity_email', $_REQUEST['entity_email'], PDO::PARAM_STR);
              $stmt->bindParam(':entity_rep', $_REQUEST['entity_rep'], PDO::PARAM_STR);
              $stmt->bindParam(':rep_role', $_REQUEST['rep_role'], PDO::PARAM_STR);
              $stmt->bindParam(':entity_postal', $_REQUEST['entity_postal'], PDO::PARAM_STR);
              $stmt->bindParam(':ownership_proof', $new_ownership_proof, PDO::PARAM_STR);
              $stmt->bindParam(':title_deed', $new_title_deed, PDO::PARAM_STR);
              $stmt->bindParam(':legal_document', $new_legal_document, PDO::PARAM_STR);
              $stmt->bindParam(':utilities', $_REQUEST['utilities'], PDO::PARAM_STR);
              $stmt->bindParam(':photo_one', $new_photo_one, PDO::PARAM_STR);
              $stmt->bindParam(':photo_two', $new_photo_two, PDO::PARAM_STR);
              $stmt->bindParam(':photo_three', $new_photo_three, PDO::PARAM_STR);
              $stmt->bindParam(':photo_four', $new_photo_four, PDO::PARAM_STR);
              $stmt->bindParam(':added_on', $added_on, PDO::PARAM_STR);
              $stmt->bindParam(':confirm', $_REQUEST['confirm'], PDO::PARAM_STR);
              $stmt->bindParam(':id', $_REQUEST['id'], PDO::PARAM_STR);
              $stmt->execute();
              echo '<div id="countdown" class="alert alert-success" role="alert"></div>
                <script>
                  var timeleft = 10;
                  var downloadTimer = setInterval(function(){
                  if(timeleft <= 0){
                    clearInterval(downloadTimer);
                    
                  } else {
                      document.getElementById("countdown").innerHTML = "Building Information Updated Succeessfully! Redirecting in " + timeleft + " seconds remaining";
                      window.location.href = "buildings.php";
                  }
                    timeleft -= 1;
                    }, 1000);
                </script>';
            }
        }catch(PDOException $e){
          echo '<div class="alert alert-danger" role="alert">
                  Update Failed "'.$e->getMessage().'"
                </div>';
        }

      }
      ?>
      <section class="content">
        <div class="card shadow">
          <div class="card-header">
            <b>Editing Building Information for <?php echo $building_name ;?></b>
          </div>
          <?php
          
          ?>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id;?>">
            <div class="card-body">

              <div class="row">
                <div class="col-lg-12 mx-auto">
                  <div class="accordion" id="accordionExample">
                    <!-- Building Identification Section -->
                    <div class="card shadow">
                      <div class="card-header" data-toggle="collapse" data-target="#buildingIdentification" aria-expanded="true" aria-controls="collapseOne" id="headingOne" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
                        <h2 class="clearfix">Building Identification Section</h2>
                      </div>
                      <div id="buildingIdentification" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">                            
                          <div class="media-body">
                            <div class="row p-3" style="border: 1px solid rgb(0, 25, 45, 0.2); border-radius: 5px;">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Building Name</label> <sup class="text-danger"><b>*</b></sup>
                                  <input type="text" class="form-control" id="building_name" name="building_name" value="<?php echo $building_name ;?>">
                                </div>
                              </div>
                            </div>
                            <h5 class="text-center mt-3" style="font-weight: bold;">Location Information</h5>
                            <div class="row p-3 mt-2" style="border: 1px solid rgb(0, 25, 45, 0.2); border-radius: 5px;">
                              <div class="col-12 col-sm-4">
                                <div class="form-group">
                                  <label>County</label>
                                  <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" id="county" name="county" onchange="FetchConstituency(this.value)">
                                    <option value="<?php echo $county ;?>" hidden selected><?php echo $county ;?></option>
                                    <?php
                                    $select_county = "SELECT * FROM county ORDER BY id ASC";
                                    $result = $conn->prepare($select_county);
                                    $result->execute();
                                    while($row = $result->fetch()) {
                                      ?>
                                      <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                      <?php
                                    }
                                    ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Constituency</label>
                                  <select class="form-control select2 select2-danger" name="constituency" id="constituency" data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="FetchWard(this.value)">
                                    <option value="<?php echo $constituency ;?>" selected hidden><?php echo $constituency ;?></option>
                                  </select>
                                  <b class="errorMessages" id="constituencyError"></b>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Ward</label>
                                  <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" name="ward" id="ward">
                                    <option value="<?php echo $ward ;?>" selected hidden><?php echo $ward ;?></option>
                                  </select>
                                  <b class="errorMessages" id="wardError"></b>
                                </div>
                              </div>
                            </div>
                            <div class="row p-3 mt-2" style="border: 1px solid rgb(0, 25, 45, 0.2); border-radius: 5px;">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Structural Type</label>
                                  <select class="form-control" name="structure_type" id="structure_type">
                                    <option value="<?php echo $structure_type ;?>"><?php echo $structure_type ;?></option>
                                    <option value="High Rise">High Rise</option>
                                    <option value="Low Structure">Low Structure</option>
                                  </select>
                                  <div class="form-group mt-1" id="floorsNo" style="display:none;">
                                    <input type="text" class="form-control" id="floors_no" name="floors_no" value="<?php echo $floors_no ;?>">
                                  </div>
                                  <?php
                                  if($structure_type == 'High Rise') {
                                    ?>
                                    <script>
                                      var floorsNoSection = document.getElementById('floorsNo');
                                      floorsNoSection.style.display = 'block';
                                    </script>
                                    <?php
                                  } else {
                                    ?>
                                    <script>
                                      floorsNoSection.style.display = 'none';
                                    </script>
                                    <?php
                                  }
                                  ?>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Number of Units</label>
                                  <input class="form-control" name="no_of_units" id="no_of_units" value="<?php echo $no_of_units ;?>">
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label>Building Type</label>
                                  <select id="building_type" name="building_type" class="form-control">
                                    <option value="<?php echo $building_type ;?>" selected hidden><?php echo $building_type ;?></option>
                                    <option value="Residential">Residential</option>
                                    <option value="Commercial">Commercial</option>
                                    <option value="Industrial">Industrial</option>
                                    <option value="Mixed-Use">Mixed-Use</option>
                                    <option value="Mixed-Use">Ware House</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-1">
                                <div class="form-group">
                                  <label>Tax</label>
                                  <input type="text" class="form-control" name="tax_rate" id="tax_rate" value="<?php echo $tax_rate ;?>">
                                </div>
                              </div>
                            </div>
                          </div>                            
                        </div>
                      </div>
                    </div>
                    <!-- Building Ownership Section -->
                    <div class="card shadow">
                      <div class="card-header" data-toggle="collapse" data-target="#buildingOwnership" aria-expanded="true" aria-controls="collapseOne" id="headingOne" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
                        <h2 class="clearfix">Building Ownership Information</h2>
                      </div>
                      <div id="buildingOwnership" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">                            
                          <div class="media-body">
                            <div class="row p-3" style="border: 1px solid rgb(0, 25, 45, 0.2); border-radius: 5px;">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Ownership Mode</label>
                                  <select class="form-control" name="ownership_info" id="ownership_info" title="Mode of Ownership">
                                    <option value="<?php echo $ownership_info ;?>" hidden selected><?php echo $ownership_info ;?></option>
                                    <option value="Individual">Individual</option>
                                    <option value="Entity">Entity</option>
                                  </select>
                                </div> <hr>
                                <!-- Individual Owner Information -->
                                <div class="card shadow" id="individualOwnership" style="display:none;">
                                  <div class="card-header" style="background-color:rgb(0, 25, 45); color: #fff;"><b>Individual Ownership Information (Hide this Later)</b></div>
                                  <div class="card-body">
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label>First Name</label>
                                          <input type="text" class="form-control" id="firstName" placeholder="First Name" name="first_name" value="<?php echo $first_name ;?>">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label>Last Name</label>
                                          <input type="text" class="form-control" id="lastName" placeholder="Last Name" name="last_name" value="<?php echo $last_name ;?>">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label>Identification Number</label>
                                      <input type="text" class="form-control" id="id_number" name="id_number" value="<?php echo $id_number ;?>">
                                    </div>
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label>Primary Contact</label>
                                          <input type="text" class="form-control" id="primary_contact" name="primary_contact" placeholder="Phone Number" value="<?php echo $primary_contact ;?>">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label>Other Contact</label>
                                          <input type="text" class="form-control" id="other_contact" name="other_contact" placeholder="Other Number" value="<?php echo $other_contact ;?>">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label>Email</label>
                                      <input type="text" class="form-control" id="ownerEmail" placeholder="Email" name="owner_email" value="<?php echo $owner_email ;?>">
                                    </div>
                                    <div class="form-group">
                                      <label>Postal Address </label><sup>(Optional)</sup>
                                      <input type="text" class="form-control" id="postal_address" name="postal_address" placeholder="Postal Address" value="<?php echo $postal_address ;?>">
                                    </div>
                                  </div>
                                </div>
                                <!-- Entity Ownership Information -->
                                <div class="card shadow" id="entityOwnership" style="display:none;">
                                  <div class="card-header" style="background-color:rgb(0, 25, 45); color: #fff;"><b>Entity Ownership Information (Hide this Later)</b></div>
                                  <div class="card-body">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label>Entity Name</label>
                                          <input type="text" class="form-control" id="entityName" name="entity_name" placeholder="Entity Name" value="<?php echo $entity_name ;?>">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label>Primary Contact</label>
                                          <input type="text" class="form-control" id="entity_phone" name="entity_phone" placeholder="Primary Contact" value="<?php echo $entity_phone ;?>">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label>Other Contact</label>
                                          <input type="text" class="form-control" id="entity_phoneother" name="entity_phoneother" placeholder="Other Contact" value="<?php echo $entity_phoneother ;?>">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label>Official Email</label>
                                      <input type="text" class="form-control" id="entityEmail" placeholder="Entity Email" name="entity_email" value="<?php echo $entity_email ;?>">
                                    </div>
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label>Entity Representative</label>
                                          <input type="text" class="form-control" id="entityRepresentative" placeholder="Entity Representative" name="entity_rep" value="<?php echo $entity_rep ;?>">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label>Role</label>
                                          <select id="entityRepRole" class="form-control" name="rep_role">
                                            <option value="<?php echo $rep_role ;?>" selected hidden><?php echo $rep_role ;?></option>
                                            <option value="CEO">CEO</option>
                                            <option value="Treasury">Treasury</option>
                                            <option value="Board Member">BoardMember</option>
                                            <option value="Signatory">Signatory</option>
                                            <option value="Founder">Founder</option>
                                            <option value="Co-Founder">Co-Founder</option>
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label>Postal Address</label> <sup>Optional</sup>
                                      <input class="form-control" name="entity_postal" id="postal_co" placeholder="Postal Address" value="<?php echo $entity_postal ;?>">
                                    </div>
                                  </div>
                                </div>
                                <?php
                                if($ownership_info == 'Individual') {
                                  ?>
                                  <script>
                                    var individualOwnershipSection = document.getElementById('individualOwnership');
                                    individualOwnershipSection.style.display = 'block';
                                  </script>
                                  <?php

                                } else {
                                  ?>
                                  <script>
                                    var entityOwnershipSection = document.getElementById('entityOwnership');
                                    entityOwnershipSection.style.display = 'block';
                                  </script>
                                  <?php
                                }
                                ?>
                              </div>
                            </div>
                          </div>                            
                        </div>
                      </div>
                    </div>
                    <!-- Building Ownership Section -->
                    <div class="card shadow">
                      <div class="card-header" data-toggle="collapse" data-target="#ownershipProof" aria-expanded="true" aria-controls="collapseOne" id="headingOne" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
                        <h2 class="clearfix">Ownership Proof Information</h2>
                      </div>
                      <div id="ownershipProof" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">                            
                          <div class="media-body">
                            <div class="row p-3 mt-3" style="border: 1px solid rgb(0, 25, 45, 0.2); border-radius: 5px;">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Proof of Ownership</label>
                                  <input type="hidden" name="old_image_one" value="<?php echo $ownership_proof ;?>">
                                  <input type="file" class="form-control" id="ownership_proof" name="ownership_proof">
                                  <img src="<?php echo $ownership_proof ;?>" class="img img-lg mt-2">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Title Deed Copy</label>
                                  <input type="hidden" name="old_image_two" value="<?php echo $title_deed ;?>">
                                  <input type="file" class="form-control" id="title_deed" name="title_deed">
                                  <img src="<?php echo $title_deed ;?>" class="img img-lg mt-2">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Other Legal Document</label>                            
                                  <input type="hidden" name="old_image_three" value="<?php echo $legal_document ;?>">
                                  <input type="file" class="form-control" id="legal_document" name="legal_document">
                                  <img src="<?php echo $legal_document ;?>" class="img img-lg mt-2">
                                </div>
                              </div>
                            </div>
                          </div>                            
                        </div>
                      </div>
                    </div>
                    <!-- Photos and Features Section -->
                    <div class="card shadow">
                      <div class="card-header" data-toggle="collapse" data-target="#photosAndFeatures" aria-expanded="true" aria-controls="collapseOne" id="headingOne" style="border-top-right-radius: 5px; border-top-left-radius: 5px;">
                        <h2 class="clearfix">Building Photos and Features Information</h2>
                      </div>
                      <div id="photosAndFeatures" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">                            
                          <div class="media-body">
                            <div class="row p-3 mt-2" style="border: 1px solid rgb(0, 25, 45, 0.2); border-radius: 5px;">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Utilities and Amenities</label>
                                  <textarea id="summernote" class="form-control" name="utilities" rows="5"><?php echo $utilities ;?></textarea>
                                </div>
                              </div>
                            </div> 
                            <div class="row p-3 mt-2" style="border: 1px solid rgb(0, 25, 45, 0.2); border-radius: 5px;">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Photo 1</label>
                                  <input type="hidden" name="photo_one_old" value="<?php echo $photo_one;?>">
                                  <input type="file" class="form-control" name="photo_one" id="photo_one">
                                  <img src="<?php echo $photo_one ;?>" class="img-lg mt-2">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Photo 2</label>
                                  <input type="hidden" name="photo_two_old" value="<?php echo $photo_two ;?>">
                                  <input type="file" class="form-control" name="photo_two" id="photo_two">
                                  <img src="<?php echo $photo_two ;?>" class="img-lg mt-2">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Photo 3</label>
                                  <input type="hidden" name="photo_three_old" value="<?php echo $photo_three ;?>">
                                  <input type="file" class="form-control" name="photo_three" id="photo_three">
                                  <img src="<?php echo $photo_three ;?>" class="img-lg mt-2">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Photo 4</label>
                                  <input type="hidden" name="photo_four_old" value="<?php echo $photo_four ;?>">
                                  <input type="file" class="form-control" name="photo_four" id="photo_four">
                                  <img src="<?php echo $photo_four ;?>" class="img-lg mt-2">
                                </div>
                              </div>
                            </div>
                            <input type="hidden" name="added_on" class="form-control" value="<?php echo $added_on ;?>" readonly>
                            <input type="hidden" name="confirm" class="form-control" value="<?php echo $confirm;?>" readonly>
                          </div>                            
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button class="btn btn-sm" type="submit" name="update" style="background-color:rgb(0, 25, 45); color: #fff;"><i class="fa fa-check"></i> Update and Save</button>
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
  <!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
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
      specifyFloorsSection.style.display='none';
    }
  </script>
  <script>
    $(document).ready(function(){
      $("#showOwnerShipMode").click(function(){
        $("#ownerShipMode").show();
        $("#hideFirstSection").show();
        $("#showOwnerShipMode").hide();
      });

      $("#hideFirstSection").click(function(){
        $("#showOwnerShipMode").show();
        $("#hideFirstSection").hide();
        $("#ownerShipMode").hide();
      });

      $("#showPhotosFeatures").click(function(){
        $("#photosFeatures").show();
        $("#hidePhotosFeatures").show();
        $("#showPhotosFeatures").hide();
      });
    });
  </script>    
  <script>
  $(function () {
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