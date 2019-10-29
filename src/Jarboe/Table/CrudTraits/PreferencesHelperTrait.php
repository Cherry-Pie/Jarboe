<?php

namespace Yaro\Jarboe\Table\CrudTraits;

trait PreferencesHelperTrait
{
    private $tableIdentifier;

    /**
     * @param null $ident
     * @return mixed|void
     */
    public function tableIdentifier($ident = null)
    {
        if (is_null($ident)) {
            return $this->tableIdentifier;
        }

        $this->tableIdentifier = $ident;
    }

    public function getCurrentLocale()
    {
        return $this->preferences()->getCurrentLocale($this->tableIdentifier()) ?: key($this->getLocales());
    }

    public function saveCurrentLocale($locale)
    {
        $this->preferences()->saveCurrentLocale($this->tableIdentifier(), $locale);
    }

    public function getPerPageParam()
    {
        return $this->preferences()->getPerPageParam($this->tableIdentifier());
    }

    public function setPerPageParam($perPage)
    {
        $this->preferences()->setPerPageParam($this->tableIdentifier(), $perPage);
    }

    public function saveSearchFilterParams(array $params)
    {
        $this->preferences()->saveSearchFilterParams($this->tableIdentifier(), $params);
    }

    public function getOrderFilterParam($column)
    {
        return $this->preferences()->getOrderFilterParam($this->tableIdentifier(), $column);
    }

    public function saveOrderFilterParam($column, $direction)
    {
        $this->preferences()->saveOrderFilterParam($this->tableIdentifier(), $column, $direction);
    }

    public function isSortableByWeightActive()
    {
        return $this->preferences()->isSortableByWeightActive($this->tableIdentifier());
    }

    public function setSortableOrderState(bool $active)
    {
        $this->preferences()->setSortableOrderState($this->tableIdentifier(), $active);
    }

    abstract public function preferences();
    abstract public function getLocales();
}
