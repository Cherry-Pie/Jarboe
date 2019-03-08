<?php

namespace Yaro\Jarboe\Table\Actions;

use Yaro\Jarboe\Table\CRUD;

class EditAction extends AbstractAction
{
    protected $ident = 'edit';

    public function render(CRUD $crud, $model = null)
    {
        return view('jarboe::crud.actions.edit', compact('crud', 'model'));
    }
}
