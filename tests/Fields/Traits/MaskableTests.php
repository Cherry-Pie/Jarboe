<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait MaskableTests
{
    /**
     * @test
     */
    public function default_mask()
    {
        $field = $this->field();

        $this->assertFalse($field->isMaskable());
    }

    /**
     * @test
     */
    public function default_mask_pattern()
    {
        $field = $this->field()->mask('**/**');

        $this->assertEquals('**/**', $field->getMaskPattern());
        $this->assertEquals('∗', $field->getMaskPlaceholder());
        $this->assertTrue($field->isMaskable());
    }

    /**
     * @test
     */
    public function set_mask_pattern_with_mask_placeholder()
    {
        $field = $this->field()->mask('--/--', '-');

        $this->assertEquals('--/--', $field->getMaskPattern());
        $this->assertEquals('-', $field->getMaskPlaceholder());
        $this->assertTrue($field->isMaskable());
    }

    abstract protected function field(): AbstractField;
}
