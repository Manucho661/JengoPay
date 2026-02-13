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
                        <div class="card shadow">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>My Requests</b>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button class="btn btn-sm" data-toggle="modal" data-target="#submitRequest" style="background-color:#00192D; color: #fff;">Make a Request</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <th>No.</th>
                                        <th>Request Date</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td><?php echo date('H:i:s M, d Y') ;?></td>
                                            <td>Oven and Cooker</td>
                                            <td>
                                                <button class="btn btn-sm text-light" style="background-color: #cc0001; font-weight:bold; width:100px;"><i class="fa fa-exclamation" data-toggle="tooltip" title="Status is Pending"></i> Pending</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><?php echo date('H:i:s M, d Y') ;?></td>
                                            <td>Air Conditioner</td>
                                            <td>
                                                <button class="btn btn-sm text-light" style="background-color: #2C9E4B; font-weight:bold; width:100px;"><i class="fa fa-check" data-toggle="tooltip" title="Sorted Status. No Further Action Required"></i> Sorted
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Make a Repair Request Form -->
                        <div class="modal fade shadow" id="submitRequest" style="overflow-y: scroll; height: 650px;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" style="background-color:#F2F3FA;">
                                    <div class="modal-header" style="background-color:#00192D; color:#fff;">
                                        <b class="modal-title">Submit a Request</b>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="" method="post" enctype="multpart/form-data" autocomplete="off">
                                        <div class="modal-body">
                                           <div class="row bg-danger" style="border-radius:5px; background-color:#cc0001 !important;">
                                              <div class="col-md-12 p-2 text-center">
                                                <b class="text-light">
                                                  <i class="fa fa-warning"></i> The Appliance Technician Service Provider reserves a right to access your house unit to assess the request made for proper and accurate procurement so that we may start the eradication process.
                                                </b>
                                              </div>
                                            </div> <hr>
                                            <div class="card shadow">
                                                <div class="card-header" style="background-color: #00172A; color: #fff;">
                                                    <b>Make a Appliance Repair Issue Request</b>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input type="hidden" class="form-control" name="appliance_issue" value="Appliance Issue">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card shadow" style="border:1px solid rgb(0, 25, 45, 0.3);" id="firstSection">
                                                        <div class="card-header" style="background-color:rgb(0,25,45); color:#fff;">
                                                            <b>Personal Information</b>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Requested By</label>
                                                                        <input type="text" class="form-control" id="requestedBy" data-toggle="tooltip"
                                                                               title="Automatically Filled Content" value="Paul Pashan" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Phone Number</label>
                                                                        <input type="text" class="form-control" id="requestedByPhone" data-toggle="tooltip"
                                                                               title="Automatically Filled Content" value="0716691440" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Email</label>
                                                                        <input type="text" class="form-control" id="requestedByPhone" data-toggle="tooltip"
                                                                               title="Automatically Filled Content" value="paulwamoka@gmail.com" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>House No.</label>
                                                                        <input type="text" class="form-control" id="requestedByHseNo" data-toggle="tooltip"
                                                                               title="Automaticall Filled Content" value="316" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Request To</label>
                                                                        <input type="text" class="form-control" id="requestedByBuilding" data-toggle="tooltip"
                                                                               title="Automaticall Filled Content" value="Biccount Technologies" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Building</label>
                                                                        <input type="text" class="form-control" id="requestedByBuilding" data-toggle="tooltip"
                                                                               title="Automaticall Filled Content" value="Crown Z Towers" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="button" class="btn btn-sm" style="background-color: #00172A; color: #fff;" id="firstSectionNextBtn">Next Step</button>
                                                        </div>
                                                    </div>
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,0.3); display:none;" id="secondSection" >
                                                        <div class="card-header" style="background-color:rgb(0,25,45); color:#fff;">
                                                            <b>Appliance Repair Issue</b>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label>Appliance Repair Issue</label>
                                                                <select class="form-control" name="appliance_issue" id="appliance_issue">
                                                                  <option value="" hidden selected>-- Select Option --</option>
                                                                  <option>Refrigirators and Freezers</option>
                                                                  <option>Oven and Cooker</option>
                                                                  <option>Washing Machine</option>
                                                                  <option>Microwave</option>
                                                                  <option>Fans</option>
                                                                  <option>Music Systems</option>
                                                                  <option>Television</option>
                                                                  <option>Air Conditioner</option>
                                                                </select>
                                                                <b class="text-danger mt-2" id="applianceIssueError"></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Detailed Description</label> <sup class="text-danger"><b>*</b></sup>
                                                                <textarea name="appliance_desc" id="appliance_desc" class="form-control" required
                                                                          placeholder="Write a brief Description of your Request Here"></textarea>
                                                                <b class="text-danger mt-2" id="applianceDescError"></b>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Urgence Level</label>
                                                                        <select name="appliance_urgence" id="appliance_urgence" class="form-control">
                                                                            <option value="" selected hidden>-- Select Option --
                                                                            </option>
                                                                            <option value="Immediate Attention Needed">Immediate
                                                                                Attention Needed</option>
                                                                            <option value="Urgent within 24 Hrs">Urgent within 24 Hrs
                                                                            </option>
                                                                            <option value="Next Available Appointment">Next Available
                                                                                Appointment</option>
                                                                        </select>
                                                                        <b class="text-danger mt-2" id="applianceUrgenceError"></b>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Today's Date</label>
                                                                        <input type="text" class="form-control" name="" id="" value="<?php echo date('d, M Y') ;?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>                                
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="button" class="btn btn-sm" style="background-color: #cc0001; color: #fff;" id="secondSectionBackBtn">Go Back</button>
                                                            <button type="button" class="btn btn-sm" style="background-color: #00172A; color: #fff;" id="secondSectionNextBtn">Next Step</button>
                                                        </div>
                                                    </div>
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45, 0.3); display:none;" id="thirdSection">
                                                        <div class="card-header" style="background-color:rgb(0,25,45); color:#fff;"><b>Do you want to Attach Photos?</b></div>
                                                        <div class="card-body">
                                                            <div class="row p-2">
                                                                <div class="col-md-6 text-center">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input type="radio" class="custom-control-input" id="customSwitchAttachPhotos" name="appliance_photos" value="Yes" onclick="displayToInsertAppliancePhotos();" required>
                                                                            <label class="custom-control-label" for="customSwitchAttachPhotos">Yes</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 text-center">
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="radio" class="custom-control-input" id="customSwitchNo" name="appliance_photos" value="No" onclick="displayToHideAppliancePhotos();" required>
                                                                        <label class="custom-control-label" for="customSwitchNo">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" id="appliancePhotosDiv" style="display: none; border:1px solid rgb(0, 25, 45, 0.3);">
                                                                <div class="card-header" style="background-color:rgb(0,25,45); color:#fff;"><b>Browse to Attach Photos</b></div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <label>You can Select more than One Image</label>
                                                                                <input class="form-control" type="file" id="image-selections" multiple>
                                                                                <div id="preview-container" class="mt-2"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <input type="hidden" class="form-control" name="status" value="Pending">
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="button" class="btn btn-sm" style="background-color: #cc0001; color: #fff;" id="thirdSectionBackBtn">Go Back</button>
                                                            <button type="button" class="btn btn-sm" style="background-color: #00172A; color: #fff;" id="thirdSectionNextBtn">Next Step</button>

                                                        </div>
                                                    </div>
                                                    <div class="card shadow" style="border:1px solid rgb(0, 25, 45, 0.3); border-radius:3px; display:none;" id="fourthSection">
                                                        <div class="card-header" style="background-color:rgb(0,25,45); color:#fff;"><b>Authorization</b></div>
                                                        <div class="card-body p-1">
                                                            <div class="form-group p-3">
                                                                <div class="custom-control custom-switch">
                                                                    <input type="radio" class="custom-control-input" id="customSwitchAuthorize" name="authorize_appliance_provider" value="Confirm Authorization">
                                                                    <label class="custom-control-label" for="customSwitchAuthorize"> I authorize the Appliance Technician Service provider to inspect and perform the requested services. Depending on the extent of the required service, I understand that additional charges may apply based on the final assessment.</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer text-right">
                                                            <button type="button" class="btn btn-sm" style="background-color: #cc0001; color: #fff;" id="fourthSectionBackBtn">Go Back</button>
                                                            <button type="submit" class="btn btn-sm" style="background-color: #00172A; color: #fff;" id="fourthSectionNextBtn">Submit</button>
                                                        </div>
                                                    </div>  
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
        <!-- Preview and Delete Images Before Upload -->
        <script>
            $(document).ready(function(){
                $("#image-selections").on("change", function(){
                    var files = $(this)[0].files;
                    $("#preview-container").empty();
                    if(files.length > 0){
                        for(var i = 0; i < files.length; i++){
                            var reader = new FileReader();
                            reader.onload = function(e){
                                $("<div class='preview' style='text-align:center;'><img class='img img-thumbnail mt-2' style='height:300px;' src='" + e.target.result + "' ><div class='deleteFileBtn mt-3'><button class='btn btn-sm' style='background-color:#cc0001; color:#fff;'><i class='fa fa-trash'></i> Delete</button></div></div>").appendTo("#preview-container");
                            };
                            reader.readAsDataURL(files[i]);
                        }
                    }
                });
            $("#preview-container").on("click", ".deleteFileBtn", function(){
                    confirm('Do you want to remove this image from the List');
                    $(this).parent(".preview").remove();
                    $("#image-selections").val(""); // Clear input value if needed
                });
            });
        </script>
    </body>

  </html>