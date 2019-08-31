<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
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
}
