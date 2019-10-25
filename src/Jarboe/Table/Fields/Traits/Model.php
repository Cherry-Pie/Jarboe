<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Model
{
    protected $model = '';

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }
}
