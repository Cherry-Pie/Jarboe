<?php

namespace Yaro\Jarboe\Helpers;

class Registry
{
    private static $instance = null;
    private $registry = [];
    private $styles   = [];
    private $scripts  = [];

    private function __construct() {}

    private function __clone() {}

    private static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function getStatic($ident, $default = 'DEFAULT_VALUE_FOR_FALSE_AND_NULL_ETC_FALLBACK')
    {
        if (!$this->hasOffset($ident)) {
            if ($default !== 'DEFAULT_VALUE_FOR_FALSE_AND_NULL_ETC_FALLBACK') {
                return is_callable($default) ? $default() : $default;
            }
            return null;
        }
        return $this->registry[$ident];
    }

    private function addStatic($ident, $value, $isForceReplace = false)
    {
        if (!$this->hasOffset($ident) || $isForceReplace) {
            $this->registry[$ident] = $value;
        }
    }

    private function addStyleStatic($value)
    {
        $this->styles[] = $value;
    }

    private function addScriptStatic($value)
    {
        $this->scripts[] = $value;
    }

    private function getScriptsStatic()
    {
        return $this->filter($this->scripts);
    }

    private function getStylesStatic()
    {
        return $this->filter($this->styles);
    }

    private function filter($array)
    {
        return array_unique(array_filter($array));
    }

    private function hasOffset($ident)
    {
        return array_key_exists($ident, $this->registry);
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getInstance();
        $method = $name.'Static';

        return call_user_func_array(array($instance, $method), $arguments);
    }
}
