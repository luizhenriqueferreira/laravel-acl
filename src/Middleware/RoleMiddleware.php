<?php

namespace LuizHenriqueBK\LaravelAcl\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  $roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (($user = $request->user()) && $user->hasRole(...$roles)) {
            return $next($request);
        }

        if (!$request->expectsJson()) {
            return response()->json('Unauthorized', 401);
        }

        return abort(401);
    }
}
