<?php

namespace Yaro\Jarboe\Http\Controllers;


use Yaro\Jarboe\Http\Requests\Admins\CreateRequest;
use Yaro\Jarboe\Http\Requests\Admins\UpdateRequest;
use Yaro\Jarboe\Models\Admin;
use Yaro\Jarboe\Table\Fields\Image;
use Yaro\Jarboe\Table\Fields\Markup\DummyField;
use Yaro\Jarboe\Table\Fields\Markup\RowMarkup;
use Yaro\Jarboe\Table\Fields\Password;
use Yaro\Jarboe\Table\Fields\Select;
use Yaro\Jarboe\Table\Fields\Text;

class AdminsController extends AbstractTableController
{

    protected function init()
    {
        $this->setModel(config('jarboe.admin_panel.admin_model', Admin::class));

        $this->crud->formClass('col-sm-12 col-md-12 col-lg-8 col-lg-offset-2');

        $this->addColumns([
            'avatar',
            'name',
            'email',
            'roles',
            'permissions',
        ]);

        $this->addFields([
            Image::make('avatar', 'Avatar')->encode()->crop()->ratio(200, 200)->placeholder('placeholder')->width(1)->col(4),
            RowMarkup::make()->col(8)->fields([
                Text::make('name', 'Name')->col(6),
                Password::make('password', 'Password')->col(6),
                Text::make('email', 'Email')->col(6),
                Password::make('password_confirmation', 'Repeat password')->col(6),
                Select::make('roles', 'Roles')
                    ->multiple()
                    ->type('select2')
                    ->relation('roles', 'name')
                    ->col(6),
                Select::make('permissions', 'Permissions')
                    ->multiple()
                    ->type('select2')
                    ->relation('permissions', 'name')
                    ->col(6),
            ]),
        ]);
    }

    public function update(UpdateRequest $request, $id)
    {
        return parent::handleUpdate($request, $id);
    }

    public function store(CreateRequest $request)
    {
        return parent::handleStore($request);
    }

}