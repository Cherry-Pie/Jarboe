<?php

namespace Yaro\Jarboe\Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractBaseTest extends TestCase
{
    protected function createRequest(array $parameters = [])
    {
        $request = new Request();
        $request->replace($parameters);

        return $request;
    }

    protected function createRequestWithFile()
    {
        $file = new UploadedFile(__FILE__, 'name.png');

        $request = new Request();
        $request->files->replace([
            $this->getFieldName() => $file,
        ]);

        return $request;
    }

    abstract protected function getFieldName(): string;
}
