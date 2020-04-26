<?php

namespace Yaro\Jarboe\Table\Fields;

use Yaro\Jarboe\Table\Fields\Traits\Inline;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;

class Date extends AbstractField
{
    use Orderable;
    use Nullable;
    use Inline;
    use Placeholder;

    protected $format = 'YYYY-MM-DD';
    protected $months = 1;

    public function default($value)
    {
        if (is_object($value) && is_a($value, \DateTime::class)) {
            /** @var \DateTime $value */
            $this->default = $value->format('Y-m-d');
        } else {
            $this->default = date('Y-m-d', strtotime($value));
        }

        return $this;
    }

    public function format(string $momentJsFormat)
    {
        $this->format = $momentJsFormat;

        return $this;
    }

    public function getDateFormat()
    {
        return $this->format;
    }

    public function months(int $months)
    {
        $this->months = $months;

        return $this;
    }

    public function getMonths()
    {
        return $this->months;
    }

    public function getListView($model)
    {
        return view('jarboe::crud.fields.date.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.date.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.date.create', [
            'field' => $this,
        ]);
    }
}
