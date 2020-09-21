<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Clipboard;
use Yaro\Jarboe\Table\Fields\Traits\Inline;
use Yaro\Jarboe\Table\Fields\Traits\Maxlength;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Rows;
use Yaro\Jarboe\Table\Fields\Traits\Tooltip;
use Yaro\Jarboe\Table\Fields\Traits\Translatable;

class Textarea extends AbstractField
{
    use Orderable;
    use Nullable;
    use Tooltip;
    use Clipboard;
    use Inline;
    use Translatable;
    use Placeholder;
    use Maxlength;
    use Rows;

    protected $expandable = false;
    protected $resizable = false;

    public function expandable(bool $expandable = true)
    {
        $this->expandable = $expandable;

        return $this;
    }

    public function isExpandable()
    {
        return $this->expandable;
    }

    public function resizable(bool $resizable = true)
    {
        $this->resizable = $resizable;

        return $this;
    }

    public function isResizable()
    {
        return $this->resizable;
    }

    public function value(Request $request)
    {
        $value = parent::value($request);
        if (is_null($value) && $this->isNullable()) {
            return null;
        }

        return is_array($value) ? $value : (string) $value;
    }

    public function getListView($model)
    {
        $template = 'list';
        if ($this->isTranslatable()) {
            $template .= '_translatable';
        }

        return view('jarboe::crud.fields.textarea.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.textarea.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.textarea.create', [
            'field' => $this,
        ]);
    }
}
