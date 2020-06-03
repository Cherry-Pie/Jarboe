<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait CreateHandlerTrait
{
    protected $viewCrudCreate = 'jarboe::crud.create';

    /**
     * Show create form page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleCreate(Request $request)
    {
        $this->beforeInit();
        $this->init();
        $this->bound();

        if (!$this->crud()->actions()->isAllowed('create')) {
            throw new PermissionDenied();
        }

        if (!$this->can('create')) {
            throw UnauthorizedException::forPermissions(['create']);
        }

        return view($this->viewCrudCreate, [
            'crud' => $this->crud(),
            'viewsAbove' => $this->getCreateViewsAbove(),
            'viewsBelow' => $this->getCreateViewsBelow(),
        ]);
    }

    /**
     * Get array of view's objects, that should be rendered above content of `create` view.
     *
     * @return array
     */
    protected function getCreateViewsAbove(): array
    {
        return [];
    }

    /**
     * Get array of view's objects, that should be rendered below content of `create` view.
     *
     * @return array
     */
    protected function getCreateViewsBelow(): array
    {
        return [];
    }

    abstract protected function beforeInit();
    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
