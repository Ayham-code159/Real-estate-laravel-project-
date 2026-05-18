<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminHasPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            abort(403);
        }

        if ($permission === 'super_admin') {
            if (! $admin->isSuperAdmin()) {
                abort(403, 'You do not have permission to access this page.');
            }

            return $next($request);
        }

        if (! $admin->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
