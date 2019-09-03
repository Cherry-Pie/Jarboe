<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;

class IconPicker extends AbstractField
{
    use Orderable;

    public function value(Request $request)
    {
        return (string) parent::value($request);
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.icon-picker.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.icon-picker.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.icon-picker.create', [
            'field' => $this,
        ]);
    }

}