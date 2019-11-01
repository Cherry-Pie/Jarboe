<?php

namespace Yaro\Jarboe\Tests\Actions;

use Illuminate\View\View;
use Yaro\Jarboe\Table\Actions\AbstractAction;
use Yaro\Jarboe\Table\Actions\DeleteAction;
use Yaro\Jarboe\Tests\Models\Model;

class DeleteActionTest extends AbstractActionTest
{
    protected function action(): AbstractAction
    {
        return DeleteAction::make();
    }

    protected function identifier()
    {
        return 'delete';
    }

    /**
     * @test
     */
    public function check_render_with_soft_deletes()
    {
        $model = Model::first();
        list($action, $crud) = $this->getPreparedActionAndCrud();
        $this->assertInstanceOf(View::class, $action->render($model));

        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);
        $this->assertInstanceOf(View::class, $action->render($model));

        $model->delete();
        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);
        $this->assertInstanceOf(View::class, $action->render($model));
    }

    /**
     * @test
     */
    public function check_is_allowed()
    {
        $model = Model::first();
        list($action, $crud) = $this->getPreparedActionAndCrud();

        $this->assertTrue($action->isAllowed());
        $this->assertTrue($action->shouldRender($model));
        $action->check();
        $this->assertFalse($action->isAllowed());
        $this->assertFalse($action->shouldRender($model));
        $action->check(function () {
            return true;
        });
        $this->assertTrue($action->isAllowed());
        $this->assertTrue($action->shouldRender($model));
        $action->check(function () {
            return false;
        });
        $this->assertFalse($action->isAllowed());
        $this->assertFalse($action->shouldRender($model));


        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);

        $this->assertTrue($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
        $action->check();
        $this->assertFalse($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
        $action->check(function () {
            return true;
        });
        $this->assertTrue($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
        $action->check(function () {
            return false;
        });
        $this->assertFalse($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));


        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);

        $model = Model::first();
        $model->delete();
        $this->assertFalse($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
        $action->check();
        $this->assertFalse($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
        $action->check(function () {
            return true;
        });
        $this->assertFalse($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
        $action->check(function () {
            return false;
        });
        $this->assertFalse($action->isAllowed($model));
        $this->assertTrue($action->shouldRender($model));
    }

    /**
     * @test
     */
    public function check_should_render()
    {
        $model = Model::first();
        list($action, $crud) = $this->getPreparedActionAndCrud();

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

        
        list($action, $crud) = $this->getPreparedActionAndCrud();
        $crud->enableSoftDelete();
        $action->setCrud($crud);

        $this->assertTrue($action->shouldRender($model));
        $action->renderCheck();
        $this->assertTrue($action->shouldRender($model));
        $action->renderCheck(function () {
            return true;
        });
        $this->assertTrue($action->shouldRender($model));
        $action->renderCheck(function () {
            return false;
        });
        $this->assertTrue($action->shouldRender($model));
    }
}
