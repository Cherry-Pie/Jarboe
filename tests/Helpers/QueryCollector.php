<?php

namespace Yaro\Jarboe\Tests\Helpers;

class QueryCollector
{
    private $calls = [];

    public function __call($name, $arguments)
    {
        $this->calls[] = compact('name', 'arguments');
    }

    public function calls(): array
    {
        return $this->calls;
    }
}
