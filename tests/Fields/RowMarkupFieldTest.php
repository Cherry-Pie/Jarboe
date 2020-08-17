<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Markup\RowMarkup;
use Yaro\Jarboe\Table\Fields\Text;

class RowMarkupFieldTest extends AbstractFieldTest
{
    protected function getFieldWithName(): AbstractField
    {
        return RowMarkup::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return RowMarkup::make(self::NAME, self::TITLE);
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
    public function fields_is_array()
    {
        $field = $this->field();

        $this->assertIsArray($field->getFields());

        $field->fields([
            Text::make('first'),
            Text::make('second'),
        ]);

        $this->assertIsArray($field->getFields());
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

    /**
     * @test
     */
    public function check_is_markup_row()
    {
        $field = $this->field();

        $this->assertTrue($field->isMarkupRow());
    }

    /**
     * @test
     */
    public function check_get_list_value_view()
    {
        $field = $this->field();

        $this->assertIsString($field->getListView($this->model()));
        $this->assertEmpty($field->getListView($this->model()));
    }

    /**
     * @test
     */
    public function check_if_fields_are_prepared_properly()
    {
        $this->expectException(\LogicException::class);

        $fieldWithException = new class {
            public function prepare($crud)
            {
                throw new \LogicException();
            }
        };

        $field = $this->field()->fields([
            $fieldWithException,
        ]);
        $field->prepare($this->app->make(CRUD::class));
    }
}
