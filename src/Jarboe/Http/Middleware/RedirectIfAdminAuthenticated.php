<?php

namespace Yaro\Jarboe\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdminAuthenticated
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
        $default = config('auth.defaults.guard');
        $guard = config('jarboe.admin_panel.auth_guard', $default);

        if (Auth::guard($guard)->check()) {
            return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
        }

        return $next($request);
    }
}
