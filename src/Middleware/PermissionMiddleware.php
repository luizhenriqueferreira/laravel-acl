<?php

namespace LuizHenriqueFerreira\LaravelAcl\Middleware;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        if (($user = $request->user()) && $user->hasPermissions(...$permissions)) {
            return $next($request);
        }

        if (!$request->expectsJson()) {
            return response()->json('Unauthorized', 401);
        }

        return abort(401);
    }
}
