<?php

namespace Yaro\Jarboe\Table\Actions;

use Yaro\Jarboe\Table\CRUD;

abstract class AbstractAction implements ActionInterface
{
    protected $ident;
    protected $checkClosure;
    protected $renderClosure = false;
    private $crud;

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

    public function setCrud(CRUD $crud)
    {
        $this->crud = $crud;
    }

    public function crud(): CRUD
    {
        return $this->crud;
    }

    public function identifier()
    {
        return $this->ident ?: static::class;
    }

    public function check(\Closure $closure = null)
    {
        $this->checkClosure = $closure;

        return $this;
    }

    public function isAllowed($model = null)
    {
        $closure = $this->checkClosure;

        return is_callable($closure) ? call_user_func_array($closure, [$model]) : false;
    }

    public function renderCheck(\Closure $closure = null)
    {
        $this->renderClosure = $closure;

        return $this;
    }

    public function shouldRender($model = null)
    {
        $closure = $this->renderClosure;
        if ($closure === false) {
            return $this->isAllowed($model);
        }

        return is_callable($closure) ? call_user_func_array($closure, [$model]) : false;
    }
}
