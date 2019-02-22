<?php

namespace Yaro\Jarboe\Http\Middleware;

class GuardSwitcher
{
    public function handle($request, \Closure $next)
    {
        auth()->setDefaultDriver(admin_user_guard());

        return $next($request);
    }
}
