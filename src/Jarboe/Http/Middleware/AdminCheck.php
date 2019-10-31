<?php

namespace Yaro\Jarboe\Http\Middleware;

use Closure;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth(admin_user_guard())->check()) {
            abort(404);
        }

        return $next($request);
    }
}
