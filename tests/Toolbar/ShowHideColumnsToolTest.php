<?php

namespace Yaro\Jarboe\Tests\Toolbar;

use Yaro\Jarboe\Table\Toolbar\AbstractTool;
use Yaro\Jarboe\Table\Toolbar\ShowHideColumnsTool;

class ShowHideColumnsToolTest extends AbstractToolTest
{
    protected function tool(): AbstractTool
    {
        $tool = new ShowHideColumnsTool();
        $tool->setCrud($this->crud());

        return $tool;
    }

    /**
     * @test
     */
    public function test_position()
    {
        $this->assertSame(AbstractTool::POSITION_HEADER, $this->tool()->position());
    }

    /**
     * @test
     */
    public function test_identifier()
    {
        $this->assertSame('show_hide_columns', $this->tool()->identifier());
    }

    /**
     * @test
     */
    public function test_check()
    {
        $this->assertTrue($this->tool()->check());
    }

    /**
     * @test
     */
    public function test_handle()
    {
        $this->assertNull(
            $this->tool()->handle($this->createRequest())
        );
    }

    /**
     * @test
     */
    public function test_get_url()
    {
        $this->assertSame('http://localhost/~/toolbar/show_hide_columns', $this->tool()->getUrl());
    }
}
