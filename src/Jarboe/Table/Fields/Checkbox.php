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

    public function getListView($model)
    {
        return view('jarboe::crud.fields.checkbox.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.checkbox.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.checkbox.create', [
            'field' => $this,
        ]);
    }

    public function oldOrAttribute($model, $locale = null)
    {
        $value = parent::oldOrAttribute($model, $locale);
        if ($value === 'true' || $value === 'false') {
            $value = $value === 'true';
        }

        return $value;
    }
}
