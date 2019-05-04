<?php

namespace Yaro\Jarboe\Table\Actions;

class CreateAction extends AbstractAction
{
    protected $ident = 'create';

    public function render($model = null)
    {
        $crud = $this->crud();

        return view('jarboe::crud.actions.create', compact('crud'));
    }
}
