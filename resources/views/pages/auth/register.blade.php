<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINRE - Register</title>
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

        <!-- Right Side - Register Form -->
        <div class="w-full md:w-2/5 bg-finre-teal flex items-center justify-center relative">
            <!-- Curved Shape Overlay -->
            <div class="absolute top-0 left-0 bottom-0 w-24 bg-finre-light rounded-r-full"></div>
            
            <!-- Register Card -->
            <div class="w-11/12 max-w-md bg-white rounded-lg shadow-lg p-5 z-10">
                <!-- Logo -->
                <div class="flex flex-col items-center">
                    <div class="mb-1">
                        <img src="{{ asset('images/finre.png') }}" alt="FINRE Financial Reporting" class="h-10">
                    </div>
                    <div class="text-gray-500 text-sm tracking-wide">FINANCIAL REPORTING</div>
                </div>

                @if ($errors->any())
                    <div class="flex flex-col items-center m-8">
                        <div class="text-red-500 text-sm tracking-wide">{{ $errors->first() }}</div>
                    </div>
                @endif

                <!-- Register Header -->
                <h2 class="text-finre-teal text-3xl font-semibold mt-3 mb-3 text-center">Register</h2>
                
                <!-- Register Form -->
                <form method="POST" action="/register-process">
                    @csrf
                    <div class="mb-4">
                        <label for="fullname" class="block text-finre-teal mb-2">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan Full Name"  class="w-full p-2 bg-finre-light/50 rounded-md focus:outline-none focus:ring-2 focus:ring-finre-teal/30">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-finre-teal mb-2">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan Email"  class="w-full p-2 bg-finre-light/50 rounded-md focus:outline-none focus:ring-2 focus:ring-finre-teal/30">
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-finre-teal mb-2">Phone</label>
                        <input type="tel" id="phone" name="phone" placeholder="Masukkan No Hp"  class="w-full p-2 bg-finre-light/50 rounded-md focus:outline-none focus:ring-2 focus:ring-finre-teal/30">
                    </div>
                    
                    <div class="mb-6 relative">
                        <label for="password" class="block text-finre-teal mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Masukkan Password" class="w-full p-2 bg-finre-light/50 rounded-md focus:outline-none focus:ring-2 focus:ring-finre-teal/30">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-3 flex items-center text-gray-600">
                                <i id="eyeIcon" class="fa-solid fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-finre-teal text-white py-3 rounded-md hover:bg-finre-teal/90 transition-colors duration-200"> Daftar </button>
                    
                    <div class="text-center mt-4 text-sm text-gray-600">
                        Sudah punya akun? <a href="/login" class="text-finre-teal">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.add("fa-eye");
                eyeIcon.classList.remove("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.add("fa-eye-slash");
                eyeIcon.classList.remove("fa-eye");
            }
        }
    </script>
</body>
</html>