<?php

namespace Yaro\Jarboe\Http\Controllers;

use Yaro\Jarboe\Etc\CustomFields\PermissionField;
use Yaro\Jarboe\Table\Fields\Text;

class AdminsRolesController extends AbstractTableController
{
    protected function init()
    {
        $class = config('permission.models.role');

        $this->setModel($class);

        $this->addTab('General', [
            Text::make('name', 'Role title')->col(4),
            PermissionField::make('permissions', 'Permissions')
                ->relation('permissions', 'name')
                ->col(8),
        ]);
    }
}
