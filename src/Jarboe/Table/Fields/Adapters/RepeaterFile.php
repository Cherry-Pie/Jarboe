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

    public function __call($method, $parameters)
    {
        return $this->field->$method(...$parameters);
    }

    public function getPaths($model): array
    {
        if (is_null($model)) {
            return [];
        }

        $filepath = $this->getAttribute($model);
        // FIXME: mb change structure for storing files data?
        $filepath = $this->sanitizeRepeaterValue($filepath);
        if (!$filepath) {
            return [];
        }

        return is_array($filepath) ? $filepath : [$filepath];
    }

    private function sanitizeRepeaterValue($filepath)
    {
        if (is_array($filepath) && isset($filepath['paths']) && is_array($filepath['paths'])) {
            $filepath = $filepath['paths'];
        }

        return $filepath;
    }
}
