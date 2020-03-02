<?php

namespace Yaro\Jarboe\Table\Actions;

class EditAction extends AbstractAction
{
    protected $ident = 'edit';

    public function render($model = null)
    {
        return view('jarboe::crud.actions.edit', [
            'crud' => $this->crud(),
            'model' => $model,
            'action' => $this,
        ]);
    }
}
