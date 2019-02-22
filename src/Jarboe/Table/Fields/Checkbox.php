<?php

namespace Yaro\Jarboe\Table\Fields;


use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;

class Checkbox extends AbstractField
{
    use Orderable;
    use Nullable;

    public function value(Request $request)
    {
        $value = parent::value($request);
        if (is_null($value) && $this->isNullable()) {
            return null;
        }

        return (bool) $value;
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.checkbox.list', [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.checkbox.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.checkbox.create', [
            'field' => $this,
        ])->render();
    }

}