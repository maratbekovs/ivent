<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Digital Space PC IUK') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/iuk.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Figtree:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Header Section -->
    <header class="main-header">
        <div class="header-content">
            <div class="logo-container">
                {{-- ИСПРАВЛЕНО: Убедитесь, что логотип находится в public/images/pc_iuk_logo.svg --}}
                <img src="{{ asset('images/iuk1.svg') }}" alt="{{ __('PC IUK Logo') }}" class="logo">
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-decoration decoration-1">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="hero-decoration decoration-2">
                <i class="fas fa-laptop-code"></i>
            </div>
            <p class="hero-subtitle">&nbsp;</p>
            <p class="hero-subtitle">&nbsp;</p>
            <h1 class="hero-title">{{ __('Digital Space PC IUK') }}</h1>
            <h2 class="hero-title text-lg font-normal mt-2">{{ __('MUKR') }}</h2>
            <p class="hero-subtitle">
                {{ __('Innovative services for students and teachers. Everything you need for effective study and work in one place.') }}
            </p>
        </section>
        
        <!-- Services Section -->
        <section>
            <h2 class="section-title">{{ __('Our Digital Services') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- AVN System Card -->
                <a href="https://avn.pc.edu.kg/" class="card" style="animation-delay: 0.1s;">
                    <div class="card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="card-title">{{ __('AVN System') }}</h3>
                    <p class="card-text">
                        {{ __('Full access to academic records, class schedules, educational materials, and electronic gradebook.') }}
                    </p>
                    <span class="card-link">
                        {{ __('Go to Service') }} <i class="fas fa-arrow-right"></i>
                    </span>
                </a>

                <!-- Technical Support Card -->
                <a href="https://techsupport.iuk.kg/" class="card" style="animation-delay: 0.2s;">
                    <div class="card-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="card-title">{{ __('Technical Support') }}</h3>
                    <p class="card-text">
                        {{ __('24/7 assistance with any technical issues. Quick resolution of access and equipment problems.') }}
                    </p>
                    <span class="card-link">
                        {{ __('Go to Service') }} <i class="fas fa-arrow-right"></i>
                    </span>
                </a>

                <!-- KAL System Card -->
                <a href="@auth {{ route('dashboard') }} @else {{ route('login') }} @endauth" class="card" style="animation-delay: 0.3s;">
                    <div class="card-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="card-title">{{ __('KAL System') }}</h3>
                    <p class="card-text">
                        {{ __('A modern platform for resource management, equipment accounting, and college inventory control.') }}
                    </p>
                     <span class="card-link">
                        {{ __('Login to System') }} <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
            </div>
        </section>
        
    </main>

    
    <!-- Footer Section -->
    <footer class="main-footer">
        <div class="footer-content">
            <h3 class="footer-title">{{ __('Join Us') }}</h3>
            <div class="social-links">
                <a href="https://www.facebook.com/pc.iuk.mukr.kg" target="_blank" class="social-link" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/pc.iuk.kg/" target="_blank" class="social-link" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://t.me/pc_iuk" target="_blank" class="social-link" title="Telegram">
                    <i class="fab fa-telegram-plane"></i>
                </a>
                <!-- <a href="https://vk.com/iuk_kg" target="_blank" class="social-link" title="VKontakte">
                    <i class="fab fa-vk"></i>
                </a> -->
            </div>
            
            <p class="copyright">&copy; {{ date('Y') }} {{ __('Digital Space PC IUK') }}. {{ __('All rights reserved.') }}</p>
            <p class="developer">{{ __('Developed by Maratbekovs') }}</p>
        </div>
    </footer>

</body>
</html>
