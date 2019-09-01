<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\File;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Tests\Fields\Traits\ClipboardTests;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;
use Yaro\Jarboe\Tests\Fields\Traits\MaskableTests;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\StorageTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;
use Yaro\Jarboe\Tests\Fields\Traits\TranslatableTests;

class FileFieldTest extends AbstractFieldTest
{
    use StorageTests;
    use NullableTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return File::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return File::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function should_skip()
    {
        $field = $this->field();

        $this->assertTrue($field->shouldSkip(
            $this->createRequest()
        ));
    }

    /**
     * @test
     */
    public function should_not_skip_with_file()
    {
        $field = $this->field();

        $this->assertFalse($field->shouldSkip(
            $this->createRequestWithFile()
        ));
    }
}
