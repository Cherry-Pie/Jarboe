<?php

namespace Yaro\Jarboe\Table\Actions;

class EditAction extends AbstractAction
{
    protected $ident = 'edit';

    public function render($model = null)
    {
        $crud = $this->crud();

        return view('jarboe::crud.actions.edit', compact('crud', 'model'));
    }
}
