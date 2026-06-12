<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Paksa root URL sesuai APP_URL di .env
        // Mencegah mismatch pada signed URL verifikasi email
        URL::forceRootUrl(config('app.url'));
    }
}
