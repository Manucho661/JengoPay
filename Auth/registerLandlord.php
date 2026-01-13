<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="main.css">
</head>

<body class="h-screen overflow-hidden">
    <div class="flex h-full">
        <!-- Left Side - Image Section -->
         <?php include_once "./includes/leftSide.php" ?>
        <!-- end left side -->

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
                <div id="FormSection">
                    <form id="registerLandlordForm" class="space-y-6">
                        <input
                            type="hidden"
                            name="role"
                            id="provider"
                            value="landlord"
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

                        <!-- Email error message -->
                        <div class="text-red-400 text-sm italic" id="emailErrorMessage"></div>

                        <!-- Password -->
                        <label for="password" class="text-sm font-medium mb-1 block">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-yellow-500 focus:outline-none transition-colors"
                            required>

                        <!-- password error message -->
                        <div class="text-red-400 text-sm italic" id="passErrorMessage"></div>

                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h4 class="font-semibold text-primary mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Note
                            </h4>
                            <p class="space-y-2 text-sm text-gray-700"> <i>Your password must have the following :-</i> </p>
                            <ul class="text-sm">
                                <li>1. At least 6 characters</li>
                                <li>2. At least 1 number</li>
                                <li>3. At least 1 uppercase letter</li>
                                <li>4. At least 1 special character</li>
                            </ul>

                        </div>
                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                                <span class="ml-2 text-gray-600 group-hover:text-gray-800">Remember me</span>
                            </label>
                            <a href="resetPassword.php" class="text-yellow-500 hover:text-yellow-600 font-medium">Forgot password?</a>
                        </div>

                        <!-- Sign In Button -->
                        <button
                            id="registerBtn"
                            type=""
                            class="w-full gradient-bg text-white font-semibold py-3 rounded-lg hover:opacity-90 transform hover:scale-[1.02] transition-all duration-200">
                            Sign Up
                        </button>

                        <!-- general error message -->
                        <div class="text-red-400 text-sm italic" id="generalErrorMessage"></div>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <!-- <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500">Or continue with</span>
                            </div> -->
                        </div>

                        <!-- Social Login Buttons -->
                        <!-- <div class="grid grid-cols-1 gap-4">
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
                        </div> -->

                        <!-- Sign Up Link -->
                        <p class="text-center text-gray-600 text-sm mt-6">
                            Already have an account?
                            <a href="login.php" class="text-yellow-500 hover:text-yellow-600 font-semibold">Sign in</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- js file -->
    <script type="module" src="js/main.js"></script>
</body>

</html>