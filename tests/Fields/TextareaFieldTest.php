<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Textarea;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;

class TextareaFieldTest extends AbstractFieldTest
{
    use InlineTests;

    protected function getFieldWithName(): AbstractField
    {
        return Textarea::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Textarea::make(self::NAME, self::TITLE);
    }
}
