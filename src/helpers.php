<?php

if (!function_exists('admin_url'))
{
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

if (!function_exists('admin_user_guard'))
{
    function admin_user_guard()
    {
        $default = config('auth.defaults.guard');
        $guard = config('jarboe.admin_panel.auth_guard') ?: $default;

        return $guard;
    }
}

if (!function_exists('admin_user'))
{
    function admin_user()
    {
        return auth(admin_user_guard())->user();
    }
}

if (!function_exists('is_current_admin_url'))
{
    function is_current_admin_url($path = '')
    {
        $chunks = explode('/~/', request()->url());
        $current = rtrim($chunks[0], '/');
        $path = rtrim(admin_url($path), '/');

        return $current == $path;
    }
}
