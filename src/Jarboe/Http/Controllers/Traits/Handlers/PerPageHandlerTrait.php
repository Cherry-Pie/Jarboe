<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Yaro\Jarboe\Table\CRUD;

trait PerPageHandlerTrait
{
    /**
     * Handle setting per page param.
     *
     * @param $perPage
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function perPage($perPage)
    {
        $this->init();
        $this->bound();

        $this->crud()->setPerPageParam((int) $perPage);

        return redirect($this->crud()->listUrl());
    }

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
