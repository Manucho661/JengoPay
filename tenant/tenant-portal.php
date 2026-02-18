<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>BT-JengoPay | Tenant Portal</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Varela+Round">
  <link rel="stylesheet" href="../plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../plugins/font-awesome-4.7.0/css/font-awesome.min.css">
  <script src="../plugins/jquery/jquery.min.js"></script>
  <script src="../plugins/popper/popper.min.js"></script>
  <script src="../plugins/bootstrap/js/bootstrap.min.js"></script>
  <style>
    .navbar {
      padding: 5px 16px;
      border-radius: 0;
      border: none;
      box-shadow: 0 0 4px rgba(0, 0, 0, .1);
    }

    .navbar img {
      border-radius: 50%;
      width: 36px;
      height: 36px;
      margin-right: 10px;
    }

    .navbar .navbar-brand {
      color: #efe5ff;
      padding-left: 0;
      padding-right: 50px;
      font-size: 24px;
    }

    .navbar .navbar-brand:hover,
    .navbar .navbar-brand:focus {
      color: #fff;
    }

    .navbar .navbar-brand i {
      font-size: 25px;
      margin-right: 5px;
    }

    .navbar .nav-item i {
      font-size: 18px;
    }

    .navbar .nav-item span {
      position: relative;
      top: 3px;
    }

    .navbar .navbar-nav>a {
      color: #efe5ff;
      padding: 8px 15px;
      font-size:20px;
    }

    .navbar .navbar-nav>a:hover,
    .navbar .navbar-nav>a:focus {
      color: #fff;
      text-shadow: 0 0 4px rgba(255, 255, 255, 0.3);
    }

    .navbar .navbar-nav>a>i {
      display: block;
      text-align: center;
    }

    .navbar .dropdown-item i {
      font-size: 16px;
      min-width: 22px;
    }

    .navbar .dropdown-item .material-icons {
      font-size: 21px;
      line-height: 16px;
      vertical-align: middle;
      margin-top: -2px;
    }

    .navbar .nav-item.open>a,
    .navbar .nav-item.open>a:hover,
    .navbar .nav-item.open>a:focus {
      color: #fff;
      background: none !important;
    }

    .navbar .dropdown-menu {
      border-radius: 1px;
      border-color: #e5e5e5;
      box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
    }

    .navbar .dropdown-menu a {
      color: #777 !important;
      padding: 8px 20px;
      line-height: normal;
      font-size: 20px;
    }

    .navbar .dropdown-menu a:hover,
    .navbar .dropdown-menu a:focus {
      color: #333 !important;
      background: transparent !important;
    }

    .navbar .navbar-nav .active a,
    .navbar .navbar-nav .active a:hover,
    .navbar .navbar-nav .active a:focus {
      color: #fff;
      text-shadow: 0 0 4px rgba(255, 255, 255, 0.2);
      background: transparent !important;
    }

    .navbar .navbar-nav .user-action {
      padding: 9px 15px;
      font-size: 20px;
    }

    .navbar .navbar-toggle {
      border-color: #fff;
    }

    .navbar .navbar-toggle .icon-bar {
      background: #fff;
    }

    .navbar .navbar-toggle:focus,
    .navbar .navbar-toggle:hover {
      background: transparent;
    }

    .navbar .navbar-nav .open .dropdown-menu {
      background: #faf7fd;
      border-radius: 1px;
      border-color: #faf7fd;
      box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
    }

    .navbar .divider {
      background-color: #e9ecef !important;
    }

    @media (min-width: 1200px) {
      .form-inline .input-group {
        width: 350px;
        margin-left: 30px;
      }
    }

    @media (max-width: 1199px) {
      .navbar .navbar-nav>a>i {
        display: inline-block;
        text-align: left;
        min-width: 30px;
        position: relative;
        top: 4px;
      }

      .navbar .navbar-collapse {
        border: none;
        box-shadow: none;
        padding: 0;
      }

      .navbar .navbar-form {
        border: none;
        display: block;
        margin: 10px 0;
        padding: 0;
      }

      .navbar .navbar-nav {
        margin: 8px 0;
      }

      .navbar .navbar-toggle {
        margin-right: 0;
      }

      .input-group {
        width: 100%;
      }
    }

    .request-card {
      background-color: #00172A;
    }

    .request-card:hover {
      background-color: #FFC107;
    }

    .serv-req {
      color: #FFC107;
      text-decoration: none;
    }

    .serv-req:hover {
      color: #00172A;
      text-decoration: none;
    }

    .custom-btn {
      background-color: #00192D !important;
      color: #fff;
    }

    .custom-btn:hover {
      color: #fff;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-xl navbar-light shadow">
    <a href="#" class="navbar-brand"><b
      style="background-color:#FFC107; color:#00192D; padding:5px; border-top-left-radius:5px;">BT</b><b
      style="background-color:#00192D; color:#FFC107; padding:5px; border-bottom-right-radius:5px;">JengoPay</b></a>
      <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Collection of nav links, forms, and other content for toggling -->
      <div id="navbarCollapse" class="collapse navbar-collapse justify-content-start">
        <div class="navbar-nav ml-auto">
          <a href="#" class="nav-item nav-link active">Home</a>
          <a href="tenant_dashboard.php" class="nav-item nav-link">Dashboard</a>
          <div class="nav-item dropdown" style="font-size:20px;">
            <a class="nav-link" data-toggle="dropdown" href="#"> <i class="fa fa-bell"></i> 
             <sup>
                 <span class="badge badge-danger navbar-badge p-1" style="background-color:#cc0001;">15</span>
            </sup> 
            </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="font-size:14px;"> 
               <span class="dropdown-item dropdown-header">Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item"> <small>Your next payment is due</small> </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item"> <small>Dear Customer, your subsription</small> </a>
                <div class="dropdown-divider"></div> <a href="#" class="dropdown-item dropdown-footer">See All
                Notifications</a>
              </div>
            </div>
            <div class="nav-item dropdown">
              <a href="#" data-toggle="dropdown" class="nav-item nav-link dropdown-toggle user-action"><img
                src="images/slide-3.jpg" class="avatar" alt="Avatar"> Pashan <b class="caret"></b></a>
                <div class="dropdown-menu">
                  <a href="tenant_dashboard.php" class="dropdown-item"><i class="fa fa-clipboard"></i> Dashboard</a>
                  <a href="#" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                </div>
              </div>
            </div>
          </div>
        </nav>
        <div class="container-fluid" style="background-image: url('images/slide-3.jpg'); background-size: cover;
        background-position: center; height:500px;">
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-4">
            <h2 class="mt-5 text-light" style="font-weight:bold;"><span style="color:#FFC107;">Welcome</span> Pashan
              Paul
            </h2>
            <h5 class="text-light">Balance Due</h5>
            <h2 class="text-light" style="font-weight:bold;">Kshs.20000.00</h2>
            <h5 class="text-light"><i class="fa fa-calendar"></i> Your Next Due Payment is on 22/12/2025</h5>
            <h5 class="text-light"><i class="fa fa-home"></i> Your house no is 316 Crown Z Towers</h5>
            <h5 class="text-light"><i class="fa fa-user-circle"></i> Your Landlord is Biccount Technologies</h5>
          </div>
          <div class="col-md-4"></div>
        </div>
      </div>
      <div class="container mt-3 mb-5">
        <div class="row mb-4">
          <div class="col-md-12 text-center">
            <h5 class="p-4"
            style="background-color:rgb(255,193,7, 0.3); border-radius:10px; color:#00192D; font-weight:bold;">
            <i class="fa fa-home"></i> Hello Pashan, this portal acts as a quick way to enhance communication
            with the
            house owner. There are major features that you can use to have your issues sorted with in the
            shortest time
            possible
          </h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mt-3">
          <div class="card bt-light text-light p-5 text-center request-card">
            <a href="#" class="serv-req" data-toggle="modal" data-target="#submitRequest">
              <h4 style="font-weight:bold;"><i class="fa fa-cogs"></i> <br><br>Submit a Repair Request</h4>
            </a>
          </div>
        </div>
        <div class="col-md-4 mt-3">
          <div class="card bt-light text-light p-5 text-center request-card">
            <a href="#" class="serv-req" data-toggle="modal" data-target="#communicateWithOwner">
              <h4 style="font-weight:bold"><i class="fa fa-comment"></i> <br><br>Communicate with the Owner
              </h4>
            </a>
          </div>
        </div>
        <div class="col-md-4 mt-3">
          <div class="card bt-light text-light p-5 text-center request-card">
            <a href="#" class="serv-req">
              <h4 style="font-weight:bold;"><i class="fa fa-money"></i> <br><br>Submit your Payment Now</h4>
            </a>
          </div>
        </div>
      </div>
      <!-- Make Request Popup -->
      <div class="modal fade shadow" id="submitRequest">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" style="background-color:#F2F3FA;">
            <div class="modal-header">
              <b class="modal-title">Submit a Request</b>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                <div class="row bg-white" style="border-radius:5px;">
                  <div class="col-md-6 p-2">
                    <h6><i class="fa fa-home"
                      style="background-color:#00192D; border-radius:6px; padding:10px; color:#FFC107;"></i>
                      Your
                    house no is 316 Crown Z Towers</h6>
                  </div>
                  <div class="col-md-6 p-2">
                    <h6><i class="fa fa-plus-circle"
                      style="background-color:#00192D; border-radius:6px; padding:10px; color:#FFC107;"></i>
                    Your Request will go to Biccount Technologies</h6>
                  </div>
                  <hr>
                </div>
              </div>
              <hr>
              <div class="container mb-3">
                <div class="row bg-danger" style="border-radius:5px;">
                  <div class="col-md-12 p-2 text-center">
                    <b class="text-light">
                      <i class="fa fa-warning"></i> We reserve a right to access your house unit
                      to assess the request
                      made for proper and accurate procurement so that we may start the repair
                      process.
                    </b>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header"><b>Select to Choose Request Category</b></div>
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchPlumbingWorks"
                          name="request_category" value="Plumbing Works" onclick="displayPlumbingForm();">
                          <label class="custom-control-label" for="customSwitchPlumbingWorks">
                          Plumbing Works</label>
                        </div>
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchElectricalshirtWorks"
                          name="request_category" value="Electrical Works" onclick="displayElectricalWorksForm();">
                          <label class="custom-control-label" for="customSwitchElectricalshirtWorks"> Electrical
                          Works</label>
                        </div>
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchStructuraltWorks"
                          name="request_category" value="Structural Repairs" onclick="displaySTructuralRepairsForm();">
                          <label class="custom-control-label" for="customSwitchStructuraltWorks">
                            Structural
                          Repairs</label>
                        </div>
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchPestControl"
                          name="request_category" value="Pest Control" onclick="displayPestControlForm();">
                          <label class="custom-control-label" for="customSwitchPestControl">
                          Pest Control</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchHvacMaintenance"
                          name="request_category" value="HVAC Maintenance" onclick="displayHvacMaintenanceForm();">
                          <label class="custom-control-label" for="customSwitchHvacMaintenance">
                            HVAC
                          Maintenance</label>
                        </div>
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchPaintingFinishing"
                          name="request_category" value="Painting and Finishing" onclick="displayPainitingReqForm();">
                          <label class="custom-control-label" for="customSwitchPaintingFinishing">
                            Painting and
                          Finishing</label>
                        </div>
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchApplianceRepairs"
                          name="request_category" value="Appliance Repairs" onclick="displayApplianceRepairForm();">
                          <label class="custom-control-label" for="customSwitchApplianceRepairs">
                            Appliance
                          Repairs</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchGeneralRepairs"
                          name="request_category" value="General Repairs" onclick="displayGeneralRepairForm();">
                          <label class="custom-control-label" for="customSwitchGeneralRepairs">
                          General Repairs</label>
                        </div>
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchOutDoorRepairs"
                          name="request_category" value="Outdoor Repairs" onclick="displayOutdoorRepairForm();">
                          <label class="custom-control-label" for="customSwitchOutDoorRepairs">
                          Outdoor Repairs</label>
                        </div>
                        <div class="custom-control custom-switch mt-3 mb-3">
                          <input type="radio" class="custom-control-input" id="customSwitchSafetySecurity"
                          name="request_category" value="Safety and Security" onclick="displaySafetySecurityForm();">
                          <label class="custom-control-label" for="customSwitchSafetySecurity">
                            Safety and
                          Security</label>
                        </div>
                      </div>
                    </div>
                    <hr>
                  </div>
                  <!-- Plumbing Works Detailed Form -->
                  <?php include_once 'includes/requests_forms/plumbing_request.php';?>
                  <!-- Electrical Works Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/electric_request.php';?>
                  <!-- Structural Repairs Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/structural_repairs.php' ;?>
                  <!-- Pest Control Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/pest_request.php' ;?>
                  <!-- HVAC Maintenance Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/hvac_maintenance.php';?>
                  <!-- Painting and Finishing Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/painting_fininshing.php';?>
                  <!-- Appliance Repairs Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/appliance_repairs.php';?> 
                  <!-- General Repairs Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/general_repairs.php';?>
                  <!-- Outdoor Repairs Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/outdoor_repairs.php';?>
                  <!-- Safety and Security Request Detailed Form -->
                  <?php include_once 'includes/requests_forms/safety_security.php';?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Make Request Popup -->

    <!-- Communicate with Owner PopUp -->
    <div class="modal fade shadow" id="communicateWithOwner">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:#F2F3FA;">
          <div class="modal-header">
            <b class="modal-title">Send a Message</b>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" autocomplete="off">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <label>Message From</label>
                  <input type="text" class="form-control" name="comm_name" value="<?php echo 'Teant Full Name';?>"
                  readonly>
                </div>
                <div class="col-md-6">
                  <label>Date</label>
                  <input type="text" class="form-control" name="comm_date" value="<?php echo date('d, M Y');?>" readonly>
                </div>
              </div>
              <div class="form-group">
                <label>Reason</label> <sup class="text-danger"><b>*</b></sup>
                <select name="comm_rzn" id="" class="form-control" required>
                  <option value="" selected hidden>-- Select Reason --</option>
                  <option value="Late Rental Payment">Late Rental Payment</option>
                  <option value="Suggestion">Suggestion</option>
                  <option value="Shifting Notice">Shifting Notice</option>
                  <option value="Nuisance">Nuisance</option>
                  <option value="Poor Service">Poor Service</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="form-group">
                <label>Description</label> <sup class="text-danger"><b>*</b></sup>
                <textarea name="comm_desc" id="" class="form-control" required
                placeholder="Write a Description of your Message Here"></textarea>
              </div>
              <div class="form-group">
                <label>Shifting Date</label>
                <input type="date" class="form-control" name="shifting_date">
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn custom-btn" name="submit_comm">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Communicate with Owner PopUp -->
  </div>
  <!-- Footer  -->
  <!-- Footer -->
  <div class="container-fluid mt-5" style="background-color:#00192D; color:#fff;">
    <footer>
      <div class="row">
        <div class="col-md-6">
          <p class="mt-3 mb-3">Site by Pashan with great <i class="fa fa-heart"></i></p>
        </div>
        <div class="col-md-6 text-md-end text-right mt-3 mb-3">
          <a href="#" class="text-light">Terms of Use</a>
          <span class="text-light">|</span>
          <a href="#" class="text-light">Privacy Policy</a>
        </div>
      </div>
    </footer>
  </div>
  <!-- Footer -->
</body>

<script>
//Requests DOM Script

//plumbing detaileed DIV
  var plumbingRequestDetailsDetailsDisplay = document.getElementById('plumbingRequestDetails');

//Electrical Detialed DIV
  var electricalRequestDetailsDisplay = document.getElementById('electricalRequestDetails');

//Structural Detailed DIV
  var structuralRequestDetailsDisplay = document.getElementById('structuralRequestDetails');

//Pest Control DIV
  var pestRequestDetailsDisplay = document.getElementById('pestRequestDetails');

//HVAC Detailed DIV
  var hvacRequestDetailsDisplay = document.getElementById('hvacRequestDetails');

//Painting and Finishing DIV
  var painitngFinishingRequestDetailsDisplay = document.getElementById('painitngFinishingRequestDetails');

//Appliance Repairs Main DIV
  var applianceRepairsRequestDetailsDisplay = document.getElementById('applianceRepairsRequestDetails');

//General Repairs Main DIV
  var generalRepairsRequestDetailsDisplay = document.getElementById('generalRepairsRequestDetails');

//Outdoor Requests Main DIV
  var outDoorRepairsRequestDetailsDisplay = document.getElementById('outDoorRepairsRequestDetails');

//Safety Security Main DIV
  var safetySecurityRequestDetailsDisplay = document.getElementById('safetySecurityRequestDetails');

  function displayPlumbingForm() {
    plumbingRequestDetailsDetailsDisplay.style.display = 'block'; //display Electrical Main Form where the requiest is specified

  //Hide other forms
    electricalRequestDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displayElectricalWorksForm() {
    electricalRequestDetailsDisplay.style.display = 'block'; // Show the form where Electrical Request Form will be Specified

  //Hide other forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displaySTructuralRepairsForm() {
    structuralRequestDetailsDisplay.style.display =
    'block'; // Show Structural Form where request details will be specified

  //Hide Other Forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    electricalRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displayPestControlForm() {
    pestRequestDetailsDisplay.style.display =
    'block'; // Show Structural Form where request details will be specified

  //Hide Other Forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    electricalRequestDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displayHvacMaintenanceForm() {
    hvacRequestDetailsDisplay.style.display =
    'block'; // Show HVAC Form where request details will be specified

  //Hide Other Forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    electricalRequestDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displayPainitingReqForm() {
    painitngFinishingRequestDetailsDisplay.style.display =
    'block'; // Show Painting and Finishing Form where request details will be specified

  //Hide Other Forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    electricalRequestDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displayApplianceRepairForm() {
    applianceRepairsRequestDetailsDisplay.style.display =
    'block'; // Show Appliance Repairs Form where request details will be specified

  //Hide Other Forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    electricalRequestDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displayGeneralRepairForm() {
    generalRepairsRequestDetailsDisplay.style.display =
    'block'; // Show General Repairs Form where request details will be specified

  //Hide Other Forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    electricalRequestDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displayOutdoorRepairForm() {
    outDoorRepairsRequestDetailsDisplay.style.display =
    'block'; // Show Outdoor Repairs Form where request details will be specified

  //Hide Other Forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    electricalRequestDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    safetySecurityRequestDetailsDisplay.style.display = 'none';
  }

  function displaySafetySecurityForm() {
    safetySecurityRequestDetailsDisplay.style.display =
    'block'; // Show Security Request Form where request details will be specified

  //Hide Other Forms
    plumbingRequestDetailsDetailsDisplay.style.display = 'none';
    electricalRequestDetailsDisplay.style.display = 'none';
    structuralRequestDetailsDisplay.style.display = 'none';
    pestRequestDetailsDisplay.style.display = 'none';
    hvacRequestDetailsDisplay.style.display = 'none';
    painitngFinishingRequestDetailsDisplay.style.display = 'none';
    applianceRepairsRequestDetailsDisplay.style.display = 'none';
    generalRepairsRequestDetailsDisplay.style.display = 'none';
    outDoorRepairsRequestDetailsDisplay.style.display = 'none';
  }

//Display an Option to Attach Plumbing Fault Photos if the user choses to include photos in the requeust form.
  var plumbingPhotosDivDisplay = document.getElementById('plumbingPhotosDiv');

  function displayToInsertPlumbingPhotos() {
    plumbingPhotosDiv.style.display = 'block';
  }

  function displayToHidePlumbingPhotos() {
    plumbingPhotosDiv.style.display = 'none';
  }

//Validation of the Form Fields in the Plumbing Form Details (Plumbing Option)
  $(document).ready(function() {
    $("#confirmPlumbingRequest").click(function(e) {
      e.preventDefault();
      $("#plumbingIssueError").html('');
      $("#plumbingDescError").html('');
      $("#plumbingUrgencyError").html('');

      if ($("#plumbing_issue").val() == '') {
        $("#plumbingIssueError").html('Select to Fill Plumbing Issue');
        $("#plumbing_issue").css('border-color', '#cc0001');
        $("#plumbing_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#plumbing_desc").val() == '') {
        $("#plumbingDescError").html('Plumbing Description Required')
        $("#plumbing_desc").css('border-color', '#cc0001');
        $("#plumbing_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#plumbing_urgence").val() == '') {
        $("#plumbingUrgencyError").html('Urgence Level Required');
        $("#plumbing_urgence").css('border-color', '#cc0001');
        $("#plumbing_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });

//Display an Option to Attach Photos Electric Fault Photos if the user choses to include in the request form.
  var electricPhotosDivDisplay = document.getElementById('electricPhotosDiv');

  function displayToInsertElectricPhotos() {
    electricPhotosDiv.style.display = 'block';
  }

  function displayToHideElectricPhotos() {
    electricPhotosDiv.style.display = 'none';
  }

//Validate Electric Form Details
  $(document).ready(function() {
    $("#confirmElectricRequest").click(function(e) {
      e.preventDefault();
      $("#electricIssueError").html('');
      $("#electricDescError").html('');
      $("#electricUrgencyError").html('');

      if ($("#electric_issue").val() == '') {
        $("#electricIssueError").html('Select to Fill Electric Issue');
        $("#electric_issue").css('border-color', '#cc0001');
        $("#electric_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#electric_desc").val() == '') {
        $("#electricDescError").html('Electric Description Required')
        $("#electric_desc").css('border-color', '#cc0001');
        $("#electric_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#electric_urgence").val() == '') {
        $("#electricUrgencyError").html('Urgence Level Required');
        $("#electric_urgence").css('border-color', '#cc0001');
        $("#electric_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });

//Display an Option to Attach Photos Structural Fault Photos if the user choses to include in the request form.
  var structuralPhotosDivDisplay = document.getElementById('structuralPhotosDiv');

  function displayToInsertStructuralPhotos() {
    structuralPhotosDiv.style.display = 'block';
  }

  function displayToHideStructuralPhotos() {
    structuralPhotosDiv.style.display = 'none';
  }

//Validate Structural Form Details
  $(document).ready(function() {
    $("#confirmStructuralRequest").click(function(e) {
      e.preventDefault();
      $("#structuralIssueError").html('');
      $("#structuralDescError").html('');
      $("#structuralUrgencyError").html('');

      if ($("#structural_issue").val() == '') {
        $("#structuralIssueError").html('Select to Fill Structural Issue');
        $("#structural_issue").css('border-color', '#cc0001');
        $("#structural_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#structural_desc").val() == '') {
        $("#structuralDescError").html('Structural Description Required')
        $("#structural_desc").css('border-color', '#cc0001');
        $("#structural_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#structural_urgence").val() == '') {
        $("#structuralUrgencyError").html('Urgence Level Required');
        $("#structural_urgence").css('border-color', '#cc0001');
        $("#structural_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });

//Display an Option to Attach Photos Pest Control Photos if the user choses to include in the request form.
  var pestPhotosDivDisplay = document.getElementById('pestPhotosDiv');

  function displayToInsertPestPhotos() {
    pestPhotosDiv.style.display = 'block';
  }

  function displayToHidePestPhotos() {
    pestPhotosDiv.style.display = 'none';
  }

//Validate Pest Form Details
  $(document).ready(function() {
    $("#confirmPestRequest").click(function(e) {
      e.preventDefault();
      $("#pestIssueError").html('');
      $("#pestDescError").html('');
      $("#pestUrgencyError").html('');

      if ($("#pest_issue").val() == '') {
        $("#pestIssueError").html('Select to Fill Pest Issue');
        $("#pest_issue").css('border-color', '#cc0001');
        $("#pest_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#pest_desc").val() == '') {
        $("#pestDescError").html('Pest Description Required')
        $("#pest_desc").css('border-color', '#cc0001');
        $("#pest_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#pest_urgence").val() == '') {
        $("#pestUrgencyError").html('Urgence Level Required');
        $("#pest_urgence").css('border-color', '#cc0001');
        $("#pest_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });

//Display an Option to Attach Photos for HVAC if the user choses to include in the request form.
  var hvacPhotosDivDisplay = document.getElementById('hvacPhotosDiv');

  function displayToInsertHvacPhotos() {
    hvacPhotosDiv.style.display = 'block';
  }

  function displayToHideHvacPhotos() {
    hvacPhotosDiv.style.display = 'none';
  }

//Validate Pest Form Details
  $(document).ready(function() {
    $("#confirmHvacRequest").click(function(e) {
      e.preventDefault();
      $("#hvacIssueError").html('');
      $("#hvacDescError").html('');
      $("#hvacUrgencyError").html('');

      if ($("#hvac_issue").val() == '') {
        $("#hvacIssueError").html('Select to Fill HVAC Issue');
        $("#hvac_issue").css('border-color', '#cc0001');
        $("#hvac_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#hvac_desc").val() == '') {
        $("#hvacDescError").html('HVAC Description Required')
        $("#hvac_desc").css('border-color', '#cc0001');
        $("#hvac_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#hvac_urgence").val() == '') {
        $("#hvacUrgencyError").html('Urgence Level Required');
        $("#hvac_urgence").css('border-color', '#cc0001');
        $("#hvac_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });

//Display an Option to Attach Photos for Paint and Finishing if the user choses to include in the request form.
  var paintfinishPhotosDivDisplay = document.getElementById('paintfinishPhotosDiv');

  function displayToInsertPaintFinishPhotos() {
    paintfinishPhotosDivDisplay.style.display = 'block';
  }

  function displayToHidePaintFinishPhotos() {
    paintfinishPhotosDivDisplay.style.display = 'none';
  }

//Validate Pest Form Details
  $(document).ready(function() {
    $("#confirmPaintFinishRequest").click(function(e) {
      e.preventDefault();
      $("#paintfinishIssueError").html('');
      $("#paintfinishDescError").html('');
      $("#paintfinishUrgencyError").html('');

      if ($("#paintfinish_issue").val() == '') {
        $("#paintfinishIssueError").html('Select to Fill Paint and Finish Issue');
        $("#paintfinish_issue").css('border-color', '#cc0001');
        $("#paintfinish_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#paintfinish_desc").val() == '') {
        $("#paintfinishDescError").html('Paint and Fininsh Description Required')
        $("#paintfinish_desc").css('border-color', '#cc0001');
        $("#paintfinish_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#paintfinish_urgence").val() == '') {
        $("#paintfinishUrgencyError").html('Urgence Level Required');
        $("#paintfinish_urgence").css('border-color', '#cc0001');
        $("#paintfinish_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });

  //Display an Option to Attach Photos for Appliances Repair if the user choses to include in the request form.
  var appliancePhotosDivDisplay = document.getElementById('appliancePhotosDiv');

  function displayToInsertAppliancePhotos() {
    appliancePhotosDiv.style.display = 'block';
  }

  function displayToHideAppliancePhotos() {
    appliancePhotosDiv.style.display = 'none';
  }

//Validate Pest Form Details
  $(document).ready(function() {
    $("#confirmAppliancesRequest").click(function(e) {
      e.preventDefault();
      $("#applianceIssueError").html('');
      $("#appliancefinishDescError").html('');
      $("#appliancefinishUrgencyError").html('');

      if ($("#appliance_issue").val() == '') {
        $("#applianceIssueError").html('Select to Appliance Mainteinance Issue');
        $("#appliance_issue").css('border-color', '#cc0001');
        $("#appliance_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#appliance_desc").val() == '') {
        $("#applianceDescError").html('Appliance Mainteinance Description Required');
        $("#appliance_desc").css('border-color', '#cc0001');
        $("#appliance_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#appliance_urgence").val() == '') {
        $("#applianceUrgencyError").html('Urgence Level Required');
        $("#appliance_urgence").css('border-color', '#cc0001');
        $("#appliance_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });



//Display an Option to Attach Photos for General Repairs if the user choses to include in the request form.
  var generalPhotosDivDisplay = document.getElementById('generalPhotosDiv');

  function displayToInsertGeneralPhotos() {
    generalPhotosDiv.style.display = 'block';
  }

  function displayToHideGeneralPhotos() {
    generalPhotosDiv.style.display = 'none';
  }

//Validate General Repairs Form Details
  $(document).ready(function() {
    $("#confirmGeneralRequest").click(function(e) {
      e.preventDefault();
      $("#generalIssueError").html('');
      $("#generalUrgencyError").html('');

      if ($("#general_issue").val() == '') {
        $("#generalIssueError").html('Select to General Mainteinance Issue');
        $("#general_issue").css('border-color', '#cc0001');
        $("#general_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#general_desc").val() == '') {
        $("#generalDescError").html('General Mainteinance Description Required');
        $("#general_desc").css('border-color', '#cc0001');
        $("#general_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#general_urgence").val() == '') {
        $("#generalUrgencyError").html('Urgence Level Required');
        $("#general_urgence").css('border-color', '#cc0001');
        $("#general_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });

  //Display an Option to Attach Photos for Outdoor Repairs if the user choses to include in the request form.
  var outdoorPhotosDivDisplay = document.getElementById('outdoorPhotosDiv');

  function displayToInsertOutdoorPhotos() {
    outdoorPhotosDivDisplay.style.display = 'block';
  }

  function displayToHideOutdoorPhotos() {
    outdoorPhotosDivDisplay.style.display = 'none';
  }

//Validate Outdoor Request Form Details
  $(document).ready(function() {
    $("#confirmOutdoorRequest").click(function(e) {
      e.preventDefault();
      $("#outdoorIssueError").html('');
      $("#outdoorUrgencyError").html('');

      if ($("#outdoor_issue").val() == '') {
        $("#outdoorIssueError").html('Select to Outdoor Mainteinance Issue');
        $("#outdoor_issue").css('border-color', '#cc0001');
        $("#outdoor_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#outdoor_desc").val() == '') {
        $("#outdoorDescError").html('Outdoor Mainteinance Description Required');
        $("#outdoor_desc").css('border-color', '#cc0001');
        $("#outdoor_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#outdoor_urgence").val() == '') {
        $("#outdoorUrgencyError").html('Urgence Level Required');
        $("#outdoor_urgence").css('border-color', '#cc0001');
        $("#outdoor_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });

  //Display an Option to Attach Photos for Safety and Security Repairs or requests if the user choses to include in the request form.
  var safetyPhotosDivDisplay = document.getElementById('safetyPhotosDiv');

  function displayToInsertSafetyPhotos() {
    safetyPhotosDivDisplay.style.display = 'block';
  }

  function displayToHideSafetyPhotos() {
    safetyPhotosDivDisplay.style.display = 'none';
  }

//Validate Outdoor Request Form Details
  $(document).ready(function() {
    $("#confirmSafetyRequest").click(function(e) {
      e.preventDefault();
      $("#safetyIssueError").html('');
      $("#safetyUrgencyError").html('');

      if ($("#safety_issue").val() == '') {
        $("#safetyIssueError").html('Select to Fill Safety and Security Request Details Issue');
        $("#safety_issue").css('border-color', '#cc0001');
        $("#safety_issue").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#safety_desc").val() == '') {
        $("#safetyDescError").html('Safety and Security Request Description Required');
        $("#safety_desc").css('border-color', '#cc0001');
        $("#safety_desc").css('background-color', '#FFDBDB');
        return false;

      } else if ($("#safety_urgence").val() == '') {
        $("#safetyUrgencyError").html('Urgence Level Required');
        $("#safety_urgence").css('border-color', '#cc0001');
        $("#safety_urgence").css('background-color', '#FFDBDB');
        return false;
      } else {
        window.location = 'process.php';
      }

    });
  });
</script>
</html>