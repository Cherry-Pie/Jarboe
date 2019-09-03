<?php

namespace Yaro\Jarboe\Table\Fields;

use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;

class Datetime extends AbstractField
{
    use Orderable;
    use Nullable;
    use Placeholder;

    protected $format = 'YYYY-MM-DD HH:mm:ss';

    public function format(string $momentJsFormat)
    {
        $this->format = $momentJsFormat;

        return $this;
    }

    public function getDateFormat()
    {
        return $this->format;
    }

    public function default($value)
    {
        if (is_object($value) && is_a($value, \DateTime::class)) {
            /** @var \DateTime $value */
            $this->default = $value->format('Y-m-d H:i:s');
        } else {
            $this->default = date('Y-m-d H:i:s', strtotime($value));
        }

        return $this;
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.datetime.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.datetime.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.datetime.create', [
            'field' => $this,
        ]);
    }
}
