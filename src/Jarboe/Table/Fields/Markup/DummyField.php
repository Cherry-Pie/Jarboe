<?php

namespace Yaro\Jarboe\Table\Fields\Markup;


use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\AbstractField;

class DummyField extends AbstractField
{

    protected $hidden = [
        'list'   => true,
        'edit'   => false,
        'create' => false,
    ];

    public function shouldSkip(Request $request)
    {
        return true;
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.markup.dummy.list', [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.markup.dummy.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.markup.dummy.create', [
            'field' => $this,
        ])->render();
    }

}