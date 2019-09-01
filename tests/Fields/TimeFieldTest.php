<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Time;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;

class TimeFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return Time::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Time::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function changed_default_value()
    {
        $field = $this->field()->default('default value');

        $this->assertEquals('00:00:00', $field->getDefault());
    }

    /**
     * @test
     */
    public function time_changed_default_value_string()
    {
        $value = '11:12:33';
        $field = $this->field()->default($value);

        $this->assertEquals('11:12:33', $field->getDefault());
    }

    /**
     * @test
     */
    public function time_changed_default_value_datetime_object()
    {
        $datetime = new \DateTime();
        $field = $this->field()->default($datetime);

        $this->assertEquals($datetime->format('H:i:s'), $field->getDefault());
    }

    /**
     * @test
     */
    public function time_default_placement()
    {
        $field = $this->field();

        $this->assertEquals(Time::BOTTOM, $field->getPlacement());
    }

    /**
     * @test
     */
    public function time_changed_placement()
    {
        $field = $this->field()->placement(Time::TOP);
        $this->assertEquals(Time::TOP, $field->getPlacement());

        $field = $this->field()->placement(Time::RIGHT);
        $this->assertEquals(Time::RIGHT, $field->getPlacement());

        $field = $this->field()->placement(Time::LEFT);
        $this->assertEquals(Time::LEFT, $field->getPlacement());

        $field = $this->field()->placement(Time::BOTTOM);
        $this->assertEquals(Time::BOTTOM, $field->getPlacement());
    }

    /**
     * @test
     */
    public function time_changed_unsupported_placement()
    {
        $field = $this->field()->placement('unsupported');

        $this->assertEquals(Time::BOTTOM, $field->getPlacement());
    }
}
