<?php

namespace Yaro\Jarboe\Table\Actions;

class ForceDeleteAction extends AbstractAction
{
    protected $ident = 'force-delete';

    public function render($model = null)
    {
        $isVisible = $this->crud()->isSoftDeleteEnabled() && $model->trashed();

        return view('jarboe::crud.actions.force_delete', [
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
