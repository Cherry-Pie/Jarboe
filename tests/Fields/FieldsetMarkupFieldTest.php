<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Markup\FieldsetMarkup;
use Yaro\Jarboe\Table\Fields\Text;

class FieldsetMarkupFieldTest extends AbstractFieldTest
{
    protected function getFieldWithName(): AbstractField
    {
        return FieldsetMarkup::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return FieldsetMarkup::make(self::NAME, self::TITLE);
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

    /**
     * @test
     */
    public function check_valid_legend()
    {
        $legend = 'some-legend-string';
        /** @var FieldsetMarkup $field */
        $field = $this->field()->legend($legend);

        $this->assertEquals($legend, $field->getLegend());

        $legend = 42;
        /** @var FieldsetMarkup $field */
        $field = $this->field()->legend($legend);

        $this->assertEquals($legend, $field->getLegend());
    }

    /**
     * @test
     */
    public function check_non_valid_legend()
    {
        $this->expectException(\TypeError::class);

        $legend = new \stdClass();
        /** @var FieldsetMarkup $field */
        $this->field()->legend($legend);
    }
}
