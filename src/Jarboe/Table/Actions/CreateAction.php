<?php

namespace Yaro\Jarboe\Table\Actions;

use Yaro\Jarboe\Table\CRUD;

class CreateAction extends AbstractAction
{
    protected $ident = 'create';

    public function render(CRUD $crud, $model = null)
    {
        return view('jarboe::crud.actions.create', compact('crud'));
    }
}
