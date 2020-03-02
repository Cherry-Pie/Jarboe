<?php

namespace Yaro\Jarboe\Table\Actions;

class CreateAction extends AbstractAction
{
    protected $ident = 'create';

    public function render($model = null)
    {
        return view('jarboe::crud.actions.create', [
            'crud' => $this->crud(),
            'action' => $this,
        ]);
    }
}
