<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BTJENGOPAY - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }

        .navbar {
            background-color: #00192D;
        }

        .navbar-brand span {
            color: #FFC107;
        }

        .nav-link {
            color: #fff !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #FFC107 !important;
        }

        .info-section {
            padding: 60px 0;
        }

        .card {
            border: none;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .card-highlight {
            background-color: #FFC107;
            color: #00192D;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .btn-warning {
            background-color: #FFC107;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        footer {
            background-color: #00192D;
            color: white;
            padding: 25px 0;
        }

        .hero-section {
            height: 70vh;
            background-size: cover;
            background-position: center;
            position: relative;
            transition: background-image 1s ease-in-out;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.5);
            /* Darken background */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .z-2 {
            z-index: 2;
            position: relative;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #00192D;">
        <div class="container-fluid px-4"> <!-- Full-width container with horizontal padding -->

            <!-- Brand: BTJENGOPAY -->
            <a class="navbar-brand fw-bold me-auto" href="#" style="font-size: 1.6rem;">
                BT<span style="color: #FFC107;">JENGOPAY</span>
            </a>

            <!-- Hamburger Toggle (for mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3">

                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>

                    <!-- About Us Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            About Us
                        </a>
                        <ul class="dropdown-menu" style="background-color: #00192D;">
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">Company Overview</a></li>
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">Our Mission</a></li>
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">Our Team</a></li>
                        </ul>
                    </li>

                    <!-- Tenants Service Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="tenantsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tenants Service
                        </a>
                        <ul class="dropdown-menu" style="background-color: #00192D;">
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">Request Help</a></li>
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">FAQs</a></li>
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">Report an Issue</a></li>
                        </ul>
                    </li>

                    <!-- Downloads Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="downloadsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Downloads
                        </a>
                        <ul class="dropdown-menu" style="background-color: #00192D;">
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">Application Forms</a></li>
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">Tenant Handbook</a></li>
                            <li><a class="dropdown-item" href="#" style="color: #FFC107;">Billing Guide</a></li>
                        </ul>
                    </li>

                    <!-- Contact -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>


    <!-- Hero Section -->
    <section id="hero" class="hero-section text-white d-flex align-items-center justify-content-center text-center">
        <div class="overlay"></div>
        <div class="container position-relative z-2">
            <h1 class="display-4 fw- bold fs-oblique mb-3">Welcome to BTJENGOPAY</h1>
            <p class="lead">Your trusted platform for seamless property management. Whether you're a tenant looking for reliable service or a landlord managing your properties, everything you need is right here—fast, easy, and transparent.</p>
        </div>
    </section>

    <!-- Services -->
    <section class="info-section text-center">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://via.placeholder.com/400x200?text=Apply+for+Electricity" class="card-img-top" alt="Apply">
                        <div class="card-body">
                            <h5 class="card-title">Apply for Electricity</h5>
                            <p class="card-text">Residential, SME, or industrial — get connected faster with BTJENGOPAY.</p>
                            <a href="#" class="btn btn-warning text-dark">Apply Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm card-highlight d-flex flex-column justify-content-center">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-bolt"></i> Report an Outage</h5>
                            <p class="card-text">Experiencing a blackout? Report easily via our web portal or *977#.</p>
                            <a href="#" class="btn btn-dark">Report Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://via.placeholder.com/400x200?text=Electricity+Bill" class="card-img-top" alt="Bill">
                        <div class="card-body">
                            <h5 class="card-title">Get My Bill</h5>
                            <p class="card-text">Access your electricity bills and payment history instantly.</p>
                            <a href="#" class="btn btn-warning text-dark">View Bill</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; 2025 BTJENGOPAY. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const hero = document.getElementById('hero');
        const images = [
            'images/ldt1.jpg',
            'images/bd1.jpg',
            'images/bd2.jpg'
        ];
        let current = 0;

        function changeBackground() {
            hero.style.backgroundImage = `url('${images[current]}')`;
            current = (current + 1) % images.length;
        }

        // Start the slideshow
        changeBackground(); // Set initial image
        setInterval(changeBackground, 2000); // Change every 2 seconds
    </script>

</body>

</html>