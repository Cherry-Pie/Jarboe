<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Markup\DummyField;

class DummyFieldTest extends AbstractFieldTest
{
    protected function getFieldWithName(): AbstractField
    {
        return DummyField::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return DummyField::make(self::NAME, self::TITLE);
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


        $field->hideList(false);
        $this->assertFalse($field->hidden('list'));
        $field->hideEdit(true);
        $this->assertTrue($field->hidden('edit'));
        $field->hideCreate(true);
        $this->assertTrue($field->hidden('create'));
    }

    /**
     * @test
     */
    public function should_skip()
    {
        $field = $this->field();

        $this->assertTrue(
            $field->shouldSkip(
                $this->createRequest([
                $field->name() => 'value',
            ])
            )
        );
    }
}
