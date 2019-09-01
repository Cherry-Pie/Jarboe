<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait PlaceholderTests
{
    /**
     * @test
     */
    public function default_placeholder()
    {
        $field = $this->field();

        $this->assertNull($field->getPlaceholder());
    }

    /**
     * @test
     */
    public function set_paceholder()
    {
        $field = $this->field()->placeholder('placeholder');

        $this->assertEquals('placeholder', $field->getPlaceholder());
    }

    abstract protected function field(): AbstractField;
}
