<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\SettingsAccess;
use App\Models\ErrorLog;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );

        $middleware->alias([
            'permission' => CheckPermission::class,
            'settings' => SettingsAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /*
        |--------------------------------------------------------------------------
        | Global Error Logging
        |--------------------------------------------------------------------------
        */

        $exceptions->report(function (Throwable $e) {
            try {
                $request = request();

                ErrorLog::create([
                    'user_id' => auth()->id(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'exception_type' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            } catch (Throwable $loggingException) {
                // Never allow error logging itself to create another error.
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Global Friendly Error Response
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (Throwable $e, Request $request) {

        \Illuminate\Support\Facades\Log::error('DASHBOARD DEBUG: '.$e->getMessage(), [
    'file' => $e->getFile(),
    'line' => $e->getLine(),
    'url' => $request->fullUrl(),
]);

            // Validation errors should continue through Laravel's
            // normal validation flow.
            if ($e instanceof ValidationException) {
                return null;
            }

            // Normal HTTP errors such as 404, 403, 401 etc.
            // should keep Laravel's normal handling.
            if ($e instanceof HttpExceptionInterface) {
                return null;
            }

            // API / AJAX requests receive a safe JSON response.
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again. If the problem continues, contact the administrator.',
                ], 500);
            }

            // Normal browser requests receive our friendly error page.
            return response()->view('errors.generic', [
                'message' => 'Something went wrong. Please try again. If the problem continues, contact the administrator.',
            ], 500);
        });

    })
    ->create();