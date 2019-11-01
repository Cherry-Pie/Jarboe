<?php

namespace Yaro\Jarboe\Tests\Actions;

use Illuminate\View\View;
use Yaro\Jarboe\Table\Actions\AbstractAction;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Tests\AbstractBaseTest;
use Yaro\Jarboe\Tests\Models\Model;

abstract class AbstractActionTest extends AbstractBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'testing');
    }

    /**
     * @test
     */
    public function check_common()
    {
        list($action, $crud) = $this->getPreparedActionAndCrud();

        $this->assertEquals($crud, $action->crud());
        $this->assertEquals($this->identifier(), $action->identifier());
        $this->assertInstanceOf(View::class, $action->render());
    }

    /**
     * @test
     */
    public function check_is_allowed()
    {
        $model = Model::first();
        list($action, $crud) = $this->getPreparedActionAndCrud();

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

    abstract protected function action(): AbstractAction;
    abstract protected function identifier();

    protected function getPreparedActionAndCrud(): array
    {
        $crud = app(CRUD::class);
        $action = $this->action();
        $action->setCrud($crud);

        return [$action, $crud];
    }
}
