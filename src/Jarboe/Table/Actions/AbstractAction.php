<?php

namespace Yaro\Jarboe\Table\Actions;


abstract class AbstractAction
{
    protected $ident;
    protected $checkClosure;

    public function __construct($ident)
    {
        $this->ident = $ident;
        $this->checkClosure = function() {
            return true;
        };
    }

    public static function make($ident)
    {
        return new static($ident);
    }

    public function check(\Closure $checkClosure = null)
    {
        $this->checkClosure = $checkClosure;

        return $this;
    }

    public function isAllowed()
    {
        $closure = $this->checkClosure;
        return $closure();
    }

}