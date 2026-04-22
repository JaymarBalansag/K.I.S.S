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
        RateLimiter::for('staff-login', function (Request $request) {
            $email = strtolower((string) $request->input('email', ''));
            $ip = (string) $request->ip();

            return [
                Limit::perMinute(20)->by($ip),
                Limit::perMinute(5)
                    ->by($email . '|' . $ip)
                    ->response(function (Request $request, array $headers) {
                        $retryAfter = (int) ($headers['Retry-After'] ?? 60);

                        return response()
                            ->json([
                                'message' => 'Too many login attempts. Please try again later.',
                                'retry_after' => $retryAfter,
                            ], 429)
                            ->withHeaders($headers);
                    }),
            ];
        });
    }
}
