<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Orders - Service Provider Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #00192D;
            --accent-color: #FFC107;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003d5c 100%);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }

        .header .tagline {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Navigation */
        .navigation {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: var(--primary-color);
            text-decoration: none;
            padding: 0.5rem 1.2rem;
            border-radius: 5px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .filter-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Request Card */
        .request-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .request-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .request-title {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .category-badge {
            background: var(--accent-color);
            color: var(--primary-color);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .property-info {
            color: #6c757d;
            margin-bottom: 0.8rem;
        }

        .property-info i {
            color: var(--accent-color);
            margin-right: 0.3rem;
        }

        .request-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--primary-color);
        }

        .request-description {
            color: #495057;
            line-height: 1.6;
            margin-bottom: 1rem;
            position: relative;
        }

        .description-short {
            display: block;
        }

        .description-full {
            display: none;
        }

        .description-short.hidden {
            display: none;
        }

        .description-full.visible {
            display: block;
        }

        .images-hidden {
            display: none;
        }

        .images-visible {
            display: block;
        }

        .read-more-btn {
            color: var(--accent-color);
            background: none;
            border: none;
            padding: 0;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            transition: color 0.3s;
            margin-top: 0.3rem;
            display: inline-block;
        }

        .read-more-btn:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .read-more-btn i {
            margin-left: 0.3rem;
        }

        /* Empty State */
        .empty-state {
            background: white;
            border-radius: 10px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .empty-state-icon {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .empty-state-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .empty-state-btn {
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
        }

        .empty-state-btn.primary {
            background: var(--primary-color);
            color: white;
        }

        .empty-state-btn.primary:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .empty-state-btn.secondary {
            background: var(--light-bg);
            color: var(--primary-color);
            border: 1px solid #dee2e6;
        }

        .empty-state-btn.secondary:hover {
            background: white;
            border-color: var(--primary-color);
        }

        .request-images {
            position: relative;
            width: 100%;
            margin-bottom: 1rem;
            border-radius: 10px;
            overflow: hidden;
        }

        .image-slider {
            position: relative;
            width: 100%;
            height: 300px;
            background: #f8f9fa;
        }

        .slider-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .slider-image.active {
            display: block;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 25, 45, 0.7);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 10;
        }

        .slider-btn:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .slider-btn.prev {
            left: 10px;
        }

        .slider-btn.next {
            right: 10px;
        }

        .slider-indicators {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .indicator-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
        }

        .indicator-dot.active {
            background: var(--accent-color);
            width: 25px;
            border-radius: 5px;
        }

        .slider-counter {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 25, 45, 0.7);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 10;
        }

        .request-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .budget-info {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .apply-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.6rem 1.8rem;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .apply-btn:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Sidebar Sections */
        .sidebar-section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .sidebar-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .category-list,
        .location-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-btn {
            background: var(--light-bg);
            border: 1px solid #dee2e6;
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-align: left;
            transition: all 0.3s;
            cursor: pointer;
        }

        .filter-btn:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        .subscribe-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003d5c 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .subscribe-section h3 {
            margin-bottom: 1rem;
        }

        .subscribe-input {
            display: flex;
            gap: 0.5rem;
        }

        .subscribe-input input {
            flex: 1;
            padding: 0.6rem;
            border: none;
            border-radius: 5px;
        }

        .subscribe-btn {
            background: var(--accent-color);
            color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 5px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Pagination */
        .pagination {
            margin: 2rem 0;
        }

        .pagination .page-link {
            color: var(--primary-color);
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            margin: 0 0.2rem;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .pagination .page-link:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            border-color: var(--accent-color);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background: #fff;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h4 {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--accent-color);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .request-header {
                flex-direction: column;
            }

            .request-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .subscribe-input {
                flex-direction: column;
            }
        }

        /* ===== Modal Header ===== */
        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ===== Job Info Section ===== */
        .modal-job-info {
            background: #f8f9fa;
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .modal-job-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .modal-job-details {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.9rem;
        }

        .modal-job-detail {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #6c757d;
        }

        /* ===== Budget / Duration Cards ===== */
        .client-budget-display {
            background: #0d6efd;
            color: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            height: 100%;
        }

        .client-budget-display .label {
            font-size: 0.8rem;
            opacity: 0.85;
            margin-bottom: 0.25rem;
        }

        .client-budget-display .amount {
            font-size: 1.6rem;
            font-weight: 600;
        }

        /* ===== Form Labels ===== */
        .form-label {
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* ===== Helper Text ===== */
        .help-text {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        /* ===== Inputs ===== */
        .form-control,
        .form-select {
            border-radius: 0.375rem;
        }

        /* ===== Duration Options ===== */
        .duration-options {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .duration-option {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            border: 1px solid #dee2e6;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .duration-option:hover {
            background: #f1f3f5;
        }

        /* ===== Footer Buttons ===== */
        .modal-footer .btn {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-submit-application {
            background: #0d6efd;
            color: #fff;
        }

        .btn-submit-application:hover {
            background: #0b5ed7;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <h1><i class="fas fa-tools"></i> Service Provider Portal</h1>
            <p class="tagline">Find and apply for service requests in your area</p>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navigation">
        <div class="container">
            <div class="nav-links">
                <a href="#" class="active"><i class="fas fa-search"></i> Find a Job</a>
                <a href="#"><i class="fas fa-file-alt"></i> Your Applications</a>
                <a href="#"><i class="fas fa-briefcase"></i> Assigned Jobs</a>
                <a href="#"><i class="fas fa-history"></i> Previous Jobs</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <!-- Filter Section -->
                <div class="filter-section">
                    <h3 class="filter-title"><i class="fas fa-filter"></i> Filter & Search</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Search requests...">
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="form-select">
                                <option>All Categories</option>
                                <option>Electrical</option>
                                <option>Plumbing</option>
                                <option>Carpentry</option>
                                <option>Painting</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-primary w-100" style="background: var(--primary-color); border: none;">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Request Cards -->
                <?php
                // Sample data - in real application, this would come from database
                $requests = [
                    [
                        'title' => 'Electrical Outlet Installation',
                        'property' => 'Silver House',
                        'unit' => 'C9',
                        'category' => 'Electrical',
                        'date' => '2026-01-10',
                        'duration' => '2-3 hours',
                        'budget' => 'KES 5,000',
                        'description' => 'Need to install 3 new electrical outlets in the living room. All materials will be provided. Looking for a certified electrician with experience in residential installations.',
                        'images' => ['placeholder1.jpg', 'placeholder2.jpg']
                    ],
                    [
                        'title' => 'Kitchen Sink Leak Repair',
                        'property' => 'Golden Heights',
                        'unit' => 'A12',
                        'category' => 'Plumbing',
                        'date' => '2026-01-12',
                        'duration' => '1-2 hours',
                        'budget' => 'KES 3,500',
                        'description' => 'Kitchen sink has a persistent leak underneath. Need urgent repair. The leak appears to be coming from the pipe connections.',
                        'images' => ['placeholder1.jpg']
                    ],
                    [
                        'title' => 'Ceiling Fan Installation',
                        'property' => 'Palm Residences',
                        'unit' => 'B5',
                        'category' => 'Electrical',
                        'date' => '2026-01-13',
                        'duration' => '3-4 hours',
                        'budget' => 'KES 7,000',
                        'description' => 'Install ceiling fan in master bedroom. Fan already purchased. Need installation and electrical connection. Must be able to work at heights safely.',
                        'images' => ['placeholder1.jpg', 'placeholder2.jpg', 'placeholder3.jpg']
                    ],
                    [
                        'title' => 'Door Lock Replacement',
                        'property' => 'Sunset Apartments',
                        'unit' => 'D3',
                        'category' => 'Carpentry',
                        'date' => '2026-01-14',
                        'duration' => '1 hour',
                        'budget' => 'KES 2,500',
                        'description' => 'Main door lock needs replacement. New lock mechanism to be provided. Simple installation required.',
                        'images' => []
                    ],
                    [
                        'title' => 'Bathroom Tile Repair',
                        'property' => 'Ocean View',
                        'unit' => 'E7',
                        'category' => 'General Maintenance',
                        'date' => '2026-01-14',
                        'duration' => '4-5 hours',
                        'budget' => 'KES 8,000',
                        'description' => 'Several bathroom tiles are cracked and need replacement. Matching tiles will be provided. Experience with tile work required.',
                        'images' => ['placeholder1.jpg', 'placeholder2.jpg']
                    ],
                    [
                        'title' => 'AC Unit Servicing',
                        'property' => 'Skyline Towers',
                        'unit' => 'F15',
                        'category' => 'HVAC',
                        'date' => '2026-01-15',
                        'duration' => '2 hours',
                        'budget' => 'KES 4,500',
                        'description' => 'Regular maintenance and servicing of split AC unit. Cleaning, filter replacement, and gas check required.',
                        'images' => []
                    ],
                    [
                        'title' => 'Garden Landscaping',
                        'property' => 'Green Valley',
                        'unit' => 'G2',
                        'category' => 'Landscaping',
                        'date' => '2026-01-15',
                        'duration' => '1 day',
                        'budget' => 'KES 15,000',
                        'description' => 'Complete garden redesign including grass planting, flower beds, and pathway installation. Materials to be discussed.',
                        'images' => ['placeholder1.jpg']
                    ],
                    [
                        'title' => 'Interior Wall Painting',
                        'property' => 'Riverside Complex',
                        'unit' => 'H9',
                        'category' => 'Painting',
                        'date' => '2026-01-16',
                        'duration' => '2 days',
                        'budget' => 'KES 12,000',
                        'description' => 'Paint all interior walls of 2-bedroom apartment. Paint will be provided. Looking for experienced painter with references.',
                        'images' => ['placeholder1.jpg', 'placeholder2.jpg']
                    ],
                    [
                        'title' => 'Water Heater Installation',
                        'property' => 'Mountain View',
                        'unit' => 'I4',
                        'category' => 'Plumbing',
                        'date' => '2026-01-16',
                        'duration' => '3 hours',
                        'budget' => 'KES 6,000',
                        'description' => 'Install new electric water heater in bathroom. Heater already purchased. Need proper electrical and plumbing connections.',
                        'images' => []
                    ],
                    [
                        'title' => 'Cabinet Door Repair',
                        'property' => 'Lake Shore',
                        'unit' => 'J11',
                        'category' => 'Carpentry',
                        'date' => '2026-01-17',
                        'duration' => '2 hours',
                        'budget' => 'KES 3,000',
                        'description' => 'Kitchen cabinet doors need adjustment and one hinge replacement. Simple carpentry work required.',
                        'images' => ['placeholder1.jpg']
                    ],
                    [
                        'title' => 'Deep Cleaning Service',
                        'property' => 'Urban Suites',
                        'unit' => 'K8',
                        'category' => 'Cleaning',
                        'date' => '2026-01-17',
                        'duration' => '4-5 hours',
                        'budget' => 'KES 5,500',
                        'description' => 'Complete deep cleaning of 3-bedroom apartment including windows, carpets, and appliances. Cleaning supplies provided.',
                        'images' => []
                    ],
                    [
                        'title' => 'Light Fixture Replacement',
                        'property' => 'Harmony Heights',
                        'unit' => 'L6',
                        'category' => 'Electrical',
                        'date' => '2026-01-18',
                        'duration' => '1-2 hours',
                        'budget' => 'KES 3,500',
                        'description' => 'Replace 4 ceiling light fixtures. New fixtures provided. Simple electrical work, certified electrician preferred.',
                        'images' => ['placeholder1.jpg', 'placeholder2.jpg']
                    ]
                ];


                // Pagination logic
                $itemsPerPage = 5;
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $totalItems = count($requests);
                $totalPages = ceil($totalItems / $itemsPerPage);
                $offset = ($currentPage - 1) * $itemsPerPage;
                $currentRequests = array_slice($requests, $offset, $itemsPerPage);

                // Check if there are any requests
                if (empty($currentRequests)): ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3>No Requests Available</h3>
                        <p>
                            There are currently no job requests matching your criteria.
                            Try adjusting your filters or check back later for new opportunities.
                        </p>
                        <div class="empty-state-actions">
                            <a href="?" class="empty-state-btn primary">
                                <i class="fas fa-sync-alt"></i> Refresh Page
                            </a>
                            <a href="#" class="empty-state-btn secondary" onclick="clearFilters(); return false;">
                                <i class="fas fa-filter"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                    <?php else:
                    foreach ($currentRequests as $request): ?>
                        <div class="request-card">
                            <div class="request-header">
                                <div>
                                    <h3 class="request-title"><?php echo $request['title']; ?></h3>
                                    <div class="property-info">
                                        <i class="fas fa-building"></i> <strong><?php echo $request['property']; ?></strong> - Unit <?php echo $request['unit']; ?>
                                    </div>
                                </div>
                                <span class="category-badge"><?php echo $request['category']; ?></span>
                            </div>

                            <div class="request-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><?php echo date('M d, Y', strtotime($request['date'])); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo $request['duration']; ?></span>
                                </div>
                            </div>

                            <?php
                            $description = $request['description'];
                            $shortDescription = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                            $needsToggle = strlen($description) > 100;
                            $hasImages = !empty($request['images']);
                            ?>

                            <div class="request-description">
                                <span class="description-short"><?php echo $shortDescription; ?></span>
                                <?php if ($needsToggle): ?>
                                    <span class="description-full"><?php echo $description; ?></span>
                                    <button class="read-more-btn" onclick="toggleDescription(this)" data-has-images="<?php echo $hasImages ? 'true' : 'false'; ?>">
                                        <span class="more-text">Read More <i class="fas fa-chevron-down"></i></span>
                                        <span class="less-text" style="display: none;">Read Less <i class="fas fa-chevron-up"></i></span>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($request['images'])): ?>
                                <div class="request-images images-hidden">
                                    <div class="image-slider" data-slider="<?php echo uniqid(); ?>">
                                        <?php foreach ($request['images'] as $index => $image): ?>
                                            <img src="https://via.placeholder.com/800x300"
                                                alt="Request image <?php echo $index + 1; ?>"
                                                class="slider-image <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <?php endforeach; ?>

                                        <?php if (count($request['images']) > 1): ?>
                                            <!-- Navigation Buttons -->
                                            <button class="slider-btn prev" onclick="changeSlide(this, -1)">
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            <button class="slider-btn next" onclick="changeSlide(this, 1)">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>

                                            <!-- Image Counter -->
                                            <div class="slider-counter">
                                                <span class="current-slide">1</span> / <?php echo count($request['images']); ?>
                                            </div>

                                            <!-- Indicators -->
                                            <div class="slider-indicators">
                                                <?php foreach ($request['images'] as $index => $image): ?>
                                                    <span class="indicator-dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                                        onclick="goToSlide(this, <?php echo $index; ?>)"></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="request-footer">
                                <div class="budget-info">
                                    <i class="fas fa-money-bill-wave"></i> <?php echo $request['budget']; ?>
                                </div>
                                <button class="apply-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#applicationModal"
                                    data-job-title="<?php echo htmlspecialchars($request['title']); ?>"
                                    data-job-property="<?php echo htmlspecialchars($request['property']); ?>"
                                    data-job-unit="<?php echo htmlspecialchars($request['unit']); ?>"
                                    data-job-budget="<?php echo htmlspecialchars($request['budget']); ?>"
                                    data-job-duration="<?php echo htmlspecialchars($request['duration']); ?>"
                                    data-job-category="<?php echo htmlspecialchars($request['category']); ?>">
                                    <i class="fas fa-paper-plane"></i> Apply Now
                                </button>
                            </div>
                        </div>
                <?php endforeach;
                endif; ?>

                <!-- Pagination -->
                <?php if ($totalPages > 1 && !empty($currentRequests)): ?>
                    <nav aria-label="Request pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Previous Button -->
                            <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next Button -->
                            <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Pagination Info -->
                    <div class="pagination-info text-center mb-4">
                        <p class="text-muted">
                            Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $itemsPerPage, $totalItems); ?>
                            of <?php echo $totalItems; ?> requests
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Subscribe Section -->
                <div class="subscribe-section">
                    <h3><i class="fas fa-bell"></i> Job Alerts</h3>
                    <p>Subscribe to receive notifications for new jobs matching your skills</p>
                    <div class="subscribe-input">
                        <input type="email" placeholder="Your email address">
                        <button class="subscribe-btn">Subscribe</button>
                    </div>
                </div>

                <!-- Job Categories -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title"><i class="fas fa-th-large"></i> Job Categories</h4>
                    <div class="category-list">
                        <button class="filter-btn"><i class="fas fa-bolt"></i> Electrical (15)</button>
                        <button class="filter-btn"><i class="fas fa-wrench"></i> Plumbing (12)</button>
                        <button class="filter-btn"><i class="fas fa-hammer"></i> Carpentry (8)</button>
                        <button class="filter-btn"><i class="fas fa-paint-roller"></i> Painting (10)</button>
                        <button class="filter-btn"><i class="fas fa-snowflake"></i> HVAC (6)</button>
                        <button class="filter-btn"><i class="fas fa-broom"></i> Cleaning (20)</button>
                        <button class="filter-btn"><i class="fas fa-leaf"></i> Landscaping (7)</button>
                        <button class="filter-btn"><i class="fas fa-tools"></i> General Maintenance (18)</button>
                    </div>
                </div>

                <!-- Job Locations -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title"><i class="fas fa-map-marker-alt"></i> Job Locations</h4>
                    <div class="location-list">
                        <button class="filter-btn"><i class="fas fa-city"></i> Nairobi (45)</button>
                        <button class="filter-btn"><i class="fas fa-city"></i> Mombasa (22)</button>
                        <button class="filter-btn"><i class="fas fa-city"></i> Kisumu (15)</button>
                        <button class="filter-btn"><i class="fas fa-city"></i> Nakuru (12)</button>
                        <button class="filter-btn"><i class="fas fa-city"></i> Eldoret (8)</button>
                        <button class="filter-btn"><i class="fas fa-city"></i> Thika (10)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Modal (Bootstrap) -->
    <div class="modal fade" id="applicationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-3 shadow">

                <!-- Header -->
                <div class="modal-header text-white"
                    style="background-color:#00192D;">
                    <h5 class="modal-title fw-semibold d-flex align-items-center gap-2">
                        <i class="fas fa-paper-plane text-warning"></i>
                        Submit Application
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Job Info -->
                    <div class="p-3 rounded bg-light mb-4">
                        <div class="fw-bold fs-6 mb-1" id="modalJobTitle"></div>

                        <div class="d-flex flex-wrap gap-3 small text-muted">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-building text-warning"></i>
                                <span id="modalJobProperty"></span>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-tag text-warning"></i>
                                <span id="modalJobCategory"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Budget / Duration -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded text-white h-100"
                                style="background-color:#00192D;">
                                <div class="small text-warning fw-semibold">
                                    Client’s Budget
                                </div>
                                <div class="fs-4 fw-bold" id="modalClientBudget"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded text-white h-100"
                                style="background-color:#003d5c;">
                                <div class="small text-warning fw-semibold">
                                    Expected Duration
                                </div>
                                <div class="fs-5 fw-bold" id="modalClientDuration"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form id="applicationForm">

                        <!-- Budget -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-flex gap-2">
                                <i class="fas fa-money-bill-wave text-warning"></i>
                                Your Proposed Budget (KES)
                            </label>
                            <input type="number" class="form-control"
                                placeholder="Enter your budget">
                            <div class="small text-muted mt-1">
                                Enter the amount you're willing to work for
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-flex gap-2">
                                <i class="fas fa-clock text-warning"></i>
                                Estimated Duration
                            </label>
                            <input type="text" class="form-control"
                                placeholder="e.g. 1–2 days">

                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <span class="badge rounded-pill border border-warning text-dark px-3 py-2">
                                    1–2 hours
                                </span>
                                <span class="badge rounded-pill border border-warning text-dark px-3 py-2">
                                    1 day
                                </span>
                                <span class="badge rounded-pill border border-warning text-dark px-3 py-2">
                                    2–3 days
                                </span>
                            </div>
                        </div>

                        <!-- Cover Letter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-flex gap-2">
                                <i class="fas fa-comment text-warning"></i>
                                Cover Letter
                            </label>
                            <textarea class="form-control" rows="4"
                                placeholder="Explain why you're a good fit..."></textarea>
                            <div class="small text-muted mt-1">
                                Keep it short, clear, and relevant
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold d-flex gap-2">
                                <i class="fas fa-calendar-check text-warning"></i>
                                Availability
                            </label>
                            <select class="form-select">
                                <option>Immediately</option>
                                <option>Within 24 hours</option>
                                <option>Within 3 days</option>
                            </select>
                        </div>

                    </form>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0">
                    <button class="btn btn-outline-secondary">
                        Cancel
                    </button>
                    <button class="btn fw-semibold text-dark"
                        style="background-color:#FFC107;">
                        <i class="fas fa-paper-plane me-1"></i>
                        Submit Application
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>About Us</h4>
                    <p>Connecting skilled service providers with property owners for quality maintenance and repair services.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#">Find Jobs</a></li>
                        <li><a href="#">How It Works</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p><i class="fas fa-envelope"></i> support@example.com</p>
                    <p><i class="fas fa-phone"></i> +254 700 000 000</p>
                    <div style="margin-top: 1rem;">
                        <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" style="color: white;"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Service Provider Portal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Populate modal with job data when it opens
        const applicationModal = document.getElementById('applicationModal');
        applicationModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            // Get job data from button attributes
            const jobTitle = button.getAttribute('data-job-title');
            const jobProperty = button.getAttribute('data-job-property');
            const jobUnit = button.getAttribute('data-job-unit');
            const jobBudget = button.getAttribute('data-job-budget');
            const jobDuration = button.getAttribute('data-job-duration');
            const jobCategory = button.getAttribute('data-job-category');

            // Update modal display content
            document.getElementById('modalJobTitle').textContent = jobTitle;
            document.getElementById('modalJobProperty').textContent = jobProperty + ' - Unit ' + jobUnit;
            document.getElementById('modalJobCategory').textContent = jobCategory;
            document.getElementById('modalClientBudget').textContent = jobBudget;
            document.getElementById('modalClientDuration').textContent = jobDuration;

            // Update hidden form fields for PHP submission
            document.getElementById('hiddenJobTitle').value = jobTitle;
            document.getElementById('hiddenJobProperty').value = jobProperty;
            document.getElementById('hiddenJobUnit').value = jobUnit;
            document.getElementById('hiddenJobCategory').value = jobCategory;
            document.getElementById('hiddenClientBudget').value = jobBudget;
            document.getElementById('hiddenClientDuration').value = jobDuration;
        });

        // Reset form when modal is closed
        applicationModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('applicationForm').reset();
            document.querySelectorAll('.duration-option').forEach(opt => {
                opt.classList.remove('selected');
            });
        });

        // Duration selection
        function selectDuration(element, duration) {
            document.querySelectorAll('.duration-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            document.getElementById('customDuration').value = duration;
        }

        // Image slider functions
        function changeSlide(button, direction) {
            const slider = button.closest('.image-slider');
            const slides = slider.querySelectorAll('.slider-image');
            const indicators = slider.querySelectorAll('.indicator-dot');
            const counter = slider.querySelector('.current-slide');

            let currentIndex = Array.from(slides).findIndex(slide => slide.classList.contains('active'));
            let newIndex = currentIndex + direction;

            // Loop around
            if (newIndex >= slides.length) newIndex = 0;
            if (newIndex < 0) newIndex = slides.length - 1;

            // Update slides
            slides[currentIndex].classList.remove('active');
            slides[newIndex].classList.add('active');

            // Update indicators
            indicators[currentIndex].classList.remove('active');
            indicators[newIndex].classList.add('active');

            // Update counter
            counter.textContent = newIndex + 1;
        }

        function goToSlide(indicator, index) {
            const slider = indicator.closest('.image-slider');
            const slides = slider.querySelectorAll('.slider-image');
            const indicators = slider.querySelectorAll('.indicator-dot');
            const counter = slider.querySelector('.current-slide');

            // Remove all active classes
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(dot => dot.classList.remove('active'));

            // Add active class to selected
            slides[index].classList.add('active');
            indicators[index].classList.add('active');

            // Update counter
            counter.textContent = index + 1;
        }

        // Clear filters function
        function clearFilters() {
            // Reset search input
            const searchInput = document.querySelector('.filter-section input[type="text"]');
            if (searchInput) searchInput.value = '';

            // Reset category select
            const categorySelect = document.querySelector('.filter-section select');
            if (categorySelect) categorySelect.selectedIndex = 0;

            // Remove active styling from filter buttons
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => btn.style.background = '');

            alert('Filters cleared! Click search to refresh results.');
        }

        // Toggle description function
        function toggleDescription(button) {
            const descriptionDiv = button.closest('.request-description');
            const shortText = descriptionDiv.querySelector('.description-short');
            const fullText = descriptionDiv.querySelector('.description-full');
            const moreText = button.querySelector('.more-text');
            const lessText = button.querySelector('.less-text');
            const hasImages = button.getAttribute('data-has-images') === 'true';

            // Find the images section (next sibling after description)
            const requestCard = button.closest('.request-card');
            const imagesSection = requestCard.querySelector('.request-images');

            if (fullText.classList.contains('visible')) {
                // Collapse
                fullText.classList.remove('visible');
                shortText.classList.remove('hidden');
                moreText.style.display = 'inline';
                lessText.style.display = 'none';

                // Hide images
                if (hasImages && imagesSection) {
                    imagesSection.classList.remove('images-visible');
                    imagesSection.classList.add('images-hidden');
                }
            } else {
                // Expand
                fullText.classList.add('visible');
                shortText.classList.add('hidden');
                moreText.style.display = 'none';
                lessText.style.display = 'inline';

                // Show images
                if (hasImages && imagesSection) {
                    imagesSection.classList.remove('images-hidden');
                    imagesSection.classList.add('images-visible');
                }
            }
        }

        // Add interactive functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Subscribe button functionality
            const subscribeBtn = document.querySelector('.subscribe-btn');
            if (subscribeBtn) {
                subscribeBtn.addEventListener('click', function() {
                    const emailInput = document.querySelector('.subscribe-input input');
                    if (emailInput.value) {
                        alert('Successfully subscribed to job alerts!');
                        emailInput.value = '';
                    } else {
                        alert('Please enter your email address');
                    }
                });
            }

            // Filter button functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons in the same section
                    const section = this.closest('.sidebar-section');
                    if (section) {
                        section.querySelectorAll('.filter-btn').forEach(btn => btn.style.background = '');
                    }
                    // Add active style to clicked button
                    this.style.background = '#FFC107';
                });
            });
        });
    </script>
</body>

</html>