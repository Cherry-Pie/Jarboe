<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Yaro\Jarboe\Table\CRUD;

trait OrderByHandlerTrait
{
    /**
     * Save direction by column.
     *
     * @param $column
     * @param $direction
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function orderBy($column, $direction)
    {
        $this->crud()->saveOrderFilterParam($column, $direction);

        return redirect($this->crud()->listUrl());
    }

    abstract protected function beforeInit();
    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
