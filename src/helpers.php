<?php

if (!function_exists('admin_url')) {
    function admin_url($path = '')
    {
        $isSubdomainBasedPanel = config('jarboe.admin_panel.subdomain_panel_enabled', false);
        $prefix = '';
        if (!$isSubdomainBasedPanel) {
            $prefix = config('jarboe.admin_panel.prefix');
        }

        return url(implode('/', [
            $prefix,
            ltrim($path, '/'),
        ]));
    }
}

if (!function_exists('admin_user_guard')) {
    function admin_user_guard()
    {
        $default = config('auth.defaults.guard');
        $guard = config('jarboe.admin_panel.auth_guard') ?: $default;

        return $guard;
    }
}

if (!function_exists('admin_user')) {
    function admin_user()
    {
        return auth(admin_user_guard())->user();
    }
}

if (!function_exists('is_current_admin_url')) {
    function is_current_admin_url($path = '')
    {
        $chunks = explode('/~/', request()->url());
        $current = rtrim($chunks[0], '/');
        $path = rtrim(admin_url($path), '/');

        return $current == $path;
    }
}

if (!function_exists('urlify')) {
    function urlify($text, int $length = 60)
    {
        return \Yaro\Jarboe\Helpers\URLify::filter($text, $length);
    }
}

if (!function_exists('collect')) {
    function collect($value = null)
    {
        return new \Illuminate\Support\Collection($value);
    }
}

if (!function_exists('array_wrap')) {
    function array_wrap($value)
    {
        return \Illuminate\Support\Arr::wrap($value);
    }
}
