<?php

namespace Yaro\Jarboe\Http\Controllers;


use Yaro\Jarboe\Http\Requests\Admins\CreateRequest;
use Yaro\Jarboe\Http\Requests\Admins\UpdateRequest;
use Yaro\Jarboe\Models\Admin;
use Yaro\Jarboe\Table\Fields\Hidden;
use Yaro\Jarboe\Table\Fields\Image;
use Yaro\Jarboe\Table\Fields\Markup\DummyField;
use Yaro\Jarboe\Table\Fields\Markup\RowMarkup;
use Yaro\Jarboe\Table\Fields\Password;
use Yaro\Jarboe\Table\Fields\Select;
use Yaro\Jarboe\Table\Fields\Text;

class AdminsRolesController extends AbstractTableController
{

    protected function init()
    {
        $default = config('auth.defaults.guard');
        $guard = config('jarboe.admin_panel.auth_guard', $default);
        $class = config('permission.models.role');

        $this->setModel($class);

        $this->addTab('General', [
            Text::make()->name('name')->title('Role title')->col(4),
            Select::make()->name('permissions')->title('Permissions')
                ->multiple()
                ->type('select2')
                ->relation('permissions', 'name')
                ->col(12),
            Hidden::make()->name('guard_name')->default($guard)->col(0),
        ]);
    }

//    public function update(UpdateRequest $request, $id)
//    {
//        return parent::handleUpdate($request, $id);
//    }
//
//
//    public function store(CreateRequest $request)
//    {
//        return parent::handleStore($request);
//    }

}