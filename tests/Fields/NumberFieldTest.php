<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Number;
use Yaro\Jarboe\Tests\Fields\Traits\ClipboardTests;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;

class NumberFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use TooltipTests;
    use ClipboardTests;
    use InlineTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return Number::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Number::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function number_check_value()
    {
        $field = $this->field();

        $this->assertEquals(0, $field->value($this->createRequest([
            self::NAME => 0,
        ])));
        $this->assertEquals(10, $field->value($this->createRequest([
            self::NAME => 10,
        ])));

        $this->assertEquals(0, $field->value($this->createRequest([
            self::NAME => '0',
        ])));
        $this->assertEquals(10, $field->value($this->createRequest([
            self::NAME => '10',
        ])));
        $this->assertEquals(0, $field->value($this->createRequest()));


        $field->nullable();

        $this->assertEquals(0, $field->value($this->createRequest([
            self::NAME => 0,
        ])));
        $this->assertEquals(10, $field->value($this->createRequest([
            self::NAME => 10,
        ])));

        $this->assertEquals(0, $field->value($this->createRequest([
            self::NAME => '0',
        ])));
        $this->assertEquals(10, $field->value($this->createRequest([
            self::NAME => '10',
        ])));
        $this->assertNull($field->value($this->createRequest()));
    }
}
