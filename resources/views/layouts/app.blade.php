<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', __('Inventory System')) }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-neutral-100 text-neutral-800"> {{-- Используем новые нейтральные цвета --}}
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow border-b border-neutral-200"> {{-- Светлый фон, нейтральная граница --}}
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex">
                <!-- Sidebar Navigation -->
                <div class="w-64 bg-primary-800 text-white min-h-screen flex flex-col shadow-lg"> {{-- Темно-синий сайдбар --}}
                    <div class="p-4 text-xl font-semibold text-center border-b border-primary-700">
                        {{ __('Inventory System') }}
                    </div>
                    <x-sidebar-nav />
                </div>

                <!-- Main Content Area -->
                <div class="flex-1 p-4 bg-neutral-100"> {{-- Светлый основной фон --}}
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
