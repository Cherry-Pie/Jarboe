<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait InlineTests
{
    /**
     * @test
     */
    public function changed_inline()
    {
        $field = $this->field()->inline();

        $this->assertTrue($field->isInline());
    }

    abstract protected function field(): AbstractField;
}
