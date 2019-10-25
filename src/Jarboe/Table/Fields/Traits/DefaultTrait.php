<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait DefaultTrait
{
    protected $default = null;

    public function default($value)
    {
        $this->default = $value;

        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }
}
