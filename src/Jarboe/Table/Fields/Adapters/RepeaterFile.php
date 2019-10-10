<?php

namespace Yaro\Jarboe\Table\Fields\Adapters;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\Fields\File;

class RepeaterFile
{
    /**
     * @var File
     */
    private $field;

    public function __construct(File $field)
    {
        $this->field = $field;
    }

    public function value(Request $request)
    {
        $paths = $this->field->value($request);
        if (!$paths) {
            $defaultValue = $this->isMultiple() ? [] : '';
            $value = $request->get($this->name(), $defaultValue);

            return !$value && $this->isNullable() ? null : $value;
        }

        return $paths;
    }

    public function __call($method, $parameters)
    {
        return $this->field->$method(...$parameters);
    }
}
