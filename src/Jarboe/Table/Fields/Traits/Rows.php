<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Rows
{
    protected $rows = 3;

    public function rows(int $rows)
    {
        $this->rows = $rows;

        return $this;
    }

    public function getRowsNum(): int
    {
        return $this->rows;
    }
}
