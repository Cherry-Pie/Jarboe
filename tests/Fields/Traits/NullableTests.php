<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait NullableTests
{
    /**
     * @test
     */
    public function default_nullable()
    {
        $field = $this->field();

        $this->assertFalse($field->isNullable());
    }

    /**
     * @test
     */
    public function enable_nullable()
    {
        $field = $this->field()->nullable();

        $this->assertTrue($field->isNullable());
    }

    abstract protected function field(): AbstractField;
}
