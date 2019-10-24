<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Clipboard;
use Yaro\Jarboe\Table\Fields\Traits\Inline;
use Yaro\Jarboe\Table\Fields\Traits\Maskable;
use Yaro\Jarboe\Table\Fields\Traits\Maxlength;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Tooltip;
use Yaro\Jarboe\Table\Fields\Traits\Translatable;

class Text extends AbstractField
{
    use Orderable;
    use Nullable;
    use Tooltip;
    use Clipboard;
    use Inline;
    use Translatable;
    use Maskable;
    use Placeholder;
    use Maxlength;

    public function value(Request $request)
    {
        $value = parent::value($request);
        if (is_null($value) && $this->isNullable()) {
            return null;
        }

        return is_array($value) ? $value : (string) $value;
    }

    public function getListValue($model)
    {
        $template = 'list';
        if ($this->isTranslatable()) {
            $template .= '_translatable';
        }

        return view('jarboe::crud.fields.text.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.text.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormValue()
    {
        $template = 'create';

        return view('jarboe::crud.fields.text.'. $template, [
            'field' => $this,
        ]);
    }
}
