<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Tab
{
    protected $tab = '';

    public function tab(string $tab)
    {
        $this->tab = $tab;

        return $this;
    }

    public function getTab(): string
    {
        return $this->tab;
    }
}
