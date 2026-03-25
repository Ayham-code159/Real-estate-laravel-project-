<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminHasPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            abort(403);
        }

        $allowed = match ($permission) {
            'super_admin' => $admin->isSuperAdmin(),
            'manage_users' => $admin->canManageUsers(),
            'manage_business_accounts' => $admin->canManageBusinessAccounts(),
            default => false,
        };

        if (! $allowed) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
