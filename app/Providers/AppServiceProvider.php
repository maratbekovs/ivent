<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Spatie\Permission\PermissionRegistrar; // Удалить или закомментировать

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Удалить или закомментировать эту строку:
        // app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
