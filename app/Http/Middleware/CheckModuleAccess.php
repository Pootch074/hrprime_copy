<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next, $moduleSlug)
    {
        $user = Auth::user();

        // Permissions could be module.action, e.g., "finance.view"
        $permissions = [
            "{$moduleSlug}.view",
            "{$moduleSlug}.create",
            "{$moduleSlug}.edit",
        ];

        // Check if user has at least one permission
        foreach ($permissions as $permission) {
            if ($user->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        // Unauthorized if no permissions
        abort(403, 'You do not have access to this module.');
    }
}
