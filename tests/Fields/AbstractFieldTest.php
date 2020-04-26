<?php

namespace Yaro\Jarboe\Tests\Fields;

use Illuminate\View\View;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Filters\TextFilter;
use Yaro\Jarboe\Tests\AbstractBaseTest;

abstract class AbstractFieldTest extends AbstractBaseTest
{
    const NAME = 'name';
    const TITLE_FROM_NAME = 'Name';
    const TITLE = 'Title';
    const ANOTHER_NAME = 'another';
    const ANOTHER_TITLE = 'Another';

    abstract protected function getFieldWithName(): AbstractField;
    abstract protected function getFieldWithNameAndTitle(): AbstractField;

    protected function field(): AbstractField
    {
        return $this->getFieldWithName();
    }

    protected function getFieldName(): string
    {
        return self::NAME;
    }

    /**
     * @test
     */
    public function check_not_supported_hidden_attribute()
    {
        $this->expectException(\RuntimeException::class);
        $this->field()->hidden('not_supported');
    }

    /**
     * @test
     */
    public function check_ability_to_be_hidden()
    {
        $field = $this->field();

        $this->assertFalse($field->hidden('list'));
        $this->assertFalse($field->hidden('edit'));
        $this->assertFalse($field->hidden('create'));


        $field->hideList(true);
        $this->assertTrue($field->hidden('list'));
        $field->hideEdit(true);
        $this->assertTrue($field->hidden('edit'));
        $field->hideCreate(true);
        $this->assertTrue($field->hidden('create'));


        $field->hide(true, false, true);
        $this->assertTrue($field->hidden('edit'));
        $this->assertFalse($field->hidden('create'));
        $this->assertTrue($field->hidden('list'));

        $field->hide(false, true, false);
        $this->assertFalse($field->hidden('edit'));
        $this->assertTrue($field->hidden('create'));
        $this->assertFalse($field->hidden('list'));
    }

    /**
     * @test
     */
    public function filter_passed()
    {
        $filter = TextFilter::make();
        $field = $this->field()->filter($filter);

        $this->assertEquals($filter, $field->filter());
    }

    /**
     * @test
     */
    public function no_filter_passed()
    {
        $field = $this->field();

        $this->assertNull($field->filter());
    }

    /**
     * @test
     */
    public function model_is_setted()
    {
        $field = $this->field();
        $field->setModel(self::class);

        $this->assertEquals(self::class, $field->getModel());
    }

    /**
     * @test
     */
    public function name_as_passed()
    {
        $field = $this->field();

        $this->assertEquals(self::NAME, $field->name());
    }

    /**
     * @test
     */
    public function name_redefined()
    {
        $field = $this->field()->name(self::ANOTHER_NAME);

        $this->assertEquals(self::ANOTHER_NAME, $field->name());
    }

    /**
     * @test
     */
    public function title_generated_from_name()
    {
        $field = $this->getFieldWithName();

        $this->assertEquals(self::TITLE_FROM_NAME, $field->title());
    }

    /**
     * @test
     */
    public function title_not_generated_from_name()
    {
        $field = $this->getFieldWithNameAndTitle();

        $this->assertEquals(self::TITLE, $field->title());
    }

    /**
     * @test
     */
    public function title_redefined()
    {
        $field = $this->getFieldWithNameAndTitle()->title(self::ANOTHER_TITLE);

        $this->assertEquals(self::ANOTHER_TITLE, $field->title());
    }

    /**
     * @test
     */
    public function title_not_generated_after_name_change()
    {
        $field = $this->getFieldWithNameAndTitle()->name(self::ANOTHER_NAME);

        $this->assertEquals(self::TITLE, $field->title());
    }

    /**
     * @test
     */
    public function default_col_width()
    {
        $field = $this->field();

        $this->assertEquals(12, $field->getCol());
    }

    /**
     * @test
     */
    public function changed_col_width()
    {
        $field = $this->field()->col(4);

        $this->assertEquals(4, $field->getCol());
    }

