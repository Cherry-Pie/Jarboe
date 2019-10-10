<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait StoreHandlerTrait
{
    /**
     * Handle store action.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleStore(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->crud()->actions()->isAllowed('create')) {
            throw new PermissionDenied();
        }

        if (!$this->can('store')) {
            throw UnauthorizedException::forPermissions(['store']);
        }

        $model = $this->crud()->repo()->store($request);
        $this->idEntity = $model->getKey();

        return redirect($this->crud()->listUrl());
    }

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
