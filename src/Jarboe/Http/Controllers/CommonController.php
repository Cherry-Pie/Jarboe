<?php

namespace Yaro\Jarboe\Http\Controllers;

use Illuminate\Routing\Controller;
use Yaro\Jarboe\Table\CRUD;

class CommonController extends Controller
{
    public function resetPanelSettings()
    {
        setcookie('body_class', null, -1, '/');

        /** @var CRUD $crud */
        $crud = app(CRUD::class);
        $crud->preferences()->resetAll();

        return redirect()->back();
    }
}
