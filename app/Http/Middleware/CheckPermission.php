<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * Usage:
     * ->middleware('permission:vehicles.view')
     */
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        if (! $request->user()) {
            abort(403);
        }

        if (! $request->user()->hasPermission($permission)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}