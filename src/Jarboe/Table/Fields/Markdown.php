<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Translatable;

class Markdown extends AbstractField
{
    use Orderable;
    use Translatable;

    public function value(Request $request)
    {
        $value = parent::value($request);

        return is_array($value) ? $value : (string) $value;
    }

    public function getListView($model)
    {
        $template = 'list';
        if ($this->isTranslatable()) {
            $template .= '_translatable';
        }

        return view('jarboe::crud.fields.markdown.'. $template, [
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
