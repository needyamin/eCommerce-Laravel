<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class CheckPermission
{
    public function handle($request, Closure $next, ...$permissions)
    {
        if (Auth::check() && Auth::user()->hasAnyPermission($permissions)) {
            return $next($request);
        }

        return abort(403, 'Unauthorized.');
    }
}
