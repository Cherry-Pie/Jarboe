<?php

namespace Yaro\Jarboe\Table\Actions;

use Yaro\Jarboe\Table\CRUD;

interface ActionInterface
{
    public function identifier();

    public function render(CRUD $crud, $model = null);

    public function isAllowed($model = null);
}
