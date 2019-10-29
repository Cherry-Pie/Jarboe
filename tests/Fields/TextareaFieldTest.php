<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Textarea;
use Yaro\Jarboe\Tests\Fields\Traits\ClipboardTests;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;
use Yaro\Jarboe\Tests\Fields\Traits\MaxlengthTests;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;
use Yaro\Jarboe\Tests\Fields\Traits\TranslatableTests;

class TextareaFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use TooltipTests;
    use ClipboardTests;
    use InlineTests;
    use TranslatableTests;
    use PlaceholderTests;
    use MaxlengthTests;

    protected function getFieldWithName(): AbstractField
    {
        return Textarea::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Textarea::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function textarea_default_rows()
    {
        $field = $this->field();

        $this->assertEquals(3, $field->getRowsNum());
    }

    /**
     * @test
     */
    public function textarea_changed_rows()
    {
        $field = $this->field()->rows(4);

        $this->assertEquals(4, $field->getRowsNum());
    }

    /**
     * @test
     */
    public function textarea_default_expandable()
    {
        $field = $this->field();

        $this->assertFalse($field->isExpandable());
    }

    /**
     * @test
     */
    public function textarea_changed_expandable()
    {
        $field = $this->field()->expandable();

        $this->assertTrue($field->isExpandable());
    }

    /**
     * @test
     */
    public function textarea_default_resizable()
    {
        $field = $this->field();

        $this->assertFalse($field->isResizable());
    }

    /**
     * @test
     */
    public function textarea_changed_resizable()
    {
        $field = $this->field()->resizable();

        $this->assertTrue($field->isResizable());
    }

    /**
     * @test
     */
    public function check_nullable_value()
    {
        $field = $this->field()->nullable();

        $this->assertNull($field->value($this->createRequest([
            $field->name() => '',
        ])));
        $this->assertNull($field->value($this->createRequest([
            $field->name() => false,
        ])));
        $this->assertNull($field->value($this->createRequest([
            $field->name() => 0,
        ])));
        $this->assertNull($field->value($this->createRequest([
            $field->name() => null,
        ])));
        $this->assertNotNull($field->value($this->createRequest([
            $field->name() => 22,
        ])));
        $this->assertNotNull($field->value($this->createRequest([
            $field->name() => 'aa',
        ])));
        $this->assertNotNull($field->value($this->createRequest([
            $field->name() => true,
        ])));
    }

    /**
     * @test
     */
    public function check_array_value()
    {
        $field = $this->field();

        $this->assertIsArray($field->value($this->createRequest([
            $field->name() => [
                'key1' => 'value1',
                'key2' => 'value2',
            ],
        ])));
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
