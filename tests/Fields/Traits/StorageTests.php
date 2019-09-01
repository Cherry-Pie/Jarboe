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
    public function storage_enabled_multiple()
    {
        $field = $this->field()->multiple();

        $this->assertTrue($field->isMultiple());
    }

    abstract protected function field(): AbstractField;
}
