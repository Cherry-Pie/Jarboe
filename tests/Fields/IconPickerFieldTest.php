<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\IconPicker;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;

class IconPickerFieldTest extends AbstractFieldTest
{
    use OrderableTests;

    protected function getFieldWithName(): AbstractField
    {
        return IconPicker::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return IconPicker::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function check_string_value()
    {
        $field = $this->field();

        $this->assertIsString($field->value($this->createRequest([
            $field->name() => 'aa',
        ])));
        $this->assertEquals('aa', $field->value($this->createRequest([
            $field->name() => 'aa',
        ])));
        $this->assertIsString($field->value($this->createRequest([
            $field->name() => 22,
        ])));
        $this->assertEquals('22', $field->value($this->createRequest([
            $field->name() => 22,
        ])));
    }
}
