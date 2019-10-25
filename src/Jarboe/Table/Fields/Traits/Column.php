<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Column
{
    protected $col = 12;

    public function col(int $col)
    {
        $this->col = $col;

        return $this;
    }

    public function getCol(): int
    {
        return $this->col;
    }
}
