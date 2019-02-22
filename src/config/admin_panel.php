<?php

return [
    /**
     * Just prefix for admin panel urls.
     */
    'prefix' => 'admin',

    /**
     * Domain for admin panel if `subdomain_panel_enabled` is `true`.
     */
    'domain' => '',

    /**
     * Host admin panel on subdomain.
     */
    'subdomain_panel_enabled' => false,

    /**
     * Enable or disable registration functionality.
     */
    'registration_enabled' => true,

    /**
     * Auth guard for admin users.
     * Leave empty to use default one.
     */
    'auth_guard' => '',

    /**
     * Dashboard uri without prefix. Will be redirected to after successful login.
     */
    'dashboard' => 'dashboard',

    /**
     * Admin user model.
     */
    'admin_model' => \Yaro\Jarboe\Models\Admin::class,

    /**
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

    /**
     * CSS theme for admin panel.
     *
     * Supported: "default", "dark", "light", "google-skin", "pixel-smash", "glass", "material"
     */
    'theme' => 'default',

    /**
     * Place navigation menu on top.
     */
    'menu_on_top' => false,
];
