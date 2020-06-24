<?php

namespace Yaro\Jarboe\Table\Filters;

class NumberFilter extends AbstractFilter
{
    protected $like = [
        'left'  => true,
        'right' => true,
    ];

    public function like($left = true, $right = true)
    {
        $this->like = [
            'left'  => $left,
            'right' => $right,
        ];

        return $this;
    }

    public function render()
    {
        return view('jarboe::crud.filters.number', [
            'filter' => $this,
        ]);
    }

    public function apply($model)
    {
        if (is_null($this->value())) {
            return;
        }

        $leftPart  = $this->like['left'] ? '%' : '';
        $rightPart = $this->like['right'] ? '%' : '';
        $sign      = array_filter($this->like) ? 'like' : $this->sign;

        $model->where(
            $this->field()->name(),
            $sign,
            $leftPart . $this->value() . $rightPart
        );
    }
}
