<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>E-Purchasing</title>

        <link rel="icon" type="image/png" href="{{ asset('images/online-shop.png') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            
            {{-- Logo Perusahaan Ditampilkan di Sini --}}
            <div>
                <a href="/">
                    {{-- Pastikan nama file logo Anda sudah benar --}}
                    <img src="{{ asset('images/online-shop.png') }}" alt="Logo Perusahaan" class="h-20 w-auto">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{-- Di sinilah konten dari login.blade.php akan ditampilkan --}}
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
