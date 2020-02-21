<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;

trait UpdateHandlerTrait
{
    /**
     * Handle update action.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws PermissionDenied
     * @throws UnauthorizedException
     */
    public function handleUpdate(Request $request, $id)
    {
        $this->init();
        $this->bound();

        if (!$this->can('update')) {
            throw UnauthorizedException::forPermissions(['update']);
        }

        $model = $this->crud()->repo()->find($id);
        if (!$this->crud()->actions()->isAllowed('edit', $model)) {
            throw new PermissionDenied();
        }

        $fields = $this->crud()->getFieldsWithoutMarkup();
        $data = [];
        /** @var AbstractField $field */
        foreach ($fields as $field) {
            if ($field->hidden('edit') || $field->isReadonly() || $field->shouldSkip($request)) {
                continue;
            }

            $field->beforeUpdate($model);

            $data += [$field->name() => $field->value($request)];
        }

        $model = $this->crud()->repo()->update($id, $data);
        /** @var AbstractField $field */
        foreach ($fields as $field) {
            $field->afterUpdate($model, $request);
        }
        $this->idEntity = $model->getKey();

        return redirect($this->crud()->listUrl());
    }

    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
