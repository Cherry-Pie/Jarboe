<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Readonly
{
    protected $readonly = false;

    public function readonly(bool $isReadonly = true)
    {
        $this->readonly = $isReadonly;

        return $this;
    }

    public function isReadonly(): bool
    {
        return $this->readonly;
    }
}
