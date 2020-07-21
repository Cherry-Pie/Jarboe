<?php

namespace Yaro\Jarboe\Http\Controllers\Traits;

use Illuminate\Support\Facades\View;
use Yaro\Jarboe\ViewComponents\Breadcrumbs\BreadcrumbsInterface;

trait BreadcrumbsTrait
{
    protected $breadcrumbs;

    public function breadcrumbs(): BreadcrumbsInterface
    {
        return $this->breadcrumbs;
    }

    protected function initBreadcrumbs()
    {
        View::composer('jarboe::crud.list', function ($view) {
            $view->with('breadcrumbs', $this->breadcrumbs());
        });
        View::composer('jarboe::crud.create', function ($view) {
            $view->with('breadcrumbs', $this->breadcrumbs());
        });
        View::composer('jarboe::crud.edit', function ($view) {
            $view->with('breadcrumbs', $this->breadcrumbs());
        });
        View::composer('jarboe::crud.history', function ($view) {
            $view->with('breadcrumbs', $this->breadcrumbs());
        });
    }
}
