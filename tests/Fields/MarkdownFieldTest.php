<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Markdown;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;

class MarkdownFieldTest extends AbstractFieldTest
{
    use OrderableTests;

    protected function getFieldWithName(): AbstractField
    {
        return Markdown::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Markdown::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function markdown_check_value_type()
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
}
