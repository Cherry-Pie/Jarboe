<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Wysiwyg;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\TranslatableTests;

class WysiwygFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use TranslatableTests;

    protected function getFieldWithName(): AbstractField
    {
        return Wysiwyg::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Wysiwyg::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function wysiwyg_check_value_type()
    {
        $field = $this->field();

        $this->assertIsString(
            $field->value($this->createRequest([
                self::NAME => 0,
            ]))
        );
        $this->assertIsString(
            $field->value($this->createRequest([
                self::NAME => 'content',
            ]))
        );
        $this->assertIsString(
            $field->value($this->createRequest())
        );
    }

    /**
     * @test
     */
    public function wysiwyg_check_translatable_value_type()
    {
        $field = $this->field();

        $this->assertIsArray(
            $field->value($this->createRequest([
                self::NAME => [
                    'en' => 0
                ],
            ]))
        );
        $this->assertIsArray(
            $field->value($this->createRequest([
                self::NAME => [
                    'en' => 'en content'
                ],
            ]))
        );
        $this->assertIsString(
            $field->value($this->createRequest())
        );
    }

    /**
     * @test
     */
    public function wysiwyg_default_type()
    {
        $field = $this->field();

        $this->assertEquals(Wysiwyg::SUMMERNOTE, $field->getType());
    }

    /**
     * @test
     */
    public function wysiwyg_changed_type()
    {
        $field = $this->field()->type(Wysiwyg::TINYMCE);

        $this->assertEquals(Wysiwyg::TINYMCE, $field->getType());
    }

    /**
     * @test
     */
    public function wysiwyg_changed_unsupported_type()
    {
        $field = $this->field()->type('unsupported_type');

        $this->assertEquals(Wysiwyg::SUMMERNOTE, $field->getType());
    }

    /**
     * @test
     */
    public function wysiwyg_check_options()
    {
        /** @var Wysiwyg $field */
        $field = $this->field()->type(Wysiwyg::SUMMERNOTE);

        $this->assertIsArray($field->getOptions());
        $this->assertNotEmpty($field->getOptions());

        /** @var Wysiwyg $field */
        $field = $this->field()->type(Wysiwyg::TINYMCE);

        $this->assertIsArray($field->getOptions());
        $this->assertNotEmpty($field->getOptions());

        $options = [
            'menubar' => false,
            'plugins' => 'code table lists autoresize link',
            'toolbar' => 'undo redo | bold italic | link | numlist bullist | table | styleselect | removeformat | code',
        ];
        $this->assertEquals($options, $field->options($options)->getOptions());
    }
}
