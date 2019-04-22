<?php 

namespace Yaro\Jarboe;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Yaro\Jarboe\Console\Commands\Install;
use Yaro\Jarboe\Console\Commands\Make\Tool as MakeTool;
use Yaro\Jarboe\Helpers\Locale;

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
        $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/admins.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'jarboe');
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/jarboe'),
        ], 'public');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'jarboe');

        $this->registerViewComposer();
        $this->registerBladeDirectives();
        $this->registerMiddlewareGroup();
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

        $this->app->singleton('jarboe', function($app) {
            return new Jarboe();
        });
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
                'default' => 'smart-style-0',
                'dark' => 'smart-style-1',
                'light' => 'smart-style-2',
                'google-skin' => 'smart-style-3',
                'pixel-smash' => 'smart-style-4',
                'glass' => 'smart-style-5',
                'material' => 'smart-style-6',
            ];

            $view->themeClass = array_get($themes, $this->app->config->get('jarboe.admin_panel.theme', 'default'));
            $view->menuOnTop = $this->app->config->get('jarboe.admin_panel.menu_on_top');
        });

        View::composer('jarboe::inc.locale_selector', function ($view) {
            $view->localeHelper = new Locale();
        });
    }
}
