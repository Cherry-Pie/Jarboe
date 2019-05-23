<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Placeholder
{
    protected $placeholder;

    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }
}
