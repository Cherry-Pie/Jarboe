<?php

namespace Yaro\Jarboe\Table\Filters;


class SelectFilter extends AbstractFilter
{
    const NO_INPUT_APPLIED = '__jarboe-no-search-input-applied';
    private $multiple = false;
    private $options = null;
    private $nullable = false;

    public function render()
    {
        $value = $this->value();
        if ($this->isMultiple()) {
            $value = $value ?: [];
        }

        return view('jarboe::crud.filters.select', [
            'filter' => $this,
            'value' => $value,
            'desearch' => self::NO_INPUT_APPLIED,
        ])->render();
    }

    public function multiple(bool $multiple = true)
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function nullable(bool $nullable = true)
    {
        $this->nullable = $nullable;

        return $this;
    }

    public function isNullable(): bool
    {
        if (is_null($this->nullable)) {
            return $this->field()->isNullable();
        }

        return $this->nullable;
    }

    public function isMultiple(): bool
    {
        if (is_null($this->multiple)) {
            return $this->field()->isMultiple();
        }

        return $this->multiple;
    }

    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        $options = $this->options;
        if (is_null($options)) {
            $options = $this->field()->getOptions();
        }

        return $options;
    }

    public function apply($query)
    {
        $value = $this->value();
        if (is_null($value) || $value == self::NO_INPUT_APPLIED) {
            return;
        }

        if ($this->field()->isRelationField()) {
            $model = $this->field()->getModel();
            $model = new $model;
            $relationQuery = $model->{$this->field()->getRelationMethod()}()->getRelated();
            $relationClass = get_class($relationQuery);
            $relationClass = new $relationClass;

            $query->whereHas($this->field()->getRelationMethod(), function($query) use($value, $relationClass) {
                if ($this->field()->isMultiple()) {
                    $query->whereIn($relationClass->getKeyName(), $value);
                } else {
                    $query->where($relationClass->getKeyName(), $value);
                }
            });
            return;
        }

        $query->where(
            $this->field()->name(),
            $this->sign,
            $value
        );
    }
}