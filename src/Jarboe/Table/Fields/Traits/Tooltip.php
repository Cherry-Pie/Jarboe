<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage as IlluminateStorage;

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
