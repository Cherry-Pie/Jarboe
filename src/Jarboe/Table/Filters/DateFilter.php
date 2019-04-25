<?php

namespace Yaro\Jarboe\Table\Filters;

class DateFilter extends AbstractFilter
{
    public function render()
    {
        return view('jarboe::crud.filters.date', [
            'filter' => $this,
        ])->render();
    }

    public function apply($model)
    {
        $value = $this->value();
        if (is_null($value)) {
            return;
        }

        $model->whereDate(
            $this->field()->name(),
            $this->sign,
            $value
        );
    }
}
