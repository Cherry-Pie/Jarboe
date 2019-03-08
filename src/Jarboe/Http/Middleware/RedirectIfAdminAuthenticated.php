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
        if (Auth::guard(admin_user_guard())->check()) {
            return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
        }

        return $next($request);
    }
}
