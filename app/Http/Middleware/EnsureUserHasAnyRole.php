<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasAnyRole
{
    public function handle(Request $request, Closure $next, string ...$roleSlugs): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if (empty($roleSlugs)) {
            return $next($request);
        }

        $hasRole = $user->roles()->whereIn('slug', $roleSlugs)->exists();
        if (! $hasRole) {
            abort(403, 'You are not authorized to access this resource.');
        }

        return $next($request);
    }
}
