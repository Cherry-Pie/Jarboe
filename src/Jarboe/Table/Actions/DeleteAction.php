<?php

namespace Yaro\Jarboe\Table\Actions;

use Yaro\Jarboe\Table\CRUD;

class DeleteAction extends AbstractAction
{
    protected $ident = 'delete';

    public function render(CRUD $crud, $model = null)
    {
        return view('jarboe::crud.actions.delete', compact('crud', 'model'));
    }
}
