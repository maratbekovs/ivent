<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('Digital Space PC IUK') }}</title> 
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Figtree:wght@700;800&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased flex flex-col min-h-screen bg-background-light text-text-primary">

        <!-- Header Section -->
        <header class="w-full py-unit-4 px-unit-2 md:px-unit-4 relative shadow-md-custom overflow-hidden" style="background: linear-gradient(135deg, theme('colors.primary-main.dark') 0%, theme('colors.primary-main.DEFAULT') 100%);">
            {{-- Фоновые эффекты хедера --}}
            <div class="absolute inset-0 z-0" style="background: radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.08) 0%, transparent 40%), radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.05) 0%, transparent 30%); opacity: 0.5;"></div>

            <nav class="relative z-10 max-w-screen-xl mx-auto flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-text-on-primary">
                    {{ __('Digital Space PC IUK') }}
                </a>
                <div class="flex items-center space-x-unit-2">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="px-unit-4 py-unit-1-5 bg-surface text-primary-main rounded-md-custom hover:bg-gray-100 transition duration-150 ease-in-out"
                        >
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="px-unit-4 py-unit-1-5 text-text-on-primary border border-text-on-primary rounded-md-custom hover:bg-surface hover:text-primary-main transition duration-150 ease-in-out"
                        >
                            {{ __('Login') }}
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="px-unit-4 py-unit-1-5 bg-surface text-primary-main rounded-md-custom hover:bg-gray-100 transition duration-150 ease-in-out">
                                {{ __('Register') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>

            <div class="relative z-10 py-unit-4 md:py-unit-8 text-center">
                <div class="flex justify-center items-center mb-unit-3">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-unit-3 shadow-md-custom">
                        <i class="fas fa-graduation-cap text-5xl text-white"></i>
                    </div>
                    <h1 class="text-white text-5xl md:text-6xl font-extrabold leading-tight tracking-tight font-heading">
                        {{ __('Polytechnic College') }}<br>{{ __('MUKR') }}
                    </h1>
                </div>
                <p class="text-white text-xl font-light max-w-3xl mx-auto">
                    {{ __('Founded on August 3, 1930') }}
                </p>
            </div>
        </header>

        <main class="flex-grow flex flex-col items-center justify-center py-unit-8 px-unit-4 sm:px-unit-6 lg:px-unit-8 text-center">
            <div class="max-w-screen-xl w-full space-y-unit-4">
                <h2 class="text-4xl md:text-5xl font-extrabold text-primary-main mb-unit-4 font-heading">
                    {{ __('Explore Our Digital Services') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-unit-4 mt-unit-8">
                    <!-- AVN System Card -->
                    <a href="https://avn.iuk.kg/" target="_blank" class="block bg-surface p-unit-4 rounded-lg-custom shadow-md-custom hover:shadow-lg-custom transition duration-200 ease-in-out transform hover:-translate-y-unit">
                        <div class="text-accent-main mb-unit-2">
                            <i class="fas fa-graduation-cap text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-text-primary mb-unit-1-5">{{ __('AVN System') }}</h3>
                        <p class="text-text-secondary">{{ __('Access your academic records and learning materials.') }}</p>
                    </a>

                    <!-- Technical Support Card -->
                    <a href="https://techsupport.iuk.kg/" target="_blank" class="block bg-surface p-unit-4 rounded-lg-custom shadow-md-custom hover:shadow-lg-custom transition duration-200 ease-in-out transform hover:-translate-y-unit">
                        <div class="text-accent-main mb-unit-2">
                            <i class="fas fa-headset text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-text-primary mb-unit-1-5">{{ __('Technical Support') }}</h3>
                        <p class="text-text-secondary">{{ __('Get assistance with any technical issues.') }}</p>
                    </a>

                    <!-- KAL System Card -->
                    <a href="{{ url('/dashboard') }}" class="block bg-surface p-unit-4 rounded-lg-custom shadow-md-custom hover:shadow-lg-custom transition duration-200 ease-in-out transform hover:-translate-y-unit">
                        <div class="text-accent-main mb-unit-2">
                            <i class="fas fa-code-branch text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-text-primary mb-unit-1-5">{{ __('KAL System (Inventory Management)') }}</h3>
                        <p class="text-text-secondary">{{ __('Our new platform for enhanced learning and collaboration.') }}</p>
                    </a>
                </div>

                <div class="mt-unit-8">
                    <h2 class="text-4xl md:text-5xl font-extrabold text-primary-main mb-unit-4 font-heading">
                        {{ __('Connect with Us') }}
                    </h2>
                    <div class="flex justify-center space-x-unit-3">
                        <a href="https://www.facebook.com/iuk.kg" target="_blank" class="text-accent-main hover:text-accent-main-hover transition duration-150 ease-in-out" title="Facebook" aria-label="Facebook">
                            <i class="fab fa-facebook-square text-5xl"></i>
                        </a>
                        <a href="https://www.instagram.com/iuk.kg" target="_blank" class="text-accent-main hover:text-accent-main-hover transition duration-150 ease-in-out" title="Instagram" aria-label="Instagram">
                            <i class="fab fa-instagram-square text-5xl"></i>
                        </a>
                        <a href="https://t.me/iuk_kg" target="_blank" class="text-accent-main hover:text-accent-main-hover transition duration-150 ease-in-out" title="Telegram" aria-label="Telegram">
                            <i class="fab fa-telegram-plane text-5xl"></i>
                        </a>
                        <a href="https://vk.com/iuk_kg" target="_blank" class="text-accent-main hover:text-accent-main-hover transition duration-150 ease-in-out" title="VKontakte" aria-label="VKontakte">
                            <i class="fab fa-vk text-5xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <footer class="w-full py-unit-3 px-unit-2 text-text-secondary text-center text-sm" style="background-color: theme('colors.primary-main.dark');">
            <p>&copy; {{ date('Y') }} {{ config('app.name', __('Digital Space PC IUK')) }}. {{ __('All rights reserved.') }}</p>
            <p class="mt-unit text-text-secondary">{{ __('Developed by Maratbekovs') }}</p>
        </footer>
    </body>
</html>
