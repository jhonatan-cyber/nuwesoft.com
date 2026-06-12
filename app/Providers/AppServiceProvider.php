<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Contracts\StorageServiceInterface::class,
            \App\Services\CloudinaryStorageService::class
        );

        $this->app->singleton(\Illuminate\Foundation\Vite::class, function ($app) {
            return new class extends \Illuminate\Foundation\Vite {
                protected function hotAsset($asset)
                {
                    $url = parent::hotAsset($asset);
                    
                    if (app()->environment('local') && !app()->runningInConsole()) {
                        $requestHost = request()->getHost();
                        if ($requestHost) {
                            $url = str_replace(
                                ['//localhost:', '//127.0.0.1:', '//0.0.0.0:'],
                                '//' . $requestHost . ':',
                                $url
                            );
                        }
                    }
                    
                    return $url;
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

         Vite::prefetch(concurrency: 3);

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip() ?: $request->user()?->id ?: $request->fingerprint());
        });
    }
}
