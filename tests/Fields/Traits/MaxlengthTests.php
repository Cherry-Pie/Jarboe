<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait MaxlengthTests
{
    /**
     * @test
     */
    public function default_maxlength()
    {
        $field = $this->field();

        $this->assertFalse($field->hasMaxlength());
        $this->assertNull($field->getMaxlength());
    }

    /**
     * @test
     */
    public function set_maxlength()
    {
        $field = $this->field()->maxlength(42);

        $this->assertTrue($field->hasMaxlength());
        $this->assertEquals(42, $field->getMaxlength());
    }

    abstract protected function field(): AbstractField;
}
