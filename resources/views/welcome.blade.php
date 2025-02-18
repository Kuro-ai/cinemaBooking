<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mingalar</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
</head>
<body class="font-sans antialiased bg-gradient-to-r from-gray-500 via-red-800 to-gray-700 text-white">

    <div class="min-h-screen flex items-center justify-center bg-cover bg-center">
        <div class="bg-black bg-opacity-50 w-full max-w-4xl p-6 rounded-xl shadow-2xl">
            <div class="text-center">
                <div class="flex lg:justify-center lg:col-start-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 -960 960 960"><path fill="white" d="m240-120 240-240 240 240zM80-280v-480q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v480q0 33-23.5 56.5T800-200H680v-80h120v-480H160v480h120v80H160q-33 0-56.5-23.5T80-280m400-200"/></svg>
                </div>
                <h1 class="text-3xl font-bold mb-6">Welcome to Mingalar Cinema</h1>
                <p class="text-lg mb-8">Experience the best of our platform. Join us now and get started!</p>

                <div class="flex justify-center space-x-6">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-6 py-3 text-lg bg-green-700 text-white rounded-md shadow-md transition-all duration-300 hover:bg-green-600">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-6 py-3 text-lg bg-green-700 text-white rounded-md shadow-md transition-all duration-300 hover:bg-green-600">
                            Log In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-6 py-3 text-lg bg-transparent border-2 border-white text-white rounded-md shadow-md transition-all duration-300 hover:bg-white hover:text-black">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

</body>
</html>
