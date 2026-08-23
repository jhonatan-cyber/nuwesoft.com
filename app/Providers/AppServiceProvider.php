<?php

namespace App\Providers;

use App\Observers\ContactMessageObserver;
use App\Observers\PostObserver;
use App\Observers\ProjectImageObserver;
use App\Observers\ProjectObserver;
use App\Observers\TechnologyObserver;
use App\Observers\TestimonialObserver;
use App\Services\CacheService;
use App\Services\EntityCacheManager;
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
        // Register Eloquent Observers
        \App\Models\Project::observe(ProjectObserver::class);
        \App\Models\Post::observe(PostObserver::class);
        \App\Models\Technology::observe(TechnologyObserver::class);
        \App\Models\ContactMessage::observe(ContactMessageObserver::class);
        \App\Models\Testimonial::observe(TestimonialObserver::class);
        \App\Models\ProjectImage::observe(ProjectImageObserver::class);

        $this->app->singleton(
            \App\Contracts\StorageServiceInterface::class,
            \App\Services\CloudinaryStorageService::class
        );

        $this->app->singleton(CacheService::class);

        // Load cache key registry from config and register with EntityCacheManager
        $cacheKeys = config('cache_keys.entities', []);
        foreach ($cacheKeys as $entity => $keys) {
            EntityCacheManager::register($entity, $keys);
        }

        $this->app->singleton(\Illuminate\Foundation\Vite::class, function ($app) {
            return new class extends \Illuminate\Foundation\Vite
            {
                protected function hotAsset($asset)
                {
                    $url = parent::hotAsset($asset);

                    if (app()->environment('local') && ! app()->runningInConsole()) {
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

        // Public read pages (blog, portafolio, servicios, welcome) — generous for SEO crawlers
        RateLimiter::for('public', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        // XML feeds (RSS, sitemap) — heavier to generate, stricter limit
        RateLimiter::for('feeds', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // Contact form page (GET) — light throttle to prevent scraping
        RateLimiter::for('public.contact', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Login — strict: 5 attempts per minute, per email+IP
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->string('email') . '|' . $request->ip()
            );
        });
    }
}
