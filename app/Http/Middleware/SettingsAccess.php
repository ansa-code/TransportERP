<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Not authenticated
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Super Admin / Owner → Full access
        if ($user->hasAnyRole([
            'Super Admin',
            
            'Owner',
            'owner',
            'super_admin',
            'superadmin',
        ])) {
            return $next($request);
        }

        // Admin → View only
        if ($user->hasAnyRole([
            'Admin',
            'admin',
        ])) {
            if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
                return $next($request);
            }

            abort(403, 'Admin users have view-only access to Settings.');
        }

        // All other roles → No access
        abort(403, 'You do not have permission to access Settings.');
    }
}