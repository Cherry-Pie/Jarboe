<?php

namespace Yaro\Jarboe\Tests\Actions;

use Yaro\Jarboe\Table\Actions\ActionsContainer;
use Yaro\Jarboe\Table\Actions\CreateAction;
use Yaro\Jarboe\Table\Actions\DeleteAction;
use Yaro\Jarboe\Table\Actions\EditAction;
use Yaro\Jarboe\Table\Actions\ForceDeleteAction;
use Yaro\Jarboe\Table\Actions\RestoreAction;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Tests\AbstractBaseTest;

class ActionsContainerTest extends AbstractBaseTest
{
    /**
     * @test
     */
    public function add_and_find()
    {
        $container = new ActionsContainer();
        $container->set([
            CreateAction::make(),
            EditAction::make(),
        ]);

        $action = $container->find('edit');
        $this->assertEquals(EditAction::make(), $action);

        $action = $container->find('delete');
        $this->assertNull($action);

        $container->set([
            DeleteAction::make(),
        ]);

        $action = $container->find('edit');
        $this->assertNull($action);

        $action = $container->find('delete');
        $this->assertEquals(DeleteAction::make(), $action);

        $container->add([
            CreateAction::make(),
            EditAction::make(),
        ]);
        $container->add(RestoreAction::make());

        $action = $container->find('create');
        $this->assertEquals(CreateAction::make(), $action);
        $action = $container->find('restore');
        $this->assertEquals(RestoreAction::make(), $action);
    }

    /**
     * @test
     */
    public function remove()
    {
        $container = new ActionsContainer();
        $container->set([
            CreateAction::make(),
            EditAction::make(),
        ]);

        $action = $container->find('edit');
        $this->assertEquals(EditAction::make(), $action);

        $container->remove('edit');

        $action = $container->find('edit');
        $this->assertNull($action);
        $action = $container->find('create');
        $this->assertEquals(CreateAction::make(), $action);
    }

    /**
     * @test
     */
    public function is_allowed()
    {
        $container = new ActionsContainer();
        $container->set([
            CreateAction::make(),
            EditAction::make()->check(function () {
                return false;
            }),
        ]);

        $this->assertFalse($container->isAllowed('edit'));
        $this->assertFalse($container->isAllowed('delete'));
        $this->assertTrue($container->isAllowed('create'));
    }

    /**
     * @test
     */
    public function should_render()
    {
        $container = new ActionsContainer();
        $container->set([
            CreateAction::make(),
            EditAction::make()->renderCheck(function () {
                return false;
            }),
        ]);

        $this->assertFalse($container->shouldRender('edit'));
        $this->assertFalse($container->shouldRender('delete'));
        $this->assertTrue($container->shouldRender('create'));
    }

    /**
     * @test
     */
    public function set_crud()
    {
        $crud = app(CRUD::class);
        $container = new ActionsContainer();
        $container->set([
            CreateAction::make(),
            EditAction::make(),
        ]);
        $container->setCrud($crud);

        $this->assertEquals($crud, $container->find('create')->crud());
        $this->assertEquals($crud, $container->find('edit')->crud());
    }

    /**
     * @test
     */
    public function get_row_actions()
    {
        $container = new ActionsContainer();
        $container->set([
            CreateAction::make(),
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);

        $this->assertEquals([
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ], $container->getRowActions());
    }

    /**
     * @test
     */
    public function check_move()
    {
        $container = new ActionsContainer();
        $container->set([
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);

        $container->moveAfter('restore', 'edit');

        $this->assertEquals([
            RestoreAction::make(),
            EditAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ], $container->getRowActions());

        $container->moveBefore('restore', 'force-delete');

        $this->assertEquals([
            ForceDeleteAction::make(),
            RestoreAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ], $container->getRowActions());
    }

    /**
     * @test
     */
    public function check_after_positioning_on_undefined_base_action()
    {
        $this->expectException(\RuntimeException::class);

        $container = new ActionsContainer();
        $container->set([
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);

        $container->moveAfter('undefined', 'restore');
    }

    /**
     * @test
     */
    public function check_after_positioning_on_undefined_movable_action()
    {
        $this->expectException(\RuntimeException::class);

        $container = new ActionsContainer();
        $container->set([
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);

        $container->moveAfter('restore', 'undefined');
    }

    /**
     * @test
     */
    public function check_after_positioning_on_undefined_both_action()
    {
        $this->expectException(\RuntimeException::class);

        $container = new ActionsContainer();
        $container->set([
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);

        $container->moveAfter('undefined', 'undefined');
    }

    /**
     * @test
     */
    public function check_before_positioning_on_undefined_base_action()
    {
        $this->expectException(\RuntimeException::class);

        $container = new ActionsContainer();
        $container->set([
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);

        $container->moveBefore('undefined', 'restore');
    }

    /**
     * @test
     */
    public function check_before_positioning_on_undefined_movable_action()
    {
        $this->expectException(\RuntimeException::class);

        $container = new ActionsContainer();
        $container->set([
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);

        $container->moveBefore('restore', 'undefined');
    }

    /**
     * @test
     */
    public function check_before_positioning_on_undefined_both_action()
    {
        $this->expectException(\RuntimeException::class);

        $container = new ActionsContainer();
        $container->set([
            EditAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
        ]);

        $container->moveBefore('undefined', 'undefined');
    }
}
