<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (Auth::check() && Auth::user()->hasAnyRole($roles)) {
            return $next($request);
        }

        // If Auth::check() is false, or user does not have the required roles
        abort(403, 'Unauthorized.'); // You can customize the error message and HTTP status code accordingly
    }
}
