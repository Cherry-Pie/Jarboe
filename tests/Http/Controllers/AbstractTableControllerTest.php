<?php

namespace Yaro\Jarboe\Tests\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yaro\Jarboe\Http\Controllers\AbstractTableController;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Table\Toolbar\MassDeleteTool;
use Yaro\Jarboe\Table\Toolbar\ShowHideColumnsTool;
use Yaro\Jarboe\Tests\AbstractBaseTest;
use Yaro\Jarboe\Tests\Models\Model;

class AbstractTableControllerTest extends AbstractBaseTest
{
    /** @var TestAbstractTableController */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/../../database/migrations'),
        ]);

        $this->controller = new TestAbstractTableController();
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
    public function check_magic_create_call()
    {
        $baseView = $this->controller->handleCreate($this->createRequest());
        $magicView = $this->controller->create($this->createRequest());

        $this->assertInstanceOf(View::class, $magicView);
        $this->assertEquals($baseView, $magicView);
    }

    /**
     * @test
     */
    public function check_magic_list_call()
    {
        $baseView = $this->controller->handleList($this->createRequest());
        $magicView = $this->controller->list($this->createRequest());

        $this->assertInstanceOf(View::class, $magicView);
        $this->assertEquals($baseView, $magicView);
    }

    /**
     * @test
     */
    public function check_magic_search_call()
    {
        $baseRedirect = $this->controller->handleSearch($this->createRequest());
        $magicRedirect = $this->controller->search($this->createRequest());

        $this->assertInstanceOf(RedirectResponse::class, $baseRedirect);
        $this->assertEquals($baseRedirect, $magicRedirect);
    }

    /**
     * @test
     */
    public function check_magic_store_call()
    {
        $baseRedirect = $this->controller->handleStore($this->createRequest());
        $magicRedirect = $this->controller->store($this->createRequest());

        $this->assertInstanceOf(RedirectResponse::class, $baseRedirect);
        $this->assertEquals($baseRedirect, $magicRedirect);
    }

    /**
     * @test
     */
    public function check_magic_edit_call()
    {
        $model = Model::first();
        $baseView = $this->controller->handleEdit($this->createRequest(), $model->id);
        $magicView = $this->controller->edit($this->createRequest(), $model->id);

        $this->assertInstanceOf(View::class, $baseView);
        $this->assertEquals($baseView, $magicView);
    }

    /**
     * @test
     */
    public function check_magic_update_call()
    {
        $model = Model::first();
        $baseRedirect = $this->controller->handleUpdate($this->createRequest(), $model->id);
        $magicRedirect = $this->controller->update($this->createRequest(), $model->id);

        $this->assertInstanceOf(RedirectResponse::class, $baseRedirect);
        $this->assertEquals($baseRedirect, $magicRedirect);
    }

    /**
     * @test
     */
    public function check_magic_delete_call()
    {
        $baseRedirect = $this->controller->handleDelete($this->createRequest(), Model::first()->id);
        $magicRedirect = $this->controller->delete($this->createRequest(), Model::first()->id);

        $this->assertInstanceOf(JsonResponse::class, $baseRedirect);
        $this->assertInstanceOf(JsonResponse::class, $magicRedirect);
    }

    /**
     * @test
     */
    public function check_magic_restore_call()
    {
        $model = Model::first();
        $baseResponse = $this->controller->handleRestore($this->createRequest(), $model->id);
        $magicResponse = $this->controller->restore($this->createRequest(), $model->id);

        $this->assertInstanceOf(JsonResponse::class, $baseResponse);
        $this->assertEquals($baseResponse, $magicResponse);
    }

    /**
     * @test
     */
    public function check_magic_force_delete_call()
    {
        $model = Model::first();
        $model->delete();
        $baseResponse = $this->controller->handleForceDelete($this->createRequest(), $model->id);

        $model = Model::first();
        $model->delete();
        $magicResponse = $this->controller->forceDelete($this->createRequest(), $model->id);

        $this->assertInstanceOf(JsonResponse::class, $baseResponse);
        $this->assertInstanceOf(JsonResponse::class, $magicResponse);
    }

    /**
     * @test
     */
    public function check_magic_inline_call()
    {
        $model = Model::first();
        $data = [
            '_pk' => $model->id,
            '_value' => 'text',
            '_name' => 'title',
        ];
        $baseResponse = $this->controller->handleInline($this->createRequest($data));
        $this->app->offsetSet('request', $this->createRequest($data));
        $magicResponse = $this->controller->inline();

        $this->assertInstanceOf(JsonResponse::class, $baseResponse);
        $this->assertEquals($baseResponse, $magicResponse);
    }

    /**
     * @test
     */
    public function add_tools_helper()
    {
        $tools = [
            new ShowHideColumnsTool(),
            new MassDeleteTool(),
        ];
        $controller = new class extends AbstractTableController {
            public function init()
            {
                $this->setModel(Model::class);
                $this->softDeletes();
                $this->filter(function ($model) {
                    $model->withTrashed();
                });

                $this->addFields([
                    Text::make('title')->inline(),
                    Text::make('description'),
                ]);

                $this->addTools([
                    new ShowHideColumnsTool(),
                    new MassDeleteTool(),
                ]);
            }

            public function crud(): CRUD
            {
                return $this->crud;
            }

            public function bound()
            {
                parent::bound();
            }
        };
        $controller->init();
        $controller->bound();

        $tools[0]->setCrud($controller->crud());
        $tools[1]->setCrud($controller->crud());

        $this->assertEquals($tools[0], $controller->crud()->getTool($tools[0]->identifier()));
        $this->assertEquals($tools[1], $controller->crud()->getTool($tools[1]->identifier()));
        $this->assertEquals(
            [
                $tools[0]->identifier() => $tools[0],
                $tools[1]->identifier() => $tools[1],
            ],
            $controller->crud()->getTools()
        );
    }

    /**
     * @test
     */
    public function add_tool_helper()
    {
        $tool = new ShowHideColumnsTool();
        $controller = new class extends AbstractTableController {
            public function init()
            {
                $this->setModel(Model::class);
                $this->softDeletes();
                $this->filter(function ($model) {
                    $model->withTrashed();
                });

                $this->addFields([
                    Text::make('title')->inline(),
                    Text::make('description'),
                ]);

                $this->addTool(new ShowHideColumnsTool());
            }

            public function crud(): CRUD
            {
                return $this->crud;
            }

            public function bound()
            {
                parent::bound();
            }
        };
        $controller->init();
        $controller->bound();

        $tool->setCrud($controller->crud());

        $this->assertEquals($tool, $controller->crud()->getTool($tool->identifier()));
        $this->assertEquals(
            [
                $tool->identifier() => $tool,
            ],
            $controller->crud()->getTools()
        );
    }

    /**
     * @test
     */
    public function check_per_page_is_setted()
    {
        $this->controller->perPage(420);

        $this->assertEquals(420, $this->controller->crud()->getPerPageParam());
    }

    /**
     * @test
     */
    public function check_order_by_is_setted()
    {
        $response = $this->controller->orderBy('title', 'desc');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('desc', $this->controller->crud()->getOrderFilterParam('title'));
        $this->assertNull($this->controller->crud()->getOrderFilterParam('none'));
    }

    /**
     * @test
     */
    public function check_can_without_permissions()
    {
        $this->assertTrue($this->controller->can('some_permission'));
    }

    /**
     * @test
     */
    public function check_notify()
    {
        $this->controller->notify('title small', 'content small', 1200, '#bbbccc', 'fa fa-users', 'small');
        $this->controller->notify('title big', 'content big', 1000, '#bbbccc', 'fa fa-users', 'big');

        $this->assertEquals(
            [
                [
                    'title' => 'title small',
                    'content' => 'content small',
                    'color' => '#bbbccc',
                    'icon' => 'fa fa-users',
                    'timeout' => 1200,
                ],
            ],
            session('jarboe_notifications.small')
        );
        $this->assertEquals(
            [
                [
                    'title' => 'title big',
                    'content' => 'content big',
                    'color' => '#bbbccc',
                    'icon' => 'fa fa-users',
                    'timeout' => 1000,
                ],
            ],
            session('jarboe_notifications.big')
        );

        session()->flush();


        $this->controller->notifySmallSuccess('title small', 'content small', 1234);
        $this->controller->notifySmallDanger('title small', 'content small', 12345);
        $this->controller->notifySmallWarning('title small', 'content small', 12346);
        $this->controller->notifySmallInfo('title small', 'content small', 12347);

        $this->controller->notifyBigSuccess('title big', 'content big', 43214);
        $this->controller->notifyBigDanger('title big', 'content big', 43213);
        $this->controller->notifyBigWarning('title big', 'content big', 43212);
        $this->controller->notifyBigInfo('title big', 'content big', 43211);

        $this->assertEquals(
            [
                [
                    'title' => 'title small',
                    'content' => 'content small',
                    'color' => '#739E73',
                    'icon' => 'fa fa-check',
                    'timeout' => 1234,
                ],
                [
                    'title' => 'title small',
                    'content' => 'content small',
                    'color' => '#C46A69',
                    'icon' => 'fa fa-warning shake animated',
                    'timeout' => 12345,
                ],
                [
                    'title' => 'title small',
                    'content' => 'content small',
                    'color' => '#C79121',
                    'icon' => 'fa fa-shield fadeInLeft animated',
                    'timeout' => 12346,
                ],
                [
                    'title' => 'title small',
                    'content' => 'content small',
                    'color' => '#3276B1',
                    'icon' => 'fa fa-bell swing animated',
                    'timeout' => 12347,
                ],
            ],
            session('jarboe_notifications.small')
        );
        $this->assertEquals(
            [
                [
                    'title' => 'title big',
                    'content' => 'content big',
                    'color' => '#739E73',
                    'icon' => 'fa fa-check',
                    'timeout' => 43214,
                ],
                [
                    'title' => 'title big',
                    'content' => 'content big',
                    'color' => '#C46A69',
                    'icon' => 'fa fa-warning shake animated',
                    'timeout' => 43213,
                ],
                [
                    'title' => 'title big',
                    'content' => 'content big',
                    'color' => '#C79121',
                    'icon' => 'fa fa-shield fadeInLeft animated',
                    'timeout' => 43212,
                ],
                [
                    'title' => 'title big',
                    'content' => 'content big',
                    'color' => '#3276B1',
                    'icon' => 'fa fa-bell swing animated',
                    'timeout' => 43211,
                ],
            ],
            session('jarboe_notifications.big')
        );

        session()->flush();
    }

    /**
     * @test
     */
    public function check_default_additional_views()
    {
        $this->assertIsArray($this->controller->getListViewsAbove());
        $this->assertEmpty($this->controller->getListViewsAbove());

        $this->assertIsArray($this->controller->getListViewsBelow());
        $this->assertEmpty($this->controller->getListViewsBelow());


        $this->assertIsArray($this->controller->getEditViewsAbove());
        $this->assertEmpty($this->controller->getEditViewsAbove());

        $this->assertIsArray($this->controller->getEditViewsBelow());
        $this->assertEmpty($this->controller->getEditViewsBelow());


        $this->assertIsArray($this->controller->getCreateViewsAbove());
        $this->assertEmpty($this->controller->getCreateViewsAbove());

        $this->assertIsArray($this->controller->getCreateViewsBelow());
        $this->assertEmpty($this->controller->getCreateViewsBelow());
    }
}