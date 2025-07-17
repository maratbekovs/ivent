<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KAL System') }}</title>
        <link rel="icon" href="{{ asset('images/iuk.png') }}" type="image/png">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Figtree:wght@700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- ИЗМЕНЕНИЕ: Добавлены стили для Tom Select -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-background-light flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-primary-dark text-white flex-shrink-0 flex flex-col">
                <div class="h-20 flex items-center justify-center bg-primary-dark/50">
                    <a href="{{ route('dashboard') }}">
                        <div class="bg-white/95 rounded-full p-1.5 shadow-lg">
                            <img src="{{ asset('images/iuk1.svg') }}" alt="{{ __('PC IUK Logo') }}" class="h-12">
                        </div>
                    </a>
                </div>
                <nav class="flex-grow">
                    <div class="p-4 text-xl font-semibold text-center border-b border-primary/50">
                        {{ __('KAL System') }}
                    </div>
                    <x-sidebar-nav />
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                @include('layouts.navigation')
                <main class="flex-1 p-6">
                    @if (isset($header))
                        <header class="bg-white shadow-sm -mx-6 -mt-6 mb-6">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- ИЗМЕНЕНИЕ: Добавлен скрипт для Tom Select -->
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        @stack('scripts')
    </body>
</html>
