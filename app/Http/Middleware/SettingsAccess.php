<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->hasAnyRole([
            'Super Admin',
            'super-admin',
            'Owner',
            'owner',
            'super_admin',
            'superadmin',
        ])) {
            return $next($request);
        }

        if ($user->hasAnyRole([
            'Admin',
            'admin',
        ])) {
            if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
                return $next($request);
            }

            abort(403, 'Admin users have view-only access to Settings.');
        }

        abort(403, 'You do not have permission to access Settings.');
    }
}