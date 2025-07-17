<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- ИЗМЕНЕНИЕ: Обновлен title и добавлен favicon --}}
        <title>{{ config('app.name', 'KAL System') }}</title>
        <link rel="icon" href="{{ asset('images/iuk.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Figtree:wght@700;800&display=swap" rel="stylesheet">
        
        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-text-primary antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center bg-background-light pt-6 sm:pt-0">
            
            <!-- Logo -->
            <div class="mb-8">
                <a href="/">
                    <img src="{{ asset('images/pc_iuk_logo.svg') }}" alt="{{ __('PC IUK Logo') }}" class="w-52 h-auto">
                </a>
            </div>

            <!-- Auth Card -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-surface shadow-md overflow-hidden rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
