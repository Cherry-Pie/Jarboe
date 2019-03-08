<?php

namespace Yaro\Jarboe\Table\Actions;

abstract class AbstractAction implements ActionInterface
{
    protected $ident;
    protected $checkClosure;

    public function __construct()
    {
        $this->checkClosure = function() {
            return true;
        };
    }

    public static function make()
    {
        return new static();
    }

    public function identifier()
    {
        return $this->ident ?: static::class;
    }

    public function check(\Closure $checkClosure = null)
    {
        $this->checkClosure = $checkClosure;

        return $this;
    }

    public function isAllowed($model = null)
    {
        $closure = $this->checkClosure;

        return is_callable($closure) ? call_user_func_array($closure, [$model]) : false;
    }
}
