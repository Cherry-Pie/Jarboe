<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Table\CRUD;

trait ListHandlerTrait
{
    /**
     * Show table list page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws UnauthorizedException
     */
    public function handleList(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->can('list')) {
            throw UnauthorizedException::forPermissions(['list']);
        }

        return view($this->viewCrudList, [
            'crud' => $this->crud(),
            'items' => $this->crud()->repo()->get(),
            'viewsAbove' => $this->getListViewsAbove(),
            'viewsBelow' => $this->getListViewsBelow(),
        ]);
    }

    /**
     * Get array of view's objects, that should be rendered above content of `list` view.
     *
     * @return array
     */
    protected function getListViewsAbove(): array
    {
        return [];
    }

    /**
     * Get array of view's objects, that should be rendered below content of `list` view.
     *
     * @return array
     */
    protected function getListViewsBelow(): array
    {
        return [];
    }

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
