<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>JengoPay | Single Units Management</title>
    
    <!-- Primary Meta Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="JengoPay - Property Management System">
    <meta name="author" content="JengoPay">
    <meta name="description" content="Comprehensive property management dashboard for single units administration">
    
    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Third Party Plugins -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Required Plugin(AdminLTE) -->
    <link rel="stylesheet" href="../../assets/main.css">
    
    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">
    
    <!-- Data Tables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="expenses.css">
    
    <!-- JavaScript Libraries (Head) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Modern Styling -->
    <style>
        :root {
            --primary-color: #00192D;
            --secondary-color: #FFC107;
            --accent-color: #2C9E4B;
            --danger-color: #cc0001;
            --warning-color: #F74B00;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            font-family: 'Inter', 'Source Sans 3', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .app-wrapper {
            background: transparent;
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin: 1rem;
            box-shadow: var(--card-shadow);
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003366 100%);
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .card-header b {
            color: var(--secondary-color);
        }

        /* Info Box Styling */
        .info-box {
            border: none;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            background: white;
            height: 100%;
        }

        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }

        .info-box-icon {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .info-box:hover .info-box-icon {
            transform: scale(1.1);
        }

        .info-box-content {
            padding-left: 1.5rem;
        }

        .info-box-text {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .info-box-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-top: 0.25rem;
        }

        /* Table Styling */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        #dataTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #dataTable thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003366 100%);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #dataTable tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f1f1;
        }

        #dataTable tbody tr:hover {
            background-color: rgba(0, 25, 45, 0.05);
            transform: scale(1.002);
        }

        #dataTable tbody td {
            padding: 1rem;
            vertical-align: middle;
            border: none;
            color: #495057;
            font-weight: 500;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            border: 2px solid transparent;
        }

        .status-occupied {
            background: linear-gradient(135deg, rgba(44, 158, 75, 0.1) 0%, rgba(44, 158, 75, 0.05) 100%);
            color: #2C9E4B;
            border-color: rgba(44, 158, 75, 0.3);
        }

        .status-vacant {
            background: linear-gradient(135deg, rgba(204, 0, 1, 0.1) 0%, rgba(204, 0, 1, 0.05) 100%);
            color: #cc0001;
            border-color: rgba(204, 0, 1, 0.3);
        }

        .status-maintenance {
            background: linear-gradient(135deg, rgba(247, 75, 0, 0.1) 0%, rgba(247, 75, 0, 0.05) 100%);
            color: #F74B00;
            border-color: rgba(247, 75, 0, 0.3);
        }

        /* Button Styling */
        .btn-group .btn {
            background: white;
            color: var(--primary-color);
            border: 1px solid rgba(0, 25, 45, 0.2);
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: var(--hover-shadow);
            padding: 0.5rem;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin: 0.15rem 0;
            color: #495057;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-item:hover {
            background: rgba(0, 25, 45, 0.1);
            color: var(--primary-color);
            transform: translateX(3px);
        }

        .dropdown-item i {
            width: 20px;
            color: var(--primary-color);
        }

        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003366 100%);
            color: white;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 25, 45, 0.1);
        }

        fieldset {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1rem;
        }

        legend {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Footer Styling */
        .app-footer {
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 1.5rem;
            margin-top: 2rem;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .info-box {
                margin-bottom: 1rem;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .info-box-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .info-box-number {
                font-size: 1.5rem;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #003366;
        }

        /* Loading Animation */
        .loader {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 25, 45, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Icon Styling in Table */
        .table-icon {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 25, 45, 0.1);
            border-radius: 6px;
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        /* Action Button */
        .action-btn {
            position: relative;
            overflow: hidden;
        }

        .action-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .action-btn:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-dark">
    <!-- App Wrapper -->
    <div class="app-wrapper">
        
        <!-- Alerts -->
        <?php if (isset($successMessage)): ?>
            <div class='alert alert-success animate-fade-in'><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class='alert alert-danger animate-fade-in'><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        
        <!-- Header -->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
        
        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="./index.html" class="brand-link">
                    <span class="brand-text font-weight-light">
                        <b class="p-2" style="background-color:#FFC107; border:2px solid #FFC107; border-top-left-radius:5px; font-weight:bold; color:#00192D;">
                            BT
                        </b>
                        <b class="p-2" style="border-bottom-right-radius:5px; font-weight:bold; border:2px solid #FFC107; color: #FFC107;">
                            JENGOPAY
                        </b>
                    </span>
                </a>
            </div>
            
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
        </aside>
        
        <!-- Main Content -->
        <main class="app-main mt-4">
            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid animate-fade-in">
                        
                        <?php
                        require_once "../db/connect.php";
                        include_once '../processes/encrypt_decrypt_function.php';
                        
                        // Submit Single Unit Information
                        if (isset($_POST['submit'])) {
                            try {
                                // Insert unit data
                                $stmt = $pdo->prepare("INSERT INTO single_units(unit_number, purpose, building_link, location, monthly_rent, occupancy_status)
                                    VALUES (:unit_number, :purpose, :building_link, :location, :monthly_rent, :occupancy_status)");
                                $stmt->execute([
                                    ':unit_number'      => $_POST['unit_number'],
                                    ':purpose'          => $_POST['purpose'],
                                    ':building_link'    => $_POST['building_link'],
                                    ':location'         => $_POST['location'],
                                    ':monthly_rent'     => (string) $_POST['monthly_rent'],
                                    ':occupancy_status' => $_POST['occupancy_status'],
                                ]);
                                
                                $unitId = $pdo->lastInsertId();
                                
                                // Insert recurring expenses if available
                                if (!empty($_POST['bill'])) {
                                    $stmtExp = $pdo->prepare("
                                        INSERT INTO single_unit_bills (unit_id, bill, qty, unit_price)
                                        VALUES (:unit_id, :bill, :qty, :unit_price)
                                    ");
                                    
                                    foreach ($_POST['bill'] as $i => $bill) {
                                        if (!empty($bill)) {
                                            $stmtExp->execute([
                                                ':unit_id'    => $unitId,
                                                ':bill'       => $bill,
                                                ':qty'        => (int) $_POST['qty'][$i],
                                                ':unit_price' => (string) $_POST['unit_price'][$i],
                                            ]);
                                        }
                                    }
                                }
                                
                                echo '<div id="countdown" class="alert alert-success animate-fade-in" role="alert"></div>
                                <script>
                                var timeleft = 10;
                                var downloadTimer = setInterval(function() {
                                    if (timeleft <= 0) {
                                        clearInterval(downloadTimer);
                                        window.location.href = window.location.href;
                                    } else {
                                        document.getElementById("countdown").innerHTML = "Single Unit Information Submitted Successfully! Redirecting in " + timeleft + " seconds remaining";
                                    }
                                    timeleft -= 1;
                                }, 1000);
                                </script>';
                            } catch (PDOException $e) {
                                echo "<div class='alert alert-danger animate-fade-in'>❌ Database error: " . $e->getMessage() . "</div>";
                            }
                        }
                        
                        // Meter Readings Submission PHP Script
                        if (isset($_POST['submit_reading'])) {
                            $id = trim($_POST['id'] ?? null);
                            $reading_date = trim($_POST['reading_date'] ?? null);
                            $meter_type = trim($_POST['meter_type'] ?? null);
                            $current_reading = trim($_POST['current_reading'] ?? null);
                            $previous_reading = trim($_POST['previous_reading'] ?? null);
                            $units_consumed = trim($_POST['units_consumed'] ?? null);
                            $cost_per_unit = trim($_POST['cost_per_unit'] ?? null);
                            $final_bill = trim($_POST['final_bill'] ?? null);
                            
                            try {
                                if (empty($reading_date) || empty($meter_type) || empty($current_reading) || empty($cost_per_unit)) {
                                    echo "<script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Invalid Input',
                                            text: 'Please ensure all required fields are filled.',
                                            background: 'white',
                                            color: '#333'
                                        });
                                    </script>";
                                } else {
                                    // Calculate units consumed and final bill
                                    $previous_reading = $previous_reading ? (float)$previous_reading : 0;
                                    $current_reading = (float)$current_reading;
                                    $cost_per_unit = (float)$cost_per_unit;
                                    
                                    if ($current_reading < $previous_reading) {
                                        echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Invalid Reading',
                                                text: 'Current reading cannot be less than previous reading.',
                                                background: 'white',
                                                color: '#333'
                                            });
                                        </script>";
                                    } else {
                                        $units_consumed = $current_reading - $previous_reading;
                                        $final_bill = $units_consumed * $cost_per_unit;
                                        
                                        // Check if reading already exists for this date and meter type
                                        $checkReading = $pdo->prepare("SELECT * FROM meter_readings WHERE reading_date = :reading_date AND meter_type = :meter_type AND unit_id = :unit_id");
                                        $checkReading->execute([
                                            ':reading_date' => $reading_date,
                                            ':meter_type' => $meter_type,
                                            ':unit_id' => $id
                                        ]);
                                        
                                        if ($checkReading->rowCount() > 0) {
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'Double Reading!',
                                                    text: 'Meter Reading for this Month has Already been Submitted!',
                                                    width: '600px',
                                                    padding: '0.6em',
                                                    background: 'white',
                                                    color: '#333',
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = 'single_units.php';
                                                    }
                                                });
                                            </script>";
                                        } else {
                                            // Insert into meter_readings table
                                            $submitMeterReading = $pdo->prepare("INSERT INTO meter_readings 
                                                (unit_id, reading_date, meter_type, current_reading, previous_reading, units_consumed, cost_per_unit, final_bill) 
                                                VALUES (:unit_id, :reading_date, :meter_type, :current_reading, :previous_reading, :units_consumed, :cost_per_unit, :final_bill)");
                                            $submitMeterReading->execute([
                                                ':unit_id' => $id,
                                                ':reading_date' => $reading_date,
                                                ':meter_type' => $meter_type,
                                                ':current_reading' => $current_reading,
                                                ':previous_reading' => $previous_reading,
                                                ':units_consumed' => $units_consumed,
                                                ':cost_per_unit' => $cost_per_unit,
                                                ':final_bill' => $final_bill
                                            ]);
                                            
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Success!',
                                                    text: 'Meter Reading Submitted Successfully.',
                                                    showConfirmButton: true,
                                                    background: 'white',
                                                    color: '#333',
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = 'all_meter_readings.php';
                                                    }
                                                });
                                            </script>";
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Database Error',
                                        text: 'Failed to insert meter reading: " . addslashes($e->getMessage()) . "',
                                        background: 'white',
                                        color: '#333'
                                    });
                                </script>";
                            }
                        }
                        
                        // Change to Under Maintenance
                        if (isset($_POST['update_maintenance_status'])) {
                            try {
                                $check = $pdo->prepare("SELECT occupancy_status FROM single_units WHERE id = :id");
                                $check->execute([':id' => $_POST['id']]);
                                $current_status = $check->fetchColumn();
                                
                                if ($current_status === $_POST['occupancy_status']) {
                                    echo "<script>
                                        Swal.fire({
                                            title: 'Warning!',
                                            text: 'Update failed. You did not change the status.',
                                            icon: 'warning',
                                            background: 'white',
                                            color: '#333',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.history.back();
                                        });
                                    </script>";
                                } else {
                                    $update = "UPDATE single_units SET occupancy_status = :occupancy_status WHERE id = :id";
                                    $stmt = $pdo->prepare($update);
                                    $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                                    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                                    $stmt->execute();
                                    
                                    echo "<script>
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: 'Occupancy status updated successfully!',
                                            background: 'white',
                                            color: '#333',
                                            confirmButtonText: 'OK'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = 'single_units.php';
                                            }
                                        });
                                    </script>";
                                }
                            } catch (PDOException $e) {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Database Error',
                                        text: '" . addslashes($e->getMessage()) . "',
                                        background: 'white',
                                        color: '#333',
                                        confirmButtonText: 'Close'
                                    });
                                </script>";
                            }
                        }
                        
                        // Change to Vacant
                        if (isset($_POST['update_vacant_status'])) {
                            try {
                                $check = $pdo->prepare("SELECT occupancy_status FROM single_units WHERE id = :id");
                                $check->execute([':id' => $_POST['id']]);
                                $current_status = $check->fetchColumn();
                                
                                if ($current_status === $_POST['occupancy_status']) {
                                    echo "<script>
                                        Swal.fire({
                                            title: 'Warning!',
                                            text: 'Update failed. You did not change the status.',
                                            icon: 'warning',
                                            background: 'white',
                                            color: '#333',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.history.back();
                                        });
                                    </script>";
                                } else {
                                    $update = "UPDATE single_units SET occupancy_status = :occupancy_status WHERE id = :id";
                                    $stmt = $pdo->prepare($update);
                                    $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                                    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                                    $stmt->execute();
                                    
                                    echo "<script>
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: 'Occupancy status updated successfully!',
                                            background: 'white',
                                            color: '#333',
                                            confirmButtonText: 'OK'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = 'single_units.php';
                                            }
                                        });
                                    </script>";
                                }
                            } catch (PDOException $e) {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Database Error',
                                        text: '" . addslashes($e->getMessage()) . "',
                                        background: 'white',
                                        color: '#333',
                                        confirmButtonText: 'Close'
                                    });
                                </script>";
                            }
                        }
                        ?>
                        
                        <!-- Summary Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <b><i class="bi bi-bar-chart-fill me-2"></i>Property Overview</b>
                            </div>
                            <div class="card-body">
                                <?php
                                try {
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM single_units WHERE occupancy_status = 'Vacant'");
                                    $stmt->execute();
                                    $vacant = $stmt->fetchColumn();
                                    
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM single_units WHERE occupancy_status = 'Occupied'");
                                    $stmt->execute();
                                    $occupied = $stmt->fetchColumn();
                                    
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM single_units WHERE occupancy_status = 'Under Maintenance'");
                                    $stmt->execute();
                                    $maintenance = $stmt->fetchColumn();
                                } catch (PDOException $e) {
                                    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                                }
                                ?>
                                
                                <div class="row g-4">
                                    <!-- Vacant Units -->
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="info-box">
                                            <span class="info-box-icon">
                                                <i class="bi bi-house-exclamation-fill"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Vacant Units</span>
                                                <span class="info-box-number"><?= htmlspecialchars($vacant) ?></span>
                                                <small class="text-muted">Available for rent</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Occupied Units -->
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="info-box">
                                            <span class="info-box-icon">
                                                <i class="bi bi-house-lock-fill"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Occupied Units</span>
                                                <span class="info-box-number"><?= htmlspecialchars($occupied); ?></span>
                                                <small class="text-muted">Currently rented</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Under Maintenance -->
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="info-box">
                                            <span class="info-box-icon">
                                                <i class="fas fa-tools"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Under Maintenance</span>
                                                <span class="info-box-number"><?= htmlspecialchars($maintenance); ?></span>
                                                <small class="text-muted">Being serviced</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- All Single Units Table -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <b><i class="bi bi-buildings-fill me-2"></i>All Single Units</b>
                                    <span class="badge bg-primary ms-2"><?= htmlspecialchars($vacant + $occupied + $maintenance) ?> Total</span>
                                </div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                                    <i class="bi bi-plus-circle me-1"></i> Add New Unit
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <?php
                                    try {
                                        $stmt = $pdo->query("SELECT * FROM single_units ORDER BY created_at DESC");
                                        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    } catch (PDOException $e) {
                                        die("<div class='alert alert-danger'>❌ Database error: " . $e->getMessage() . "</div>");
                                    }
                                    ?>
                                    
                                    <table class="table table-hover" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>Unit No</th>
                                                <th>Building</th>
                                                <th>Purpose</th>
                                                <th>Monthly Rent</th>
                                                <th>Status</th>
                                                <th>Added On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            try {
                                                $select = "SELECT * FROM single_units";
                                                $stmt = $pdo->prepare($select);
                                                $stmt->execute();
                                                
                                                while ($row = $stmt->fetch()) {
                                                    $id = encryptor('encrypt', $row['id']);
                                                    $unit_number = $row['unit_number'];
                                                    $building_link = $row['building_link'];
                                                    $purpose = $row['purpose'];
                                                    $location = $row['location'];
                                                    $monthly_rent = $row['monthly_rent'];
                                                    $occupancy_status = $row['occupancy_status'];
                                                    $created_at = $row['created_at'];
                                                    $unit_category = $row['unit_category'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="table-icon">
                                                            <i class="bi bi-house-door"></i>
                                                        </span>
                                                        <strong><?= htmlspecialchars($unit_number) ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="table-icon">
                                                            <i class="bi bi-building"></i>
                                                        </span>
                                                        <?= htmlspecialchars($building_link) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php
                                                        $icon = 'bi-house';
                                                        $color = 'text-primary';
                                                        if (htmlspecialchars($purpose) == 'Business') {
                                                            $icon = 'bi-shop';
                                                            $color = 'text-success';
                                                        } else if (htmlspecialchars($purpose) == 'Office') {
                                                            $icon = 'bi-briefcase';
                                                            $color = 'text-info';
                                                        } else if (htmlspecialchars($purpose) == 'Residential') {
                                                            $icon = 'bi-file-person';
                                                            $color = 'text-warning';
                                                        } else if (htmlspecialchars($purpose) == 'Store') {
                                                            $icon = 'bi-house-gear';
                                                            $color = 'text-secondary';
                                                        }
                                                        ?>
                                                        <span class="table-icon <?= $color ?>">
                                                            <i class="bi <?= $icon ?>"></i>
                                                        </span>
                                                        <?= htmlspecialchars($purpose) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark fs-6">
                                                        <i class="bi bi-currency-dollar me-1"></i>
                                                        <?= htmlspecialchars(number_format($monthly_rent, 2)) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (htmlspecialchars($occupancy_status) == 'Occupied') {
                                                        echo '<span class="status-badge status-occupied"><i class="fa fa-user me-1"></i>' . htmlspecialchars($occupancy_status) . '</span>';
                                                    } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                        echo '<span class="status-badge status-vacant"><i class="bi bi-house-exclamation me-1"></i>' . htmlspecialchars($occupancy_status) . '</span>';
                                                    } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                        echo '<span class="status-badge status-maintenance"><i class="fas fa-tools me-1"></i>' . htmlspecialchars($occupancy_status) . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="table-icon">
                                                            <i class="bi bi-calendar"></i>
                                                        </span>
                                                        <?= date('M d, Y', strtotime($created_at)) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu shadow-lg">
                                                            <?php if (htmlspecialchars($occupancy_status) == 'Occupied'): ?>
                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?= $id ?>">
                                                                    <i class="bi bi-eye me-2"></i> View Details
                                                                </a>
                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?= $id ?>">
                                                                    <i class="bi bi-pen me-2"></i> Edit Unit
                                                                </a>
                                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#meterReadingModal<?= $id ?>">
                                                                    <i class="bi bi-speedometer me-2"></i> Meter Reading
                                                                </a>
                                                                <hr class="dropdown-divider">
                                                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#markAsVacant<?= $id ?>">
                                                                    <i class="bi bi-house-exclamation me-2"></i> Mark as Vacant
                                                                </a>
                                                            <?php elseif (htmlspecialchars($occupancy_status) == 'Vacant'): ?>
                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?= $id ?>">
                                                                    <i class="bi bi-eye me-2"></i> View Details
                                                                </a>
                                                                <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?= $id ?>">
                                                                    <i class="bi bi-sliders me-2"></i> Inspect Unit
                                                                </a>
                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?= $id ?>">
                                                                    <i class="bi bi-pen me-2"></i> Edit Unit
                                                                </a>
                                                                <hr class="dropdown-divider">
                                                                <a class="dropdown-item text-success" href="rent_single_unit.php?rent=<?= $id ?>">
                                                                    <i class="bi bi-person-fill-check me-2"></i> Rent Unit
                                                                </a>
                                                                <a class="dropdown-item text-warning" href="#" data-bs-toggle="modal" data-bs-target="#underMaintenance<?= $id ?>">
                                                                    <i class="bi bi-house-gear me-2"></i> Mark for Maintenance
                                                                </a>
                                                            <?php elseif (htmlspecialchars($occupancy_status) == 'Under Maintenance'): ?>
                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?= $id ?>">
                                                                    <i class="bi bi-pen me-2"></i> Edit Unit
                                                                </a>
                                                                <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?= $id ?>">
                                                                    <i class="bi bi-sliders me-2"></i> Inspect Unit
                                                                </a>
                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?= $id ?>">
                                                                    <i class="bi bi-eye me-2"></i> View Details
                                                                </a>
                                                                <hr class="dropdown-divider">
                                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#meterReadingModal<?= $id ?>">
                                                                    <i class="bi bi-speedometer me-2"></i> Meter Reading
                                                                </a>
                                                                <a class="dropdown-item text-success" href="rent_single_unit.php?rent=<?= $id ?>">
                                                                    <i class="bi bi-person-fill-check me-2"></i> Rent Unit
                                                                </a>
                                                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#markAsVacant<?= $id ?>">
                                                                    <i class="bi bi-house-exclamation me-2"></i> Mark as Vacant
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <!-- Meter Readings Modal
                                            <div class="modal fade" id="meterReadingModal<?= $id ?>" tabindex="-1" aria-labelledby="meterReadingModalLabel<?= $id ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-speedometer me-2"></i>
                                                                Add Meter Reading for Unit <?= htmlspecialchars($unit_number); ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off" id="meterReadingForm<?= $id ?>">
                                                            <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                                            <div class="modal-body">
                                                                <div class="row g-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Reading Date <span class="text-danger">*</span></label>
                                                                            <input type="date" class="form-control" name="reading_date" id="reading_date_<?= $id ?>" required 
                                                                                   value="<?= date('Y-m-d') ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Meter Type <span class="text-danger">*</span></label>
                                                                            <select class="form-select meter-type" name="meter_type" id="meter_type_<?= $id ?>" required>
                                                                                <option value="" selected disabled>Select Meter Type</option>
                                                                                <option value="Water">Water Meter</option>
                                                                                <option value="Electricity">Electricity Meter</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row g-3 mt-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Current Reading: <span class="text-danger">*</span></label>
                                                                            <input type="number" name="current_reading" placeholder="Enter current reading" 
                                                                                   required class="form-control current-reading" id="current_reading_<?= $id ?>" 
                                                                                   step="0.01" min="0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Previous Reading:</label>
                                                                            <input type="number" name="previous_reading" placeholder="Enter previous reading" 
                                                                                   class="form-control previous-reading" id="previous_reading_<?= $id ?>" 
                                                                                   step="0.01" min="0">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <fieldset class="border p-4 mt-4 rounded-3">
                                                                    <legend class="px-2">
                                                                        <i class="bi bi-calculator me-2"></i>Bill Calculations
                                                                    </legend>
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Units Consumed:</label>
                                                                                <input class="form-control bg-light units-consumed" id="units_consumed_<?= $id ?>" 
                                                                                       name="units_consumed" readonly type="text">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Cost Per Unit (Ksh): <span class="text-danger">*</span></label>
                                                                                <input class="form-control cost-per-unit" id="cost_per_unit_<?= $id ?>" 
                                                                                       type="number" name="cost_per_unit" step="0.01" min="0" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mt-3">
                                                                        <label class="form-label">Total Bill (Ksh):</label>
                                                                        <input class="form-control bg-light fs-5 fw-bold final-bill" id="final_bill_<?= $id ?>" 
                                                                               name="final_bill" readonly type="text">
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="submit_reading" class="btn btn-primary" id="submitReadingBtn<?= $id ?>">
                                                                    <i class="bi bi-send me-1"></i> Submit Reading
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div> -->
                                            
                                            <!-- Mark as Vacant Modal -->
                                            <div class="modal fade" id="markAsVacant<?= $id ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-danger">
                                                                <i class="bi bi-house-exclamation me-2"></i>
                                                                Mark Unit as Vacant
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                                            <div class="modal-body text-center">
                                                                <div class="mb-4">
                                                                    <i class="bi bi-house-exclamation-fill text-danger" style="font-size: 3rem;"></i>
                                                                </div>
                                                                <h5 class="mb-3">Are you sure you want to mark Unit <strong><?= htmlspecialchars($row['unit_number']); ?></strong> as Vacant?</h5>
                                                                <p class="text-muted">This will change the occupancy status to "Vacant" and make it available for rent.</p>
                                                                <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                                                <div class="form-group mt-4">
                                                                    <label class="form-label">New Status:</label>
                                                                    <input class="form-control bg-light text-center fw-bold" id="occupancy_status" name="occupancy_status" value="Vacant" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger" name="update_vacant_status">
                                                                    <i class="bi bi-check-circle me-1"></i> Confirm & Update
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Under Maintenance Modal -->
                                            <div class="modal fade" id="underMaintenance<?= $id ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-warning">
                                                                <i class="bi bi-tools me-2"></i>
                                                                Mark Unit for Maintenance
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                                            <div class="modal-body text-center">
                                                                <div class="mb-4">
                                                                    <i class="bi bi-tools text-warning" style="font-size: 3rem;"></i>
                                                                </div>
                                                                <h5 class="mb-3">Mark Unit <strong><?= htmlspecialchars($row['unit_number']); ?></strong> for Maintenance?</h5>
                                                                <p class="text-muted">This will change the occupancy status to "Under Maintenance" and remove it from available rentals.</p>
                                                                <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                                                <div class="form-group mt-4">
                                                                    <label class="form-label">New Status:</label>
                                                                    <input type="text" class="form-control bg-light text-center fw-bold" id="occupancy_status" name="occupancy_status" value="Under Maintenance" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-warning text-white" name="update_maintenance_status">
                                                                    <i class="bi bi-check-circle me-1"></i> Confirm Maintenance
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                }
                                            } catch (PDOException $e) {
                                                echo '<div class="alert alert-danger">Selection Failed! "' . $e->getMessage() . '"</div>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="app-footer mt-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <strong>
                            <i class="bi bi-copyright me-1"></i>
                            Copyright &copy; 2014-2024
                            <a href="https://adminlte.io" class="text-decoration-none" style="color: var(--primary-color);">
                                JENGO PAY
                            </a>
                        </strong>
                        <span class="text-muted ms-2">All rights reserved.</span>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="text-muted">v2.0.1</span>
                        <span class="ms-3">
                            <i class="bi bi-heart-fill text-danger"></i>
                            <span class="text-muted ms-1">Built with passion</span>
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Add Unit Modal (Sample) -->
    <div class="modal fade" id="addUnitModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>
                        Add New Unit
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <p class="text-muted">Fill in the details for the new unit.</p>
                        <!-- Add unit form fields here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="submit">
                            <i class="bi bi-plus-circle me-1"></i> Add Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- JavaScript Files -->
    <script src="../../js/adminlte.js"></script>
    <script src="js/main.js"></script>
    <script src="../../../landlord/assets/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    Meter Readings JavaScript
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize DataTable
        $('#dataTable').DataTable({
            pageLength: 10,
            responsive: true,
            order: [[0, 'asc']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search units...",
                lengthMenu: "_MENU_ records per page"
            }
        });
        
        // Initialize all modals and attach event listeners
        function initializeMeterReadingModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            // Get the specific form elements for this modal
            const currentReading = modal.querySelector('.current-reading');
            const previousReading = modal.querySelector('.previous-reading');
            const costPerUnit = modal.querySelector('.cost-per-unit');
            const unitsConsumed = modal.querySelector('.units-consumed');
            const finalBill = modal.querySelector('.final-bill');
            
            if (!currentReading || !previousReading || !costPerUnit || !unitsConsumed || !finalBill) return;
            
            // Function to calculate bill for this specific modal
            function calculateBill() {
                const current = parseFloat(currentReading.value) || 0;
                const previous = parseFloat(previousReading.value) || 0;
                const cost = parseFloat(costPerUnit.value) || 0;
                
                let units = current - previous;
                if (units < 0) units = 0;
                
                unitsConsumed.value = units.toFixed(2);
                finalBill.value = (units * cost).toFixed(2);
            }
            
            // Add input event listeners
            currentReading.addEventListener('input', calculateBill);
            previousReading.addEventListener('input', calculateBill);
            costPerUnit.addEventListener('input', calculateBill);
            
            // Also calculate when modal is shown
            modal.addEventListener('shown.bs.modal', function() {
                calculateBill();
            });
            
            // Clear form when modal is hidden
            modal.addEventListener('hidden.bs.modal', function() {
                // Don't clear the form if it was submitted
                if (modal.dataset.submitted !== 'true') {
                    const form = modal.querySelector('form');
                    if (form) {
                        form.reset();
                        unitsConsumed.value = '';
                        finalBill.value = '';
                    }
                }
                modal.dataset.submitted = 'false';
            });
            
            // Handle form submission
            const form = modal.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    modal.dataset.submitted = 'true';
                    
                    // Show loading state on submit button
                    const submitBtn = modal.querySelector('button[name="submit_reading"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<span class="loader"></span> Processing...';
                        submitBtn.disabled = true;
                        
                        // Reset after 5 seconds if still on page
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 5000);
                    }
                });
            }
        }
        
        // Find and initialize all meter reading modals
        document.querySelectorAll('[id^="meterReadingModal"]').forEach(modal => {
            initializeMeterReadingModal(modal.id);
        });
        
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Add loading state to other submit buttons
        document.querySelectorAll('button[type="submit"]').forEach(button => {
            if (!button.closest('[id^="meterReadingModal"]')) {
                button.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="loader"></span> Processing...';
                    this.disabled = true;
                    
                    // Reset after 3 seconds if still on page
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 3000);
                });
            }
        });
        
        // Set today's date as default for all reading date fields
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[name="reading_date"]').forEach(input => {
            if (!input.value) {
                input.value = today;
            }
        });
        
        // Validate meter reading form before submission
        document.querySelectorAll('form[id^="meterReadingForm"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const currentReading = this.querySelector('.current-reading');
                const previousReading = this.querySelector('.previous-reading');
                const costPerUnit = this.querySelector('.cost-per-unit');
                
                if (currentReading && previousReading) {
                    const current = parseFloat(currentReading.value) || 0;
                    const previous = parseFloat(previousReading.value) || 0;
                    
                    if (current < previous) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Reading',
                            text: 'Current reading cannot be less than previous reading.',
                            background: 'white',
                            color: '#333'
                        });
                        return false;
                    }
                }
                
                if (costPerUnit && (!costPerUnit.value || parseFloat(costPerUnit.value) <= 0)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Cost',
                        text: 'Please enter a valid cost per unit.',
                        background: 'white',
                        color: '#333'
                    });
                    return false;
                }
                
                return true;
            });
        });
    });
    </script>
</body>
</html>