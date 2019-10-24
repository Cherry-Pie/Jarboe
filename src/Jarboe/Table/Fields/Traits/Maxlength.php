<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Maxlength
{
    protected $maxlength = null;

    public function maxlength(int $length)
    {
        $this->maxlength = $length;

        return $this;
    }

    public function hasMaxlength(): bool
    {
        return !is_null($this->maxlength);
    }

    public function getMaxlength()
    {
        return $this->maxlength;
    }
}
