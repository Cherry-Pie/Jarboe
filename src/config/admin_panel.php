<?php

return [
    /*
     * Just prefix for admin panel urls.
     */
    'prefix' => 'admin',

    /*
     * Domain for admin panel if `subdomain_panel_enabled` is `true`.
     */
    'domain' => '',

    /*
     * Host admin panel on subdomain.
     */
    'subdomain_panel_enabled' => false,

    /*
     * Enable or disable registration functionality.
     */
    'registration_enabled' => true,

    /*
     * Auth guard for admin users.
     * Leave empty to use default one.
     */
    'auth_guard' => 'admin',

    /*
     * OTP 2-factor authentication settings.
     */
    'two_factor_auth' => [
        /*
         * Enable/disable 2-factor authentication.
         */
        'enabled' => false,

        /*
         * Issuer name. Leave null for using application name.
         */
        'issuer' => null,
    ],

    /*
     * Dashboard uri without prefix. Will be redirected to after successful login.
     */
    'dashboard' => 'dashboard',

    /*
     * Admin user model.
     */
    'admin_model' => \Yaro\Jarboe\Models\Admin::class,

    /*
     * Middleware groups for admin panel routes.
     */
    'middleware_groups' => [
        'jarboe' => [
            \Yaro\Jarboe\Http\Middleware\GuardSwitcher::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Yaro\Jarboe\Http\Middleware\AdminCheck::class,
            \Yaro\Jarboe\Http\Middleware\ChangeLocale::class,
        ]
    ],

    /*
     * CSS theme for admin panel.
     *
     * Supported: "default", "dark", "light", "google-skin", "pixel-smash", "glass", "material".
     * Non-supported theme name will be added as class to `<body>` element.
     */
    'theme' => 'light',

    /*
     * Place navigation menu on top.
     */
    'menu_on_top' => false,

    /*
     * Default routes for admin-panel section, e.g. admins and roles/permissions crud tables.
     */
    'default_routes_enabled' => true,

    /*
     * License key.
     */
    'license_key' => env('JARBOE_LICENSE_KEY'),
];
