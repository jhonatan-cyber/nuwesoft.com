<?php

namespace App\Http\Middleware;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $posthogFlags = [];
        if (config('services.posthog.key') && $request->user()) {
            try {
                $posthog = new \PostHog\PostHog(config('services.posthog.key'), [
                    'host' => config('services.posthog.host'),
                ]);
                $posthogFlags = $posthog->getAllFlags($request->user()->email);
            } catch (\Throwable $e) {
                // Don't break the app if PostHog is unavailable
                report($e);
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'settings' => fn () => Setting::getAll(),
            'unread_messages' => fn () => $request->user()
                ? ContactMessage::whereNull('read_at')->count()
                : 0,
            'posthog_flags' => $posthogFlags,
        ];
    }
}
