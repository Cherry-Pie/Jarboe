<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait TooltipTests
{
    /**
     * @test
     */
    public function default_tooltip()
    {
        $field = $this->field();

        $this->assertFalse($field->hasTooltip());
    }

    /**
     * @test
     */
    public function enable_tooltip()
    {
        $field = $this->field()->tooltip('tooltip');

        $this->assertTrue($field->hasTooltip());
        $this->assertEquals('tooltip', $field->getTooltip());
    }

    abstract protected function field(): AbstractField;
}
