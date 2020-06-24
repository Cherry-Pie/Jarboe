<?php

namespace Yaro\Jarboe\Table\Filters;

class TextFilter extends AbstractFilter
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
        return view('jarboe::crud.filters.text', [
            'filter' => $this,
        ]);
    }

    public function apply($query)
    {
        $value = $this->value();
        if (is_null($value)) {
            return;
        }

        $leftPart  = $this->like['left'] ? '%' : '';
        $rightPart = $this->like['right'] ? '%' : '';
        $sign      = array_filter($this->like) ? 'like' : $this->sign;

        if ($this->field()->isRelationField()) {
            $query->whereHas($this->field()->getRelationMethod(), function ($query) use ($value, $sign, $leftPart, $rightPart) {
                $query->where(
                    $this->field()->getRelationTitleField(),
                    $sign,
                    $leftPart . $value . $rightPart
                );
            });
            return;
        }

        $query->where(
            $this->field->name(),
            $sign,
            $leftPart . $value . $rightPart
        );
    }
}
