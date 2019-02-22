<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Nullable
{
    protected $nullable = false;

    public function nullable(bool $nullable = true)
    {
        $this->nullable = $nullable;

        return $this;
    }

    public function isNullable()
    {
        return $this->nullable;
    }
}
