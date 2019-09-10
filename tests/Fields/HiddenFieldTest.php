<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Hidden;
use Yaro\Jarboe\Table\Fields\Password;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;

class HiddenFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;

    protected function getFieldWithName(): AbstractField
    {
        return Hidden::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Hidden::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function default_col_width()
    {
        $field = $this->field();

        $this->assertEquals(0, $field->getCol());
    }

    /**
     * @test
     */
    public function check_ability_to_be_hidden()
    {
        $field = $this->field();

        $this->assertTrue($field->hidden('list'));
        $this->assertFalse($field->hidden('edit'));
        $this->assertFalse($field->hidden('create'));
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
