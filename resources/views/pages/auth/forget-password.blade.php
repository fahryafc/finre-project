<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
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
                        <form method="POST" action="/forget-password-process" autocomplete="off">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                       for="LoggingEmailAddress">Email Address</label>
                                <input id="LoggingEmailAddress" class="form-input" type="email"
                                       placeholder="Enter your email" value="{{ old('email') }}" name="email">
                            </div>
                            <div class="flex justify-center mb-6">
                                <button class="btn w-full text-white bg-primary"> Check User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/app.js'])
</body>
</html>
