<?php
/**
 * User: Maros Jasan
 * Date: 23. 2. 2021
 * Time: 14:28
 */

namespace Geodeticca\Iam\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AutoLogin
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $guard = Auth::guard('geodeticca-autologin');

        if (!$guard->check()) {
            // login with iam system user account
            $attempt = $guard->attempt();

            if (!$attempt) {
                return Response::json(['message' => 'Unauthorized'], 401);
            }
        }

        return $next($request);
    }
}
