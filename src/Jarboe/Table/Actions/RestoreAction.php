<?php

namespace Yaro\Jarboe\Table\Actions;

class RestoreAction extends AbstractAction
{
    protected $ident = 'restore';

    public function render($model = null)
    {
        $crud = $this->crud();
        $isVisible = $crud->isSoftDeleteEnabled() && $model->trashed();

        return view('jarboe::crud.actions.restore', compact('crud', 'model', 'isVisible'));
    }

    public function isAllowed($model = null)
    {
        if (!$this->crud()->isSoftDeleteEnabled()) {
            return false;
        }

        return parent::isAllowed($model);
    }
}
