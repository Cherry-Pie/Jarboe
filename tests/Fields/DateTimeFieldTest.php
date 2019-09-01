<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Datetime;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;

class DateTimeFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return Datetime::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Datetime::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function changed_default_value()
    {
        $field = $this->field()->default('default value');

        $this->assertEquals('1970-01-01 00:00:00', $field->getDefault());
    }

    /**
     * @test
     */
    public function datetime_changed_default_value_string()
    {
        $value = '3333/11/22 11:12';
        $field = $this->field()->default($value);

        $this->assertEquals('3333-11-22 11:12:00', $field->getDefault());
    }

    /**
     * @test
     */
    public function datetime_changed_default_value_datetime_object()
    {
        $datetime = new \DateTime();
        $field = $this->field()->default($datetime);

        $this->assertEquals($datetime->format('Y-m-d H:i:s'), $field->getDefault());
    }

    /**
     * @test
     */
    public function datetime_default_format()
    {
        $field = $this->field();

        $this->assertEquals('YYYY-MM-DD HH:mm:ss', $field->getDateFormat());
    }

    /**
     * @test
     */
    public function datetime_changed_format()
    {
        $field = $this->field()->format('YYYY');

        $this->assertEquals('YYYY', $field->getDateFormat());
    }
}
