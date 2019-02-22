<?php

namespace Yaro\Jarboe\Http\Middleware;

use Closure;
use Yaro\Jarboe\Helpers\Locale;

class ChangeLocale
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
        $helper = new Locale();
        $locale = $request->get('__jarboe-locale') ?: $helper->current();
        $helper->setLocale($locale);

        if ($request->has('__jarboe-locale')) {
            return redirect()->back();
        }

        return $next($request);
    }
}
