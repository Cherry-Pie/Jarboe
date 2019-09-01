<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;

class Hidden extends AbstractField
{
    use Orderable;
    use Nullable;

    protected $col = 0;
    protected $hidden = [
        'list'   => true,
        'edit'   => false,
        'create' => false,
    ];

    public function value(Request $request)
    {
        return (string) parent::value($request);
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.hidden.list', [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.hidden.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.hidden.create', [
            'field' => $this,
        ])->render();
    }

}