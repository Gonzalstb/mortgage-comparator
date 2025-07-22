<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-dark bg-elegant-gradient min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">
            <div class="relative z-10 flex flex-col items-center gap-2">
                <a href="/" class="flex items-center gap-4">
                    <x-application-logo class="w-20 h-20 fill-current text-accent" />
                    <span class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-accent to-gold bg-clip-text text-transparent drop-shadow-elegant tracking-tight" style="letter-spacing:0.01em;">Comparador de hipotecas</span>
                </a>
            </div>
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-elegant border border-gray-200 rounded-2xl relative z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
