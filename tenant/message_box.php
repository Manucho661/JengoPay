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
                    <div class="card shadow" style="background-color:#00192D; color: #fff;">
                        <div class="card-body">
                            <b>In App Communication: Message Box</b>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="compose_message.php" class="btn btn-block mb-3" style="background-color:#00192D; color:#fff;"><i class="fas fa-edit"></i> Compose Message</a>
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
                                                        <i class="fas fa-envelope-open-text"></i> Sent
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
                                    <div class="card" style="border-top: 5px solid #00192D;">
                                        <div class="card-header">
                                            <h3 class="card-title">Inbox</h3>
                                            <div class="card-tools">
                                                <form action="" method="post">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" placeholder="Search Messages">
                                                        <div class="input-group-append">
                                                            <button class="btn" type="submit" style="background-color:#00192D; color:#fff;">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="mailbox-controls">
                                                <div class="float-right">
                                                    1-50/200
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                                                        <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                                                        <!-- /.btn-group -->
                                                    </div>
                                                    <!-- /.float-right -->
                                                </div>
                                                <div class="table-responsive mailbox-messages">
                                                <table class="table table-hover table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="icheck-primary">
                                                                    <input type="checkbox" value="" id="check1">
                                                                    <label for="check1"></label>
                                                                </div>
                                                            </td>
                                                            <td class="mailbox-star">
                                                                <a href="#"><i class="fas fa-star text-warning"></i></a>
                                                            </td>
                                                            <td class="mailbox-name">
                                                                <a href="read_message.php">Paul Pashan</a>
                                                            </td>
                                                            <td class="mailbox-subject">
                                                                <b>Rental Payment</b> - Hello a Quick Reminder about...
                                                            </td>
                                                            <td class="mailbox-attachment"></td>
                                                            <td class="mailbox-date">
                                                                <?php echo date ('d, M Y') ;?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="icheck-primary">
                                                                    <input type="checkbox" value="" id="check2">
                                                                    <label for="check2"></label>
                                                                </div>
                                                            </td>
                                                            <td class="mailbox-star">
                                                                <a href="#"><i class="fas fa-star-o text-warning"></i></a>
                                                            </td>
                                                            <td class="mailbox-name">
                                                                <a href="read_message.php">Angela</a>
                                                            </td>
                                                            <td class="mailbox-subject">
                                                                <b>Garbage Collection</b> - This is a Quick reminder about...
                                                            </td>
                                                            <td class="mailbox-attachment">
                                                                <i class="fas fa-paperclip"></i>
                                                            </td>
                                                            <td class="mailbox-date">
                                                                <?php echo date ("d M Y") ;?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="icheck-primary">
                                                                    <input type="checkbox" value="" id="check3">
                                                                    <label for="check3"></label>
                                                                </div>
                                                            </td>
                                                            <td class="mailbox-star">
                                                                <a href="#"><i class="fas fa-star-o text-warning"></i></a>
                                                            </td>
                                                            <td class="mailbox-name">
                                                                <a href="read_message.php">Anthony</a>
                                                            </td>
                                                            <td class="mailbox-subject">
                                                                <b>Get Together</b> - Hello, We'll have a neighbourhood get...
                                                            </td>
                                                            <td class="mailbox-attachment">
                                                                <i class="fas fa-paperclip"></i>
                                                            </td>
                                                            <td class="mailbox-date">1 Week ago</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="icheck-primary">
                                                                    <input type="checkbox" value="" id="check4">
                                                                    <label for="check4"></label>
                                                                </div>
                                                            </td>
                                                            <td class="mailbox-star">
                                                                <a href="#"><i class="fas fa-star text-warning"></i></a>
                                                            </td>
                                                            <td class="mailbox-name">
                                                                <a href="read_message.php">Joan</a>
                                                            </td>
                                                            <td class="mailbox-subject">
                                                                <b>Loud Music</b> - Could you kindly reduce the music volume...
                                                            </td>
                                                            <td class="mailbox-attachment"></td>
                                                            <td class="mailbox-date">1 Month ago</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="icheck-primary">
                                                                    <input type="checkbox" value="" id="check5">
                                                                    <label for="check5"></label>
                                                                </div>
                                                            </td>
                                                            <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                                                            <td class="mailbox-name"><a href="read_message.php">Meaty Butchery</a></td>
                                                            <td class="mailbox-subject"><b>Payment Request</b> - Hello, you owe me Kshs.1500, kindly...
                                                            </td>
                                                            <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                                                            <td class="mailbox-date">Yesterday</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="icheck-primary">
                                                                    <input type="checkbox" value="" id="check6">
                                                                    <label for="check6"></label>
                                                                </div>
                                                            </td>
                                                            <td class="mailbox-star"><a href="#"><i class="fas fa-star-o text-warning"></i></a></td>
                                                            <td class="mailbox-name"><a href="read_message.php">Glen Plumbers</a></td>
                                                            <td class="mailbox-subject"><b>Clogged Toilet</b> - Did you request for toilet clogged solution...
                                                            </td>
                                                            <td class="mailbox-attachment"><i class="fas fa-paperclip"></i></td>
                                                            <td class="mailbox-date">2 days ago</td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
                                                    <!-- /.table -->
                                                </div>
                                                <!-- /.mail-box-messages -->
                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-footer p-0">
                                                <div class="mailbox-controls">
                                                    <!-- Check all button -->
                                                    <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                                        <i class="far fa-square"></i>
                                                    </button>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm">
                                                            <i class="fas fa-reply"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm">
                                                            <i class="fas fa-share"></i>
                                                        </button>
                                                    </div>
                                                    <!-- /.btn-group -->
                                                    <button type="button" class="btn btn-default btn-sm">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                    <div class="float-right">
                                                        1-50/200
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default btn-sm">
                                                                <i class="fas fa-chevron-left"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-default btn-sm">
                                                                <i class="fas fa-chevron-right"></i>
                                                            </button>
                                                        </div>
                                                        <!-- /.btn-group -->
                                                    </div>
                                                    <!-- /.float-right -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                            </div>   
                        </div>
                        <div class="card shadow">
                            <div class="card-header" style="background-color:#00192D; color:#fff;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>Recent Messages</b>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button class="btn btn-sm bg-light" data-toggle="modal" data-target="#composemsg-modal">
                                            <i class="bi bi-plus-square-fill"></i> Compose Message
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <th>Time</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Incoming | Outgoing</th>
                                        <th>Options</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php echo date('H:m:s') ;?><br>
                                                <?php echo date('d, M Y') ;?>
                                            </td>
                                            <td>Request to Vacate the Premise</td>
                                            <td>Hello Landlord, I'd like to tell you that I will be....</td>
                                            <td>
                                                <button class="btn btn-sm" type="button" style="background-color: #2C9E4B; color: #fff;"><i class="fa fa-arrow-up"></i> Outgoing</button>
                                            </td>
                                            <td>
                                                <a href="view_chat_history.php">
                                                    <button class="btn btn-sm" style="background-color:#00192D; color:#fff;"><i class="bi bi-eye"></i> View </button>
                                                </a>
                                                <a href="clear_chat_history.php">
                                                    <button class="btn btn-sm" style="background-color:#cc0001;color: #fff;"><i class="bi bi-trash"></i> Delete</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php echo date('H:m:s') ;?><br>
                                                <?php echo date('d, M Y') ;?>
                                            </td>
                                            <td>Music Menace</td>
                                            <td>Hello Tenant, it has come to our attention that you play loud music....</td>
                                            <td>
                                                <button class="btn btn-sm" type="button" style="background-color: #0C5662; color: #fff;"><i class="fa fa-arrow-down"></i> Incoming</button>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm" style="background-color:#00192D; color:#fff;"><i class="bi bi-eye"></i> View </button>
                                                <button class="btn btn-sm" style="background-color:#cc0001;color: #fff;"><i class="bi bi-trash"></i> Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                        <!-- Message Container -->
                        <div class="modal fade" id="composemsg-modal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <b class="modal-title">Compose Message</b>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Recepient</label>
                                                <input class="form-control" name="receipient" value="<?php echo 'Landlord Name';?>" id="receipient" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Title</label>
                                                <select name="title" id="title" class="form-control">
                                                    <option value="" selected hidden>-- Select Option --</option>
                                                    <option value="Late Rental Payment">Late Rental Payment</option>
                                                    <option value="Suggestion">Suggestion</option>
                                                    <option value="Shifting Notice">Shifting Notice</option>
                                                    <option value="Nuisance">Nuisance</option>
                                                    <option value="Poor Service">Poor Service</option>
                                                    <option value="Hyegiene">Hyegiene</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Message</label>
                                                <textarea class="form-control" name="message" id="message" rows="2" placeholder="Type Message Here"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Do you want to Attach a Photo</label>
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <input type="radio" name="attach_confirm" value="Yes" onclick="displayMessageAttachments();"> Yes
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="radio" name="attach_confirm" value="No" onclick="hideMessageAttachments();"> No
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card shadow" id="messageAttachments" style="display:none;">
                                                <div class="card-body">
                                                    <input  name="attachments"  type="file" id="fileInput" onchange="handleFiles(event)" class="form-control" multiple>
                                                    <!-- Section to display selected files' previews and sizes -->
                                                    <div id="filePreviews" class="card shadow mt-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-sm" data-dismiss="modal" style="background-color:#cc0001; color:#fff;"><i class="fa fa-times"></i>  Dismiss</button>
                                            <button type="submit" class="btn btn-sm" style="background-color:#00192D; color:#fff;"><i class="fa fa-check"></i> Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

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
            // Function to handle multiple files selection
            function handleFiles(event) {
                const files = event.target.files;  // Get all selected files
                const previewContainer = document.getElementById('filePreviews');
                previewContainer.innerHTML = '';  // Clear previous previews

                let imageCount = 0; // Keep track of how many images we preview

                Array.from(files).forEach(file => {
                    const fileSizeInMB = (file.size / (1024 * 1024)).toFixed(2);  // Convert to MB
                    const fileType = file.type;

                    // Create a container for each file's preview and size
                    const fileContainer = document.createElement('div');
                    fileContainer.style.marginBottom = '30px';

                    // Display the file size
                    const fileSizeElement = document.createElement('p');
                    fileSizeElement.textContent = `File size: ${fileSizeInMB} MB`;
                    fileContainer.appendChild(fileSizeElement);

                    // Preview the file based on type
                    if (fileType.startsWith('image/')) {
                        if (imageCount >= 3) {
                            const warning = document.createElement('p');
                            warning.style.color = 'red';
                            warning.textContent = 'You can only upload 3 images at a time.';
                            previewContainer.appendChild(warning);
                            return;
                        }

                        const img = document.createElement('img');
                        img.style.width = '70%';
                        img.style.display = 'flex';
                        img.src = URL.createObjectURL(file);
                        img.onload = function () {
                            URL.revokeObjectURL(img.src); // Free memory
                        };

                        fileContainer.appendChild(img);
                        imageCount++;

                    } else if (fileType === 'application/pdf') {
                        const pdfEmbed = document.createElement('embed');
                        pdfEmbed.style.width = '100%';
                        pdfEmbed.style.height = '100%';
                        pdfEmbed.src = URL.createObjectURL(file);
                        fileContainer.appendChild(pdfEmbed);

                    } else {
                        const fileName = document.createElement('p');
                        fileName.textContent = `File: ${file.name}`;
                        fileContainer.appendChild(fileName);
                    }

                    // Append the file container to the previews section
                    previewContainer.appendChild(fileContainer);
                });
            }
        </script>
        <script>
            // Function to open the complaint popup
            function opennewtextPopup() {
                document.getElementById("newtextPopup").style.display = "flex";
            }

            // Function to close the complaint popup
            function closenewtextPopup() {
                document.getElementById("newtextPopup").style.display = "none";
            }

            function toggleShrink() {
                let recipientBox = document.getElementById("recipient");
                let first_field_group = document.getElementById("field-group-first");
                let field_group_third = document.getElementById("field-group-third");
                let field_group_second = document.getElementById("field-group-second");

                if (recipientBox.value === "all") {
                    first_field_group.classList.remove("shrink"); // shrink if the option is not all
                    field_group_second.style.display= "none";
                    field_group_third.style.display= "none";

                } else {
                    first_field_group.classList.add("shrink"); // Do not shrink is the option is all
                    field_group_second.style.display= "block";
                }

            }


            function toggleShrink1() {
                let recipientBox_units = document.getElementById("recipient-units");

                let field_group_second = document.getElementById("field-group-second");
                let field_group_third = document.getElementById("field-group-third");

                if (recipientBox_units.value === "all") {
                    field_group_second.classList.remove("shrink"); // shrink if the option is not all
                    field_group_third.style.display= "none";

                } else {
                    field_group_second.classList.add("shrink"); // Do not shrink is the option is all
                    field_group_third.style.display= "block";
                }

            }
        </script>
        <!-- image preview -->
        <script>
            document.getElementById('imageUpload').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('imagePreview');
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        </script>
        <script>

            // Major Variables.
            const all_messages_summary =  document.getElementById('all-messages-summary');
            const individual_message_summary = document.getElementById('individual-message-summmary');


            // const   next_text = document.getElementById('respond_btn');
            const   respond_window = document.getElementById('respond');
            const   close_text_overlay = document.getElementById("closeTextWindow");

            next_text.addEventListener('click', ()=>{

                respond_window.style.display= "flex";
                document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
                const now = new Date();
                const formattedTime = now.toLocaleString(); // Format the date and time
                timestamp.textContent = `Sent on: ${formattedTime}`;


            });

            close_text_overlay.addEventListener('click', ()=>{

                respond_window.style.display= "none";
                document.querySelector('.app-wrapper').style.opacity = '1';
            });

        </script>
        <!-- Give an Option to Attach Files when composing a Message -->
        <script type="text/javascript">
            const messageAttachmentsHolder = document.getElementById('messageAttachments');
            function displayMessageAttachments(){
                messageAttachmentsHolder.style.display = 'block';
            }
            function hideMessageAttachments() {
                messageAttachmentsHolder.style.display = 'none';
            }
        </script>
    </body>
</html>
