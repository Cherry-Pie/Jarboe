<?php

namespace Yaro\Jarboe\Table\Actions;

interface ActionInterface
{
    public function identifier();

    public function render($model = null);

    public function isAllowed($model = null);

    public function shouldRender($model = null);
}
