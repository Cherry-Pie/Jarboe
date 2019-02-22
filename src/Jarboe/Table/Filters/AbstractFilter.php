<?php

namespace Yaro\Jarboe\Table\Filters;


use Yaro\Jarboe\Table\Fields\AbstractField;

abstract class AbstractFilter
{

    /** @var AbstractField */
    protected $field;
    protected $value;
    protected $sign = '=';

    public static function make()
    {
        return new static;
    }

    public function field(AbstractField $field = null)
    {
        if (is_null($field)) {
            return $this->field;
        }

        $this->field = $field;
    }

    public function value($value = null)
    {
        if (is_null($value)) {
            return $this->value;
        }

        $this->value = $value;
    }

    public function getValue($tableIdentifier)
    {
        $key = sprintf('jarboe.%s.search.%s', $tableIdentifier, $this->field()->name());

        return session($key);
    }

    public function sign(string $sign)
    {
        $this->sign = $sign;

        return $this;
    }

    abstract public function render();

    abstract public function apply($query);

}