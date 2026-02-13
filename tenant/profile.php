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
                            <div class="col-md-3">
                                <!-- Profile Image -->
                                <div class="card shadow" style="border-top:4px solid #cc0001;">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img class="profile-user-img img-fluid img-circle" src="../dist/img/user4-128x128.jpg" alt="User Profile Image">
                                        </div>
                                        <h3 class="profile-username text-center" style="color:#00192D; font-weight:bold;">Paul Pashan Paul</h3>
                                        <p class="text-muted text-center">Sofware Developer <br>
                                        Pashan Technologies Nairobi <br>
                                        <i class="fa fa-building"></i> Angela Apartments<br>
                                        <i class="fa fa-home"></i> CH010</p>
                                        <!--<ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Followers</b> <a class="float-right">1,322</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Following</b> <a class="float-right">543</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Friends</b> <a class="float-right">13,287</a>
                                            </li>
                                        </ul>-->
                                        <a lass="nav-link" href="#updateProfile" data-toggle="tab" class="btn btn-block" style="background-color:#cc0001; color:#fff;"><b>Edit Profile</b></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="card shadow" style="border-top:4px solid #cc0001;">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#generalSummary" data-toggle="tab" style="font-weight:bold;">General Summary</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#trackPayment" data-toggle="tab"><b>Track Payment</b></a></li>
                                            <li class="nav-item"><a class="nav-link" href="#updateProfile" data-toggle="tab"><b>Edit Profile</b></a></li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="generalSummary">
                                                <div class="post">
                                                    <div class="card shadow">
                                                        <div class="card-header"><b>Official Names</b></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4"><b>First Name: Paul</b></div>
                                                                <div class="col-md-4"><b>Middle Name: Pashan</b></div>
                                                                <div class="col-md-4"><b>Last Name: Pashan</b></div>
                                                            </div>
                                                        </div>
                                                    </div> <hr>
                                                    <div class="card shadow">
                                                        <div class="card-header"><b>Contact Information</b></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4"><b>Main Contact: 0716691440</b></div>
                                                                <div class="col-md-4"><b>Alt Contact: 0712812990</b></div>
                                                                <div class="col-md-4"><b>Email: paulwamoka@gmail.com</b></div>
                                                            </div>
                                                        </div>
                                                    </div> <hr>
                                                    <div class="card shadow">
                                                        <div class="card-header"><b>Identifications</b></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6"><b>ID Number: 32192901</b></div>
                                                                <div class="col-md-6"><b>KRA PIN: AA20392LS</b></div>
                                                            </div>
                                                        </div>
                                                    </div> <hr>
                                                    <div class="card shadow">
                                                        <div class="card-header"><b>Attachments</b></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6"><b>Copy of Id will be Here</b></div>
                                                                <div class="col-md-6"><b>Copy of KRA to be Here</b></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Timeline Tab -->
                                            <div class="tab-pane" id="trackPayment">
                                                <div class="timeline timeline-inverse">
                                                    
                                                    <div>
                                                        <i class="fas fa-envelope" style="background-color:#cc0001; color:#fff;"></i>
                                                        <!-- Timeline Item -->
                                                        <div class="timeline-item shadow">
                                                            <span class="time"><i class="far fa-calendar"></i> <?php echo date('d, m Y H:m:s');?></span>
                                                            <h3 class="timeline-header"><a href="#">Rental Payment</a> Message Details</h3>
                                                            <div class="timeline-body">
                                                                <p>TD85YMOHL7 Confirmed, Kshs. 75,000.00 sent to Co-operative Bank Money Transfar for Account 40015184 on <?php echo date('d/m/y') .' at '. date('h:m');?> New M-PESA balance is Kshs.200,000.00. Transaction cost, 120.00. Amount you can transact within the day is 494,985.00. Save frequent paybills for quick payment on M-PESA app <a href="https://bit.ly/mpesalnk">https://bit.ly/mpesalnk</a></p>
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a href="delete_message" class="btn btn-danger btn-sm" onclick="return confirm ('Warning! Deleting will lead to the Absolute Total Loss of this Message. Be reminded that this action CAN\'T be Reversed.');" style="background-color:#cc0001; color:#fff;">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-envelope" style="background-color:#cc0001; color:#fff;"></i>
                                                        <!-- Timeline Item -->
                                                        <div class="timeline-item">
                                                            <span class="time"><i class="far fa-clock"></i> <?php echo date('H:m:s');?></span>
                                                            <h3 class="timeline-header"><a href="#">Rental Payment</a> Message Details</h3>
                                                            <div class="timeline-body">
                                                                <p>TD30OANOZA8 Confirmed, Kshs. 75,000.00 sent to Co-operative Bank Money Transfar for Account 40015184 on <?php echo date('d/m/y') .' at '. date('h:m');?> New M-PESA balance is Kshs.310,000.00. Transaction cost, 120.00. Amount you can transact within the day is 500,985.00. Save frequent paybills for quick payment on M-PESA app <a href="https://bit.ly/mpesalnk">https://bit.ly/mpesalnk</a></p>
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a href="delete_message" class="btn btn-danger btn-sm" onclick="return confirm ('Warning! Deleting will lead to the Absolute Total Loss of this Message. Be reminded that this action CAN\'T be Reversed.');" style="background-color:#cc0001; color:#fff;">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->
                                                    
                                                    <div>
                                                        <i class="fas fa-envelope" style="background-color:#cc0001; color:#fff;"></i>
                                                        <!-- Timeline Item -->
                                                        <div class="timeline-item">
                                                            <span class="time"><i class="far fa-clock"></i> <?php echo date('H:m:s');?></span>
                                                            <h3 class="timeline-header"><a href="#">Rental Payment</a> Message Details</h3>
                                                            <div class="timeline-body">
                                                                <p>TD30OANOZA8 Confirmed, Kshs. 75,000.00 sent to Co-operative Bank Money Transfar for Account 40015184 on <?php echo date('d/m/y') .' at '. date('h:m');?> New M-PESA balance is Kshs.129,000.00. Transaction cost, 120.00. Amount you can transact within the day is 500,985.00. Save frequent paybills for quick payment on M-PESA app <a href="https://bit.ly/mpesalnk">https://bit.ly/mpesalnk</a></p>
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a href="delete_message" class="btn btn-danger btn-sm" onclick="return confirm ('Warning! Deleting will lead to the Absolute Total Loss of this Message. Be reminded that this action CAN\'T be Reversed.');" style="background-color:#cc0001; color:#fff;">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->
                                                    
                                                    <div>
                                                        <i class="fas fa-envelope" style="background-color:#cc0001; color:#fff;"></i>
                                                        <!-- Timeline Item -->
                                                        <div class="timeline-item">
                                                            <span class="time"><i class="far fa-clock"></i> <?php echo date('H:m:s');?></span>
                                                            <h3 class="timeline-header"><a href="#">Rental Payment</a> Message Details</h3>
                                                            <div class="timeline-body">
                                                                <p>TD30OANOZA8 Confirmed, Kshs. 75,000.00 sent to Co-operative Bank Money Transfar for Account 40015184 on <?php echo date('d/m/y') .' at '. date('h:m');?> New M-PESA balance is Kshs.291,000.00. Transaction cost, 120.00. Amount you can transact within the day is 500,985.00. Save frequent paybills for quick payment on M-PESA app <a href="https://bit.ly/mpesalnk">https://bit.ly/mpesalnk</a></p>
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a href="delete_message" class="btn btn-danger btn-sm" onclick="return confirm ('Warning! Deleting will lead to the Absolute Total Loss of this Message. Be reminded that this action CAN\'T be Reversed.');" style="background-color:#cc0001; color:#fff;">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->
                                                    
                                                </div>
                                            </div>

                                            <!-- Settings Tab -->
                                            <div class="tab-pane" id="updateProfile">
                                                <form action="" method="post" enctype="multipart/form-data">
                        <!-- Main Tenant Information Entries -->
                        <div class="card shadow" id="mainTenantCard">
                            <div class="card-header" style="background-color:#00192D; color:#FFC107;">
                                <b>Main Tenant Registration Process</b>
                            </div>
                            <div class="card-body">
                                <div class="media-body">
                                    <!-- Indicators Section Start Here -->
                                    <div class="row mt-2" style="justify-content:center; align-items:center;">
                                        <!-- Step One Personal Information Section -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepOneIndicatorNo">1</b>
                                            <p class="mt-2" id="stepOneIndicatorText" style="font-size:14px; font-weight:bold;">Personal Information</p>
                                        </div>
                                        <!-- Step Two Occupants Information Details -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepTwoIndicatorNo">2</b>
                                            <p class="mt-2" id="stepTwoIndicatorText" style="font-size:14px; font-weight:bold;">Occupants &amp; Unit</p>
                                        </div>
                                        <!-- Step Three Pets Ownership Information -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepThreeIndicatorNo">3</b>
                                            <p class="mt-2" id="stepThreeIndicatorText" style="font-size:14px; font-weight:bold;">Pets Information</p>
                                        </div>
                                        <!-- Step 4 Source of Income Information -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepFourIndicatorNo">4</b>
                                            <p class="mt-2" id="stepFourIndicatorText" style="font-size:14px; font-weight:bold;">Source of Income</p>
                                        </div>
                                        <!-- Step Five Copy of Agreement Copy Upload  -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepFiveIndicatorNo">5</b>
                                            <p class="mt-2" id="stepFiveIndicatorText" style="font-size:14px; font-weight:bold;">Rental Agreement Copy</p>
                                        </div>
                                    </div>
                                    <!-- Indicators Section End Here -->
                                    <!-- Section One Personal Information -->
                                    <div class="card shadow" id="sectionOnePersonalInfo">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Personal Information</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>First Name</label> <sup class="text-danger"><b>*</b></sup>
                                                    <input type="text" class="form-control" name="tenant_f_name" id="tenant_f_name" placeholder="First Name">
                                                    <b class="text-danger" id="tenant_f_nameError"></b>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Middle Name</label> <sup class="text-danger"><b>*</b></sup>
                                                    <input type="text" class="form-control" name="tenant_m_name" id="tenant_m_name" placeholder="Middle Name">
                                                    <b class="text-danger" id="tenant_m_nameError"></b>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Last Name</label> <sup class="text-danger"><b>*</b></sup>
                                                    <input type="text" class="form-control" name="tenant_l_name" id="tenant_l_name" placeholder="Last Name">
                                                    <b class="text-danger" id="tenant_l_nameError"></b>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Main Contact Phone</label> <sup class="text-danger"><b>*</b></sup>
                                                        <input type="tel" class="form-control" name="tenant_m_contact" id="tenant_m_contact" placeholder="Main Contact Phone">
                                                    </div>
                                                    <b class="text-danger" id="tenant_m_contactError"></b>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Alt Contact Phone</label> <sup class="text-danger"><b>*</b></sup>
                                                        <input type="tel" class="form-control" name="tenant_a_contact" id="tenant_a_contact" placeholder="Alternative Contact Phone">
                                                    </div>
                                                    <b class="text-danger" id="tenant_a_contactError"></b>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email</label> <sup class="text-danger"><b>*</b></sup>
                                                        <input type="email" class="form-control" name="tenant_email" id="tenant_email" placeholder="Email">
                                                    </div>
                                                    <b class="text-danger" id="tenant_emailError"></b>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Identification No.</label> <sup class="text-danger"><b>*</b></sup>
                                                        <input type="text" class="form-control" name="tenant_id_no" id="tenant_id_no" placeholder="Identification Number">
                                                    </div>
                                                    <b class="text-danger" id="tenant_id_noError"></b>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Upload a Copy of Identification</label>
                                                        <input type="file" class="form-control" id="tenant_id_copy" id="tenant_id_copy">
                                                    </div>
                                                    <b class="text-danger" id="tenant_id_copyError"></b>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>KRA PIN</label>
                                                        <input type="text" class="form-control" id="kra_pin" id="kra_pin">
                                                    </div>
                                                    <b class="text-danger" id="kra_pin_Error"></b>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>KRA PIN Copy</label>
                                                        <input type="file" class="form-control" id="kra_pin_copy" id="kra_pin_copy">
                                                    </div>
                                                    <b class="text-danger" id="kra_pin_copy_Error"></b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-sm next-btn" id="firstStepNextBtn">Next Step</button>
                                        </div>
                                    </div>
                                    <!-- Section Two Occupants Information -->
                                    <div class="card" id="sectionTwoOccpantsInfo" style="display:none;">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Occupants Information</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <label>Do you have other Occupants?</label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row text-center">
                                                <div class="col-md-6">
                                                    <div class="icheck-dark d-inline">
                                                        <input type="radio" name="other_occupants" id="yesOtherOccupants" onclick="clickToSpecifyOtherOccupants();" value="Yes" value="Yes">
                                                        <label for="yesOtherOccupants"> Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="icheck-dark d-inline">
                                                        <input type="radio" name="other_occupants" id="noOtherOccupants" onclick="hideSpecifyOtherTenants();" value="No" value="No">
                                                        <label for="noOtherOccupants"> No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mt-2" id="specifyOtherTenants" style="display:none;">
                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Specify other Occupants Information</b></div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="">How many other Occupants?</label>
                                                        <input type="text" class="form-control" name="occupants_no">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Please Name them &amp; who they are in Brackets <sup>(One Name is Enough. Identity Proof will be Required before House is Given)</sup></label>
                                                            <textarea name="ocupants_names" id="ocupants_names" class="form-control" placeholder="E.g. John (Husband)"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn" id="secondStepPreviousBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn" id="secondStepNextBtn">Next Step</button>
                                        </div>
                                    </div>

                                    <!-- Section Three Pets Ownership Information -->
                                    <div class="card" id="sectionThreePetsInfo" style="display:none;">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC206;"><b>Pets Ownership Information</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <div class="form-group"><label>Do you have Pets?</label></div>
                                                </div>
                                            </div>
                                            <div class="row text-center">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="radio" class="custom-control-input" value="Yes" name="pets" id="customSwitchPetYes">
                                                            <label class="custom-control-label" for="customSwitchPetYes">Yes</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="radio" class="custom-control-input" value="No" name="pets" id="customNoPets" onclick="hideToSpecifyPets();">
                                                            <label class="custom-control-label" for="customNoPets">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card" id="specifyPetsCard" style="display:none;">
                                                <div class="card-header" style="background-color:#00192D; color:#FFC206;"><b>Please Specify Pets you Own</b></div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Select Pet(s)</label> <sup>Multiple Allowed</sup>
                                                        <select class="select2" multiple="multiple" data-placeholder="Specify Pet(s)" style="width: 100%;">
                                                            <option>Dog</option>
                                                            <option>Cat</option>
                                                            <option>Parrot</option>
                                                            <option>Snake</option>
                                                            <option>Lion</option>
                                                            <option>Tiger</option>
                                                            <option>Goat</option>
                                                            <option>Robot</option>
                                                            <option>Bunny</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn" id="thirdStepPreviousBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn" id="thirdStepNextBtn">Next Step</button>
                                        </div>
                                    </div>
                                    <!-- Section Four Source of Income Information -->
                                    <div class="card" id="sectionFourIncomeSourceInfo" style="display:none;">
                                        <div class="card-header"><b>Source of Income Information</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <label>What is your Main Source of Income?</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="radio" class="custom-control-input" id="employmentSelectionOption" value="Employment" name="income_source">
                                                            <label class="custom-control-label" for="employmentSelectionOption">Employment</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="radio" class="custom-control-input" id="business" value="Business" name="income_source">
                                                            <label class="custom-control-label" for="business">Business</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="radio" class="custom-control-input" id="empBus" value="Employment and Business" name="income_source">
                                                            <label class="custom-control-label" for="empBus">Employment &amp; Business</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card" id="employmentCard" style="display:none;">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC206;"><b>Specify your Job</b></div>
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label>Where do you Work?</label>
                                                                <input type="text" class="form-control" name="tenant_workplace">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Specify your Job Title</label>
                                                                <input type="text" class="form-control" name="tenant_jobtitle">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn" id="fourthStepPreviousBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn" id="fourthStepNextBtn">Next Step</button>
                                        </div>
                                    </div>
                                    <!-- Section Five Rental Agreement Copy Information -->
                                    <div class="card" id="sectionFiveRentalAgreementInfo" style="display:none;">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC206;"><b>Rental Agreement Copy</b></div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="">Upload a Copy of Signed Agreement</label>
                                                <input type="file" class="form-control" id="agreementAttachmentCopy" name="agreemeny_copy">
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn" id="fifththStepPreviousBtn">Back</button>
                                            <button type="submit" class="btn btn-sm next-btn" id="fifththStepNextBtn">Submit</button>
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
        <!-- Required Scripts -->
        <?php include_once 'includes/required_scripts.php';?>
    </body>
</html>