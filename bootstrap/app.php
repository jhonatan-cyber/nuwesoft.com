<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(prepend: [
            SecurityHeaders::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 404 logging for monitoring
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            $logData = [
                'url' => $request->fullUrl(),
                'referer' => $request->header('referer'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toIso8601String(),
            ];

            // Log 404s to monitor broken links
            Log::warning('404 Not Found', $logData);

            // Dispatch async job to send 404 event to PostHog (non-blocking)
            \App\Jobs\Send404ToPostHog::dispatch($logData);

            // Persist 404 log to database
            try {
                \App\Models\FourOhFourLog::create([
                    'url' => $logData['url'],
                    'referer' => $logData['referer'],
                    'ip' => $logData['ip'],
                    'user_agent' => $logData['user_agent'],
                    'logged_at' => now(),
                ]);
            } catch (\Throwable $th) {
                Log::error('Failed to persist 404 log: ' . $th->getMessage());
            }

            // Send notification for critical 404s (from external referrers)
            if ($request->header('referer') && !str_contains($request->header('referer') ?? '', $request->getHost())) {
                try {
                    $user = \App\Models\User::first();
                    if ($user) {
                        $user->notify(new \App\Notifications\Critical404($logData));
                    }
                } catch (\Throwable $th) {
                    // Don't break the page if notification fails
                    Log::error('Failed to send 404 notification: ' . $th->getMessage());
                }
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Not Found'], 404);
            }

            return Inertia::render('Errors/NotFound', ['status' => 404])->toResponse($request)->setStatusCode(404);
        });

        // 403 Forbidden
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            return Inertia::render('Errors/Forbidden', ['status' => 403])->toResponse($request)->setStatusCode(403);
        });

        // Maintenance mode render
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 503) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Service Unavailable'], 503);
                }

                return Inertia::render('Errors/Maintenance', ['status' => 503])->toResponse($request)->setStatusCode(503);
            }
        });
    })
    ->create();
