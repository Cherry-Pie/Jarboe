<?php

namespace Yaro\Jarboe\Table\Fields\Markup;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;

class FieldsetMarkup extends AbstractField
{
    protected $legend = '';
    protected $fields = [];

    public function fields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function legend(string $legend)
    {
        $this->legend = $legend;

        return $this;
    }

    public function getLegend(): string
    {
        return $this->legend;
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

    public function prepare(CRUD $crud)
    {
        foreach ($this->fields as $field) {
            $field->prepare($crud);
        }
    }

    public function getListView($model)
    {
        return '';
    }

    public function getEditFormView($model)
    {
        return view('jarboe::crud.fields.markup.fieldset.edit', [
            'model' => $model,
            'field' => $this,
            'rowsLeft' => 12,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.markup.fieldset.create', [
            'field' => $this,
            'rowsLeft' => 12,
        ]);
    }
}
