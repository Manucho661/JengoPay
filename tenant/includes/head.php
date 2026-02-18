    <?php
      include_once 'db_connection/db_connection.php';
    ?>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Dhibiti Homes | Dashboard</title>
      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome Icons -->
      <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
      <!-- Font Awesome Icons -->
      <link rel="stylesheet" type="text/css" href="plugins/font-awesome-4.7.0/css/font-awesome.min.css">
      <!-- IonIcons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Fas Fa Icons 
       <link rel="stylesheet" href="plugins/fas-fontawesome/css/fontawesome.min.css">-->
      <!-- Theme style -->
      <link rel="stylesheet" href="../dist/css/adminlte.min.css">
      <!-- Lower Left QUick Access Buttons -->
      <link rel="stylesheet" type="text/css" href="../custom-css/floating_buttons.css">
      <!-- Sidebar Styling -->
      <link rel="stylesheet" type="text/css" href="../custom-css/side_bar_custom_styling.css">
      <!-- Right Section Popping Side Bar -->
      <link rel="stylesheet" type="text/css" href="../custom-css/right-side-bar.css">
      <!-- Custom CSS -->
      <link rel="stylesheet" type="text/css" href="includes/custom_css/styling.css">
      <!-- Datatables CSS -->
      <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
      <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
      <!-- Icons -->
      <link rel="stylesheet" href="../plugins/bootstrap/bootstrap-icons.css">

      <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
      <!-- summernote -->
      <link rel="stylesheet" href="summernote/summernote-bs4.min.css">

      <!-- daterange picker -->
      <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
      <!-- iCheck for checkboxes and radio inputs -->
      <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
      <!-- Bootstrap Color Picker -->
      <link rel="stylesheet" href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
      <!-- Tempusdominus Bootstrap 4 -->
      <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
      <!-- Select2 -->
      <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
      <!-- Bootstrap4 Duallistbox -->
      <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
      <!-- BS Stepper -->
      <link rel="stylesheet" href="../plugins/bs-stepper/css/bs-stepper.min.css">
      <!-- dropzonejs -->
      <link rel="stylesheet" href="../plugins/dropzone/min/dropzone.min.css">

      <!-- Pop Up Form on the Lower Right Styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

      <style type="text/css">
        /* Button used to open the contact form - fixed at the bottom of the page */
    .open-button {
      background-color: #193042;
      color:#FFC107;
      padding: 16px 12px;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      position: fixed;
      bottom: 23px;
      right: 28px;
      width: 60px;
      height: 60px;
      box-shadow: 1px #FFC107;
    }
    .open-button i{
      font-size: 30px;
    }

    /* The popup form - hidden by default */
    .form-popup {
      display: none;
      position: fixed;
      bottom: 0;
      right: 15px;
      z-index: 9;
    }

    /* Add styles to the form container */
    .form-container {
      max-width: 350px;
      padding: 15px;
      background: linear-gradient(#00192D, 60%, #FFFFFF);
      margin-bottom: 10px;
      border-top-right-radius: 10px;
      border-top-left-radius: 10px;
      border-bottom-right-radius: 10px;
      border-bottom-left-radius: 10px;
    }

    /* Full-width input fields */
    .form-container input[type=text] {
      width: 100%;
      padding: 15px;
      margin: 5px 0 22px 0;
      border: none;
      background: #f1f1f1;
    }

    /* When the inputs get focus, do something */
    .form-container input[type=text]:focus:focus {
      background-color: #ddd;
      outline: none;
    }

    /* Set a style for the submit/login button */
    .form-container .btn {
      background-color: #04AA6D;
      color: white;
      padding: 16px 20px;
      border: none;
      cursor: pointer;
      width: 100%;
      margin-bottom:10px;
      opacity: 0.8;
    }

    /* Add a red background color to the cancel button */
    .form-container .cancel {
      background-color: #cc0001;
      height: 40px;
      padding: 10px;
      font-weight: bold;
    }

    /* Add some hover effects to buttons */
    .form-container .btn:hover, .open-button:hover {
      opacity: 1;
    }
      </style>


  <!-- Chat Stylings -->
  <style>
  .chatbot-container {
      width: 100%;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(39, 8, 214, 0.1);
    }

  #chatbot {
      box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.1);
      border-radius: 4px;
    }
    
    #header {
      background:linear-gradient(#00192D, 80%, rgb(0, 25, 45, .9));
      color: #ffffff;
      padding: 15px;
      font-size: 16px;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
    }

    message-container {
      width: 100%;
      display: flex;
      align-items: center;
      padding: 10px;
    }

  #conversation {
      overflow-y: auto;
      padding: 5px;
      display: flex;
      flex-direction: column;
    }

    @keyframes message-fade-in {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .chatbot-message {
      display: flex;
      align-items: flex-start;
      position: relative;
      font-size: 14px;
      line-height: 20px;
      border-radius: 20px;
      word-wrap: break-word;
      white-space: pre-wrap;
      max-width: 100%;
      padding: 0 5px;
    }

    .user-message {
      justify-content: flex-end;
    }


  .chatbot-text {
      background-color: rgb(234, 237, 238);
      color: #000000;
      font-size: 14px;
      padding: 10px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-top: 5px;
      font-weight: bold;
    }
    
    #input-form {
      display: flex;
      align-items: center;
      border-top: 1px solid rgb(141,142,146,0.5);
    }
    
    #input-field {
      flex: 1;
      height: 45px;
      border: 1px solid #B6B6B6;
      padding: 0 10px;
      font-size: 14px;
      transition: border-color 0.3s;
      color: #000000;
      background-color: #EDEDED;
      width: 100%;
    }

    .send-icon {
      cursor: pointer;
      height: 45px;
    }
    
    #input-field:focus {
      border-color: #000000;
      outline: none;
    }
    
    #submit-button {
      background:linear-gradient(#00192D, 80%, rgb(0, 25, 45, .9));
      border: none;
      width: auto;
    }

    p[sentTime]:hover::after {    
      content: attr(sentTime);
      position: absolute;
      top: -3px;
      font-size: 14px;
      color: gray;
    }

    .chatbot p[sentTime]:hover::after {  
      left: 15px;
    }

    .user-message p[sentTime]:hover::after {  
      right: 15px;
    }
    

    /* width */
  ::-webkit-scrollbar {
      width: 10px;
    }
    
    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #888; 
    }
    
    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #555; 
    }

  
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
  </style>

    </head>
