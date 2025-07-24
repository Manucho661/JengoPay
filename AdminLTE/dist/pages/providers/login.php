<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body,
        html {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-side {
            flex: 1;
            background: url('jg.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
            position: relative;
            box-shadow: inset 0 0 0 2000px rgba(0, 25, 45, 0.5);
        }

        .right-side {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            padding: 20px;
        }

        .login-box {
            width: 90%;
            max-width: 420px;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px 30px;

        }

        .login-box h2 {
            margin-bottom: 25px;
            color: #00192D;
            font-size: 28px;
            text-align: center;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #00192D;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #FFC107;
            outline: none;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 40px;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #666;
        }

        .forgot {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .forgot a {
            color: #FFC107;
            text-decoration: none;
        }

        .forgot a:hover {
            text-decoration: underline;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #FFC107;
            color: #00192D;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
            position: relative;
            overflow: hidden;
        }

        input[type="submit"]::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        input[type="submit"]:hover::after {
            opacity: 1;
        }

        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(255, 193, 7, 0.4);
        }


        .signup {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #444;
        }

        .signup a {
            color: #00192D;
            text-decoration: none;
            font-weight: bold;
        }

        .signup a:hover {
            text-decoration: underline;
        }

        .social-login {
            text-align: center;
            margin-top: 25px;
        }

        .social-text {
            font-size: 14px;
            color: #555;
        }

        .social-buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .social-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            /* Updated this */
        }


        .facebook {
            background-color: #3b5998;
        }

        .google {
            background-color: #db4437;
        }

        .linkedin {
            background-color: #0077b5;
        }

        .social-btn:hover {
            opacity: 1;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }


        .center-separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 30px 0;
        }

        .center-separator::before,
        .center-separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ccc;
        }

        .center-separator:not(:empty)::before {
            margin-right: .75em;
        }

        .center-separator:not(:empty)::after {
            margin-left: .75em;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-side {
                height: 200px;
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-img {
            max-width: 120px;
            height: auto;
            border-radius: 50%;
            /* This makes the image circular */
            object-fit: cover;
            /* Ensures image content fits nicely */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="left-side"></div>

        <div class="right-side">
            <div class="login-box">

                <div class="logo-container">
                    <img src="logosp.jpg" alt="JengoPay Logo" class="logo-img">
                </div>


                <div style="font-size: 22px; font-weight: bold; font-style:italic; color: #FFC107; letter-spacing: 1px;">
                    <h2>Welcome back to Jengo<span style="color: #00192D; color:#FFC107;">Pay</span></h2>
                    <form method="POST" action="#">
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="text" id="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="form-group password-wrapper">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            <span class="toggle-password" onclick="togglePassword()">👁</span>
                        </div>

                        <div class="forgot">
                            <a href="#">Forgot password?</a>
                        </div>

                        <input type="submit" value="Sign In">
                    </form>

                    <div class="center-separator">Or</div>

                    <div class="social-login">
                        <p class="social-text">Sign in with:</p>
                        <div class="social-buttons">
                            <button class="social-btn facebook"><i class="fab fa-facebook-f"></i> Facebook</button>
                            <button class="social-btn google"><i class="fab fa-google"></i> Google</button>
                            <button class="social-btn linkedin"><i class="fab fa-linkedin-in"></i> LinkedIn</button>
                        </div>
                    </div>

                    <div class="signup">
                        Don’t have an account? <a href="#">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function togglePassword() {
                const pwd = document.getElementById('password');
                pwd.type = pwd.type === 'password' ? 'text' : 'password';
            }

            // Redirect to providers.php after sign in
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.querySelector("form");
                form.addEventListener("submit", function(e) {
                    e.preventDefault(); // prevent real submission for now
                    // You can add validation/authentication here before redirect
                    window.location.href = "providers.php"; // redirect to providers page
                });
            });
        </script>

</body>

</html>