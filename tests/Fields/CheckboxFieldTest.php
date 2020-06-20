<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Checkbox;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Models\Model;

class CheckboxFieldTest extends AbstractFieldTest
{
    const NAME = 'checkbox';
    const TITLE_FROM_NAME = 'Checkbox';

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

    /**
     * @test
     */
    public function check_get_attribute()
    {
        $model = Model::first();
        $field = $this->field();

        $model->setAttribute(self::NAME, false);
        $this->assertFalse($field->getAttribute($model));

        $model->setAttribute(self::NAME, true);
        $this->assertTrue($field->getAttribute($model));
    }

    /**
     * @test
     */
    public function check_old_or_get_attribute_with_old()
    {
        $model = Model::first();
        $field = $this->field();

        $model->setAttribute(self::NAME, false);
        $this->setOld(true);
        $this->assertTrue($field->oldOrAttribute($model));

        $model->setAttribute(self::NAME, true);
        $this->setOld(false);
        $this->assertFalse($field->oldOrAttribute($model));

        // parsing checkbox values for Repeater field
        $model->setAttribute(self::NAME, 'false');
        $this->setOld('true');
        $this->assertTrue($field->oldOrAttribute($model));

        $model->setAttribute(self::NAME, 'true');
        $this->setOld('false');
        $this->assertFalse($field->oldOrAttribute($model));
    }

    /**
     * @test
     */
    public function check_old_or_get_attribute_without_old()
    {
        $model = Model::first();
        $field = $this->field();

        $model->setAttribute(self::NAME, false);
        $this->assertFalse($field->oldOrAttribute($model));

        $model->setAttribute(self::NAME, true);
        $this->assertTrue($field->oldOrAttribute($model));

        // parsing checkbox values for Repeater field
        $model->setAttribute(self::NAME, 'false');
        $this->assertFalse($field->oldOrAttribute($model));

        $model->setAttribute(self::NAME, 'true');
        $this->assertTrue($field->oldOrAttribute($model));
    }
}
