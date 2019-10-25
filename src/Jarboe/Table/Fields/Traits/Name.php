<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Name
{
    protected $name  = '';

    public function name(string $name = null)
    {
        if (is_null($name)) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }
}
