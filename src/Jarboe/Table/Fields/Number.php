<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Clipboard;
use Yaro\Jarboe\Table\Fields\Traits\Inline;
use Yaro\Jarboe\Table\Fields\Traits\MinMaxStep;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Tooltip;

class Number extends AbstractField
{
    use Orderable;
    use Nullable;
    use Tooltip;
    use Clipboard;
    use Inline;
    use Placeholder;
    use MinMaxStep;

    public function value(Request $request)
    {
        $value = $request->get($this->name());
        if (!$value && $value !== "0" && $this->isNullable()) {
            return null;
        }

        return is_null($value) ? 0 : (float) $value;
    }

    public function getListView($model)
    {
        return view('jarboe::crud.fields.number.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.number.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.number.create', [
            'field' => $this,
        ]);
    }
}
