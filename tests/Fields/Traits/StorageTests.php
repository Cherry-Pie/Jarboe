<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait StorageTests
{
    /**
     * @test
     */
    public function storage_default_multiple()
    {
        $field = $this->field();

        $this->assertFalse($field->isMultiple());
    }

    /**
     * @test
     */
    public function storage_disk()
    {
        $field = $this->field()->disk('public');

        $this->assertEquals('public', $field->getDisk());
    }

    /**
     * @test
     */
    public function storage_path()
    {
        $field = $this->field()->path('path/path');

        $this->assertEquals('path/path', $field->getPath());
    }

    abstract protected function field(): AbstractField;
}
