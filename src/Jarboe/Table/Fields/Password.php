<?php

namespace Yaro\Jarboe\Table\Fields;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\Traits\Nullable;
use Yaro\Jarboe\Table\Fields\Traits\Orderable;
use Yaro\Jarboe\Table\Fields\Traits\Placeholder;
use Yaro\Jarboe\Table\Fields\Traits\Tooltip;

class Password extends AbstractField
{
    use Orderable;
    use Nullable;
    use Tooltip;
    use Placeholder;

    protected $hash = 'bcrypt';

    public function hash($hash)
    {
        if (is_object($hash) && is_a($hash, \Closure::class)) {
            $this->hash = $hash;
            return $this;
        }

        if (is_string($hash) && function_exists($hash)) {
            $this->hash = $hash;
            return $this;
        }

        // dummy
        if (is_null($hash)) {
            $this->hash = function($value) {
                return $value;
            };
            return $this;
        }

        throw new \RuntimeException('Hash for PasswordField must be valid function name or closure');
    }

    public function value(Request $request)
    {
        $hash = $this->hash;

        return $hash(parent::value($request));
    }

    public function shouldSkip(Request $request)
    {
        return !$request->get($this->name());
    }

    public function getListValue($model)
    {
        return view('jarboe::crud.fields.password.list', [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getEditFormValue($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.password.'. $template, [
            'model' => $model,
            'field' => $this,
        ])->render();
    }

    public function getCreateFormValue()
    {
        return view('jarboe::crud.fields.password.create', [
            'field' => $this,
        ])->render();
    }
}
