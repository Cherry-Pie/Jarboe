<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait OrderableTests
{
    /**
     * @test
     */
    public function default_orderable()
    {
        $field = $this->field();

        $this->assertFalse($field->isOrderable());
    }

    /**
     * @test
     */
    public function enable_orderable()
    {
        $field = $this->field()->orderable();

        $this->assertTrue($field->isOrderable());
        $this->assertNull($field->getOverridedOrderCallback());
    }

    /**
     * @test
     */
    public function enable_orderable_with_overrided_order()
    {
        $callback = function () {
            return 'some';
        };
        $field = $this->field()->orderable(true, $callback);

        $this->assertTrue($field->isOrderable());
        $this->assertEquals($callback, $field->getOverridedOrderCallback());
    }

    abstract protected function field(): AbstractField;
}
