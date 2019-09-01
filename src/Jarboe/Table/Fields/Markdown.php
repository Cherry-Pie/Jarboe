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

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.markdown.list', [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.markdown.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.markdown.create', [
            'field' => $this,
        ])->render();
    }
}
