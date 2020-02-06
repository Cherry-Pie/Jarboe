<?php

namespace Yaro\Jarboe\Table\Actions;

class DeleteAction extends AbstractAction
{
    protected $ident = 'delete';

    public function render($model = null)
    {
        $isVisible = parent::shouldRender($model);
        if ($this->crud()->isSoftDeleteEnabled()) {
            $isVisible = !$model->trashed();
        }

        return view('jarboe::crud.actions.delete', [
            'crud' => $this->crud(),
            'model' => $model,
            'isVisible' => $isVisible,
            'action' => $this,
        ]);
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
