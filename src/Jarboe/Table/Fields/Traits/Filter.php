<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Yaro\Jarboe\Table\Filters\AbstractFilter;

trait Filter
{
    protected $filter = null;

    public function filter(AbstractFilter $filter = null)
    {
        if (is_null($filter)) {
            return $this->filter;
        }

        $filter->field($this);

        $this->filter = $filter;

        return $this;
    }
}
