<?php

namespace Yaro\Jarboe\Http\Middleware;

use Closure;

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
        if (auth(admin_user_guard())->check()) {
            return redirect(admin_url(config('jarboe.admin_panel.dashboard')));
        }

        return $next($request);
    }
}
