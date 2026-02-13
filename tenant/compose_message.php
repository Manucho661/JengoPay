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
                 <div class="card-body">
                     <b>Compose Message Here</b>
                 </div>
             </div>
                <div class="row">
                    <div class="col-md-3">
                        <a href="message_box.php" class="btn btn-block mb-3" style="background-color:#00192D; color:#fff;">Back to Inbox</a>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Sections</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="nav nav-pills flex-column">
                                    <li class="nav-item active">
                                        <a href="message_box.php" class="nav-link">
                                            <i class="fas fa-inbox"></i> Inbox
                                            <span class="badge bg-primary float-right">12</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-envelope"></i> Sent
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-file-alt"></i> Drafts
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="fas fa-filter"></i> Junk
                                            <span class="badge bg-warning float-right">65</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-trash-alt"></i> Trash
                                        </a>
                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                    <div class="col-md-9">
                        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div class="card" style="border-top: 5px solid #00192D;">
                  <div class="card-header">
                    <h3 class="card-title">Compose New Message</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="form-group">
                    <label> To</label>
                     <select name="msg_to" id="msg_to" class="form-control" required>
                         <option value="" selected hidden>-- Select Receipients --</option>
                         <option value="Select Registered Users From Database">Select Registered Users From Database</option>
                     </select>
                    </div>
                    <div class="form-group">
                     <label>Subject</label>
                      <input class="form-control" name="subject" placeholder="Subject" required type="text">
                    </div>
                    <div class="form-group">
                       <label>Message</label>
                        <textarea id="compose-textarea" class="form-control" style="height: 300px">
                          <h1><u>Heading Of Message</u></h1>
                          <h4>Subheading</h4>
                          <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                            was born and I will give you a complete account of the system, and expound the actual teachings
                            of the great explorer of the truth, the master-builder of human happiness. No one rejects,
                            dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know
                            how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again
                            is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain,
                            but because occasionally circumstances occur in which toil and pain can procure him some great
                            pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise,
                            except to obtain some advantage from it? But who has any right to find fault with a man who
                            chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that
                            produces no resultant pleasure? On the other hand, we denounce with righteous indignation and
                            dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so
                            blinded by desire, that they cannot foresee</p>
                          <ul>
                            <li>List item one</li>
                            <li>List item two</li>
                            <li>List item three</li>
                            <li>List item four</li>
                          </ul>
                          <p>Thank you,</p>
                          <p>John Doe</p>
                        </textarea>
                    </div>
                    <div class="form-group">
                      <label>Attachment</label>
                      <input type="file" name="attachment" class="form-control" onchange="handleFiles(event)">
                      <!-- Section to display selected files' previews and sizes -->
                     <div id="filePreviews" class="card"></div>
                      <p class="help-block">Max. 32MB</p>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <div class="float-right">
                      
                      <button type="submit" class="btn btn-sm" style="background-color:#00192D; color:#fff;"><i class="fa fa-check"></i> Send</button>
                    </div>
                    <button type="button" class="btn btn-sm" style="background-color:#cc0001; color:#fff;"><i class="fa fa-times"></i> Dismiss</button>
                  </div>
                  <!-- /.card-footer -->
                </div>
                        </form>
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
      <!-- Summernote -->
    <script>
        $(function() {
            // Summernote
            $('#compose-textarea').summernote()

            // CodeMirror
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        })
    </script>
    </body>

    </html>