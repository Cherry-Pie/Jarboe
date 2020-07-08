<?php

namespace Yaro\Jarboe\Http\Controllers;

use Yaro\Jarboe\Etc\CustomFields\OtpSecret;
use Yaro\Jarboe\Etc\CustomFields\PermissionField;
use Yaro\Jarboe\Etc\CustomFields\RoleField;
use Yaro\Jarboe\Http\Requests\Admins\CreateRequest;
use Yaro\Jarboe\Http\Requests\Admins\UpdateRequest;
use Yaro\Jarboe\Models\Admin;
use Yaro\Jarboe\Table\Fields\Image;
use Yaro\Jarboe\Table\Fields\Markup\RowMarkup;
use Yaro\Jarboe\Table\Fields\Password;
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

        $fields = [
            Text::make('name', 'Name')->col(6),
            Password::make('password', 'Password')->col(6),
            Text::make('email', 'Email')->col(6),
            Password::make('password_confirmation', 'Repeat password')->col(6),
            RoleField::make('roles', 'Roles')
                ->relation('roles', 'name')
                ->col(6),
            PermissionField::make('permissions', 'Permissions')
                ->relation('permissions', 'name')
                ->col(6),
        ];
        if (config('jarboe.admin_panel.two_factor_auth.enabled')) {
            $fields[] = OtpSecret::make('otp_secret')->tooltip('Will be generated automatically on save')->placeholder('OTP secret')->col(6);
        }


        $this->addFields([
            Image::make('avatar', 'Avatar')->encode()->crop(false)->ratio(200, 200)->width(1)->col(4),
            RowMarkup::make()->col(8)->fields($fields),
        ]);
    }

    public function update(UpdateRequest $request, $id)
    {
        return parent::update($request, $id);
    }

    public function store(CreateRequest $request)
    {
        return parent::store($request);
    }
}
