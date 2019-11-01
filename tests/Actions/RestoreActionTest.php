<?php

namespace Yaro\Jarboe\Tests\Actions;

use Yaro\Jarboe\Table\Actions\AbstractAction;
use Yaro\Jarboe\Table\Actions\RestoreAction;
use Yaro\Jarboe\Tests\Models\Model;

class RestoreActionTest extends AbstractActionTest
{
    protected function action(): AbstractAction
    {
        return RestoreAction::make();
    }

    protected function identifier()
    {
        return 'restore';
    }

    /**
     * @test
     */
    public function check_is_allowed()
    {
        $model = Model::first();
        list($action, $crud) = $this->getPreparedActionAndCrud();

        $this->assertFalse($action->isAllowed($model));
        $this->assertFalse($action->shouldRender($model));
        $action->check();
        $this->assertFalse($action->isAllowed($model));
        $this->assertFalse($action->shouldRender($model));
        $action->check(function () {
            return true;
        });
        $this->assertFalse($action->isAllowed($model));
        $this->assertFalse($action->shouldRender($model));
        $action->check(function () {
            return false;
        });
        $this->assertFalse($action->isAllowed($model));
        $this->assertFalse($action->shouldRender($model));


        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);

        $this->assertTrue($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
        $action->check();
        $this->assertFalse($action->isAllowed($model));
        $this->assertFalse($action->shouldRender($model));
        $action->check(function () {
            return true;
        });
        $this->assertTrue($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
        $action->check(function () {
            return false;
        });
        $this->assertFalse($action->isAllowed($model));
        $this->assertFalse($action->shouldRender($model));
    }

    /**
     * @test
     */
    public function check_should_render()
    {
        $model = Model::first();
        list($action, $crud) = $this->getPreparedActionAndCrud();

        $this->assertFalse($action->shouldRender($model));
        $action->renderCheck();
        $this->assertFalse($action->shouldRender($model));
        $action->renderCheck(function () {
            return true;
        });
        $this->assertTrue($action->shouldRender($model));
        $action->renderCheck(function () {
            return false;
        });
        $this->assertFalse($action->shouldRender($model));


        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);

        $this->assertTrue($action->shouldRender($model));
        $action->renderCheck();
        $this->assertFalse($action->shouldRender($model));
        $action->renderCheck(function () {
            return true;
        });
        $this->assertTrue($action->shouldRender($model));
        $action->renderCheck(function () {
            return false;
        });
        $this->assertFalse($action->shouldRender($model));
    }
}
