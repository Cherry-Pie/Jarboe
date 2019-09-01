<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Number;
use Yaro\Jarboe\Tests\Fields\Traits\ClipboardTests;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;

class NumberFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use TooltipTests;
    use ClipboardTests;
    use InlineTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return Number::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Number::make(self::NAME, self::TITLE);
    }
}
