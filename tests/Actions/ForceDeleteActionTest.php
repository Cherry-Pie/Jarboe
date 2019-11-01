<?php

namespace Yaro\Jarboe\Tests\Actions;

use Yaro\Jarboe\Table\Actions\AbstractAction;
use Yaro\Jarboe\Table\Actions\ForceDeleteAction;

class ForceDeleteActionTest extends AbstractActionTest
{
    protected function action(): AbstractAction
    {
        return ForceDeleteAction::make();
    }

    protected function identifier()
    {
        return 'force-delete';
    }

    /**
     * @test
     */
    public function check_is_allowed()
    {
        list($action, $crud) = $this->getPreparedActionAndCrud();

        $this->assertFalse($action->isAllowed());
        $this->assertFalse($action->shouldRender());
        $action->check();
        $this->assertFalse($action->isAllowed());
        $this->assertFalse($action->shouldRender());
        $action->check(function () {
            return true;
        });
        $this->assertFalse($action->isAllowed());
        $this->assertFalse($action->shouldRender());
        $action->check(function () {
            return false;
        });
        $this->assertFalse($action->isAllowed());
        $this->assertFalse($action->shouldRender());


        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);

        $this->assertTrue($action->isAllowed());
        $this->assertTrue($action->shouldRender());
        $action->check();
        $this->assertFalse($action->isAllowed());
        $this->assertFalse($action->shouldRender());
        $action->check(function () {
            return true;
        });
        $this->assertTrue($action->isAllowed());
        $this->assertTrue($action->shouldRender());
        $action->check(function () {
            return false;
        });
        $this->assertFalse($action->isAllowed());
        $this->assertFalse($action->shouldRender());
    }

    /**
     * @test
     */
    public function check_should_render()
    {
        list($action, $crud) = $this->getPreparedActionAndCrud();

        $this->assertFalse($action->shouldRender());
        $action->renderCheck();
        $this->assertFalse($action->shouldRender());
        $action->renderCheck(function () {
            return true;
        });
        $this->assertTrue($action->shouldRender());
        $action->renderCheck(function () {
            return false;
        });
        $this->assertFalse($action->shouldRender());


        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);

        $this->assertTrue($action->shouldRender());
        $action->renderCheck();
        $this->assertFalse($action->shouldRender());
        $action->renderCheck(function () {
            return true;
        });
        $this->assertTrue($action->shouldRender());
        $action->renderCheck(function () {
            return false;
        });
        $this->assertFalse($action->shouldRender());
    }
}
