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
        $values = is_array($value) ? $value : [$value];

        return view('jarboe::crud.filters.select', [
            'filter' => $this,
            'values' => $values,
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

    public function getSelectedOptions(array $values, int $index = 0): array
    {
        $total = 0;
        $options = $this->field()->getOptions(null, null, null, $total, $index, function ($query, $related) use ($values) {
            $query->whereIn($related->getTable() .'.'. $related->getKeyName(), $values);
        });

        return $options;
    }

    public function getSelectedGroupedOptions(): array
    {
        $value = $this->value();
        $values = is_array($value) ? $value : [$value];

        $options = [];
        foreach ($this->field()->getRelations() as $index => $relation) {
            $groupValues = [];
            foreach ($values as $value) {
                list($groupHash, $groupValue) = explode('~~~', $value);
                if ($groupHash == crc32($relation['group'])) {
                    $groupValues[] = $groupValue;
                }
            }

            if ($groupValues) {
                $options[$relation['group']] = $this->getSelectedOptions($groupValues, $index);
            }
        }

        return array_filter($options);
    }

    public function isSelectField(): bool
    {
        return method_exists($this->field(), 'isSelect2Type');
    }

    public function apply($query)
    {
        $value = $this->value();
        if (is_null($value) || $value == self::NO_INPUT_APPLIED) {
            return;
        }

        if ($this->field()->isRelationField()) {
            $values = is_array($value) ? $value : [$value];
            $model = $this->field()->getModel();
            $model = new $model;

            foreach ($this->field()->getRelations() as $index => $relation) {
                $groupValues = $values;
                if ($this->field()->isGroupedRelation()) {
                    $groupValues = [];
                    foreach ($values as $value) {
                        list($groupHash, $groupValue) = explode('~~~', $value);
                        if ($groupHash == crc32($relation['group'])) {
                            $groupValues[] = $groupValue;
                        }
                    }
                }

                if ($groupValues) {
                    $this->applyRelationValues($model, $query, $groupValues, $index);
                }
            }

            return;
        }

        if ($this->field()->isMultiple()) {
            $query->whereIn($this->field()->name(), $value);
            return;
        }

        $query->where(
            $this->field()->name(),
            $this->sign,
            $value
        );
    }

    private function applyRelationValues($model, $query, $values, int $index = 0)
    {
        $relationQuery = $model->{$this->field()->getRelationMethod($index)}()->getRelated();
        $relationClass = get_class($relationQuery);
        $relationClass = new $relationClass;

        $query->whereHas($this->field()->getRelationMethod($index), function ($query) use ($values, $relationClass, $relationQuery) {
            $query->whereIn($relationQuery->getTable() .'.'. $relationClass->getKeyName(), $values);
        });
    }
}
