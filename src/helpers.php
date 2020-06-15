<?php

if (!function_exists('admin_url')) {
    function admin_url()
    {
        $isSubdomainBasedPanel = config('jarboe.admin_panel.subdomain_panel_enabled', false);
        $prefix = '';
        if (!$isSubdomainBasedPanel) {
            $prefix = config('jarboe.admin_panel.prefix');
        }

        $args = func_get_args();
        $args[0] = implode('/', [
            $prefix,
            ltrim($args[0] ?? '', '/'),
        ]);

        return call_user_func_array('url', $args);
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

if (!function_exists('admin_auth')) {
    function admin_auth()
    {
        return auth(admin_user_guard());
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

if (!function_exists('is_associative_array')) {
    function is_associative_array(array $array)
    {
        if ($array === []) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}

if (!function_exists('collection_wrap')) {
    function collection_wrap($value = null)
    {
        if (is_object($value) && (is_a($value, \Illuminate\Support\Collection::class) || is_a($value, \Illuminate\Database\Eloquent\Collection::class))) {
            return $value;
        }
        return new \Illuminate\Support\Collection(
            \Illuminate\Support\Arr::wrap($value)
        );
    }
}
