<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string ...$permissionSlugs): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if (empty($permissionSlugs)) {
            return $next($request);
        }

        foreach ($permissionSlugs as $permissionSlug) {
            if ($user->hasPermission($permissionSlug)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this resource.');
    }
}
