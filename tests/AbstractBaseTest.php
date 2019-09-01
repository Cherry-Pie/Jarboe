<?php

namespace Yaro\Jarboe\Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;

abstract class AbstractBaseTest extends TestCase
{
    protected function createRequest(array $parameters = [])
    {
        $request = new Request();
        $request->replace($parameters);

        return $request;
    }
}
