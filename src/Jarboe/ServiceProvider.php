<?php

namespace Yaro\Jarboe;

use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Yaro\Jarboe\Console\Commands\Install;
use Yaro\Jarboe\Console\Commands\Make\Tool as MakeTool;
use Yaro\Jarboe\Helpers\Locale;
use Yaro\Jarboe\Helpers\System;
use Yaro\Jarboe\Table\Repositories\EloquentModelRepository;
use Yaro\Jarboe\Table\Repositories\ModelRepositoryInterface;
use Yaro\Jarboe\ViewComponents\Breadcrumbs\Breadcrumbs;
use Yaro\Jarboe\ViewComponents\Breadcrumbs\BreadcrumbsInterface;

class ServiceProvider extends IlluminateServiceProvider
{

    /**
     * A list of artisan commands
     *
     * @var array
     */
    protected $commands = [
        Install::class,
        MakeTool::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/admin_panel.php' => config_path('jarboe/admin_panel.php'),
            __DIR__.'/../config/crud.php' => config_path('jarboe/crud.php'),
            __DIR__.'/../config/locales.php' => config_path('jarboe/locales.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

        //$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/common.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
        if (config('jarboe.admin_panel.default_routes_enabled')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/admins.php');
        }
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'jarboe');
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/jarboe'),
        ], 'public');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'jarboe');

        $this->registerViewComposer();
        $this->registerBladeDirectives();
        $this->registerMiddlewareGroup();
        $this->registerBindings();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/admin_panel.php', 'jarboe.admin_panel');
        $this->mergeConfigFrom(__DIR__.'/../config/crud.php', 'jarboe.crud');
        $this->mergeConfigFrom(__DIR__.'/../config/locales.php', 'jarboe.locales');

        $this->app->singleton('jarboe', function ($app) {
            return new Jarboe();
        });

        $this->initFallbackRoute();
    }

    private function registerBladeDirectives()
    {
        Blade::directive('pushonce', function ($expression) {
            $params = collect(explode(',', $expression))->map(function ($item) {
                return trim($item);
            });
            $stack = $params->shift();
            $content = $params->implode(',');

            $isDisplayed = '__pushonce_'.md5($content);
            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; \$__env->startPush({$stack}); ?>"
                . $content
                . '<?php $__env->stopPush(); endif; ?>';
        });
    }

    private function registerMiddlewareGroup()
    {
        foreach ($this->app->config->get('jarboe.admin_panel.middleware_groups', []) as $group => $middlewares) {
            $this->app->router->middlewareGroup($group, $middlewares);
        }
    }

    private function registerViewComposer()
    {
        View::composer('jarboe::layouts.main', function ($view) {
            $themes = [
                'brown' => 'smart-style-0',
                'dark' => 'smart-style-1',
                'light' => 'smart-style-2',
                'google-skin' => 'smart-style-3',
                'pixel-smash' => 'smart-style-4',
                'glass' => 'smart-style-5',
                'material' => 'smart-style-6',
            ];
            $selectedTheme = $this->app->config->get('jarboe.admin_panel.theme', 'light');

            $view->themeClass = Arr::get($themes, $selectedTheme, $selectedTheme);
            $view->menuOnTop = $this->app->config->get('jarboe.admin_panel.menu_on_top');

            $localeHelper = new Locale();
            $currentLocale = $localeHelper->current();
            if ($currentLocale == 'en' || $currentLocale == 'en-US') {
                $currentLocale = false;
            }
            $view->currentLocale = $currentLocale;
        });

        View::composer('jarboe::inc.locale_selector', function ($view) {
            $view->localeHelper = new Locale();
        });

        View::composer('jarboe::inc.footer', function ($view) {
            $view->jarboeVersion = Jarboe::VERSION;
            $view->laravelVersion = Application::VERSION;
            $view->system = new System();
        });
    }

    private function initFallbackRoute()
    {
        $this->app->booted(function () {
            $router = $this->app->router;
            $router->group(app('jarboe')->routeGroupOptions(), function () use ($router) {
                $router->get('{any}', function () {
                    return response()->view('jarboe::errors.404')->setStatusCode(404);
                })->where('any', '.*');
            });
        });
    }

    private function registerBindings()
    {
        $this->app->bind(BreadcrumbsInterface::class, function ($app) {
            return new Breadcrumbs();
        });
        $this->app->bind(ModelRepositoryInterface::class, function ($app) {
            return new EloquentModelRepository();
        });
    }
}
