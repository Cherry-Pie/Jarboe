<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;

class TextFieldTest extends AbstractFieldTest
{
    use InlineTests;

    protected function getFieldWithName(): AbstractField
    {
        return Text::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Text::make(self::NAME, self::TITLE);
    }

}
