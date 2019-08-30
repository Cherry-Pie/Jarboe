<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Closure;

trait Orderable
{
    protected $orderable = false;
    protected $overridedOrderCallback;

    public function orderable(bool $orderable = true, Closure $overridedOrderCallback = null)
    {
        $this->orderable = $orderable;
        $this->overridedOrderCallback = $overridedOrderCallback;

        return $this;
    }

    public function isOrderable()
    {
        return $this->orderable;
    }

    public function getOverridedOrderCallback()
    {
        return $this->overridedOrderCallback;
    }
}
