<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
       
       dd('RouteServiceProvider is booting!'); // Tambahkan baris ini
    // ... kode lainnya
        // Konfigurasi rate limiting untuk API
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Memuat file route
        $this->routes(function () {
            // Memuat route API
            // Route ini akan memiliki prefix 'api' dan middleware 'api'
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Memuat route Web
            // Route ini akan menggunakan middleware 'web'
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}