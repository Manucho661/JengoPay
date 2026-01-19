<?php
// error.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Temporarily Unavailable</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8d7da, #f5c2c7);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        /* Container */
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.15);
            max-width: 600px;
            width: 90%;
            position: relative;
            z-index: 2;
            animation: fadeIn 1s ease forwards;
        }

        /* Emoji Bounce */
        .error-container h1 {
            font-size: 4rem;
            color: #d63384;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        .error-container h3 {
            color: #6c757d;
            margin-bottom: 20px;
            animation: fadeInText 1.5s ease forwards;
        }

        .error-container p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            animation: fadeInText 1.5s ease forwards;
            animation-delay: 0.3s;
        }

        .btn-home {
            background-color: #d63384;
            color: white;
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: bold;
            text-decoration: none;
            animation: fadeInText 1.5s ease forwards;
            animation-delay: 0.6s;
        }

        .btn-home:hover {
            background-color: #bd2163;
            color: white;
        }

        /* Floating gears in background */
        .gear {
            position: absolute;
            font-size: 4rem;
            color: rgba(214, 51, 132, 0.1);
            animation: floatGear linear infinite;
        }
        .gear:nth-child(1) { top: 10%; left: 20%; animation-duration: 10s; }
        .gear:nth-child(2) { top: 30%; left: 80%; animation-duration: 12s; }
        .gear:nth-child(3) { top: 70%; left: 50%; animation-duration: 14s; }

        /* Animations */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInText {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes floatGear {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0) rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Floating gears -->
    <div class="gear">⚙️</div>
    <div class="gear">⚙️</div>
    <div class="gear">⚙️</div>

    <div class="error-container">
        <h1>😞</h1>
        <h3>Our services are temporarily unavailable</h3>
        <p>
            Our technical team is working hard to restore everything as quickly as possible. 
            We appreciate your patience and understanding.
        </p>
        <p>
            In the meantime, feel free to visit our homepage or try again shortly.
        </p>
        <a href="/" class="btn-home">Go to Homepage</a>
    </div>
</body>
</html>
