<?php

namespace Yaro\Jarboe\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Yaro\Jarboe\ServiceProvider as JarboeServiceProvider;
use Yaro\Jarboe\Table\CRUD;

abstract class AbstractBaseTest extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            JarboeServiceProvider::class,
        ];
    }

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

    protected function model()
    {
        return new \ArrayObject();
    }

    protected function crud()
    {
        return app(CRUD::class);
    }
}
