<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Width
{
    protected $width;

    public function width(int $width)
    {
        $this->width = $width;

        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }
}
