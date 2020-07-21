<?php

namespace Yaro\Jarboe\Table\Actions;

class HistoryAction extends AbstractAction
{
    protected $ident = 'history';

    public function render($model = null)
    {
        return view('jarboe::crud.actions.history', [
            'crud' => $this->crud(),
            'model' => $model,
            'action' => $this,
        ]);
    }
}
