<?php

namespace Yaro\Jarboe\Table\Actions;

class RestoreAction extends AbstractAction
{
    protected $ident = 'restore';

    public function render($model = null)
    {
        $isVisible = $this->crud()->isSoftDeleteEnabled() && $model->trashed();

        return view('jarboe::crud.actions.restore', [
            'crud' => $this->crud(),
            'model' => $model,
            'isVisible' => $isVisible,
            'action' => $this,
        ]);
    }

    public function isAllowed($model = null)
    {
        if (!$this->crud()->isSoftDeleteEnabled()) {
            return false;
        }

        return parent::isAllowed($model);
    }
}
