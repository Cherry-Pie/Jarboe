<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait ClipboardTests
{
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
    public function enable_clipboard()
    {
        $model = null;
        $field = $this->field()->clipboard();

        $this->assertTrue($field->hasClipboardButton());
        $this->assertNull($field->getClipboardCaption($model));

        $field = $this->field()->clipboard(true, 'caption');

        $this->assertTrue($field->hasClipboardButton());
        $this->assertEquals('caption', $field->getClipboardCaption($model));
    }

    /**
     * @test
     */
    public function enable_clipboard_with_closure_for_caption()
    {
        $model = null;
        $field = $this->field()->clipboard(true, function ($model) {
            return 22;
        });

        $this->assertTrue($field->hasClipboardButton());
        $this->assertEquals(22, $field->getClipboardCaption($model));
    }

    abstract protected function field(): AbstractField;
}
