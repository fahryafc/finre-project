<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINRE - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'finre-teal': '#2a7d8c',
                        'finre-light': '#e6f0f3',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
    <div class="flex h-screen w-full bg-white relative overflow-hidden">
        <!-- Left Content Area with Illustration -->
        <div class="hidden md:flex md:w-3/5 items-center justify-center relative">
            <div class="max-w-2xl z-10">
                <img src="{{ asset('images/bg-login.png') }}" alt="Financial Reporting Illustration" class="w-full">
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-2/5 bg-finre-teal flex items-center justify-center relative">
            <!-- Curved Shape Overlay -->
            <div class="absolute top-0 left-0 bottom-0 w-24 bg-finre-light rounded-r-full"></div>
            
            <!-- Login Card -->
            <div class="w-11/12 max-w-md bg-white rounded-lg shadow-lg p-8 z-10">
                <!-- Logo -->
                <div class="flex flex-col items-center mb-8">
                    <div class="mb-1">
                        <img src="{{ asset('images/finre.png') }}" alt="FINRE Financial Reporting" class="h-10">
                    </div>
                    <div class="text-gray-500 text-sm tracking-wide">FINANCIAL REPORTING</div>
                </div>

                @if ($errors->any())
                    <div class="flex flex-col items-center mb-8">
                        <div class="text-red-500 text-sm tracking-wide">{{ $errors->first() }}</div>
                    </div>
                @endif

                <!-- Login Header -->
                <h2 class="text-finre-teal text-3xl font-semibold mb-6 text-center">Login</h2>
                
                <!-- Login Form -->
                <form method="POST" action="/login-process">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-finre-teal mb-2">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan Email" class="w-full p-3 bg-finre-light/50 rounded-md focus:outline-none focus:ring-2 focus:ring-finre-teal/30">
                    </div>
                    
                    <!-- Password Input dengan Icon -->
                    <div class="mb-6 relative">
                        <label for="password" class="block text-finre-teal mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Masukkan Password" 
                                class="w-full p-3 pr-12 bg-finre-light/50 rounded-md focus:outline-none focus:ring-2 focus:ring-finre-teal/30">
                            <span id="togglePassword" class="absolute top-1/2 right-4 transform -translate-y-1/2 cursor-pointer text-gray-500">
                                <i class="fas fa-eye-slash text-lg"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-finre-teal text-white py-3 rounded-md hover:bg-finre-teal/90 transition-colors duration-200"> Login </button>
                    
                    <div class="text-center mt-4 text-sm text-gray-600">
                        Belum punya akun? <a href="/register" class="text-finre-teal">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script untuk Toggle Password -->
    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            let passwordInput = document.getElementById("password");
            let icon = this.querySelector("i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        });
    </script>
</body>
</html>