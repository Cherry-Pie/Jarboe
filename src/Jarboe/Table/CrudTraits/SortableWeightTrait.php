<?php

namespace Yaro\Jarboe\Table\CrudTraits;

trait SortableWeightTrait
{
    private $sortableWeightField = null;

    public function enableSortableByWeight(string $field)
    {
        $this->sortableWeightField = $field;
    }

    public function isSortableByWeight()
    {
        return (bool) $this->getSortableWeightFieldName();
    }

    public function getSortableWeightFieldName()
    {
        return $this->sortableWeightField;
    }
}
