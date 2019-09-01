<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Checkbox;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;

class CheckboxFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;

    protected function getFieldWithName(): AbstractField
    {
        return Checkbox::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Checkbox::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function checkbox_check_value_type()
    {
        $field = $this->field();

        $this->assertTrue(
            $field->value($this->createRequest([
                self::NAME => 1,
            ]))
        );
        $this->assertTrue(
            $field->value($this->createRequest([
                self::NAME => '1',
            ]))
        );

        $this->assertFalse(
            $field->value($this->createRequest([
                self::NAME => 0,
            ]))
        );
        $this->assertFalse(
            $field->value($this->createRequest([
                self::NAME => '0',
            ]))
        );
    }

    /**
     * @test
     */
    public function checkbox_check_nullable_value_type()
    {
        $field = $this->field()->nullable();

        $this->assertTrue(
            $field->value($this->createRequest([
                self::NAME => 1,
            ]))
        );
        $this->assertTrue(
            $field->value($this->createRequest([
                self::NAME => '1',
            ]))
        );

        $this->assertNull(
            $field->value($this->createRequest([
                self::NAME => 0,
            ]))
        );
        $this->assertNull(
            $field->value($this->createRequest([
                self::NAME => '0',
            ]))
        );
    }
}