    /**
     * @test
     */
    public function default_width()
    {
        $field = $this->field();

        $this->assertNull($field->getWidth());
    }

    /**
     * @test
     */
    public function changed_width()
    {
        $field = $this->field()->width(40);

        $this->assertEquals(40, $field->getWidth());
    }

    /**
     * @test
     */
    public function default_tab()
    {
        $field = $this->field();

        $this->assertEquals(AbstractField::DEFAULT_TAB_IDENT, $field->getTab());
    }

    /**
     * @test
     */
    public function changed_tab()
    {
        $field = $this->field()->tab('tab');

        $this->assertEquals('tab', $field->getTab());
    }

    /**
     * @test
     */
    public function default_default_value()
    {
        $field = $this->field();

        $this->assertNull($field->getDefault());
    }

    /**
     * @test
     */
    public function changed_default_value()
    {
        $field = $this->field()->default('default value');

        $this->assertEquals('default value', $field->getDefault());
    }

    /**
     * @test
     */
    public function default_readonly()
    {
        $field = $this->field();

        $this->assertFalse($field->isReadonly());
    }

    /**
     * @test
     */
    public function changed_readonly()
    {
        $field = $this->field()->readonly();

        $this->assertTrue($field->isReadonly());
    }

    /**
     * @test
     */
    public function default_inline()
    {
        $field = $this->field();

        $this->assertFalse($field->isInline());
    }

    /**
     * @test
     */
    public function should_skip()
    {
        $field = $this->field();

        $this->assertFalse($field->shouldSkip(
            $this->createRequest([
                $field->name() => 'value',
            ]))
        );
    }

    /**
     * @test
     */
    public function check_get_list_value_view()
    {
        $field = $this->field();

        $this->assertInstanceOf(View::class, $field->getListView($this->model()));
    }

    /**
     * @test
     */
    public function check_get_create_form_value_view()
    {
        $field = $this->field();

        $this->assertInstanceOf(View::class, $field->getCreateFormView());
    }

    /**
     * @test
     */
    public function check_get_edit_form_value_view()
    {
        $field = $this->field();

        $this->assertInstanceOf(View::class, $field->getEditFormView($this->model()));
    }

    /**
     * @test
     */
    public function check_is_markup_row()
    {
        $field = $this->field();

        $this->assertFalse($field->isMarkupRow());
    }

    /**
     * @test
     */
    public function check_is_relation_field()
    {
        $field = $this->field();

        $this->assertFalse($field->isRelationField());
    }

    /**
     * @test
     */
    public function check_is_multiple_field()
    {
        $field = $this->field();

        $this->assertFalse($field->isMultiple());
    }

    /**
     * @test
     */
    public function check_is_encode_field()
    {
        $field = $this->field();

        $this->assertFalse($field->isEncode());
    }

    /**
     * @test
     */
    public function default_orderable()
    {
        $field = $this->field();

        $this->assertFalse($field->isOrderable());
    }

    /**
     * @test
     */
    public function default_ajax()
    {
        $field = $this->field();

        $this->assertFalse($field->isAjax());
    }

    /**
     * @test
     */
    public function default_clipboard()
    {
        $field = $this->field();

        $this->assertFalse($field->hasClipboardButton());
    }

    /**
     * @test
     */
    public function default_tooltip()
    {
        $field = $this->field();

        $this->assertFalse($field->hasTooltip());
    }

    /**
     * @test
     */
    public function default_mask()
    {
        $field = $this->field();

        $this->assertFalse($field->isMaskable());
    }

    /**
     * @test
     */
    public function default_translatable()
    {
        $field = $this->field();

        $this->assertFalse($field->isTranslatable());
    }

    /**
     * @test
     */
    public function default_placeholder()
    {
        $field = $this->field();

        $this->assertNull($field->getPlaceholder());
    }

    /**
     * @test
     */
    public function default_maxlength()
    {
        $field = $this->field();

        $this->assertFalse($field->hasMaxlength());
    }
}
