<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Date;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;

class DateFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use InlineTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return Date::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Date::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function changed_default_value()
    {
        $field = $this->field()->default('default value');

        $this->assertEquals('1970-01-01', $field->getDefault());
    }

    /**
     * @test
     */
    public function date_changed_default_value_string()
    {
        $value = '3333/11/22';
        $field = $this->field()->default($value);

        $this->assertEquals('3333-11-22', $field->getDefault());
    }

    /**
     * @test
     */
    public function date_changed_default_value_datetime_object()
    {
        $datetime = new \DateTime();
        $field = $this->field()->default($datetime);

        $this->assertEquals($datetime->format('Y-m-d'), $field->getDefault());
    }

    /**
     * @test
     */
    public function date_default_format()
    {
        $field = $this->field();

        $this->assertEquals('YYYY-MM-DD', $field->getDateFormat());
    }

    /**
     * @test
     */
    public function date_changed_format()
    {
        $field = $this->field()->format('YYYY');

        $this->assertEquals('YYYY', $field->getDateFormat());
    }

    /**
     * @test
     */
    public function date_default_months()
    {
        $field = $this->field()->months(1);

        $this->assertEquals(1, $field->getMonths());
    }

    /**
     * @test
     */
    public function date_changed_months()
    {
        $field = $this->field()->months(4);

        $this->assertEquals(4, $field->getMonths());
    }

    /**
     * @test
     */
    public function check_properties()
    {
        $this->assertObjectHasAttribute('months', $this->field());
        $this->assertObjectHasAttribute('format', $this->field());
    }
}
