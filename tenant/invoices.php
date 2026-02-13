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
              <b>All Invoices</b>
            </div>
            <div class="card-body">
              <table class="table table-striped" id="dataTable">
                <thead>
                  <th>Invoice No.</th>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Options</th>
                </thead>
                <tbody>
                  <tr>
                    <td><?php echo 'INV-'.rand(0,200000);?></td>
                    <td><?php echo date('d, M Y');?></td>
                    <td>Kshs.24,000</td>
                    <td>
                      <button class="btn btn-sm" type="button" style="border:1px solid #cc0001; color: #cc0001;"><i class="fa fa-exclamation"></i> Pending</button>
                    </td>
                    <td>
                      <button class="btn btn-sm" style="border: 1px solid #00192D; color: #00192D;" data-toggle="modal" data-target="#pending-invoice">
                        <i class="fa fa-check"></i> Pay Now
                      </button>
                      <button class="btn btn-sm" style="border: 1px solid #00192D; color: #00192D;" data-toggle="modal" data-target="#invoice-details">
                        <i class="fa fa-check"></i> Details
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td><?php echo 'INV-'.rand(0,200000);?></td>
                    <td><?php echo date('d, M Y');?></td>
                    <td>Kshs.55,000</td>
                    <td>
                      <button class="btn btn-sm" type="button" style="border:1px solid #24953E; color: #24953E;"><i class="fa fa-check"></i> Cleared</button>
                    </td>
                    <td>
                      <button class="btn btn-sm" style="border: 1px solid #00192D; color: #00192D;">
                        <i class="fa fa-check"></i> Details
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!-- Pay Pending Invoice Modal -->
              <div class="modal fade" id="pending-invoice">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <b class="modal-title">Invoice Payment</b>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="" method="post" enctype="multpart/form-data">
                      <div class="modal-body">
                        <b class="text-center">This is the Preview of the Invoice Sent to you</b>
                        <table class="table table-striped">
                          <thead>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Total</th>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Rent</td>
                              <td>Rent for July 2025</td>
                              <td>1</td>
                              <td>Kshs.45.000</td>
                              <td>Kshs.45.000</td>
                            </tr>
                            <tr>
                              <td>Garbage</td>
                              <td>Garbage for July</td>
                              <td>1</td>
                              <td>Kshs.500</td>
                              <td>Kshs.500</td>
                            </tr>
                            <tr>
                              <td>Water</td>
                              <td>Water for July</td>
                              <td>10</td>
                              <td>Kshs.150</td>
                              <td>Kshs.1500</td>
                            </tr>
                          </tbody>
                          <tbody>
                            <tr>
                              <td></td>
                              <td>
                                <b>Totals</b>
                              </td>
                              <td>
                                <b>12</b>
                              </td>
                              <td><b>Kshs.45,650</b></td>
                              <td></td>
                            </tr>
                          </tbody>
                        </table>
                        <hr>
                        <div class="card shadow">
                          <div class="card-header"><b>The Invoice Preview above is what you are supposed to Pay.</b></div>
                          <div class="card-body text-center">                            
                            <b>Choose Payment Option</b>
                            <div class="row">
                              <div class="col-md-6 p-3 text-right">
                                <input type="radio" name="payment_option" data-toggle="modal" data-target="#mpesaModal"> MPESA
                              </div>
                              <div class="col-md-6 p-3 text-left">
                                <input type="radio" name="payment_option"> Bank
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- Invoice Details -->
              <div class="modal fade" id="invoice-details">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Large Modal</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <p>One fine body&hellip;</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
              <!--MPESA Modal -->
              <div class="modal fade" id="mpesaModal">
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <div class="modal-header text-center">
                      <b>Payment Confirmation</b>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      </button>
                    </div>
                    <form>
                    <div class="modal-body" id="mpesaModal">
                      <p class="text-center">You are about to Pay <b>Kshs.45,650</b> Which will be duducted from your MPESA Account. Just Enter the PIN on your Phone to complete the Payment</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-danger btn-sm" onclick="closeMpesaPayModal()" style="background-color:#cc0001; color: #fff;">Cancel</button>
                      <button type="submit" class="btn btn-sm" style="background-color: #00192D; color: #fff;">Compelete Payment</button>
                    </div>
                    </form>
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
  <!-- ./wrapper -->
  <!-- Required Scripts -->
  <?php include_once 'includes/required_scripts.php';?>
  <script>
    var mpesaModalDisplay = document.getElementById('mpesaModal');
    function closeMpesaPayModal(){
      mpesaModalDisplay.style.display = 'none';
    }
  </script>
</body>

</html>