<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;

trait EditHandlerTrait
{
    /**
     * Show edit form page.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleEdit(Request $request, $id)
    {
        $this->init();
        $this->bound();

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('edit', $model)) {
            throw new PermissionDenied();
        }

        if (!$this->can('edit')) {
            throw UnauthorizedException::forPermissions(['edit']);
        }

        $this->idEntity = $model->getKey();

        return view($this->viewCrudEdit, [
            'crud' => $this->crud(),
            'item' => $model,
            'viewsAbove' => $this->getEditViewsAbove(),
            'viewsBelow' => $this->getEditViewsBelow(),
        ]);
    }

    /**
     * Get array of view's objects, that should be rendered above content of `edit` view.
     *
     * @return array
     */
    protected function getEditViewsAbove(): array
    {
        return [];
    }

    /**
     * Get array of view's objects, that should be rendered below content of `edit` view.
     *
     * @return array
     */
    protected function getEditViewsBelow(): array
    {
        return [];
    }

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
