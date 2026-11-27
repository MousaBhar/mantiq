<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Limits assistant usage per user (falls back to IP).
        RateLimiter::for('assistant', function (Request $request) {
            return Limit::perMinute(config('assistant.rate_limit_per_minute'))
                ->by($request->user()?->id ?: $request->ip());
        });
    }
}
