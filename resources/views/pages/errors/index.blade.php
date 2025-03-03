<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <img src="https://via.placeholder.com/400x300?text=Error+Image" alt="Error Image" class="mx-auto mb-4">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Oops! Something went wrong.</h1>
            <p class="text-lg text-gray-600 mb-6">We can't seem to find the page you're looking for.</p>
            <button
                @if (Auth::check())
                    onclick="history.back()"
                @else
                    onclick="window.location.href = '/login'"
                @endif
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Go back
            </button>
        </div>
    </div>
</body>
</html>
