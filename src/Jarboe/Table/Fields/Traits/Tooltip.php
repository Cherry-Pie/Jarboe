<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Tooltip
{
    protected $tooltip;

    public function tooltip($message)
    {
        $this->tooltip = $message;

        return $this;
    }

    public function getTooltip()
    {
        return $this->tooltip;
    }

    public function hasTooltip()
    {
        return (bool) $this->tooltip;
    }
}
