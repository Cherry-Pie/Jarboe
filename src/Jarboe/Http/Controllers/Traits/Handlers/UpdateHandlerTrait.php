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
        $this->beforeInit();
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

        $inputs = [];
        /** @var AbstractField $field */
        foreach ($fields as $field) {
            if ($field->belongsToArray()) {
                $inputs += [$field->name() => $request->input($field->getDotPatternName())];
            }
        }
        $request->replace(
            $request->all() + $inputs
        );

        $data = [];
        $additional = [];
        /** @var AbstractField $field */
        foreach ($fields as $field) {
            if ($field->hidden('edit') || $field->isReadonly() || $field->shouldSkip($request)) {
                continue;
            }

            $field->beforeUpdate($model);

            if ($field->belongsToArray()) {
                $additional[$field->getAncestorName()][$field->getDescendantName()] = $field->value($request);
                continue;
            }

            $data += [$field->name() => $field->value($request)];
        }

        $data = $data + $additional;

        $model = $this->crud()->repo()->update($id, $data);
        /** @var AbstractField $field */
        foreach ($fields as $field) {
            $field->afterUpdate($model, $request);
        }
        $this->idEntity = $model->getKey();

        return redirect($this->crud()->listUrl());
    }

    abstract protected function beforeInit();
    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
