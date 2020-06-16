<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Ajax
{
    protected $ajax = false;

    public function ajax(bool $ajax = true)
    {
        $this->ajax = $ajax;

        return $this;
    }

    public function isAjax()
    {
        return $this->ajax;
    }
}
