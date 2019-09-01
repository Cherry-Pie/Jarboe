<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\IconPicker;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;

class IconPickerFieldTest extends AbstractFieldTest
{
    use OrderableTests;

    protected function getFieldWithName(): AbstractField
    {
        return IconPicker::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return IconPicker::make(self::NAME, self::TITLE);
    }
}
