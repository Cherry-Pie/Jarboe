<?php

namespace Yaro\Jarboe\Table\CrudTraits;

trait BatchCheckboxesTrait
{
    private $batchCheckboxesEnabled = false;

    public function enableBatchCheckboxes(bool $enabled = true)
    {
        $this->batchCheckboxesEnabled = $enabled;
    }

    public function isBatchCheckboxesEnabled(): bool
    {
        return $this->batchCheckboxesEnabled;
    }
}
