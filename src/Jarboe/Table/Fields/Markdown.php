<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;

class Markdown extends AbstractField
{
    use Orderable;

    public function value(Request $request)
    {
        return (string) parent::value($request);
    }

    public function getListView($model)
    {
        return view('jarboe::crud.fields.markdown.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.markdown.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.markdown.create', [
            'field' => $this,
        ]);
    }
}
