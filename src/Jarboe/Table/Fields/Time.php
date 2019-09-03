<?php

namespace Yaro\Jarboe\Table\Fields;

use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;

class Time extends AbstractField
{
    use Orderable;
    use Nullable;
    use Placeholder;

    const TOP    = 'top';
    const RIGHT  = 'right';
    const BOTTOM = 'bottom';
    const LEFT   = 'left';

    protected $placement = 'bottom';

    public function placement(string $placement)
    {
        $placement = strtolower($placement);
        switch ($placement) {
            case self::TOP:
            case self::RIGHT:
            case self::BOTTOM:
            case self::LEFT:
                $this->placement = $placement;
                break;
            default:
                $this->placement = self::BOTTOM;
        }

        return $this;
    }

    public function getPlacement()
    {
        return $this->placement;
    }

    public function default($value)
    {
        if (is_object($value) && is_a($value, \DateTime::class)) {
            /** @var \DateTime $value */
            $this->default = $value->format('H:i:s');
        } else {
            $this->default = date('H:i:s', strtotime($value));
        }

        return $this;
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.time.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.time.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.time.create', [
            'field' => $this,
        ]);
    }
}
