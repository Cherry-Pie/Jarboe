<?php

namespace Yaro\Jarboe\Tests\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Http\Controllers\AbstractTableController;
use Yaro\Jarboe\Models\Admin;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\Checkbox;
use Yaro\Jarboe\Table\Fields\Markup\RowMarkup;
use Yaro\Jarboe\Table\Fields\Select;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Table\Fields\Textarea;
use Yaro\Jarboe\Table\Filters\TextFilter;
use Yaro\Jarboe\Table\Toolbar\MassDeleteTool;
use Yaro\Jarboe\Table\Toolbar\ShowHideColumnsTool;
use Yaro\Jarboe\Tests\AbstractBaseTest;
use Yaro\Jarboe\Tests\Models\Model;
use Yaro\Jarboe\ViewComponents\Breadcrumbs\BreadcrumbsInterface;
use Yaro\Jarboe\ViewComponents\Breadcrumbs\Crumb;

class AbstractTableControllerTest extends AbstractBaseTest
{
    /** @var TestAbstractTableController */
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/../../database/migrations'),
        ]);
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();

        auth('admin')->login(Admin::first());

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
        $app['config']->set('auth.guards.admin.driver', 'session');
        $app['config']->set('auth.guards.admin.provider', 'admins');
        $app['config']->set('auth.providers.admins.driver', 'eloquent');
        $app['config']->set('auth.providers.admins.model', \Yaro\Jarboe\Models\Admin::class);
        $app['config']->set('permission.models.permission', \Spatie\Permission\Models\Permission::class);
        $app['config']->set('permission.models.role', \Spatie\Permission\Models\Role::class);
        $app['config']->set('permission.cache.expiration_time', 0);
        $app['config']->set('permission.table_names', [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ]);
        $app['config']->set('permission.column_names', [
            'model_morph_key' => 'model_id',
        ]);
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
    public function check_magic_create_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $this->controller->setPermissions('unauthorized');

        $this->controller->handleCreate($this->createRequest());
    }

    /**
     * @test
     */
    public function check_magic_create_call_permission_denied()
    {
        $this->expectException(PermissionDenied::class);

        $this->controller->crud()->actions()->find('create')->check(function () {
            return false;
        });

        $this->controller->handleCreate($this->createRequest());
    }

    /**
     * @test
     */
    public function check_magic_create_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $response = $this->controller->create($this->createRequest());
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['create'])),
            $response
        );
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
    public function check_magic_list_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $this->controller->setPermissions('unauthorized');

        $this->controller->handleList($this->createRequest());
    }

    /**
     * @test
     */
    public function check_magic_list_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $response = $this->controller->list($this->createRequest());
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['list'])),
            $response
        );
    }

    /**
     * @test
     */
    public function check_magic_search_call()
    {
        $baseRedirect = $this->controller->handleSearch($this->createRequest());
        $magicRedirect = $this->controller->search($this->createRequest());

        $this->assertInstanceOf(RedirectResponse::class, $baseRedirect);
        $this->assertEquals($baseRedirect->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'), $magicRedirect->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'));
    }

    /**
     * @test
     */
    public function check_magic_search_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $this->controller->setPermissions('unauthorized');

        $this->controller->handleSearch($this->createRequest());
    }

    /**
     * @test
     */
    public function check_magic_search_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $response = $this->controller->search($this->createRequest());
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['search'])),
            $response
        );
    }

    /**
     * @test
     */
    public function check_magic_store_call()
    {
        $baseRedirect = $this->controller->handleStore($this->createRequest());
        $magicRedirect = $this->controller->store($this->createRequest());

        $this->assertInstanceOf(RedirectResponse::class, $baseRedirect);
        $this->assertEquals($baseRedirect->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'), $magicRedirect->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'));
    }

    /**
     * @test
     */
    public function check_magic_store_call_permission_denied()
    {
        $this->expectException(PermissionDenied::class);

        $this->controller->crud()->actions()->find('create')->check(function () {
            return false;
        });

        $this->controller->handleStore($this->createRequest());
    }

    /**
     * @test
     */
    public function check_magic_store_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $this->controller->setPermissions('unauthorized');

        $this->controller->handleStore($this->createRequest());
    }

    /**
     * @test
     */
    public function check_magic_store_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $response = $this->controller->store($this->createRequest());
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['store'])),
            $response
        );
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
    public function check_magic_edit_call_permission_denied()
    {
        $this->expectException(PermissionDenied::class);

        $model = Model::first();
        $this->controller->crud()->actions()->find('edit')->check(function () {
            return false;
        });

        $this->controller->handleEdit($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_edit_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $model = Model::first();
        $this->controller->setPermissions('unauthorized');

        $this->controller->handleEdit($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_edit_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $model = Model::first();
        $response = $this->controller->edit($this->createRequest(), $model->id);
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['edit'])),
            $response
        );
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
        $this->assertEquals($baseRedirect->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'), $magicRedirect->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'));
    }

    /**
     * @test
     */
    public function check_magic_update_call_permission_denied()
    {
        $this->expectException(PermissionDenied::class);

        $model = Model::first();
        $this->controller->crud()->actions()->find('edit')->check(function () {
            return false;
        });

        $this->controller->handleUpdate($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_update_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $model = Model::first();
        $this->controller->setPermissions('unauthorized');

        $this->controller->handleUpdate($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_update_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $model = Model::first();
        $response = $this->controller->update($this->createRequest(), $model->id);
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['update'])),
            $response
        );
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
    public function check_magic_delete_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $model = Model::first();
        $this->controller->setPermissions('unauthorized');

        $this->controller->handleDelete($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_delete_call_permission_denied()
    {
        $this->expectException(PermissionDenied::class);

        $model = Model::first();
        $this->controller->crud()->actions()->find('delete')->check(function () {
            return false;
        });

        $this->controller->handleDelete($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_delete_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $model = Model::first();
        $response = $this->controller->delete($this->createRequest(), $model->id);
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['delete'])),
            $response
        );
    }

    /**
     * @test
     */
    public function check_unavailable_magic_call()
    {
        $magicRedirect = $this->controller->notexistedmethod($this->createRequest());

        $this->assertInstanceOf(RedirectResponse::class, $magicRedirect);
        $this->assertEquals(session()->get('jarboe_notifications.big'), [[
            'title' => 'RuntimeException',
            'content' => 'Invalid method notexistedmethod',
            'color' => '#C46A69',
            'icon' => 'fa fa-warning shake animated',
            'timeout' => 0,
        ]]);
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
        $this->assertEquals(Response::HTTP_OK, $magicResponse->getStatusCode());
        $this->assertEquals($baseResponse->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'), $magicResponse->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'));
    }

    /**
     * @test
     */
    public function check_magic_restore_call_unsuccessful()
    {
        Model::restoring(function ($model) {
            return false;
        });
        $model = Model::first();

        $baseResponse = $this->controller->handleRestore($this->createRequest(), $model->id);
        $magicResponse = $this->controller->restore($this->createRequest(), $model->id);

        $this->assertInstanceOf(JsonResponse::class, $baseResponse);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $magicResponse->getStatusCode());
        $this->assertEquals($baseResponse->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'), $magicResponse->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'));
    }

    /**
     * @test
     */
    public function check_magic_restore_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $model = Model::first();
        $this->controller->setPermissions('unauthorized');

        $this->controller->handleRestore($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_restore_call_permission_denied()
    {
        $this->expectException(PermissionDenied::class);

        $this->controller->disableSoftDelete();

        $this->controller->handleRestore($this->createRequest(), 1);
    }

    /**
     * @test
     */
    public function check_magic_restore_call_permission_denied_by_action()
    {
        $this->expectException(PermissionDenied::class);

        $this->controller->crud()->actions()->find('restore')->check(function () {
            return false;
        });

        $this->controller->handleRestore($this->createRequest(), 1);
    }

    /**
     * @test
     */
    public function check_magic_restore_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $model = Model::first();
        $response = $this->controller->restore($this->createRequest(), $model->id);
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['restore'])),
            $response
        );
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
    public function check_magic_force_delete_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $model = Model::first();
        $model->delete();
        $this->controller->setPermissions('unauthorized');

        $this->controller->handleForceDelete($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_force_delete_permission_denied_by_action()
    {
        $this->expectException(PermissionDenied::class);

        $model = Model::first();
        $model->delete();
        $this->controller->crud()->actions()->find('force-delete')->check(function () {
            return false;
        });

        $this->controller->handleForceDelete($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_force_delete_non_trashed_model_call_permission_denied()
    {
        $this->expectException(PermissionDenied::class);

        $model = Model::first();

        $this->controller->handleForceDelete($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_force_delete_non_soft_delete_crud_call_permission_denied()
    {
        $this->expectException(PermissionDenied::class);

        $this->controller->disableSoftDelete();
        $model = Model::first();
        $model->delete();

        $this->controller->handleForceDelete($this->createRequest(), $model->id);
    }

    /**
     * @test
     */
    public function check_magic_force_delete_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $model = Model::first();
        $model->delete();
        $response = $this->controller->forceDelete($this->createRequest(), $model->id);
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['force-delete'])),
            $response
        );
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
        $this->assertEquals($baseResponse->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'), $magicResponse->header('date', 'Fri, 01 Jan 1990 00:00:00 GMT'));
    }

    /**
     * @test
     */
    public function check_magic_inline_call_unauthorized()
    {
        $this->expectException(UnauthorizedException::class);

        $this->controller->setPermissions('unauthorized');

        $this->controller->handleInline($this->createRequest());
    }

    /**
     * @test
     */
    public function check_magic_inline_call_unauthorized_response()
    {
        $this->controller->setPermissions('unauthorized');

        $response = $this->controller->inline($this->createRequest());
        $this->assertEquals(
            $this->controller->createUnauthorizedResponse($this->createRequest(), UnauthorizedException::forPermissions(['inline'])),
            $response
        );
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


        $this->controller->notifySmall('title small', 'content small', 1200, '#bbbccc', 'fa fa-users');
        $this->controller->notifyBig('title big', 'content big', 1000, '#bbbccc', 'fa fa-users');

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

    /**
     * @test
     */
    public function check_permissions()
    {
        $this->assertTrue($this->controller->can('any'));

        $this->controller->setPermissions([
            'existed' => 'permission',
        ]);
        $this->assertTrue($this->controller->can('any'));
        $this->assertFalse($this->controller->can('existed'));

        $this->controller->setPermissions('existed');
        $this->assertTrue($this->controller->can('list'));
        $this->assertFalse($this->controller->can('delete'));
        $this->assertFalse($this->controller->can('notexisted'));
    }

    /**
     * @test
     */
    public function check_unauthorized_response()
    {
        $exception = UnauthorizedException::forPermissions(['hey']);
        $request = $this->createRequest();
        $request->headers->set('Accept', 'text/json');

        $response = $this->controller->createUnauthorizedResponse($request, $exception);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $response = $this->controller->createUnauthorizedResponse($this->createRequest(), $exception);
        $this->assertInstanceOf(View::class, $response);
    }

    /**
     * @test
     */
    public function check_validation_exception_should_be_validation_exception()
    {
        $this->expectException(ValidationException::class);

        $this->controller->overrideListMethodToThrowValidationException();
        $this->controller->list($this->createRequest());
    }

    /**
     * @test
     */
    public function check_breadcrumbs()
    {
        $this->controller->breadcrumbs()->add(Crumb::make('hai'));
        $this->controller->bound();

        $this->assertInstanceOf(BreadcrumbsInterface::class, $this->controller->breadcrumbs());

        $view = view('jarboe::crud.list');
        $view->getFactory()->callComposer($view);
        $this->assertEquals($this->controller->breadcrumbs(), $view->breadcrumbs);

        $view = view('jarboe::crud.create');
        $view->getFactory()->callComposer($view);
        $this->assertEquals($this->controller->breadcrumbs(), $view->breadcrumbs);

        $view = view('jarboe::crud.edit');
        $view->getFactory()->callComposer($view);
        $this->assertEquals($this->controller->breadcrumbs(), $view->breadcrumbs);
    }

    /**
     * @test
     */
    public function check_locales_alias()
    {
        $locales = [
            'en' => 'EN',
            'JP' => 'JP',
        ];
        $this->controller->locales($locales);

        $this->assertEquals($locales, $this->controller->crud()->getLocales());


        $locales = [
            'en',
            'JP',
        ];
        $this->controller->locales($locales);

        $this->assertEquals(array_combine($locales, $locales), $this->controller->crud()->getLocales());
    }

    /**
     * @test
     */
    public function check_add_column_alias_by_field()
    {
        $columns = $this->controller->crud()->getColumns();

        $this->assertEmpty($columns);
        $this->assertIsArray($columns);
        $this->assertEquals(
            $this->controller->crud()->getFields(),
            $this->controller->crud()->getColumnsAsFields()
        );

        $field = Text::make('title');
        $this->controller->addColumn($field);
        $columns = $this->controller->crud()->getColumns();

        $this->assertEquals([$field], $columns);
    }

    /**
     * @test
     */
    public function check_add_column_alias_by_identifier()
    {
        $columns = $this->controller->crud()->getColumns();

        $this->assertEmpty($columns);
        $this->assertIsArray($columns);

        $this->controller->init();
        $this->controller->bound();
        $this->controller->addColumn('description');

        $this->assertEquals(
            [$this->controller->crud()->getFieldByName('description')],
            $this->controller->crud()->getColumnsAsFields()
        );
    }

    /**
     * @test
     */
    public function check_add_column_alias_by_new_identifier()
    {
        $columns = $this->controller->crud()->getColumns();

        $this->assertEmpty($columns);
        $this->assertIsArray($columns);

        $this->controller->init();
        $this->controller->bound();
        $this->controller->addColumn('hi');

        $this->assertNull($this->controller->crud()->getFieldByName('hi'));
        $this->assertEquals(
            [Text::make('hi')],
            $this->controller->crud()->getColumnsAsFields()
        );
    }

    /**
     * @test
     */
    public function check_add_columns_alias()
    {
        $fields = [
            Text::make('title'),
            Textarea::make('description'),
            'hi',
        ];
        $this->controller->addColumns($fields);

        $this->assertNull($this->controller->crud()->getFieldByName('hi'));

        array_pop($fields);
        $fields[] = Text::make('hi');

        $this->assertEquals(
            $fields,
            $this->controller->crud()->getColumnsAsFields()
        );
    }

    /**
     * @test
     */
    public function check_field_extraction()
    {
        $text = Text::make('title');
        $textarea = Textarea::make('description');
        $select = Select::make('select');
        $checkbox = Checkbox::make('checkbox');
        $fields = [
            $text,
            RowMarkup::make()->fields([
                $select,
                $checkbox,
            ]),
            $textarea,
        ];
        $this->controller->addFields($fields);

        $this->assertEquals(
            [
                $text,
                $select,
                $checkbox,
                $textarea,
            ],
            $this->controller->crud()->getFieldsWithoutMarkup()
        );
    }

    /**
     * @test
     */
    public function check_fields_with_no_filter()
    {
        $this->controller->init();
        $this->controller->bound();

        $field = Text::make('title');
        $this->controller->addField($field);

        $this->assertFalse($this->controller->crud()->hasAnyFieldFilter());
    }

    /**
     * @test
     */
    public function check_fields_with_filter()
    {
        $this->controller->init();
        $this->controller->bound();

        $field = Text::make('title')->filter(TextFilter::make());
        $this->controller->addField($field);

        $this->assertTrue($this->controller->crud()->hasAnyFieldFilter());
    }

    /**
     * @test
     */
    public function check_getting_columns_without_related_fields()
    {
        $this->controller->init();
        $this->controller->bound();

        $field = Text::make('schwifty');
        $this->controller->addColumn($field);

        $this->assertEquals([$field], $this->controller->crud()->getColumnsWithoutRelatedField());
    }

    /**
     * @test
     */
    public function check_sortable_weight_is_not_set()
    {
        $this->assertFalse($this->controller->crud()->isSortableByWeight());
        $this->assertNull($this->controller->crud()->getSortableWeightFieldName());
    }

    /**
     * @test
     */
    public function check_sortable_weight_is_set()
    {
        $this->controller->sortable('sortme');

        $this->assertTrue($this->controller->crud()->isSortableByWeight());
        $this->assertEquals('sortme', $this->controller->crud()->getSortableWeightFieldName());
    }

    /**
     * @test
     */
    public function check_urls()
    {
        $this->assertEquals('http://localhost', $this->controller->crud()->baseUrl());
        $this->assertEquals('http://localhost/~/42', $this->controller->crud()->editUrl(42));
        $this->assertEquals('http://localhost/~/create', $this->controller->crud()->createUrl());
        $this->assertEquals('http://localhost/~/42/delete', $this->controller->crud()->deleteUrl(42));
        $this->assertEquals('http://localhost/~/42/restore', $this->controller->crud()->restoreUrl(42));
        $this->assertEquals('http://localhost/~/42/force-delete', $this->controller->crud()->forceDeleteUrl(42));
        $this->assertEquals('http://localhost/~/toolbar/sometool', $this->controller->crud()->toolbarUrl('sometool'));
        $this->assertEquals('http://localhost/~/per-page/42', $this->controller->crud()->perPageUrl(42));
        $this->assertEquals('http://localhost/~/search', $this->controller->crud()->searchUrl());
        $this->assertEquals('http://localhost/~/search/relation', $this->controller->crud()->relationSearchUrl());
        $this->assertEquals('http://localhost/~/order/price/desc', $this->controller->crud()->orderUrl('price', 'desc'));
        $this->assertEquals('http://localhost/~/reorder/switch', $this->controller->crud()->reorderUrl());
        $this->assertEquals('http://localhost/~/reorder/move/42', $this->controller->crud()->reorderMoveItemUrl(42));
    }
}
