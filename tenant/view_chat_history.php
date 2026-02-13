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
                <div class="card-header" style="background-color:#00192D; color:#fff;">
                  <p><i class="fa fa-comments"></i> Your Messaging History</p>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4" style="border:1px solid #F8F9FA;">
                      <h5 class="p-2">Paul Pashan <span style="font-weight:bold; font-size: 25px; color: #cc0001;">|</span> <i class="fa fa-home"></i> C101</h5>
                    </div>
                    <div class="col-md-8" style="border:1px solid #F8F9FA;">
                      <h5 class="p-2">
                        <!-- Text that will appear in accordance to the Message Title -->
                        <div id="messageHeadingContainer"></div>
                      </h5>
                    </div>
                  </div>
                  <div class="row">
                    <!-- Chat Heading, Time it was sent and some small description -->
                    <div class="col-md-4 p-2">
                      <div class="card">
                        <div class="card-body">
                          <!-- Message Container -->
                          <div class="message-container mt-2 shadow mt-2 mb-2 p-3" style="border:1px solid #F8F9FA;">
                            <!-- Messaging Time -->
                            <div><i class="fa fa-clock"></i> <?php echo date('H:m:s, d M Y');?></div>
                            <!-- Message Heading -->
                            <div id="messageHeading">
                              <a href="#" style="color:#00192D; font-weight:bold; font-size:1.3rem;">Request for Tap Repair</a>
                            </div>
                            <!-- Message Content -->
                            <div>
                              <small>Hello, Could you please repair the leaking tab that has been destroyed for...</small>
                            </div>
                          </div>

                          <!-- Message Container -->
                          <div class="message-container mt-2 shadow mt-2 mb-2 p-3" style="border:1px solid #F8F9FA;">
                            <!-- Messaging Time -->
                            <div><i class="fa fa-clock"></i> <?php echo date('H:m:s, d M Y');?></div>
                            <!-- Message Heading -->
                            <div id="messageHeading">
                              <a href="#" style="color:#00192D; font-weight:bold; font-size:1.3rem;">Request for Tap Repair</a>
                            </div>
                            <!-- Message Content -->
                            <div>
                              <small>Hello, Could you please repair the leaking tab that has been destroyed for...</small>
                            </div>
                          </div>
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
      <!-- ./wrapper -->
      <!-- Required Scripts -->
      <?php include_once 'includes/required_scripts.php';?>
      
      <!-- Display the Message Heading into the DIV section -->
      <script>
        const button = document.getElementById('messageHeading');
        const messageHeadingContainer = document.getElementById('messageHeadingContainer');

        button.addEventListener('click', function() {
          const buttonText = button.textContent;
          const newMessageHeading = document.createElement('h5');
          newMessageHeading.textContent = buttonText;
          messageHeadingContainer.appendChild(newMessageHeading);
        });
      </script>
    </body>
    </html>