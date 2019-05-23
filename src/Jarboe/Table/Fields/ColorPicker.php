<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;

class ColorPicker extends AbstractField
{
    use Orderable;
    use Placeholder;

    const HEX = 'hex';
    const RGBA = 'rgba';

    protected $type = self::HEX;

    public function value(Request $request)
    {
        return (string) parent::value($request);
    }

    public function type($type)
    {
        $type = strtolower($type);
        switch ($type) {
            case self::HEX:
            case self::RGBA:
                $this->type = $type;
                break;
            default:
                $this->type = self::HEX;
        }


        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.color-picker.list', [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.color-picker.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.color-picker.create', [
            'field' => $this,
        ])->render();
    }
}
