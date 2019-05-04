<?php

namespace Yaro\Jarboe\Table\Actions;

class DeleteAction extends AbstractAction
{
    protected $ident = 'delete';

    public function render($model = null)
    {
        $crud = $this->crud();
        $isVisible = parent::shouldRender($model);
        if ($crud->isSoftDeleteEnabled()) {
            $isVisible = !$model->trashed();
        }

        return view('jarboe::crud.actions.delete', compact('crud', 'model', 'isVisible'));
    }

    public function isAllowed($model = null)
    {
        $isAllowed = parent::isAllowed($model);
        if ($this->crud()->isSoftDeleteEnabled()) {
            return $isAllowed && !$model->trashed();
        }

        return $isAllowed;
    }

    public function shouldRender($model = null)
    {
        if ($this->crud()->isSoftDeleteEnabled()) {
            return true;
        }

        return parent::shouldRender($model);
    }
}
