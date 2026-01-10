<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        \Illuminate\Support\Facades\RateLimiter::for('booking_submission', function (\Illuminate\Http\Request $request) {
            if (app()->environment('local')) {
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(1000);
            }
            return \Illuminate\Cache\RateLimiting\Limit::perMinutes(30, 1)->by($request->user()?->id ?: $request->ip())->response(function () {
                return view('errors.429');
            });
        });
    }
}
