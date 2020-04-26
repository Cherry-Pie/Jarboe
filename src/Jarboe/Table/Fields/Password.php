<?php

namespace Yaro\Jarboe\Table\Fields;

use Closure;
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

    /** @var string|Closure */
    protected $hash = 'bcrypt';

    public function hash($hash)
    {
        if (is_object($hash) && is_a($hash, Closure::class)) {
            $this->hash = $hash;
            return $this;
        }

        if (is_string($hash) && function_exists($hash)) {
            $this->hash = $hash;
            return $this;
        }

        // dummy
        if (is_null($hash)) {
            $this->hash = function ($value) {
                return $value;
            };
            return $this;
        }

        throw new \RuntimeException('Hash for Password field must be valid function name or Closure');
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

    public function getListView($model)
    {
        return view('jarboe::crud.fields.password.list', [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getEditFormView($model)
    {
        $template = $this->isReadonly() ? 'readonly' : 'edit';

        return view('jarboe::crud.fields.password.'. $template, [
            'model' => $model,
            'field' => $this,
        ]);
    }

    public function getCreateFormView()
    {
        return view('jarboe::crud.fields.password.create', [
            'field' => $this,
        ]);
    }
}
