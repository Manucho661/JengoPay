<aside class="main-sidebar sidebar-dark-warning text-light elevation-4" id="sidebar-container">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <!--<img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
        <span class="brand-text font-weight-light"><b class="p-2"
                style="background-color:#FFC107; border:2px solid #FFC107; border-top-left-radius:5px; font-weight:bold; color:#00192D;">BT</b><b
                class="p-2"
                style=" border-bottom-right-radius:5px; font-weight:bold; border:2px solid #FFC107; color: #FFC107;">JENGOPAY</b></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <!--<div class="image"> <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> </div>-->
            <div class="info"> <a href="#" class="d-block">Paul Wamoka (Tenant)</a> </div>
        </div>
        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar"> <i class="fas fa-search fa-fw"></i> </button>
                </div>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <li class="nav-item menu-open">
                    <a href="tenant_dashboard.php" class="nav-link active"> <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link"> <i class="nav-icon fas fa-home"></i>
                        <p> Rental Life <i class="fas fa-angle-left right"></i> <span class="badge badge-info right"
                                style="background-color:#FFC107;">2</span> </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="all_rental_payments.php" class="nav-link"> <i class="fas fa-retweet nav-icon"></i>
                                <p>Payment History</p>
                            </a>
                        </li>
                        <!--
                        <li class="nav-item">
                            <a href="pay_rent.php" class="nav-link"> <i class="fas fa-wallet nav-icon"></i>
                                <p>Pay Rent</p>
                            </a>
                        </li>-->
                    </ul>
                </li>
                <!-- Repairs and Mainteinance Requests -->
                <li class="nav-item">
                    <a href="#" class="nav-link"> <i class="nav-icon fas fa-wrench"></i>
                        <p> Repairs | Maintainance <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="plumbing_works.php" class="nav-link"> <i class="fas fa-faucet nav-icon"></i>
                                <p>Plumbing Works</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="electrical_works.php" class="nav-link"> <i class="fas fa-plug nav-icon"></i>
                                <p>Electrical Works</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="structural_repairs.php" class="nav-link"> <i class="fas fa-home nav-icon"></i>
                                <p>Structural Repairs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pest_control.php" class="nav-link"> <i class="fas fa-bug nav-icon"></i>
                                <p>Pest Control</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="hvac_maintenance.php" class="nav-link"> <i class="fas fa-air-freshener nav-icon"></i>
                                <p>HVAC Maintenance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="painting_finishing.php" class="nav-link"> <i class="fas fa-paint-roller nav-icon"></i>
                                <p>Painting &amp; Finishing</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="appliance_repairs.php" class="nav-link"> <i class="fas fa-tv nav-icon"></i>
                                <p>Appliance Repairs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="general_repairs.php" class="nav-link"> <i class="fas fa-tools nav-icon"></i>
                                <p>General Repairs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="outdoor_repairs.php" class="nav-link"> <i class="far fa-building nav-icon"></i>
                                <p>Outdoor Repairs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="safety_security.php" class="nav-link"> <i class="fas fa-user-lock nav-icon"></i>
                                <p>Safety &amp; Security</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="invoices.php" class="nav-link"><i class="fa fa-file nav-icon"></i>
                        <p>Invoices</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="message_box.php" class="nav-link"> <i class="fas fa-envelope-open-text nav-icon"></i>
                        <p> Communication </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> <i class="fas fa-hand-holding	 nav-icon"></i>
                        <p> All Requests </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link"> <i class="nav-icon fa fa-cogs"></i>
                        <p> Settings </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="openForm()"> <i class="nav-icon fa fa-question"></i>
                        <p> Help </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link"> <i class="nav-icon fa fa-power-off"></i>
                        <p> Signout </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
