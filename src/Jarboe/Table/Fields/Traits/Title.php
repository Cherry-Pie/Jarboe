<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Title
{
    protected $title = '';

    public function title(string $title = null)
    {
        if (is_null($title)) {
            return $this->title;
        }

        $this->title = $title;

        return $this;
    }
}
