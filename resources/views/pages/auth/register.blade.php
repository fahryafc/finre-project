<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    @include('layouts.shared/head-css')
</head>
<body>
    <div class="bg-gradient-to-r from-rose-100 to-teal-100 dark:from-gray-700 dark:via-gray-900 dark:to-black">
        <div class="h-screen w-screen flex justify-center items-center">
            <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
                <div class="card overflow-hidden sm:rounded-md rounded-none">
                    <div class="p-6 space-y-3">
                        <div class="block mb-4">
                            <img class="h-10 block" src="{{ asset('images/brands/logo.png') }}" alt="">
                        </div>
                        @if ($errors->any())
                            <div class="bg-red-500 text-sm text-white rounded-md p-4" role="alert">
                                <ul class="list-disc ps-4">
                                    @foreach ($errors->all() as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="/register-process" method="POST" autocomplete="off">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2" for="LoggingEmailAddress">Full Name</label>
                                <input id="LoggingEmailAddress" name="name" class="form-input" value="{{ old('name') }}" type="text" placeholder="Enter your Name" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2" for="LoggingEmailAddress">Email Address</label>
                                <input id="LoggingEmailAddress" name="email" class="form-input" value="{{ old('email') }}" type="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2" for="LoggingPhone">Phone</label>
                                <input id="LoggingPhone" name="phone" class="form-input" value="{{ old('phone') }}" type="tel" placeholder="Enter your phone" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2" for="loggingPassword">Password</label>
                                <div class="flex items-center">
                                    <input id="loggingPassword" class="form-input rounded-e-none" name="password" type="password" placeholder="Enter your password" required>
                                    <button id="toggle-password" class="rounded-e p-2 bg-slate-500 w-10" type="button">
                                        <i class="mgc_eye_2_fill text-lg text-white m-0 p-0"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" class="form-checkbox rounded" id="checkbox-signup" required>
                                    <label class="ms-2 text-slate-900 dark:text-slate-200" for="checkbox-signup">I accept <a
                                            href="#" class="text-gray-400 underline">Terms and Conditions</a></label>
                                </div>
                            </div>
                            <div class="flex justify-center mb-6">
                                <button type="submit" class="btn w-full text-white bg-primary"> Register</button>
                            </div>
                        </form>
                        <p class="text-gray-500 dark:text-gray-400 text-center">Already have account ?
                            <a href="/login" class="text-primary ms-1"><b>Log In</b></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $("#toggle-password").click(function () {
                $("#loggingPassword").attr('type') === 'password' ? $('#loggingPassword').attr('type', 'text') : $('#loggingPassword').attr('type', 'password');
            })
        })
    </script>
</body>
</html>
