<?php

namespace Yaro\Jarboe\Table\Fields\Markup;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;

class RowMarkup extends AbstractField
{
    protected $fields = [];

    public function fields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    protected $hidden = [
        'list'   => true,
        'edit'   => false,
        'create' => false,
    ];

    public function shouldSkip(Request $request)
    {
        return true;
    }

    public function isMarkupRow()
    {
        return true;
    }

    public function getListValue($model)
    {
        return '';
    }

    // TODO: interface type-hinting
    public function prepare(CRUD $crud)
    {
        foreach ($this->fields as $field) {
            $field->prepare($crud);
        }
    }

    public function getEditFormValue($model)
    {
        return view('jarboe::crud.inc.edit.tab', [
            'item'     => $model,
            'fields'   => $this->getFields(),
            'rowsLeft' => 12,
        ]);
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.inc.create.tab', [
            'fields'   => $this->getFields(),
            'rowsLeft' => 12,
        ]);
    }
}
