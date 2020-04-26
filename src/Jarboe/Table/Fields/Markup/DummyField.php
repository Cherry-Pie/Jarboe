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

    public function getListView($model)
    {
        return view('jarboe::crud.fields.markup.dummy.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.markup.dummy.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.markup.dummy.create', [
            'field' => $this,
        ]);
    }
}
