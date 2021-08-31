<?php

namespace Geodeticca\Iam\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, \Closure $next, $guard = null, $route = null)
    {
        if (!Auth::guard($guard)->check()) {
            throw new AuthenticationException('Unauthenticated.', [$guard], route($route));
        }

        return $next($request);
    }
}
