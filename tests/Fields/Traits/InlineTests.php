<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait InlineTests
{
    /**
     * @test
     */
    public function default_inline()
    {
        $field = $this->field();

        $this->assertFalse($field->isInline());
    }

    /**
     * @test
     */
    public function enable_inline()
    {
        $field = $this->field()->inline();

        $this->assertTrue($field->isInline());
    }

    /**
     * @test
     */
    public function enable_and_disable_inline()
    {
        $field = $this->field()->inline()->inline(false);

        $this->assertFalse($field->isInline());
    }

    /**
     * @test
     */
    public function enable_inline_with_options()
    {
        $options = ['options'];
        $field = $this->field()->inline(true, $options);

        $this->assertEquals($options, $field->getInlineOptions());
    }

    /**
     * @test
     */
    public function check_inline_url()
    {
        $field = $this->field();
        $field->setInlineUrl('url');

        $this->assertEquals('url', $field->getInlineUrl());
    }

    abstract protected function field(): AbstractField;
}
