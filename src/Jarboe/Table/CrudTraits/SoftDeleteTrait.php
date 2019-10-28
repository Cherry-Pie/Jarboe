<?php

namespace Yaro\Jarboe\Table\CrudTraits;

trait SoftDeleteTrait
{
    private $softDeleteEnabled = false;

    public function enableSoftDelete(bool $enabled = true)
    {
        $this->softDeleteEnabled = $enabled;
    }

    public function isSoftDeleteEnabled(): bool
    {
        return $this->softDeleteEnabled;
    }
}
