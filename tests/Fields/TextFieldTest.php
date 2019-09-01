<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Tests\Fields\Traits\ClipboardTests;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;
use Yaro\Jarboe\Tests\Fields\Traits\MaskableTests;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;
use Yaro\Jarboe\Tests\Fields\Traits\TranslatableTests;

class TextFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use TooltipTests;
    use ClipboardTests;
    use InlineTests;
    use TranslatableTests;
    use MaskableTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return Text::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Text::make(self::NAME, self::TITLE);
    }
}
