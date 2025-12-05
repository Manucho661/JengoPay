<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #00192D 0%, #003d5c 100%);
        }

        .animated-gradient {
            background: linear-gradient(45deg, #00192D, #003d5c, #FFC107, #ffcd38);
            background-size: 300% 300%;
            animation: gradient 8s ease infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .hover-lift {
            transition: transform 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .image-overlay {
            background: linear-gradient(135deg, rgba(0, 25, 45, 0.85), rgba(0, 61, 92, 0.75));
        }
    </style>
</head>

<body class="h-screen overflow-hidden">
    <div class="flex h-full">
        <!-- Left Side - Image Section -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1557683316-973673baf926?w=1200&q=80"
                alt="Background"
                class="object-cover w-full h-full">
            <div class="image-overlay absolute inset-0 flex flex-col justify-center items-center text-white p-12">
                <div class="fade-in text-center">
                    <h1 class="text-5xl font-bold mb-6">Welcome Back to <span class="text-yellow-600">Jengo</span>Pay<span></span> !</h1>
                    <p class="text-xl font-light max-w-md">Access your account and continue your journey with us.</p>
                    <div class="mt-8 flex gap-4 justify-center">
                        <div class="w-16 h-1 bg-white rounded"></div>
                        <div class="w-16 h-1 bg-white/50 rounded"></div>
                        <div class="w-16 h-1 bg-white/30 rounded"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 overflow-y-auto">
            <div class="w-full max-w-md h-full fade-in">
                <!-- Logo/Brand -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 animated-gradient rounded-2xl mx-auto mb-4 flex items-center justify-center">
                        <span class="text-3xl font-bold text-white">JP</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800">JengoPay</h2>
                    <p class="text-gray-500 mt-2">Sign in to your account</p>
                </div>
                <!-- Form -->
                <div>
                    <form id="registerForm" class="space-y-6">
                        <input
                            type="hidden"
                            name="role"
                            id="provider"
                            value="provider"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:outline-none transition-colors"
                            required>

                        <!-- Name -->
                        <label for="userName" class="text-sm font-medium mb-1 block">Your name/Business name</label>
                        <input
                            type="text"
                            id="userName"
                            placeholder="Nairobi Plumbers Center"
                            name="userName"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:outline-none transition-colors mb-4"
                            required>

                        <!-- Email -->
                        <label for="email" class="text-sm font-medium mb-1 block">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            placeholder="nairobiP@gmail.com"
                            name="email"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:outline-none transition-colors mb-4"
                            required>

                        <!-- Password -->
                        <label for="password" class="text-sm font-medium mb-1 block">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:outline-none transition-colors"
                            required>


                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                <span class="ml-2 text-gray-600 group-hover:text-gray-800">Remember me</span>
                            </label>
                            <a href="#" class="text-yellow-500 hover:text-yellow-600 font-medium">Forgot password?</a>
                        </div>

                        <!-- Sign In Button -->
                        <button
                            type=""
                            class="w-full gradient-bg text-white font-semibold py-3 rounded-lg hover:opacity-90 transform hover:scale-[1.02] transition-all duration-200">
                            Sign Up
                        </button>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500">Or continue with</span>
                            </div>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="grid grid-cols-1 gap-4">
                            <button
                                type="button"
                                class="flex items-center justify-center px-4 py-3 border-2 border-gray-200 rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-all w-full">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                </svg>
                                Google
                            </button>
                        </div>

                        <!-- Sign Up Link -->
                        <p class="text-center text-gray-600 text-sm mt-6">
                            Don't have an account?
                            <a href="#" class="text-yellow-500 hover:text-yellow-600 font-semibold">Sign up</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="js/main.js"></script>
    <!-- <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Simple validation
            // if (email && password) {
            //     // Simulate login
            //     const button = e.target.querySelector('button[type="submit"]');
            //     button.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
            //     setTimeout(() => {
            //         button.innerHTML = 'Sign In';
            //         e.target.reset();
            //     }, 1500);
            // }
        });
    </script> -->
</body>

</html>