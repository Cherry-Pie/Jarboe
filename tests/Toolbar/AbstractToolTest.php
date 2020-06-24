<?php

namespace Yaro\Jarboe\Tests\Toolbar;

use Illuminate\View\View;
use Yaro\Jarboe\Table\Toolbar\AbstractTool;
use Yaro\Jarboe\Tests\AbstractBaseTest;

abstract class AbstractToolTest extends AbstractBaseTest
{
    abstract protected function tool(): AbstractTool;

    /**
     * @test
     */
    public function test_render()
    {
        $this->assertInstanceOf(View::class, $this->tool()->render());
    }
}
