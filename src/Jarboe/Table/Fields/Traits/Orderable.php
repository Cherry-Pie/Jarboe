<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage as IlluminateStorage;

trait Orderable
{
    protected $orderable = false;


    public function orderable(bool $orderable = true)
    {
        $this->orderable = $orderable;

        return $this;
    }

    public function isOrderable()
    {
        return $this->orderable;
    }
}