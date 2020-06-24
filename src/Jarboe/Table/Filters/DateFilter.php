<?php

namespace Yaro\Jarboe\Table\Filters;

class DateFilter extends AbstractFilter
{
    private $range = false;

    public function range(bool $enable = true)
    {
        $this->range = $enable;

        return $this;
    }

    public function isRange(): bool
    {
        return $this->range;
    }

    public function render()
    {
        $view = 'date';
        if ($this->isRange()) {
            $view .= '_range';
        }

        return view('jarboe::crud.filters.'. $view, [
            'filter' => $this,
        ]);
    }

    public function apply($model)
    {
        $value = $this->value();
        if (is_null($value)) {
            return;
        }

        if ($this->isRange()) {
            $model->when($value['from'] ?? null, function ($query, $value) {
                return $query->whereDate($this->field()->name(), '>=', $value);
            });
            $model->when($value['to'] ?? null, function ($query, $value) {
                return $query->whereDate($this->field()->name(), '<=', $value);
            });
            return;
        }

        $model->whereDate(
            $this->field()->name(),
            $this->sign,
            $value
        );
    }
}
