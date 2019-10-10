<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Table\CRUD;

trait SearchHandlerTrait
{
    /**
     * Handle search action.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws UnauthorizedException
     */
    public function handleSearch(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->can('search')) {
            throw UnauthorizedException::forPermissions(['search']);
        }

        $this->crud()->saveSearchFilterParams($request->get('search', []));

        return redirect($this->crud()->listUrl());
    }

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
