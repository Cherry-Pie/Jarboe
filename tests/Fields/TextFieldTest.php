<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Tests\Fields\Traits\ClipboardTests;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;
use Yaro\Jarboe\Tests\Fields\Traits\MaskableTests;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;
use Yaro\Jarboe\Tests\Fields\Traits\TranslatableTests;

class TextFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use TooltipTests;
    use ClipboardTests;
    use InlineTests;
    use TranslatableTests;
    use MaskableTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return Text::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Text::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function text_check_value()
    {
        $field = $this->field();

        $this->assertEquals('0', $field->value($this->createRequest([
            self::NAME => 0,
        ])));
        $this->assertIsString($field->value($this->createRequest([
            self::NAME => 0,
        ])));
        $this->assertEquals('10', $field->value($this->createRequest([
            self::NAME => 10,
        ])));
        $this->assertIsString($field->value($this->createRequest([
            self::NAME => 10,
        ])));
        $this->assertEquals('', $field->value($this->createRequest()));
        $this->assertIsString($field->value($this->createRequest()));

        $this->assertEquals(
            [
                'en' => 'value'
            ],
            $field->value($this->createRequest([
                self::NAME => [
                    'en' => 'value'
                ],
            ]))
        );
        $this->assertIsArray($field->value($this->createRequest([
            self::NAME => [
                'en' => 'value'
            ],
        ])));


        $field->nullable();

        $this->assertEquals('10', $field->value($this->createRequest([
            self::NAME => '10',
        ])));
        $this->assertEquals('10', $field->value($this->createRequest([
            self::NAME => 10,
        ])));

        $this->assertNull($field->value($this->createRequest([
            self::NAME => '0',
        ])));
        $this->assertNull($field->value($this->createRequest([
            self::NAME => 0,
        ])));
        $this->assertNull($field->value($this->createRequest()));
    }
}
