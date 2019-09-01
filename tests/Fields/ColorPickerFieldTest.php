<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\ColorPicker;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;

class ColorPickerFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return ColorPicker::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return ColorPicker::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function colorpicker_default_type()
    {
        $field = $this->field();

        $this->assertEquals(ColorPicker::HEX, $field->getType());
    }

    /**
     * @test
     */
    public function colorpicker_changed_type()
    {
        $field = $this->field()->type(ColorPicker::RGBA);

        $this->assertEquals(ColorPicker::RGBA, $field->getType());
    }

    /**
     * @test
     */
    public function colorpicker_changed_unsupported_type()
    {
        $field = $this->field()->type('unsupported_type');

        $this->assertEquals(ColorPicker::HEX, $field->getType());
    }
}
